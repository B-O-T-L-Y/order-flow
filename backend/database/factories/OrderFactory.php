<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::query()->inRandomOrder()->first()->id ?? User::factory(),
            'status' => fake()->randomElement(['new', 'in_progress', 'shipped', 'delivered']),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            $products = Product::factory()->count(rand(1, 5))->create();

            foreach ($products as $product) {
                $amount = rand(1, 5);

                $order->products()->attach($product, [
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'amount' => $amount,
                    'total_price' => $product->price * $amount,
                ]);
            }
        });
    }
}
