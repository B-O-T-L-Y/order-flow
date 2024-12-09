<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

readonly class OrderService
{
    private const CACHE_TTl = 3600;

    public function getUserOrdersWithFilters(array $filters, int $userId): LengthAwarePaginator
    {
        $cacheKey = $this->generateCacheKey($filters, $userId);

        return Cache::remember($cacheKey, self::CACHE_TTl, function () use ($filters, $userId) {
            $query = Order::where('user_id', $userId)
                ->with('products')
                ->withSum('products as total_sum', 'order_product.total_price');

            // Filtering by status
            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            // Filtering by date
            if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
                $query->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
            }


            if (!empty($filters['min_total'])) {
                $query->where('total_sum', '>=', $filters['min_total']);
            }

            if (!empty($filters['max_total'])) {
                $query->where('total_sum', '<=', $filters['max_total']);
            }

            return $query->paginate(10);
        });
    }

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

    public function clearUserOrderCache(array $filters, int $userId): void
    {
        $cacheKey = $this->generateCacheKey($filters, $userId);
        Cache::forget($cacheKey);
    }

    private function generateCacheKey(array $filters, int $userId): string
    {
        $key = "user:{$userId}:orders";

        if (!empty($filters)) {
            $key .= ':' . md5(json_encode($filters));
        }

        return $key;
    }
}
