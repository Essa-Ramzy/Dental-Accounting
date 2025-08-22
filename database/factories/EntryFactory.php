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
    $amount = 0;
    $teeth = collect($quadrants)
      ->random(rand(1, 4))
      ->map(function ($q) use (&$amount) {
        $numbers = collect(range(1, 8))->random(rand(1, 8))->implode('');
        $amount += strlen($numbers);
        return "$q-$numbers";
      })
      ->implode(', ');
    $unit_price = $this->faker->randomFloat(2, 200, 800);
    $discount = $this->faker->randomFloat(2, 0, $unit_price * $amount);
    $cost = $this->faker->randomFloat(2, $unit_price * 0.5, $unit_price * 0.8) * $amount;

    return [
      'customer_id' => Customer::factory(),
      'item_id' => Item::factory(),
      'date' => $this->faker->dateTimeBetween('-2 month', '-1 day'),
      'teeth' => $teeth,
      'amount' => $amount,
      'unit_price' => $unit_price,
      'discount' => $discount,
      'price' => $unit_price * $amount - $discount,
      'cost' => $cost
    ];
  }

  public function withItem(Item $item)
  {
    return $this->afterMaking(function ($entry) use ($item) {
      $entry->unit_price = $item->price;
      $entry->cost = $item->cost * $entry->amount;
      $entry->discount = $this->faker->randomFloat(2, 0, $item->price * $entry->amount);
      $entry->price = $item->price * $entry->amount - $entry->discount;
    });
  }
}
