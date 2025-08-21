<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Entry;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Test;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
  use RefreshDatabase, WithFaker;

  protected function setUp(): void
  {
    parent::setUp();
    $this->artisan('migrate');
  }

  #[Test]
  public function test_index_returns_customers_list()
  {
    $customers = Customer::factory(5)->create();

    $response = $this->get(route('Customer.index'));

    $response->assertStatus(200);
    $response->assertViewIs('pages.customers');
    $response->assertViewHas('customers');
    $response->assertViewHas('trash', false);
  }

  #[Test]
  public function test_index_returns_json_for_ajax_requests()
  {
    $customers = Customer::factory(3)->create();

    $response = $this->get(route('Customer.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

    $response->assertStatus(200);
    $response->assertJsonStructure(['body', 'footer', 'links']);
  }

  #[Test]
  public function test_create_returns_create_form()
  {
    $response = $this->get(route('Customer.create'));

    $response->assertStatus(200);
    $response->assertViewIs('forms.add-customer');
    $response->assertViewHas('previousUrl');
    $response->assertViewHas('countTrash');
  }

  #[Test]
  public function test_store_creates_customer_with_valid_data()
  {
    $customerData = [
      'name' => $this->faker->name
    ];

    $response = $this->post(route('Customer.store'), $customerData);

    $response->assertRedirect(route('Customer.create'));
    $response->assertSessionHas('success', 'Customer created successfully.');
    $this->assertDatabaseHas('customers', $customerData);
  }

  #[Test]
  public function test_store_validates_required_name()
  {
    $response = $this->post(route('Customer.store'), []);

    $response->assertSessionHasErrors('name');
  }

  #[Test]
  public function test_store_validates_unique_name()
  {
    $existingCustomer = Customer::factory()->create();

    $response = $this->post(route('Customer.store'), [
      'name' => $existingCustomer->name
    ]);

    $response->assertSessionHasErrors('name');
  }

  #[Test]
  public function test_store_allows_same_name_if_previous_is_soft_deleted()
  {
    $deletedCustomer = Customer::factory()->create();
    $deletedCustomer->delete();

    $response = $this->post(route('Customer.store'), [
      'name' => $deletedCustomer->name
    ]);

    $response->assertRedirect();
    $response->assertSessionHasNoErrors();
  }

  #[Test]
  public function test_edit_returns_edit_form()
  {
    $customer = Customer::factory()->create();

    $response = $this->get(route('Customer.edit', $customer->id));

    $response->assertStatus(200);
    $response->assertViewIs('forms.edit-customer');
    $response->assertViewHas('customer', $customer);
  }

  #[Test]
  public function test_edit_fails_with_invalid_id()
  {
    $response = $this->get(route('Customer.edit', 999));
    $response->assertStatus(404);
  }

  #[Test]
  public function test_update_modifies_customer_with_valid_data()
  {
    $customer = Customer::factory()->create();
    $newName = $this->faker->name;

    $response = $this->patch(route('Customer.update', $customer->id), [
      'name' => $newName
    ]);

    $response->assertRedirect(route('Customer.index'));
    $this->assertDatabaseHas('customers', [
      'id' => $customer->id,
      'name' => $newName
    ]);
  }

  #[Test]
  public function test_update_validates_unique_name()
  {
    $customer1 = Customer::factory()->create();
    $customer2 = Customer::factory()->create();

    $response = $this->patch(route('Customer.update', $customer1->id), [
      'name' => $customer2->name
    ]);

    $response->assertSessionHasErrors('name');
  }

  #[Test]
  public function test_update_allows_keeping_same_name()
  {
    $customer = Customer::factory()->create();

    $response = $this->patch(route('Customer.update', $customer->id), [
      'name' => $customer->name
    ]);

    $response->assertRedirect(route('Customer.index'));
    $response->assertSessionHasNoErrors();
  }

  #[Test]
  public function test_delete_single_customer()
  {
    $customer = Customer::factory()->create();

    $response = $this->delete(route('Customer.delete'), [
      'filter' => 'single',
      'search' => $customer->id
    ]);

    $response->assertRedirect(route('Customer.index'));
    $this->assertSoftDeleted('customers', ['id' => $customer->id]);
  }

  #[Test]
  public function test_delete_multiple_customers_via_search()
  {
    $customers = [
      Customer::factory()->create(['name' => 'Test Customer']),
      Customer::factory()->create(['name' => 'Test Customer 2']),
      Customer::factory()->create(['name' => 'Test Customer 3']),
    ];

    $response = $this->delete(route('Customer.delete'), [
      'filter' => 'name',
      'search' => 'Test Customer'
    ]);

    $response->assertRedirect(route('Customer.index'));
    foreach ($customers as $customer) {
      $this->assertSoftDeleted('customers', ['id' => $customer->id]);
    }
  }

  #[Test]
  public function test_delete_cascades_to_entries()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $this->delete(route('Customer.delete'), [
      'filter' => 'single',
      'search' => $customer->id
    ]);

    $this->assertSoftDeleted('customers', ['id' => $customer->id]);
    $this->assertSoftDeleted('entries', ['id' => $entry->id]);
  }

  #[Test]
  public function test_records_redirects_to_entries_with_customer_filter()
  {
    $customer = Customer::factory()->create();

    $response = $this->get(route('Customer.records', $customer->id));

    $response->assertRedirect(route('Entry.index'));
    $response->assertSessionHas('customer', $customer->name);
  }

  #[Test]
  public function test_search_with_name_filter()
  {
    $customer1 = Customer::factory()->create(['name' => 'John Doe']);
    $customer2 = Customer::factory()->create(['name' => 'Jane Smith']);

    $response = $this->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
      ->get(route('Customer.search') . '?' . http_build_query([
        'search' => 'John',
        'filter' => 'name'
      ]));

    $response->assertStatus(200);
    $response->assertJsonStructure(['body', 'footer', 'links']);
  }

  #[Test]
  public function test_search_with_all_filter()
  {
    $customer = Customer::factory()->create(['name' => 'Test Customer']);

    $response = $this->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
      ->get(route('Customer.search') . '?' . http_build_query([
        'search' => 'Test',
        'filter' => 'all'
      ]));

    $response->assertStatus(200);
    $response->assertJsonStructure(['body', 'footer', 'links']);
  }

  #[Test]
  public function test_search_with_date_filters()
  {
    $customer = Customer::factory()->create();

    $response = $this->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
      ->get(route('Customer.search') . '?' . http_build_query([
        'from_date' => now()->subDays(7)->format('Y-m-d'),
        'to_date' => now()->format('Y-m-d')
      ]));

    $response->assertStatus(200);
  }

  #[Test]
  public function test_search_redirects_back_for_non_ajax()
  {
    $response = $this->get(route('Customer.search'), [
      'search' => 'test'
    ]);

    $response->assertRedirect();
  }

  #[Test]
  public function test_trash_shows_deleted_customers()
  {
    $customer = Customer::factory()->create();
    $customer->delete();

    $response = $this->get(route('Customer.trash'));

    $response->assertStatus(200);
    $response->assertViewIs('pages.customers');
    $response->assertViewHas('customers');
    $response->assertViewHas('trash', true);
  }

  #[Test]
  public function test_trash_returns_json_for_ajax_requests()
  {
    $customer = Customer::factory()->create();
    $customer->delete();

    $response = $this->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
      ->get(route('Customer.trash'));

    $response->assertStatus(200);
    $response->assertJsonStructure(['body', 'footer', 'links']);
  }

  #[Test]
  public function test_restore_single_customer()
  {
    $customer = Customer::factory()->create();
    $customer->delete();

    $response = $this->patch(route('Customer.restore'), [
      'filter' => 'single',
      'search' => $customer->id
    ]);

    $response->assertRedirect(route('Customer.trash'));
    $response->assertSessionHas('success');
    $this->assertDatabaseHas('customers', [
      'id' => $customer->id,
      'deleted_at' => null
    ]);
  }

  #[Test]
  public function test_restore_fails_if_name_already_exists()
  {
    $activeCustomer = Customer::factory()->create(['name' => 'Test Customer']);
    $deletedCustomer = Customer::factory()->create(['name' => 'Test Customer']);
    $deletedCustomer->delete();

    $response = $this->patch(route('Customer.restore'), [
      'filter' => 'single',
      'search' => $deletedCustomer->id
    ]);

    $response->assertRedirect(route('Customer.trash'));
    $response->assertSessionHas('failed_customers');
    $this->assertSoftDeleted('customers', ['id' => $deletedCustomer->id]);
  }

  #[Test]
  public function test_restore_multiple_customers()
  {
    $customers = Customer::factory(2)->create();
    foreach ($customers as $customer) {
      $customer->delete();
    }

    $response = $this->patch(route('Customer.restore'), [
      'filter' => 'all'
    ]);

    $response->assertRedirect(route('Customer.trash'));
    $response->assertSessionHas('success');

    foreach ($customers as $customer) {
      $this->assertDatabaseHas('customers', [
        'id' => $customer->id,
        'deleted_at' => null
      ]);
    }
  }

  #[Test]
  public function test_restore_handles_invalid_customer()
  {
    $response = $this->patch(route('Customer.restore'), [
      'filter' => 'single',
      'search' => 999
    ]);

    $response->assertRedirect(route('Customer.trash'));
    $response->assertSessionHas('failed_customers');
  }

  #[Test]
  public function test_force_delete_single_customer()
  {
    $customer = Customer::factory()->create();
    $customer->delete();

    $response = $this->delete(route('Customer.forceDelete'), [
      'filter' => 'single',
      'search' => $customer->id
    ]);

    $response->assertRedirect(route('Customer.trash'));
    $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
  }

  #[Test]
  public function test_force_delete_multiple_customers()
  {
    $customers = Customer::factory(2)->create();
    foreach ($customers as $customer) {
      $customer->delete();
    }

    $response = $this->delete(route('Customer.forceDelete'), [
      'filter' => 'all'
    ]);

    $response->assertRedirect(route('Customer.trash'));

    foreach ($customers as $customer) {
      $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }
  }

  #[Test]
  public function test_force_delete_cascades_entries()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    $entry = Entry::factory()->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $customer->delete();

    $this->delete(route('Customer.forceDelete'), [
      'filter' => 'single',
      'search' => $customer->id
    ]);

    $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    $this->assertDatabaseMissing('entries', ['id' => $entry->id]);
  }

  #[Test]
  public function test_previous_url_session_handling_in_create()
  {
    $response = $this->withSession(['customer_previous_url' => route('Customer.index')])
      ->get(route('Customer.create'));

    $response->assertStatus(200);
    $response->assertSessionHas('customer_previous_url');
  }

  #[Test]
  public function test_store_redirects_to_previous_url_when_appropriate()
  {
    $response = $this->withSession(['customer_previous_url' => '/some-other-page'])
      ->post(route('Customer.store'), ['name' => 'Test Customer']);

    $response->assertSessionMissing('customer_previous_url');
  }

  #[Test]
  public function test_pagination_works_correctly()
  {
    Customer::factory(60)->create();

    $response = $this->get(route('Customer.index'));

    $response->assertStatus(200);
    $customers = $response->viewData('customers');
    $this->assertEquals(50, $customers->count());
  }

  #[Test]
  public function test_customer_with_entries_count()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    Entry::factory(3)->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $response = $this->get(route('Customer.index'));

    $response->assertStatus(200);
    $customers = $response->viewData('customers');
    $this->assertEquals(3, $customers->first()->entries_count);
  }
}
