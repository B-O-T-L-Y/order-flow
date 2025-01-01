<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(18);

        return response()->json([
                'message' => 'Products retrieved successfully.',
                'code' => 'PRODUCTS_FETCHED_SUCCESS'
            ] + $products->toArray());
    }
}
