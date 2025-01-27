<?php

namespace Tests\Unit;

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_fetch_paginated_products(): void
    {
        Product::factory()->count(50)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'message' => 'Products retrieved successfully.',
            'code' => 'PRODUCTS_FETCHED_SUCCESS',
        ]);

        $response->assertJsonCount(48, 'data');
    }

    public function test_returns_empty_list_when_no_products_exist(): void
    {
        Product::query()->delete();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200);

        $response->assertJsonCount(0, 'data');

        $response->assertJsonFragment([
            'message' => 'Products retrieved successfully.',
            'code' => 'PRODUCTS_FETCHED_SUCCESS',
        ]);
    }
}
