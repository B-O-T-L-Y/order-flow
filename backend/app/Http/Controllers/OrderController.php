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
        $filters = $request->only(['status', 'start_date', 'end_date', 'min_total', 'max_total']);
        $orders = $this->orderService->getUserOrdersWithFilters($filters, $request->user()->id);

        return response()->json(['data' => $orders]);
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Throwable
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $userId = $request->user()->id;
        $order = $this->orderService->createOrder($request->validated(), $userId);

        return response()->json(['data' => $order], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     * @throws AuthorizationException
     */
    public function show(Order $order): JsonResponse
    {
        $this->authorize('view', $order);

        return response()->json(['data' => $order->load('products')]);
    }

    /**
     * Update the specified resource in storage.
     * @throws AuthorizationException
     */
    public function update(OrderRequest $request, Order $order): JsonResponse
    {
        $this->authorize('update', $order);
        $updateOrder = $this->orderService->updateOrderStatus($order, $request->input('status'));

        return response()->json(['data' => $updateOrder]);
    }

    /**
     * Remove the specified resource from storage.
     * @throws AuthorizationException
     */
    public function destroy(Order $order): JsonResponse
    {
        $this->authorize('delete', $order);
        $order->delete();

        return response()->json([
            'success' => [
                'message' => 'Order deleted successfully.',
                'code' => 'ORDER_DELETED_SUCCESS',
            ]
        ]);
    }
}
