<?php

namespace App\Http\Controllers\Swagger;

/**
 * @OA\Tag(
 *     name="Products",
 *     description="Operations related to products"
 * )
 */
class ProductController
{
    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="Get list of products",
     *     tags={"Products"},
     *     @OA\Response(response=200, description="Products retrieved successfully"),
     * )
     */
    public function index()
    {
    }
}
