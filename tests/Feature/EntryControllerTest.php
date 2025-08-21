<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Entry;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Test;
use Tests\TestCase;

class EntryControllerTest extends TestCase
{
  use RefreshDatabase, WithFaker;

  protected function setUp(): void
  {
    parent::setUp();
    $this->artisan('migrate');
  }

  #[Test]
  public function test_index_returns_entries_list_with_footer()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    Entry::factory(3)->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $response = $this->get(route('Entry.index'));

    $response->assertStatus(200);
    $response->assertViewIs('pages.entries');
    $response->assertViewHas('entries');
    $response->assertViewHas('footer');
    $response->assertViewHas('trash', false);

    $footer = $response->viewData('footer');
    $this->assertArrayHasKey('count', $footer);
    $this->assertArrayHasKey('total_price', $footer);
    $this->assertArrayHasKey('total_cost', $footer);
  }

  #[Test]
  public function test_index_returns_json_for_ajax_requests()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    Entry::factory(2)->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $response = $this->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
      ->get(route('Entry.index'));

    $response->assertStatus(200);
    $response->assertJsonStructure(['body', 'footer', 'links']);
  }

  #[Test]
  public function test_create_returns_create_form_with_customers_and_items()
  {
    $customers = Customer::factory(3)->create();
    $items = Item::factory(3)->create();

    $response = $this->get(route('Entry.create'));

    $response->assertStatus(200);
    $response->assertViewIs('forms.add-entry');
    $response->assertViewHas('customers');
    $response->assertViewHas('items');
    $response->assertViewHas('previous_url');
  }

  #[Test]
  public function test_store_creates_entry_with_valid_data()
  {
    $customer = Customer::factory()->create(['name' => 'John Doe']);
    $item = Item::factory()->create([
      'name' => 'Dental Crown',
      'price' => 100.00,
      'cost' => 50.00
    ]);

    $entryData = [
      'name' => 'John Doe',
      'item' => 'Dental Crown',
      'date' => now()->format('Y-m-d'),
      'teeth' => ['11-1', '11-2', '12-1'],
      'discount' => 10.00
    ];

    $response = $this->post(route('Entry.store'), $entryData);

    $response->assertRedirect(route('Entry.create'));
    $response->assertSessionHas('success', 'Entry created successfully.');

    $this->assertDatabaseHas('entries', [
      'customer_id' => $customer->id,
      'item_id' => $item->id,
      'amount' => 3,
      'unit_price' => 100.00,
      'discount' => 10.00,
      'price' => 290.00, // (100 * 3) - 10
      'cost' => 150.00,   // 50 * 3
      'teeth' => '11-12, 12-1'
    ]);
  }

  #[Test]
  public function test_store_creates_entry_without_discount()
  {
    $customer = Customer::factory()->create(['name' => 'Jane Smith']);
    $item = Item::factory()->create([
      'name' => 'Tooth Filling',
      'price' => 50.00,
      'cost' => 25.00
    ]);

    $entryData = [
      'name' => 'Jane Smith',
      'item' => 'Tooth Filling',
      'teeth' => ['21-1'],
    ];

    $response = $this->post(route('Entry.store'), $entryData);

    $response->assertRedirect(route('Entry.create'));

    $this->assertDatabaseHas('entries', [
      'customer_id' => $customer->id,
      'item_id' => $item->id,
      'amount' => 1,
      'discount' => 0,
      'price' => 50.00,
      'cost' => 25.00
    ]);
  }

  #[Test]
  public function test_store_creates_entry_without_date()
  {
    $customer = Customer::factory()->create(['name' => 'Test Patient']);
    $item = Item::factory()->create(['name' => 'Test Procedure']);

    $entryData = [
      'name' => 'Test Patient',
      'item' => 'Test Procedure',
      'teeth' => ['11-1']
    ];

    $response = $this->post(route('Entry.store'), $entryData);

    $response->assertRedirect(route('Entry.create'));

    $entry = Entry::latest()->first();
    $this->assertNotNull($entry->date);
    /** @var \Carbon\Carbon $entry->date */
    $this->assertEquals(now()->format('Y-m-d'), $entry->date->format('Y-m-d'));
  }

  #[Test]
  public function test_store_validates_required_fields()
  {
    $response = $this->post(route('Entry.store'), []);

    $response->assertSessionHasErrors(['name', 'item', 'teeth']);
  }

  #[Test]
  public function test_store_validates_discount_not_exceeding_total()
  {
    $customer = Customer::factory()->create(['name' => 'Test Customer']);
    $item = Item::factory()->create([
      'name' => 'Test Item',
      'price' => 100.00
    ]);

    $response = $this->post(route('Entry.store'), [
      'name' => 'Test Customer',
      'item' => 'Test Item',
      'teeth' => ['11-1'],
      'discount' => 150.00 // More than total price of 100
    ]);

    $response->assertSessionHasErrors('discount');
  }

  #[Test]
  public function test_store_validates_date_format()
  {
    $customer = Customer::factory()->create(['name' => 'Test Customer']);
    $item = Item::factory()->create(['name' => 'Test Item']);

    $response = $this->post(route('Entry.store'), [
      'name' => 'Test Customer',
      'item' => 'Test Item',
      'teeth' => ['11-1'],
      'date' => 'invalid-date'
    ]);

    $response->assertSessionHasErrors('date');
  }

  #[Test]
  public function test_store_validates_numeric_discount()
  {
    $customer = Customer::factory()->create(['name' => 'Test Customer']);
    $item = Item::factory()->create(['name' => 'Test Item']);

    $response = $this->post(route('Entry.store'), [
      'name' => 'Test Customer',
      'item' => 'Test Item',
      'teeth' => ['11-1'],
      'discount' => 'not-a-number'
    ]);

    $response->assertSessionHasErrors('discount');
  }

  #[Test]
  public function test_store_validates_minimum_discount()
  {
    $customer = Customer::factory()->create(['name' => 'Test Customer']);
    $item = Item::factory()->create(['name' => 'Test Item']);

    $response = $this->post(route('Entry.store'), [
      'name' => 'Test Customer',
      'item' => 'Test Item',
      'teeth' => ['11-1'],
      'discount' => -10
    ]);

    $response->assertSessionHasErrors('discount');
  }

  #[Test]
  public function test_teeth_format_grouping()
  {
    $customer = Customer::factory()->create(['name' => 'Test Customer']);
    $item = Item::factory()->create(['name' => 'Test Item']);

    $this->post(route('Entry.store'), [
      'name' => 'Test Customer',
      'item' => 'Test Item',
      'teeth' => ['11-1', '11-2', '11-3', '12-1', '12-2']
    ]);

    $entry = Entry::latest()->first();
    $this->assertEquals('11-123, 12-12', $entry->teeth);
  }

  #[Test]
  public function test_edit_returns_edit_form()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $customers = Customer::factory(2)->create();
    $items = Item::factory(2)->create();

    $response = $this->get(route('Entry.edit', $entry->id));

    $response->assertStatus(200);
    $response->assertViewIs('forms.edit-entry');
    $response->assertViewHas('entry', $entry);
    $response->assertViewHas('customers');
    $response->assertViewHas('items');
  }

  #[Test]
  public function test_edit_fails_with_invalid_id()
  {
    $response = $this->get(route('Entry.edit', 999));
    $response->assertStatus(404);
  }

  #[Test]
  public function test_update_modifies_entry_with_valid_data()
  {
    $customer1 = Customer::factory()->create(['name' => 'Customer 1']);
    $customer2 = Customer::factory()->create(['name' => 'Customer 2']);
    $item1 = Item::factory()->create(['name' => 'Item 1', 'price' => 100]);
    $item2 = Item::factory()->create(['name' => 'Item 2', 'price' => 150]);

    $entry = Entry::factory()->create([
      'customer_id' => $customer1->id,
      'item_id' => $item1->id
    ]);

    $updateData = [
      'name' => 'Customer 2',
      'item' => 'Item 2',
      'unit_price' => 200.00,
      'cost' => 75.00,
      'teeth' => ['21-1', '21-2'],
      'discount' => 50.00
    ];

    $response = $this->patch(route('Entry.update', $entry->id), $updateData);

    $response->assertRedirect(route('Entry.index'));

    $this->assertDatabaseHas('entries', [
      'id' => $entry->id,
      'customer_id' => $customer2->id,
      'item_id' => $item2->id,
      'amount' => 2,
      'unit_price' => 200.00,
      'discount' => 50.00,
      'price' => 350.00, // (200 * 2) - 50
      'cost' => 150.00,   // 75 * 2
      'teeth' => '21-12'
    ]);
  }

  #[Test]
  public function test_update_validates_discount_not_exceeding_total()
  {
    $customer = Customer::factory()->create(['name' => 'Test Customer']);
    $item = Item::factory()->create(['name' => 'Test Item']);
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $response = $this->patch(route('Entry.update', $entry->id), [
      'name' => 'Test Customer',
      'item' => 'Test Item',
      'unit_price' => 100,
      'teeth' => ['11-1'],
      'discount' => 150 // More than total
    ]);

    $response->assertSessionHasErrors('discount');
  }

  #[Test]
  public function test_delete_single_entry()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $response = $this->delete(route('Entry.delete'), [
      'filter' => 'single',
      'search' => $entry->id
    ]);

    $response->assertRedirect(route('Entry.index'));
    $this->assertSoftDeleted('entries', ['id' => $entry->id]);
  }

  #[Test]
  public function test_delete_multiple_entries_via_search()
  {
    $customer = Customer::factory()->create(['name' => 'Test Customer']);
    $item = Item::factory()->create();
    $entries = Entry::factory(3)->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $response = $this->delete(route('Entry.delete'), [
      'filter' => 'name',
      'search' => 'Test Customer'
    ]);

    $response->assertRedirect(route('Entry.index'));
    foreach ($entries as $entry) {
      $this->assertSoftDeleted('entries', ['id' => $entry->id]);
    }
  }

  #[Test]
  public function test_search_with_customer_name_filter()
  {
    $customer = Customer::factory()->create(['name' => 'John Doe']);
    $item = Item::factory()->create();
    Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $response = $this->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
      ->get(route('Entry.search') . '?' . http_build_query([
        'search' => 'John',
        'filter' => 'name'
      ]));

    $response->assertStatus(200);
    $response->assertJsonStructure(['body', 'footer', 'links']);
  }

  #[Test]
  public function test_search_with_item_filter()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create(['name' => 'Crown Procedure']);
    Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $response = $this->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
      ->get(route('Entry.search') . '?' . http_build_query([
        'search' => 'Crown',
        'filter' => 'item'
      ]));

    $response->assertStatus(200);
    $response->assertJsonStructure(['body', 'footer', 'links']);
  }

  #[Test]
  public function test_search_with_all_filter()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id,
      'teeth' => '11-1'
    ]);

    $response = $this->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
      ->get(route('Entry.search') . '?' . http_build_query([
        'search' => '11-1',
        'filter' => 'all'
      ]));

    $response->assertStatus(200);
  }

  #[Test]
  public function test_search_with_date_filters()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id,
      'date' => now()
    ]);

    $response = $this->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
      ->get(route('Entry.search') . '?' . http_build_query([
        'from_date' => now()->subDays(1)->format('Y-m-d'),
        'to_date' => now()->addDays(1)->format('Y-m-d')
      ]));

    $response->assertStatus(200);
  }

  #[Test]
  public function test_search_redirects_back_for_non_ajax()
  {
    $response = $this->get(route('Entry.search') . '?' . http_build_query([
      'search' => 'test'
    ]));

    $response->assertRedirect();
  }

  #[Test]
  public function test_export_generates_pdf()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    Entry::factory(3)->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $response = $this->post(route('Entry.export'), [
      'id' => 1,
      'customer' => 1,
      'item' => 1
    ]);

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/pdf');
  }

  #[Test]
  public function test_trash_shows_deleted_entries_with_trashed_relations()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);
    $entry->delete();

    $response = $this->get(route('Entry.trash'));

    $response->assertStatus(200);
    $response->assertViewIs('pages.entries');
    $response->assertViewHas('entries');
    $response->assertViewHas('footer');
    $response->assertViewHas('trash', true);
  }

  #[Test]
  public function test_trash_returns_json_for_ajax_requests()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);
    $entry->delete();

    $response = $this->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
      ->get(route('Entry.trash'));

    $response->assertStatus(200);
    $response->assertJsonStructure(['body', 'footer', 'links']);
  }

  #[Test]
  public function test_restore_single_entry_with_active_relations()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);
    $entry->delete();

    $response = $this->patch(route('Entry.restore'), [
      'filter' => 'single',
      'search' => $entry->id
    ]);

    $response->assertRedirect(route('Entry.trash'));
    $response->assertSessionHas('success');
    $this->assertDatabaseHas('entries', [
      'id' => $entry->id,
      'deleted_at' => null
    ]);
  }

  #[Test]
  public function test_restore_entry_also_restores_trashed_customer()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $customer->delete();
    $entry->delete();

    $this->patch(route('Entry.restore'), [
      'filter' => 'single',
      'search' => $entry->id
    ]);

    $this->assertDatabaseHas('entries', ['id' => $entry->id, 'deleted_at' => null]);
    $this->assertDatabaseHas('customers', ['id' => $customer->id, 'deleted_at' => null]);
  }

  #[Test]
  public function test_restore_entry_also_restores_trashed_item()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $item->delete();
    $entry->delete();

    $this->patch(route('Entry.restore'), [
      'filter' => 'single',
      'search' => $entry->id
    ]);

    $this->assertDatabaseHas('entries', ['id' => $entry->id, 'deleted_at' => null]);
    $this->assertDatabaseHas('items', ['id' => $item->id, 'deleted_at' => null]);
  }

  #[Test]
  public function test_restore_fails_if_customer_name_conflict()
  {
    $activeCustomer = Customer::factory()->create(['name' => 'John Doe']);
    $trashedCustomer = Customer::factory()->create(['name' => 'John Doe']);
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $trashedCustomer->id,
      'item_id' => $item->id
    ]);

    $trashedCustomer->delete();
    $entry->delete();

    $response = $this->patch(route('Entry.restore'), [
      'filter' => 'single',
      'search' => $entry->id
    ]);

    $response->assertRedirect(route('Entry.trash'));
    $response->assertSessionHas('failed_entries');
    $this->assertSoftDeleted('entries', ['id' => $entry->id]);
  }

  #[Test]
  public function test_restore_fails_if_item_name_conflict()
  {
    $customer = Customer::factory()->create();
    $activeItem = Item::factory()->create(['name' => 'Crown']);
    $trashedItem = Item::factory()->create(['name' => 'Crown']);
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $trashedItem->id
    ]);

    $trashedItem->delete();
    $entry->delete();

    $response = $this->patch(route('Entry.restore'), [
      'filter' => 'single',
      'search' => $entry->id
    ]);

    $response->assertRedirect(route('Entry.trash'));
    $response->assertSessionHas('failed_entries');
    $this->assertSoftDeleted('entries', ['id' => $entry->id]);
  }

  #[Test]
  public function test_restore_fails_if_customer_no_longer_exists()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $entry->delete();
    $customer->forceDelete(); // Permanently delete

    $response = $this->patch(route('Entry.restore'), [
      'filter' => 'single',
      'search' => $entry->id
    ]);

    $response->assertRedirect(route('Entry.trash'));
    $response->assertSessionHas('failed_entries');
    $this->assertDatabaseMissing('entries', ['id' => $entry->id]);
  }

  #[Test]
  public function test_restore_multiple_entries()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entries = Entry::factory(2)->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    foreach ($entries as $entry) {
      $entry->delete();
    }

    $response = $this->patch(route('Entry.restore'), [
      'filter' => 'all'
    ]);

    $response->assertRedirect(route('Entry.trash'));
    $response->assertSessionHas('success');

    foreach ($entries as $entry) {
      $this->assertDatabaseHas('entries', [
        'id' => $entry->id,
        'deleted_at' => null
      ]);
    }
  }

  #[Test]
  public function test_force_delete_single_entry()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);
    $entry->delete();

    $response = $this->delete(route('Entry.forceDelete'), [
      'filter' => 'single',
      'search' => $entry->id
    ]);

    $response->assertRedirect(route('Entry.trash'));
    $this->assertDatabaseMissing('entries', ['id' => $entry->id]);
  }

  #[Test]
  public function test_force_delete_multiple_entries()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entries = Entry::factory(2)->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    foreach ($entries as $entry) {
      $entry->delete();
    }

    $response = $this->delete(route('Entry.forceDelete'), [
      'filter' => 'all'
    ]);

    $response->assertRedirect(route('Entry.trash'));

    foreach ($entries as $entry) {
      $this->assertDatabaseMissing('entries', ['id' => $entry->id]);
    }
  }

  #[Test]
  public function test_pagination_works_correctly()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    Entry::factory(60)->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $response = $this->get(route('Entry.index'));

    $response->assertStatus(200);
    $entries = $response->viewData('entries');
    $this->assertEquals(50, $entries->count());
  }

  #[Test]
  public function test_footer_calculations_are_accurate()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entries = Entry::factory(3)->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id,
      'price' => 100,
      'cost' => 50
    ]);

    $response = $this->get(route('Entry.index'));

    $footer = $response->viewData('footer');
    $this->assertEquals(3, $footer['count']);
    $this->assertEquals(300, $footer['total_price']);
    $this->assertEquals(150, $footer['total_cost']);
  }

  #[Test]
  public function test_entry_relationships_are_loaded()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $response = $this->get(route('Entry.index'));

    $response->assertStatus(200);
    $entries = $response->viewData('entries');
    $firstEntry = $entries->first();

    // Check if relationships are loaded
    $this->assertTrue($firstEntry->relationLoaded('customer'));
    $this->assertTrue($firstEntry->relationLoaded('item'));
  }
}
