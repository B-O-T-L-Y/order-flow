<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use DatabaseTransactions;

    public function test_product_can_be_created(): void
    {
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'price' => 100.00,
            'description' => 'This is a test product.',
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Test Product',
            'price' => 100.00,
            'description' => 'This is a test product.',
        ]);
    }

    public function test_product_can_be_associated_with_orders(): void
    {
        $product = Product::factory()->create();
        $order = Order::factory()->create();

        $order->products()->attach($product, [
            'product_name' => $product->name,
            'price' => $product->price,
            'quantity' => 2,
            'total_price' => $product->price * 2,
        ]);

        $this->assertCount(1, $product->orders);
        $this->assertDatabaseHas('order_product', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'total_price' => $product->price * 2,
        ]);
    }
}
