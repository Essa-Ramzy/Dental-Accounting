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
    $customers = Customer::factory()->count(10)->create();
    $items = Item::factory()->count(10)->create();
    foreach ($customers as $customer) {
      foreach ($items->random(3) as $item) {
        Entry::factory()->create([
          'customer_id' => $customer->id,
          'item_id' => $item->id,
        ]);
      }
    }
  }
}
