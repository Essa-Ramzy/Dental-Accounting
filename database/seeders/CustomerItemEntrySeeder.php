<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Entry;

class CustomerItemEntrySeeder extends Seeder
{
  public function run()
  {
    $customers = Customer::factory()->count(100)->create();
    $items = Item::factory()->count(100)->create();
    foreach ($customers as $customer) {
      foreach ($items->random(30) as $item) {
        Entry::factory()
          ->withItem($item)
          ->create([
            'customer_id' => $customer->id,
            'item_id' => $item->id
          ]);
      }
    }
  }
}
