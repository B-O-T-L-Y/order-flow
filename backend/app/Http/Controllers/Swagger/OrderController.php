<?php

namespace App\Http\Controllers\Swagger;

/**
 * @OA\Tag(
 *     name="Orders",
 *     description="Operations related to user orders"
 * )
 */
class OrderController
{
    /**
     * @OA\Get(
     *     path="/api/orders",
     *     summary="Get list of orders",
     *     security={{"sanctum":{}}},
     *     tags={"Orders"},
     *     @OA\Response(response=200, description="Orders retrieved successfully"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index()
    {
    }

    /**
     * @OA\Post(
     *     path="/api/orders",
     *     summary="Create a new order",
     *     security={{"sanctum":{}}},
     *     tags={"Orders"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"products"},
     *             @OA\Property(property="products", type="array", @OA\Items(type="integer"))
     *         )
     *     ),
     *     @OA\Response(response=201, description="Order created successfully"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store()
    {
    }

    /**
     * @OA\Delete(
     *     path="/api/orders/{order}",
     *     summary="Delete an order",
     *     security={{"sanctum":{}}},
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="order",
     *         in="path",
     *         required=true,
     *         description="Order ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Order deleted successfully"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Unauthorized action"),
     *     @OA\Response(response=404, description="Order not found")
     * )
     */
    public function destroy()
    {
    }
}
