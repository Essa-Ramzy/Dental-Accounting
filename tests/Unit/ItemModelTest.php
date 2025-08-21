<?php

namespace Tests\Unit;

use App\Models\Customer;
use App\Models\Entry;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Test;
use Tests\TestCase;

class ItemModelTest extends TestCase
{
  use RefreshDatabase;

  protected function setUp(): void
  {
    parent::setUp();
    $this->artisan('migrate');
  }

  #[Test]
  public function test_item_has_fillable_attributes()
  {
    $item = new Item();
    $fillable = $item->getFillable();

    $this->assertEquals(['name', 'price', 'cost', 'description'], $fillable);
  }

  #[Test]
  public function test_item_uses_soft_deletes()
  {
    $item = Item::factory()->create();
    $itemId = $item->id;

    $item->delete();

    $this->assertSoftDeleted('items', ['id' => $itemId]);
    $this->assertDatabaseHas('items', ['id' => $itemId]);
  }

  #[Test]
  public function test_item_can_be_restored_after_soft_delete()
  {
    $item = Item::factory()->create();
    $item->delete();

    $item->restore();

    $this->assertDatabaseHas('items', [
      'id' => $item->id,
      'deleted_at' => null
    ]);
  }

  #[Test]
  public function test_item_can_be_force_deleted()
  {
    $item = Item::factory()->create();
    $itemId = $item->id;

    $item->forceDelete();

    $this->assertDatabaseMissing('items', ['id' => $itemId]);
  }

  #[Test]
  public function test_item_has_many_entries_relationship()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();

    $entries = Entry::factory(4)->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $this->assertCount(4, $item->entries);
    $this->assertInstanceOf(Entry::class, $item->entries->first());
  }

  #[Test]
  public function test_item_entries_are_soft_deleted_when_item_is_deleted()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();

    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $item->delete();

    $this->assertSoftDeleted('items', ['id' => $item->id]);
    $this->assertSoftDeleted('entries', ['id' => $entry->id]);
  }

  #[Test]
  public function test_item_attributes_can_be_set_and_retrieved()
  {
    $item = new Item();
    $item->name = 'Dental Crown';
    $item->price = 150.00;
    $item->cost = 75.50;
    $item->description = 'Ceramic crown for molars';

    $this->assertEquals('Dental Crown', $item->name);
    $this->assertEquals(150.00, $item->price);
    $this->assertEquals(75.50, $item->cost);
    $this->assertEquals('Ceramic crown for molars', $item->description);
  }

  #[Test]
  public function test_item_has_correct_table_name()
  {
    $item = new Item();

    $this->assertEquals('items', $item->getTable());
  }

  #[Test]
  public function test_item_has_correct_primary_key()
  {
    $item = new Item();

    $this->assertEquals('id', $item->getKeyName());
  }

  #[Test]
  public function test_item_factory_creates_valid_item()
  {
    $item = Item::factory()->create();

    $this->assertNotNull($item->name);
    $this->assertNotNull($item->price);
    $this->assertNotNull($item->cost);
    $this->assertIsString($item->name);
    $this->assertIsNumeric($item->price);
    $this->assertIsNumeric($item->cost);
    $this->assertDatabaseHas('items', ['id' => $item->id]);
  }

  #[Test]
  public function test_item_factory_can_override_attributes()
  {
    $item = Item::factory()->create([
      'name' => 'Custom Procedure',
      'price' => 200.00,
      'cost' => 100.00,
      'description' => 'Custom description'
    ]);

    $this->assertEquals('Custom Procedure', $item->name);
    $this->assertEquals(200.00, $item->price);
    $this->assertEquals(100.00, $item->cost);
    $this->assertEquals('Custom description', $item->description);
  }

  #[Test]
  public function test_item_timestamps_are_maintained()
  {
    $item = Item::factory()->create();

    $this->assertNotNull($item->created_at);
    $this->assertNotNull($item->updated_at);
    $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $item->created_at);
    $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $item->updated_at);
  }

  #[Test]
  public function test_item_updated_at_changes_on_update()
  {
    $item = Item::factory()->create();
    $originalUpdatedAt = $item->updated_at;

    // Force a small delay to ensure timestamp difference
    sleep(1);

    $item->price = 999.99;
    $item->save();

    $this->assertNotEquals($originalUpdatedAt, $item->fresh()->updated_at);
  }

  #[Test]
  public function test_item_can_be_found_by_name()
  {
    $item = Item::factory()->create(['name' => 'Unique Procedure']);

    $foundItem = Item::where('name', 'Unique Procedure')->first();

    $this->assertNotNull($foundItem);
    $this->assertEquals($item->id, $foundItem->id);
  }

  #[Test]
  public function test_item_entries_relationship_returns_correct_type()
  {
    $item = Item::factory()->create();

    $relationship = $item->entries();

    $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $relationship);
  }

  #[Test]
  public function test_item_soft_delete_trait_is_used()
  {
    $item = new Item();
    $traits = class_uses_recursive($item);

    $this->assertContains(\Illuminate\Database\Eloquent\SoftDeletes::class, $traits);
  }

  #[Test]
  public function test_item_has_factory_trait()
  {
    $item = new Item();
    $traits = class_uses_recursive($item);

    $this->assertContains(\Illuminate\Database\Eloquent\Factories\HasFactory::class, $traits);
  }

  #[Test]
  public function test_item_deletion_with_no_entries()
  {
    $item = Item::factory()->create();

    // Should not throw any errors
    $item->delete();

    $this->assertSoftDeleted('items', ['id' => $item->id]);
  }

  #[Test]
  public function test_item_force_deletion_with_no_entries()
  {
    $item = Item::factory()->create();

    // Should not throw any errors
    $item->forceDelete();

    $this->assertDatabaseMissing('items', ['id' => $item->id]);
  }

  #[Test]
  public function test_item_only_trashed_scope_works()
  {
    $activeItem = Item::factory()->create(['name' => 'Active Item']);
    $deletedItem = Item::factory()->create(['name' => 'Deleted Item']);
    $deletedItem->delete();

    $trashedItems = Item::onlyTrashed()->get();

    $this->assertCount(1, $trashedItems);
    $this->assertEquals('Deleted Item', $trashedItems->first()->name);
  }

  #[Test]
  public function test_item_with_trashed_scope_includes_both()
  {
    $activeItem = Item::factory()->create(['name' => 'Active Item']);
    $deletedItem = Item::factory()->create(['name' => 'Deleted Item']);
    $deletedItem->delete();

    $allItems = Item::withTrashed()->get();

    $this->assertCount(2, $allItems);
  }

  #[Test]
  public function test_item_price_accepts_decimal_values()
  {
    $item = Item::factory()->create(['price' => 123.45]);

    $this->assertEquals(123.45, $item->price);
    $this->assertIsFloat($item->price);
  }

  #[Test]
  public function test_item_cost_accepts_decimal_values()
  {
    $item = Item::factory()->create(['cost' => 67.89]);

    $this->assertEquals(67.89, $item->cost);
    $this->assertIsFloat($item->cost);
  }

  #[Test]
  public function test_item_price_can_be_zero()
  {
    $item = Item::factory()->create(['price' => 0]);

    $this->assertEquals(0, $item->price);
  }

  #[Test]
  public function test_item_cost_can_be_zero()
  {
    $item = Item::factory()->create(['cost' => 0]);

    $this->assertEquals(0, $item->cost);
  }

  #[Test]
  public function test_item_description_can_be_null()
  {
    $item = Item::factory()->create(['description' => null]);

    $this->assertNull($item->description);
  }

  #[Test]
  public function test_item_description_can_be_long_text()
  {
    $longDescription = str_repeat('This is a very long description. ', 50);
    $item = Item::factory()->create(['description' => $longDescription]);

    $this->assertEquals($longDescription, $item->description);
  }

  #[Test]
  public function test_item_can_have_multiple_entries_with_different_customers()
  {
    $customer1 = Customer::factory()->create();
    $customer2 = Customer::factory()->create();
    $item = Item::factory()->create();

    $entry1 = Entry::factory()->create([
      'customer_id' => $customer1->id,
      'item_id' => $item->id
    ]);

    $entry2 = Entry::factory()->create([
      'customer_id' => $customer2->id,
      'item_id' => $item->id
    ]);

    $this->assertCount(2, $item->entries);
    $this->assertTrue($item->entries->contains($entry1));
    $this->assertTrue($item->entries->contains($entry2));
  }

  #[Test]
  public function test_item_deleting_event_is_registered()
  {
    $item = new Item();

    // Check if the deleting event is registered
    $events = $item->getObservableEvents();
    $this->assertContains('deleting', $events);
  }

  #[Test]
  public function test_item_name_is_searchable_case_insensitive()
  {
    $item = Item::factory()->create(['name' => 'Dental Crown']);

    // Test case insensitive search works 
    $foundItem = Item::where('name', 'like', '%dental crown%')->first();
    $this->assertNotNull($foundItem);
    $this->assertEquals($item->id, $foundItem->id);

    $foundItem = Item::whereRaw('LOWER(name) LIKE ?', ['%dental crown%'])->first();
    $this->assertNotNull($foundItem);
    $this->assertEquals($item->id, $foundItem->id);
  }

  #[Test]
  public function test_item_price_and_cost_precision()
  {
    $item = Item::factory()->create([
      'price' => 123.456789,
      'cost' => 67.123456
    ]);

    // Values should be stored with precision
    $this->assertEquals(123.456789, $item->price);
    $this->assertEquals(67.123456, $item->cost);
  }
}
