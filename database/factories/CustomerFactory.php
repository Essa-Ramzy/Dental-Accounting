<?php
namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
  protected $model = Customer::class;

  public function definition()
  {
    return [
      'name' => $this->faker->unique()->name,
      'created_at' => $this->faker->dateTimeBetween('-2 month', 'now'),
      'updated_at' => $this->faker->dateTimeBetween('-2 month', 'now'),
    ];
  }
}
