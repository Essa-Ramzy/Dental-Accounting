<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Entry;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Test;
use Tests\TestCase;

class ItemControllerTest extends TestCase
{
  use RefreshDatabase, WithFaker;

  protected function setUp(): void
  {
    parent::setUp();
    $this->artisan('migrate');
  }

  #[Test]
  public function test_index_returns_items_list()
  {
    $items = Item::factory(5)->create();

    $response = $this->get(route('Item.index'));

    $response->assertStatus(200);
    $response->assertViewIs('pages.items');
    $response->assertViewHas('items');
    $response->assertViewHas('trash', false);
  }

  #[Test]
  public function test_index_returns_json_for_ajax_requests()
  {
    $items = Item::factory(3)->create();

    $response = $this->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
      ->get(route('Item.index'));

    $response->assertStatus(200);
    $response->assertJsonStructure(['body', 'footer', 'links']);
  }

  #[Test]
  public function test_create_returns_create_form()
  {
    $response = $this->get(route('Item.create'));

    $response->assertStatus(200);
    $response->assertViewIs('forms.add-item');
    $response->assertViewHas('previousUrl');
  }

  #[Test]
  public function test_store_creates_item_with_valid_data()
  {
    $itemData = [
      'name' => $this->faker->word,
      'price' => $this->faker->randomFloat(2, 10, 100),
      'cost' => $this->faker->randomFloat(2, 5, 50),
      'description' => $this->faker->sentence
    ];

    $response = $this->post(route('Item.store'), $itemData);

    $response->assertRedirect(route('Item.create'));
    $response->assertSessionHas('success', 'Item created successfully.');
    $this->assertDatabaseHas('items', $itemData);
  }

  #[Test]
  public function test_store_creates_item_without_description()
  {
    $itemData = [
      'name' => $this->faker->word,
      'price' => $this->faker->randomFloat(2, 10, 100),
      'cost' => $this->faker->randomFloat(2, 5, 50)
    ];

    $response = $this->post(route('Item.store'), $itemData);

    $response->assertRedirect(route('Item.create'));
    $this->assertDatabaseHas('items', $itemData);
  }

  #[Test]
  public function test_store_validates_required_fields()
  {
    $response = $this->post(route('Item.store'), []);

    $response->assertSessionHasErrors(['name', 'price', 'cost']);
  }

  #[Test]
  public function test_store_validates_unique_name()
  {
    $existingItem = Item::factory()->create();

    $response = $this->post(route('Item.store'), [
      'name' => $existingItem->name,
      'price' => 50,
      'cost' => 25
    ]);

    $response->assertSessionHasErrors('name');
  }

  #[Test]
  public function test_store_validates_numeric_price_and_cost()
  {
    $response = $this->post(route('Item.store'), [
      'name' => 'Test Item',
      'price' => 'not-a-number',
      'cost' => 'also-not-a-number'
    ]);

    $response->assertSessionHasErrors(['price', 'cost']);
  }

  #[Test]
  public function test_store_validates_minimum_price_and_cost()
  {
    $response = $this->post(route('Item.store'), [
      'name' => 'Test Item',
      'price' => -10,
      'cost' => -5
    ]);

    $response->assertSessionHasErrors(['price', 'cost']);
  }

  #[Test]
  public function test_store_allows_zero_price_and_cost()
  {
    $itemData = [
      'name' => 'Free Item',
      'price' => 0,
      'cost' => 0
    ];

    $response = $this->post(route('Item.store'), $itemData);

    $response->assertRedirect(route('Item.create'));
    $this->assertDatabaseHas('items', $itemData);
  }

  #[Test]
  public function test_edit_returns_edit_form()
  {
    $item = Item::factory()->create();

    $response = $this->get(route('Item.edit', $item->id));

    $response->assertStatus(200);
    $response->assertViewIs('forms.edit-item');
    $response->assertViewHas('item', $item);
  }

  #[Test]
  public function test_edit_fails_with_invalid_id()
  {
    $response = $this->get(route('Item.edit', 999));
    $response->assertStatus(404);
  }

  #[Test]
  public function test_update_modifies_item_with_valid_data()
  {
    $item = Item::factory()->create();
    $newData = [
      'name' => 'Updated Item',
      'price' => 75.50,
      'cost' => 35.25,
      'description' => 'Updated description'
    ];

    $response = $this->patch(route('Item.update', $item->id), $newData);

    $response->assertRedirect(route('Item.index'));
    $this->assertDatabaseHas('items', array_merge(['id' => $item->id], $newData));
  }

  #[Test]
  public function test_update_validates_unique_name()
  {
    $item1 = Item::factory()->create();
    $item2 = Item::factory()->create();

    $response = $this->patch(route('Item.update', $item1->id), [
      'name' => $item2->name,
      'price' => 50,
      'cost' => 25
    ]);

    $response->assertSessionHasErrors('name');
  }

  #[Test]
  public function test_update_allows_keeping_same_name()
  {
    $item = Item::factory()->create();

    $response = $this->patch(route('Item.update', $item->id), [
      'name' => $item->name,
      'price' => 100,
      'cost' => 50
    ]);

    $response->assertRedirect(route('Item.index'));
    $response->assertSessionHasNoErrors();
  }

  #[Test]
  public function test_delete_single_item()
  {
    $item = Item::factory()->create();

    $response = $this->delete(route('Item.delete'), [
      'filter' => 'single',
      'search' => $item->id
    ]);

    $response->assertRedirect(route('Item.index'));
    $this->assertSoftDeleted('items', ['id' => $item->id]);
  }

  #[Test]
  public function test_delete_multiple_items_via_search()
  {
    $items = [
      Item::factory()->create(['name' => 'Test Item']),
      Item::factory()->create(['name' => 'Test Item 2']),
      Item::factory()->create(['name' => 'Test Item 3']),
    ];

    $response = $this->delete(route('Item.delete'), [
      'filter' => 'name',
      'search' => 'Test Item'
    ]);

    $response->assertRedirect(route('Item.index'));
    foreach ($items as $item) {
      $this->assertSoftDeleted('items', ['id' => $item->id]);
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

    $this->delete(route('Item.delete'), [
      'filter' => 'single',
      'search' => $item->id
    ]);

    $this->assertSoftDeleted('items', ['id' => $item->id]);
    $this->assertSoftDeleted('entries', ['id' => $entry->id]);
  }

  #[Test]
  public function test_records_redirects_to_entries_with_item_filter()
  {
    $item = Item::factory()->create();

    $response = $this->get(route('Item.records', $item->id));

    $response->assertRedirect(route('Entry.index'));
    $response->assertSessionHas('item', $item->name);
  }

  #[Test]
  public function test_search_with_name_filter()
  {
    $item1 = Item::factory()->create(['name' => 'Dental Crown']);
    $item2 = Item::factory()->create(['name' => 'Tooth Filling']);

    $response = $this->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
      ->get(route('Item.search') . '?' . http_build_query([
        'search' => 'Crown',
        'filter' => 'name'
      ]));

    $response->assertStatus(200);
    $response->assertJsonStructure(['body', 'footer', 'links']);
  }

  #[Test]
  public function test_search_with_all_filter()
  {
    $item = Item::factory()->create([
      'name' => 'Test Item',
      'description' => 'Special dental procedure'
    ]);

    $response = $this->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
      ->get(route('Item.search') . '?' . http_build_query([
        'search' => 'Special',
        'filter' => 'all'
      ]));

    $response->assertStatus(200);
    $response->assertJsonStructure(['body', 'footer', 'links']);
  }

  #[Test]
  public function test_search_with_price_filter()
  {
    $item = Item::factory()->create(['price' => 150.00]);

    $response = $this->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
      ->get(route('Item.search') . '?' . http_build_query([
        'search' => '150',
        'filter' => 'price'
      ]));

    $response->assertStatus(200);
  }

  #[Test]
  public function test_search_with_cost_filter()
  {
    $item = Item::factory()->create(['cost' => 75.00]);

    $response = $this->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
      ->get(route('Item.search') . '?' . http_build_query([
        'search' => '75',
        'filter' => 'cost'
      ]));

    $response->assertStatus(200);
  }

  #[Test]
  public function test_search_with_date_filters()
  {
    $item = Item::factory()->create();

    $response = $this->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
      ->get(route('Item.search') . '?' . http_build_query([
        'from_date' => now()->subDays(7)->format('Y-m-d'),
        'to_date' => now()->format('Y-m-d')
      ]));

    $response->assertStatus(200);
  }

  #[Test]
  public function test_search_redirects_back_for_non_ajax()
  {
    $response = $this->get(route('Item.search') . '?' . http_build_query([
      'search' => 'test'
    ]));

    $response->assertRedirect();
  }

  #[Test]
  public function test_trash_shows_deleted_items()
  {
    $item = Item::factory()->create();
    $item->delete();

    $response = $this->get(route('Item.trash'));

    $response->assertStatus(200);
    $response->assertViewIs('pages.items');
    $response->assertViewHas('items');
    $response->assertViewHas('trash', true);
  }

  #[Test]
  public function test_trash_returns_json_for_ajax_requests()
  {
    $item = Item::factory()->create();
    $item->delete();

    $response = $this->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
      ->get(route('Item.trash'));

    $response->assertStatus(200);
    $response->assertJsonStructure(['body', 'footer', 'links']);
  }

  #[Test]
  public function test_restore_single_item()
  {
    $item = Item::factory()->create();
    $item->delete();

    $response = $this->patch(route('Item.restore'), [
      'filter' => 'single',
      'search' => $item->id
    ]);

    $response->assertRedirect(route('Item.trash'));
    $response->assertSessionHas('success');
    $this->assertDatabaseHas('items', [
      'id' => $item->id,
      'deleted_at' => null
    ]);
  }

  #[Test]
  public function test_restore_fails_if_name_already_exists()
  {
    $activeItem = Item::factory()->create(['name' => 'Test Item']);
    $deletedItem = Item::factory()->create(['name' => 'Test Item']);
    $deletedItem->delete();

    $response = $this->patch(route('Item.restore'), [
      'filter' => 'single',
      'search' => $deletedItem->id
    ]);

    $response->assertRedirect(route('Item.trash'));
    $response->assertSessionHas('failed_items');
    $this->assertSoftDeleted('items', ['id' => $deletedItem->id]);
  }

  #[Test]
  public function test_restore_multiple_items()
  {
    $items = Item::factory(2)->create();
    foreach ($items as $item) {
      $item->delete();
    }

    $response = $this->patch(route('Item.restore'), [
      'filter' => 'all'
    ]);

    $response->assertRedirect(route('Item.trash'));
    $response->assertSessionHas('success');

    foreach ($items as $item) {
      $this->assertDatabaseHas('items', [
        'id' => $item->id,
        'deleted_at' => null
      ]);
    }
  }

  #[Test]
  public function test_restore_handles_invalid_item()
  {
    $response = $this->patch(route('Item.restore'), [
      'filter' => 'single',
      'search' => 999
    ]);

    $response->assertRedirect(route('Item.trash'));
    $response->assertSessionHas('failed_items');
  }

  #[Test]
  public function test_force_delete_single_item()
  {
    $item = Item::factory()->create();
    $item->delete();

    $response = $this->delete(route('Item.forceDelete'), [
      'filter' => 'single',
      'search' => $item->id
    ]);

    $response->assertRedirect(route('Item.trash'));
    $this->assertDatabaseMissing('items', ['id' => $item->id]);
  }

  #[Test]
  public function test_force_delete_multiple_items()
  {
    $items = Item::factory(2)->create();
    foreach ($items as $item) {
      $item->delete();
    }

    $response = $this->delete(route('Item.forceDelete'), [
      'filter' => 'all'
    ]);

    $response->assertRedirect(route('Item.trash'));

    foreach ($items as $item) {
      $this->assertDatabaseMissing('items', ['id' => $item->id]);
    }
  }

  #[Test]
  public function test_previous_url_session_handling_in_create()
  {
    $response = $this->withSession(['item_previous_url' => route('Item.index')])
      ->get(route('Item.create'));

    $response->assertStatus(200);
    $response->assertSessionHas('item_previous_url');
  }

  #[Test]
  public function test_store_redirects_to_previous_url_when_appropriate()
  {
    $response = $this->withSession(['item_previous_url' => '/some-other-page'])
      ->post(route('Item.store'), [
        'name' => 'Test Item',
        'price' => 50,
        'cost' => 25
      ]);

    $response->assertSessionMissing('item_previous_url');
  }

  #[Test]
  public function test_pagination_works_correctly()
  {
    Item::factory(60)->create();

    $response = $this->get(route('Item.index'));

    $response->assertStatus(200);
    $items = $response->viewData('items');
    $this->assertEquals(50, $items->count());
  }

  #[Test]
  public function test_item_with_entries_count()
  {
    $customer = Customer::factory()->create();
    $item = Item::factory()->create();
    Entry::factory(5)->create([
      'customer_id' => $customer->id,
      'item_id' => $item->id
    ]);

    $response = $this->get(route('Item.index'));

    $response->assertStatus(200);
    $items = $response->viewData('items');
    $this->assertEquals(5, $items->first()->entries_count);
  }

  #[Test]
  public function test_store_with_large_numbers()
  {
    $itemData = [
      'name' => 'Expensive Procedure',
      'price' => 9999.99,
      'cost' => 5000.50
    ];

    $response = $this->post(route('Item.store'), $itemData);

    $response->assertRedirect(route('Item.create'));
    $this->assertDatabaseHas('items', $itemData);
  }

  #[Test]
  public function test_search_by_description()
  {
    $item = Item::factory()->create(['description' => 'Root canal treatment']);

    $response = $this->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
      ->get(route('Item.search') . '?' . http_build_query([
        'search' => 'Root canal',
        'filter' => 'description'
      ]));

    $response->assertStatus(200);
  }

  #[Test]
  public function test_items_are_properly_soft_deleted_not_hard_deleted()
  {
    $item = Item::factory()->create();
    $originalId = $item->id;

    $this->delete(route('Item.delete'), [
      'filter' => 'single',
      'search' => $item->id
    ]);

    // Should still exist in database but with deleted_at timestamp
    $this->assertDatabaseHas('items', ['id' => $originalId]);
    $this->assertSoftDeleted('items', ['id' => $originalId]);

    // Should not be found in regular queries
    $this->assertNull(Item::find($originalId));

    // Should be found in trashed queries
    $this->assertNotNull(Item::withTrashed()->find($originalId));
  }
}
