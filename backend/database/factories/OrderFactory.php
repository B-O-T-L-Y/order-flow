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
            'status' => fake()->randomElement(['new', 'processing', 'shipped', 'delivered']),
            'amount' => 0,
        ];
    }

    public function configure(): OrderFactory|Factory
    {
        return $this->afterCreating(function (Order $order) {
            $products = Product::factory()->count(rand(1, 5))->create();
            $amount = 0;

            /** @var Product $product */
            foreach ($products as $product) {
                $quantity = rand(1, 5);
                $lineTotal = $product->price * $quantity;
                $amount += $lineTotal;

                $order->products()->attach($product, [
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $quantity,
                    'total_price' => $lineTotal,
                ]);
            }

            $order->update(compact('amount'));
        });
    }
}
