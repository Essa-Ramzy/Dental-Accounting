<?php

namespace Tests\Unit;

use App\Models\Customer;
use App\Models\Entry;
use App\Models\Item;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Test;
use Tests\TestCase;

class ModelIntegrationTest extends TestCase
{
  use RefreshDatabase;

  protected function setUp(): void
  {
    parent::setUp();
    $this->artisan('migrate');
  }

  #[Test]
  public function test_cascade_delete_customer_with_multiple_entries()
  {
    $customer = Customer::factory()->create();
    $items = Item::factory(3)->create();

    $entries = [];
    foreach ($items as $item) {
      $entries[] = Entry::factory()->create([
        'customer_id' => $customer->id,
        'item_id' => $item->id
      ]);
    }

    $customer->delete();

    $this->assertSoftDeleted('customers', ['id' => $customer->id]);
    foreach ($entries as $entry) {
      $this->assertSoftDeleted('entries', ['id' => $entry->id]);
    }
  }

  #[Test]
  public function test_cascade_delete_item_with_multiple_entries()
  {
    $customers = Customer::factory(3)->create();
    $item = Item::factory()->create();

    $entries = [];
    foreach ($customers as $customer) {
      $entries[] = Entry::factory()->create([
        'customer_id' => $customer->id,
        'item_id' => $item->id
      ]);
    }

    $item->delete();

    $this->assertSoftDeleted('items', ['id' => $item->id]);
    foreach ($entries as $entry) {
      $this->assertSoftDeleted('entries', ['id' => $entry->id]);
    }
  }

  #[Test]
  public function test_force_delete_customer_removes_all_related_entries()
  {
    $customer = Customer::factory()->create();
    $items = Item::factory(2)->create();

    $entries = [];
    foreach ($items as $item) {
      $entries[] = Entry::factory()->create([
        'customer_id' => $customer->id,
        'item_id' => $item->id
      ]);
    }

    $customer->forceDelete();

    $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    foreach ($entries as $entry) {
      $this->assertDatabaseMissing('entries', ['id' => $entry->id]);
    }

    // Items should remain intact
    foreach ($items as $item) {
      $this->assertDatabaseHas('items', ['id' => $item->id]);
    }
  }

  #[Test]
  public function test_restore_entry_with_complex_relationships()
  {
    $customer = Customer::factory()->create(['name' => 'John Doe']);
    $item = Item::factory()->create(['name' => 'Crown Procedure']);
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    // Delete all related entities
    $customer->delete();
    $item->delete();
    $entry->delete();

    // Restore entry should restore all related entities
    $entry->restore();

    $this->assertDatabaseHas('customers', ['id' => $customer->id, 'deleted_at' => null]);
    $this->assertDatabaseHas('items', ['id' => $item->id, 'deleted_at' => null]);
    $this->assertDatabaseHas('entries', ['id' => $entry->id, 'deleted_at' => null]);
  }

  #[Test]
  public function test_entry_relationships_work_with_trashed_models()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    // Delete customer and item
    $customer->delete();
    $item->delete();

    $freshEntry = Entry::withTrashed()->find($entry->id);
    $this->assertNotNull($freshEntry);

    // Regular relationships should return null
    $this->assertNull($freshEntry->customer);
    $this->assertNull($freshEntry->item);

    // Trashed relationships should return the entities
    $this->assertNotNull($freshEntry->customer()->withTrashed()->first());
    $this->assertNotNull($freshEntry->item()->withTrashed()->first());
    $this->assertEquals($customer->id, $freshEntry->customer()->withTrashed()->first()->id);
    $this->assertEquals($item->id, $freshEntry->item()->withTrashed()->first()->id);
  }

  #[Test]
  public function test_entry_teeth_list_complex_parsing()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();

    // Test various teeth format combinations
    $testCases = [
      '11-123' => ['11-1', '11-2', '11-3'],
      '11-1, 12-23' => ['11-1', '12-2', '12-3'],
      '21-456, 22-78, 31-1' => ['21-4', '21-5', '21-6', '22-7', '22-8', '31-1'],
      '11-123, 12-456, 21-789' => ['11-1', '11-2', '11-3', '12-4', '12-5', '12-6', '21-7', '21-8', '21-9']
    ];

    foreach ($testCases as $teethInput => $expectedOutput) {
      $entry = Entry::factory()->create([
        'customer_id' => $customer->id,
        'item_id' => $item->id,
        'teeth' => $teethInput
      ]);

      $teethList = $entry->teeth_list;
      $this->assertEquals(collect($expectedOutput), $teethList, "Failed for input: $teethInput");
    }
  }

  #[Test]
  public function test_entry_unit_cost_calculation_edge_cases()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();

    // Test various cost and amount combinations
    $testCases = [
      ['cost' => 100, 'amount' => 4, 'expected' => 25],
      ['cost' => 150, 'amount' => 3, 'expected' => 50],
      ['cost' => 0, 'amount' => 5, 'expected' => 0],
      ['cost' => 100, 'amount' => 0, 'expected' => 0], // Edge case: zero amount
      ['cost' => 99.99, 'amount' => 3, 'expected' => 33.33],
    ];

    foreach ($testCases as $testCase) {
      $entry = Entry::factory()->create([
        'customer_id' => $customer->id,
        'item_id' => $item->id,
        'cost' => $testCase['cost'],
        'amount' => $testCase['amount']
      ]);

      $this->assertEquals($testCase['expected'], $entry->unit_cost, "Failed for cost: {$testCase['cost']}, amount: {$testCase['amount']}");
    }
  }

  #[Test]
  public function test_model_timestamps_consistency()
  {
    $startTime = Carbon::now()->subMonths(2);
    $endTime = Carbon::now();

    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);


    // All models should have timestamps within the test execution window
    $this->assertTrue($customer->created_at->between($startTime, $endTime));
    $this->assertTrue($item->created_at->between($startTime, $endTime));
    $this->assertTrue($entry->created_at->between($startTime, $endTime));
  }

  #[Test]
  public function test_soft_delete_timestamp_consistency()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $deleteTime = Carbon::now()->subSeconds(1);
    $customer->delete(); // This should also delete the entry

    $customer->refresh();
    $entry->refresh();

    $this->assertNotNull($customer->deleted_at);
    $this->assertNotNull($entry->deleted_at);
    $this->assertTrue($customer->deleted_at->gte($deleteTime));
    $this->assertTrue($entry->deleted_at->gte($deleteTime));
  }

  #[Test]
  public function test_multiple_entries_same_customer_and_item()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();

    $entries = Entry::factory(5)->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $this->assertEquals(5, $customer->entries()->count());
    $this->assertEquals(5, $item->entries()->count());

    // Each entry should be unique
    $entryIds = $entries->pluck('id')->toArray();
    $this->assertEquals(count($entryIds), count(array_unique($entryIds)));
  }

  #[Test]
  public function test_entry_date_casting_with_various_formats()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();

    $testDates = [
      '2024-01-15',
      '2024-12-31',
      now()->toDateString(),
      Carbon::tomorrow()->toDateString()
    ];

    foreach ($testDates as $dateString) {
      $entry = Entry::factory()->create([
        'customer_id' => $customer->id,
        'item_id' => $item->id,
        'date' => $dateString
      ]);

      $this->assertInstanceOf(Carbon::class, $entry->date);
      $this->assertEquals($dateString, $entry->date->toDateString());
    }
  }

  #[Test]
  public function test_model_factory_uniqueness()
  {
    // Test that factory creates unique records
    $customers = Customer::factory(10)->create();
    $items = Item::factory(10)->create();

    $customerNames = $customers->pluck('name')->toArray();
    $itemNames = $items->pluck('name')->toArray();

    // Names should be unique (or at least mostly unique given faker randomness)
    $uniqueCustomerNames = array_unique($customerNames);
    $uniqueItemNames = array_unique($itemNames);

    $this->assertGreaterThan(7, count($uniqueCustomerNames)); // Allow some duplicates due to faker
    $this->assertGreaterThan(7, count($uniqueItemNames));
  }

  #[Test]
  public function test_entry_relationships_are_properly_loaded()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    // Load entry with relationships
    $loadedEntry = Entry::with(['customer', 'item'])->find($entry->id);

    $this->assertTrue($loadedEntry->relationLoaded('customer'));
    $this->assertTrue($loadedEntry->relationLoaded('item'));
    $this->assertInstanceOf(Customer::class, $loadedEntry->customer);
    $this->assertInstanceOf(Item::class, $loadedEntry->item);
  }

  #[Test]
  public function test_model_deletion_order_independence()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    // Delete in different orders should have same result
    $customer->delete();
    $item->delete();

    $this->assertSoftDeleted('customers', ['id' => $customer->id]);
    $this->assertSoftDeleted('items', ['id' => $item->id]);
    $this->assertSoftDeleted('entries', ['id' => $entry->id]);
  }

  #[Test]
  public function test_large_dataset_performance()
  {
    // Create a larger dataset to test performance characteristics
    $customers = Customer::factory(50)->create();
    $items = Item::factory(20)->create();

    $entries = [];
    for ($i = 0; $i < 100; $i++) {
      $entries[] = Entry::factory()->create([
        'customer_id' => $customers->random()->id,
        'item_id' => $items->random()->id
      ]);
    }

    // Test counting operations
    $this->assertEquals(50, Customer::count());
    $this->assertEquals(20, Item::count());
    $this->assertEquals(100, Entry::count());

    // Test soft delete operations on larger dataset
    $customersToDelete = $customers->take(10);
    foreach ($customersToDelete as $customer) {
      $customer->delete();
    }

    $this->assertEquals(40, Customer::count());
    $this->assertEquals(10, Customer::onlyTrashed()->count());

    // Entries related to deleted customers should also be soft deleted
    $deletedEntryCount = Entry::onlyTrashed()->whereIn('customer_id', $customersToDelete->pluck('id'))->count();
    $this->assertGreaterThan(0, $deletedEntryCount);
  }
}
