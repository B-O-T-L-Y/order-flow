<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function store(Request $request, OrderService $orderService)
    {
        $userId = $request->user()->id;
        $orderService->createOrder($request->input('items'), $userId);

        return response()->json(['message' => 'Order created successfully.']);
    }
}
