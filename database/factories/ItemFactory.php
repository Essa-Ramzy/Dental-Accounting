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
      'price' => $this->faker->numberBetween(400, 800),
      'cost' => $this->faker->numberBetween(200, 400),
      'description' => $this->faker->sentence,
      'created_at' => $this->faker->dateTimeBetween('-2 month', 'now'),
      'updated_at' => $this->faker->dateTimeBetween('-2 month', 'now'),
    ];
  }
}
