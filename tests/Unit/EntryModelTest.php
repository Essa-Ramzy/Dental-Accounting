<?php

namespace Tests\Unit;

use App\Models\Customer;
use App\Models\Entry;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Test;
use Tests\TestCase;

class EntryModelTest extends TestCase
{
  use RefreshDatabase;

  protected function setUp(): void
  {
    parent::setUp();
    $this->artisan('migrate');
  }

  #[Test]
  public function test_entry_has_fillable_attributes()
  {
    $entry = new Entry();
    $fillable = $entry->getFillable();

    $this->assertEquals([
      'customer_id',
      'item_id',
      'date',
      'teeth',
      'amount',
      'unit_price',
      'discount',
      'price',
      'cost'
    ], $fillable);
  }

  #[Test]
  public function test_entry_has_date_cast()
  {
    $entry = new Entry();
    $casts = $entry->getCasts();

    $this->assertArrayHasKey('date', $casts);
    $this->assertEquals('date', $casts['date']);
  }

  #[Test]
  public function test_entry_uses_soft_deletes()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);
    $entryId = $entry->id;

    $entry->delete();

    $this->assertSoftDeleted('entries', ['id' => $entryId]);
    $this->assertDatabaseHas('entries', ['id' => $entryId]);
  }

  #[Test]
  public function test_entry_can_be_restored_after_soft_delete()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $entry->delete();
    $entry->restore();

    $this->assertDatabaseHas('entries', [
      'id' => $entry->id,
      'deleted_at' => null
    ]);
  }

  #[Test]
  public function test_entry_belongs_to_customer()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $this->assertInstanceOf(Customer::class, $entry->customer);
    $this->assertEquals($customer->id, $entry->customer->id);
  }

  #[Test]
  public function test_entry_belongs_to_item()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $this->assertInstanceOf(Item::class, $entry->item);
    $this->assertEquals($item->id, $entry->item->id);
  }

  #[Test]
  public function test_entry_customer_relationship_returns_correct_type()
  {
    $entry = new Entry();
    $relationship = $entry->customer();

    $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $relationship);
  }

  #[Test]
  public function test_entry_item_relationship_returns_correct_type()
  {
    $entry = new Entry();
    $relationship = $entry->item();

    $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $relationship);
  }

  #[Test]
  public function test_entry_customer_with_trashed_relationship()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $customer->delete();

    // Regular relationship should return null for soft deleted customer
    $this->assertNull($entry->fresh()->customer);

    // customerWithTrashed should return the trashed customer
    $this->assertNotNull($entry->fresh()->customer()->withTrashed()->first());
    $this->assertEquals($customer->id, $entry->fresh()->customer()->withTrashed()->first()->id);
  }

  #[Test]
  public function test_entry_item_with_trashed_relationship()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $item->delete();

    // Regular relationship should return null for soft deleted item
    $this->assertNull($entry->fresh()->item);

    // itemWithTrashed should return the trashed item
    $this->assertNotNull($entry->fresh()->item()->withTrashed()->first());
    $this->assertEquals($item->id, $entry->fresh()->item()->withTrashed()->first()->id);
  }

  #[Test]
  public function test_entry_teeth_list_attribute()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id,
      'teeth' => '11-123, 12-45'
    ]);

    $teethList = $entry->teeth_list;

    $this->assertInstanceOf(\Illuminate\Support\Collection::class, $teethList);
    $expected = collect(['11-1', '11-2', '11-3', '12-4', '12-5']);
    $this->assertEquals($expected, $teethList);
  }

  #[Test]
  public function test_entry_teeth_list_attribute_with_empty_teeth()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id,
      'teeth' => ''
    ]);

    $teethList = $entry->teeth_list;

    $this->assertInstanceOf(\Illuminate\Support\Collection::class, $teethList);
    $this->assertTrue($teethList->isEmpty());
  }

  #[Test]
  public function test_entry_teeth_list_attribute_with_single_quadrant()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id,
      'teeth' => '21-67'
    ]);

    $teethList = $entry->teeth_list;
    $expected = collect(['21-6', '21-7']);
    $this->assertEquals($expected, $teethList);
  }

  #[Test]
  public function test_entry_unit_cost_attribute()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id,
      'cost' => 150.00,
      'amount' => 3
    ]);

    $unitCost = $entry->unit_cost;

    $this->assertEquals(50.00, $unitCost);
  }

  #[Test]
  public function test_entry_unit_cost_attribute_with_zero_amount()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id,
      'cost' => 150.00,
      'amount' => 0
    ]);

    $unitCost = $entry->unit_cost;

    $this->assertEquals(0, $unitCost);
  }

  #[Test]
  public function test_entry_has_correct_table_name()
  {
    $entry = new Entry();

    $this->assertEquals('entries', $entry->getTable());
  }

  #[Test]
  public function test_entry_has_correct_primary_key()
  {
    $entry = new Entry();

    $this->assertEquals('id', $entry->getKeyName());
  }

  #[Test]
  public function test_entry_factory_creates_valid_entry()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $this->assertNotNull($entry->customer_id);
    $this->assertNotNull($entry->item_id);
    $this->assertNotNull($entry->date);
    $this->assertNotNull($entry->teeth);
    $this->assertNotNull($entry->amount);
    $this->assertNotNull($entry->unit_price);
    $this->assertNotNull($entry->price);
    $this->assertNotNull($entry->cost);
    $this->assertDatabaseHas('entries', ['id' => $entry->id]);
  }

  #[Test]
  public function test_entry_timestamps_are_maintained()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $this->assertNotNull($entry->created_at);
    $this->assertNotNull($entry->updated_at);
    $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $entry->created_at);
    $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $entry->updated_at);
  }

  #[Test]
  public function test_entry_date_is_cast_to_carbon()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id,
      'date' => '2024-01-15'
    ]);

    $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $entry->date);
    $this->assertEquals('2024-01-15', $entry->date->format('Y-m-d'));
  }

  #[Test]
  public function test_entry_soft_delete_trait_is_used()
  {
    $entry = new Entry();
    $traits = class_uses_recursive($entry);

    $this->assertContains(\Illuminate\Database\Eloquent\SoftDeletes::class, $traits);
  }

  #[Test]
  public function test_entry_has_factory_trait()
  {
    $entry = new Entry();
    $traits = class_uses_recursive($entry);

    $this->assertContains(\Illuminate\Database\Eloquent\Factories\HasFactory::class, $traits);
  }

  #[Test]
  public function test_entry_discount_can_be_zero()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id,
      'discount' => 0
    ]);

    $this->assertEquals(0, $entry->discount);
  }

  #[Test]
  public function test_entry_discount_can_be_decimal()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id,
      'discount' => 15.50
    ]);

    $this->assertEquals(15.50, $entry->discount);
  }

  #[Test]
  public function test_entry_restoring_event_restores_trashed_customer()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    // Soft delete customer and entry
    $customer->delete();
    $entry->delete();

    // Restore entry should also restore customer
    $entry->restore();

    $this->assertDatabaseHas('customers', [
      'id' => $customer->id,
      'deleted_at' => null
    ]);
    $this->assertDatabaseHas('entries', [
      'id' => $entry->id,
      'deleted_at' => null
    ]);
  }

  #[Test]
  public function test_entry_restoring_event_restores_trashed_item()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    // Soft delete item and entry
    $item->delete();
    $entry->delete();

    // Restore entry should also restore item
    $entry->restore();

    $this->assertDatabaseHas('items', [
      'id' => $item->id,
      'deleted_at' => null
    ]);
    $this->assertDatabaseHas('entries', [
      'id' => $entry->id,
      'deleted_at' => null
    ]);
  }

  #[Test]
  public function test_entry_restoring_event_restores_both_customer_and_item()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    // Soft delete customer, item, and entry
    $customer->delete();
    $item->delete();
    $entry->delete();

    // Restore entry should restore both customer and item
    $entry->restore();

    $this->assertDatabaseHas('customers', [
      'id' => $customer->id,
      'deleted_at' => null
    ]);
    $this->assertDatabaseHas('items', [
      'id' => $item->id,
      'deleted_at' => null
    ]);
    $this->assertDatabaseHas('entries', [
      'id' => $entry->id,
      'deleted_at' => null
    ]);
  }

  #[Test]
  public function test_entry_restoring_event_does_not_affect_active_relations()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    // Only soft delete entry (customer and item remain active)
    $entry->delete();

    // Restore entry should not affect already active customer and item
    $entry->restore();

    $this->assertDatabaseHas('customers', [
      'id' => $customer->id,
      'deleted_at' => null
    ]);
    $this->assertDatabaseHas('items', [
      'id' => $item->id,
      'deleted_at' => null
    ]);
    $this->assertDatabaseHas('entries', [
      'id' => $entry->id,
      'deleted_at' => null
    ]);
  }

  #[Test]
  public function test_entry_can_have_zero_discount()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id,
      'discount' => 0
    ]);

    $this->assertEquals(0, $entry->discount);
  }

  #[Test]
  public function test_entry_amount_is_integer()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id,
      'amount' => 5
    ]);

    $this->assertEquals(5, $entry->amount);
    $this->assertIsInt($entry->amount);
  }

  #[Test]
  public function test_entry_price_accepts_decimal_values()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id,
      'price' => 123.45
    ]);

    $this->assertEquals(123.45, $entry->price);
  }

  #[Test]
  public function test_entry_cost_accepts_decimal_values()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id,
      'cost' => 67.89
    ]);

    $this->assertEquals(67.89, $entry->cost);
  }

  #[Test]
  public function test_entry_unit_price_accepts_decimal_values()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id,
      'unit_price' => 99.99
    ]);

    $this->assertEquals(99.99, $entry->unit_price);
  }

  #[Test]
  public function test_entry_only_trashed_scope_works()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $activeEntry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);
    $deletedEntry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);
    $deletedEntry->delete();

    $trashedEntries = Entry::onlyTrashed()->get();

    $this->assertCount(1, $trashedEntries);
    $this->assertEquals($deletedEntry->id, $trashedEntries->first()->id);
  }

  #[Test]
  public function test_entry_with_trashed_scope_includes_both()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $activeEntry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);
    $deletedEntry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);
    $deletedEntry->delete();

    $allEntries = Entry::withTrashed()->get();

    $this->assertCount(2, $allEntries);
  }
}
