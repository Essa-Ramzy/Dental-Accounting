<?php
namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
  protected $model = Item::class;

  public function definition()
  {
    return [
      'name' => $this->faker->unique()->word,
      'price' => $this->faker->randomFloat(2, 10, 500),
      'cost' => $this->faker->randomFloat(2, 5, 400),
      'description' => $this->faker->sentence,
    ];
  }
}
