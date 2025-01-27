<?php

namespace Tests\Unit;

use App\Events\OrderCreated;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_can_view_their_orders(): void
    {
        $user = User::factory()->create();
        Order::factory()->count(5)->create(['user_id' => $user->id]);

        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/orders');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'code',
                'data' => [
                    '*' => ['id', 'user_id', 'amount', 'status', 'created_at', 'updated_at']
                ]
            ]);
    }

    public function test_user_cannot_view_other_users_orders(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        Order::factory()->create(['user_id' => $otherUser->id]);

        $response_login = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $response_login->json('token'))
            ->getJson('/api/orders');

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    public function test_admin_can_view_all_orders(): void
    {
        $admin = User::factory()->admin()->create();
        Order::factory()->count(5)->create(['user_id' => $admin->id]);

        $token = $admin->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/orders?user_id=' . $admin->id);

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    public function test_user_can_create_order(): void
    {
        Event::fake();

        $user = User::factory()->create();
        $token = $user->createToken('test_token')->plainTextToken;

        $product = Product::factory()->create([
            'name' => fake()->name(),
            'price' => 100,
            'description' => fake()->text(),
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/orders', [
                'products' => [
                    [
                        'product_id' => $product->id,
                        'quantity' => 2
                    ],
                ],
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'user_id',
                    'amount',
                    'status',
                    'created_at',
                    'updated_at',
                ],
                'message',
                'code',
            ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'amount' => 200,
            'status' => 'new',
        ]);

        $this->assertDatabaseHas('order_product', [
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 100,
            'total_price' => 200,
        ]);

        Event::assertDispatched(OrderCreated::class);
    }
}
