<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stock>
 */
class StockFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition()
  {
    return [
      // それぞれのモデルにあるhasfactoryがあるので、model::factory()でそれぞれのモデルのコラムに準じたfactoryができる。
      'product_id' => $this->faker->numberBetween(1, 100),
      'type' => $this->faker->numberBetween(1, 2), 'quantity' => $this->faker->randomNumber,
    ];
  }
}
