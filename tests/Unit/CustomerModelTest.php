<?php

namespace Tests\Unit;

use App\Models\Customer;
use App\Models\Entry;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Test;
use Tests\TestCase;

class CustomerModelTest extends TestCase
{
  use RefreshDatabase;

  protected function setUp(): void
  {
    parent::setUp();
    $this->artisan('migrate');
  }

  #[Test]
  public function test_customer_has_fillable_attributes()
  {
    $customer = new Customer();
    $fillable = $customer->getFillable();

    $this->assertEquals(['name'], $fillable);
  }

  #[Test]
  public function test_customer_uses_soft_deletes()
  {
    $customer = Customer::factory()->create();
    $customerId = $customer->id;

    $customer->delete();

    $this->assertSoftDeleted('customers', ['id' => $customerId]);
    $this->assertDatabaseHas('customers', ['id' => $customerId]);
  }

  #[Test]
  public function test_customer_can_be_restored_after_soft_delete()
  {
    $customer = Customer::factory()->create();
    $customer->delete();

    $customer->restore();

    $this->assertDatabaseHas('customers', [
      'id' => $customer->id,
      'deleted_at' => null
    ]);
  }

  #[Test]
  public function test_customer_can_be_force_deleted()
  {
    $customer = Customer::factory()->create();
    $customerId = $customer->id;

    $customer->forceDelete();

    $this->assertDatabaseMissing('customers', ['id' => $customerId]);
  }

  #[Test]
  public function test_customer_has_many_entries_relationship()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();

    $entries = Entry::factory(3)->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $this->assertCount(3, $customer->entries);
    $this->assertInstanceOf(Entry::class, $customer->entries->first());
  }

  #[Test]
  public function test_customer_entries_are_soft_deleted_when_customer_is_deleted()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();

    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $customer->delete();

    $this->assertSoftDeleted('customers', ['id' => $customer->id]);
    $this->assertSoftDeleted('entries', ['id' => $entry->id]);
  }

  #[Test]
  public function test_customer_entries_are_force_deleted_when_customer_is_force_deleted()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();

    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $customer->forceDelete();

    $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    $this->assertDatabaseMissing('entries', ['id' => $entry->id]);
  }

  #[Test]
  public function test_customer_deleting_event_handles_already_trashed_entries()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();

    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    // Trash the entry first
    $entry->delete();

    // Now force delete customer (should force delete the already trashed entry)
    $customer->forceDelete();

    $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    $this->assertDatabaseMissing('entries', ['id' => $entry->id]);
  }

  #[Test]
  public function test_customer_name_can_be_set_and_retrieved()
  {
    $customer = new Customer();
    $customer->name = 'John Doe';

    $this->assertEquals('John Doe', $customer->name);
  }

  #[Test]
  public function test_customer_has_correct_table_name()
  {
    $customer = new Customer();

    $this->assertEquals('customers', $customer->getTable());
  }

  #[Test]
  public function test_customer_has_correct_primary_key()
  {
    $customer = new Customer();

    $this->assertEquals('id', $customer->getKeyName());
  }

  #[Test]
  public function test_customer_factory_creates_valid_customer()
  {
    $customer = Customer::factory()->create();

    $this->assertNotNull($customer->name);
    $this->assertIsString($customer->name);
    $this->assertDatabaseHas('customers', ['id' => $customer->id]);
  }

  #[Test]
  public function test_customer_factory_can_override_attributes()
  {
    $customer = Customer::factory()->create(['name' => 'Specific Customer Name']);

    $this->assertEquals('Specific Customer Name', $customer->name);
  }

  #[Test]
  public function test_customer_timestamps_are_maintained()
  {
    $customer = Customer::factory()->create();

    $this->assertNotNull($customer->created_at);
    $this->assertNotNull($customer->updated_at);
    $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $customer->created_at);
    $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $customer->updated_at);
  }

  #[Test]
  public function test_customer_updated_at_changes_on_update()
  {
    $customer = Customer::factory()->create();
    $originalUpdatedAt = $customer->updated_at;

    // Force a small delay to ensure timestamp difference
    sleep(1);

    $customer->name = 'Updated Name';
    $customer->save();

    $this->assertNotEquals($originalUpdatedAt, $customer->fresh()->updated_at);
  }

  #[Test]
  public function test_customer_can_be_found_by_name()
  {
    $customer = Customer::factory()->create(['name' => 'Unique Customer']);

    $foundCustomer = Customer::where('name', 'Unique Customer')->first();

    $this->assertNotNull($foundCustomer);
    $this->assertEquals($customer->id, $foundCustomer->id);
  }

  #[Test]
  public function test_customer_entries_relationship_returns_correct_type()
  {
    $customer = Customer::factory()->create();

    $relationship = $customer->entries();

    $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $relationship);
  }

  #[Test]
  public function test_customer_soft_delete_trait_is_used()
  {
    $customer = new Customer();
    $traits = class_uses_recursive($customer);

    $this->assertContains(\Illuminate\Database\Eloquent\SoftDeletes::class, $traits);
  }

  #[Test]
  public function test_customer_has_factory_trait()
  {
    $customer = new Customer();
    $traits = class_uses_recursive($customer);

    $this->assertContains(\Illuminate\Database\Eloquent\Factories\HasFactory::class, $traits);
  }

  #[Test]
  public function test_customer_deletion_with_no_entries()
  {
    $customer = Customer::factory()->create();

    // Should not throw any errors
    $customer->delete();

    $this->assertSoftDeleted('customers', ['id' => $customer->id]);
  }

  #[Test]
  public function test_customer_force_deletion_with_no_entries()
  {
    $customer = Customer::factory()->create();

    // Should not throw any errors
    $customer->forceDelete();

    $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
  }

  #[Test]
  public function test_customer_only_trashed_scope_works()
  {
    $activeCustomer = Customer::factory()->create(['name' => 'Active Customer']);
    $deletedCustomer = Customer::factory()->create(['name' => 'Deleted Customer']);
    $deletedCustomer->delete();

    $trashedCustomers = Customer::onlyTrashed()->get();

    $this->assertCount(1, $trashedCustomers);
    $this->assertEquals('Deleted Customer', $trashedCustomers->first()->name);
  }

  #[Test]
  public function test_customer_with_trashed_scope_includes_both()
  {
    $activeCustomer = Customer::factory()->create(['name' => 'Active Customer']);
    $deletedCustomer = Customer::factory()->create(['name' => 'Deleted Customer']);
    $deletedCustomer->delete();

    $allCustomers = Customer::withTrashed()->get();

    $this->assertCount(2, $allCustomers);
  }
}
