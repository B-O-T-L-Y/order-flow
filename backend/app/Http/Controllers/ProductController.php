<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $products = Product::paginate(48);

        return response()->json([
                'message' => 'Products retrieved successfully.',
                'code' => 'PRODUCTS_FETCHED_SUCCESS'
            ] + $products->toArray());
    }
}
