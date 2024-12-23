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

    public function getUserOrdersWithFilters(array $filters, int $userId, int $page = 1): LengthAwarePaginator
    {
        $cacheKey = $this->generateCacheKey($filters, $userId, $page);

        return Cache::remember($cacheKey, self::CACHE_TTl, function () use ($filters, $userId) {
            $query = Order::where('user_id', $userId)
                ->with(['products', 'user'])
                ->withSum('products as total_sum', 'order_product.total_price');

            // Filtering by status
            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            // Filtering by date
            if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
                $query->whereBetween(DB::raw('DATE(created_at)'), [$filters['start_date'], $filters['end_date']]);
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

            $amount = 0;

            foreach ($data['products'] as $productData) {
                /** @var Product $product */
                $product = Product::findOrFail($productData['product_id']);
                $quantity = $productData['quantity'];
                $lineTotal = $product->price * $quantity;
                $amount += $lineTotal;

                $order->products()->attach($product->id, [
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $quantity,
                    'total_price' => $lineTotal,
                ]);
            }

            $order->update(compact('amount'));

            $this->clearUserOrderCache($userId);

            return $order;
        });
    }

    public function updateOrderStatus(Order $order, string $status): Order
    {
        $order->update(['status' => $status]);

        $this->clearUserOrderCache($order->user_id);

        return $order;
    }

    public function deleteOrder(Order $order): void
    {
        $order->delete();

        $this->clearUserOrderCache($order->user_id);
    }

    private function clearUserOrderCache(int $userId): void
    {
        Cache::increment("user:{$userId}:orders_version");
    }

    private function generateCacheKey(array $filters, int $userId, int $page = 1): string
    {
        $version = Cache::get("user:{$userId}:orders_version", 1);
        $filterHash = md5(json_encode($filters));

        return "user:{$userId}:orders:v{$version}:{$filterHash}:page={$page}";
    }
}
