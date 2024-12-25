<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private readonly OrderService $orderService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'start_date', 'end_date', 'min_amount', 'max_amount']);
        $page = request()->input('page', 1);
        $orders = $this->orderService->getUserOrdersWithFilters($filters, $request->user()->id, $page);
        return response()->json([
                'message' => 'Orders retrieved successfully.',
                'code' => 'ORDERS_FETCHED_SUCCESS'
            ] + $orders->toArray());
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Throwable
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $userId = $request->user()->id;
        $order = $this->orderService->createOrder($request->validated(), $userId);

        return response()->json([
            'data' => $order,
            'message' => 'Order created successfully.',
            'code' => 'ORDER_CREATED_SUCCESS'
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     * @throws AuthorizationException
     */
    public function show(Order $order): JsonResponse
    {
        $this->authorize('view', $order);

        return response()->json([
            'data' => $order->load('products'),
            'amount' => $order->amount,
            'message' => 'Order retrieved successfully.',
            'code' => 'ORDER_FETCHED_SUCCESS'
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @throws AuthorizationException
     */
    public function update(OrderRequest $request, Order $order): JsonResponse
    {
        $this->authorize('update', $order);
        $updateOrder = $this->orderService->updateOrderStatus($order, $request->input('status'));

        return response()->json([
            'data' => $updateOrder,
            'message' => 'Order updated successfully.',
            'code' => 'ORDER_UPDATED_SUCCESS'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @throws AuthorizationException
     */
    public function destroy(Order $order): JsonResponse
    {
        $this->authorize('delete', $order);
        $this->orderService->deleteOrder($order);

        return response()->json([
            'data' => null,
            'message' => 'Order deleted successfully.',
            'code' => 'ORDER_DELETED_SUCCESS'
        ]);
    }
}
