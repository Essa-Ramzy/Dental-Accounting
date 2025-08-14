<?php
namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
  protected $model = Item::class;

  public function definition()
  {
    $price = $this->faker->randomFloat(2, 200, 800);
    return [
      'name' => $this->faker->unique()->word,
      'price' => $price,
      'cost' => $this->faker->randomFloat(2, $price * 0.5, $price * 0.8),
      'description' => $this->faker->sentence(rand(5, 25)),
      'created_at' => $this->faker->dateTimeBetween('-2 month', 'now'),
      'updated_at' => $this->faker->dateTimeBetween('-2 month', 'now'),
    ];
  }
}
