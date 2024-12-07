<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

readonly class OrderService
{
    /**
     * @throws \Throwable
     */
    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $order = Order::create([
                'user_id' => $data['user_id'],
                'status' => 'new',
            ]);

            foreach ($data['products'] as $productData) {
                /** @var Product $product */
                $product = Product::findOrFail($productData['product_id']);
                $amount = $productData['amount'];
                $order->products()->attach($product->id, [
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'amount' => $amount,
                    'total_price' => $product->price * $amount,
                ]);
            }

            return $order;
        });
    }

    public function updateOrderStatus(Order $order, string $status): Order
    {
        $order->update(['status' => $status]);

        return $order;
    }

//    public function getUserOrders(int $userId): iterable
//    {
//        return Order::where('user_id', $userId)->with('products')->get();
//    }

    public function getUserOrdersWithFilters(array $filters, int $userId): LengthAwarePaginator
    {
        $query = Order::where('user_id', $userId);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->with('products')->paginate(10);
    }
}
