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
    public function createOrder(array $data, int $userId): Order
    {
        return DB::transaction(function () use ($data, $userId) {
            $order = Order::create([
                'user_id' => $userId,
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

        // Filtering by status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filtering by date
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
        }

        if (!empty($filters['min_total']) && !empty($filters['max_total'])) {
            $query->whereHas('products', function ($subQuery) use ($filters) {
                $subQuery->selectRaw('SUM(order_products.total_price) as total_sum)')
                    ->groupBy('order_product.order_id');

                if (!empty($filters['min_total'])) {
                    $subQuery->havingRaw('total_sum >= ' . $filters['min_total']);
                }

                if (!empty($filters['max_total'])) {
                    $subQuery->havingRaw('total_sum <= ' . $filters['max_total']);
                }
            });
        }

        return $query->with('products')->paginate(10);
    }
}
