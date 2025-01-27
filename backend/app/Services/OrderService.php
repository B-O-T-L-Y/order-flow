<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

readonly class OrderService
{
    private const CACHE_TTl = 3600;

    public function getUserOrdersWithFilters(array $filters, int $userId, bool $isAdmin, int $page = 1): LengthAwarePaginator
    {
        $cacheKey = $this->generateCacheKey($filters, $userId, $isAdmin, $page);

        return Cache::remember($cacheKey, self::CACHE_TTl, function () use ($filters, $userId, $isAdmin) {
            $query = Order::with(['products', 'user']);

            if (!$isAdmin) {
                $query->where('user_id', $userId);
            } else {
                if (!empty($filters['user_id'])) {
                    $query->where('user_id', $filters['user_id']);
                }
            }

            // Filtering by status
            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            // Filtering by date
            if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
                $query->whereBetween(DB::raw('DATE(created_at)'), [$filters['start_date'], $filters['end_date']]);
            }

            if (!empty($filters['min_amount'])) {
                $query->where('amount', '>=', $filters['min_amount']);
            }

            if (!empty($filters['max_amount'])) {
                $query->where('amount', '<=', $filters['max_amount']);
            }

            return $query->paginate(50);
        });
    }

    public function getAllFilteredOrdersQuery(array $filters, int $userId, bool $isAdmin): Builder
    {
        $query = Order::with(['products', 'user']);

        if (!$isAdmin) {
            $query->where('user_id', $userId);
        } else {
            if (!empty($filters['user_id'])) {
                $query->where('user_id', $filters['user_id']);
            }
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween(DB::raw('DATE(created_at)'), [
                $filters['start_date'],
                $filters['end_date']
            ]);
        }

        if (!empty($filters['min_amount'])) {
            $query->where('amount', '>=', $filters['min_amount']);
        }

        if (!empty($filters['max_amount'])) {
            $query->where('amount', '<=', $filters['max_amount']);
        }

        return $query;
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
                'amount' => 0,
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
        $versionKey = 'user:user_%s:orders_version';

        Cache::increment(sprintf($versionKey, $userId));
        Cache::increment(sprintf($versionKey, 'admin'));
    }

    private function generateCacheKey(array $filters, int $userId, bool $isAdmin, int $page = 1): string
    {
        $userId = $isAdmin ? 'admin' : $userId;

        $version = Cache::rememberForever("user:user_{$userId}:orders_version", fn() => 1);
        $filterHash = md5(json_encode($filters));

        return "orders:user_{$userId}:orders:v{$version}:{$filterHash}:page={$page}";
    }
}
