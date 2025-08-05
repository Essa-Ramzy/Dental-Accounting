<?php
namespace Database\Factories;

use App\Models\Entry;
use App\Models\Customer;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntryFactory extends Factory
{
  protected $model = Entry::class;

  public function definition()
  {
    $quadrants = ['UR', 'UL', 'LL', 'LR'];
    $teeth = collect($quadrants)
      ->random(rand(1, 3))
      ->map(function ($q) {
        $numbers = collect(range(1, 8))->random(rand(1, 2))->implode('');
        return "$q-$numbers";
      })->implode(', ');
    return [
      'customer_id' => Customer::factory(),
      'item_id' => Item::factory(),
      'date' => $this->faker->date(),
      'teeth' => $teeth,
      'amount' => $this->faker->numberBetween(1, 10),
      'unit_price' => $this->faker->randomFloat(2, 10, 500),
      'discount' => $this->faker->randomFloat(2, 0, 100),
      'price' => $this->faker->randomFloat(2, 10, 500),
      'cost' => $this->faker->randomFloat(2, 5, 400),
    ];
  }
}
