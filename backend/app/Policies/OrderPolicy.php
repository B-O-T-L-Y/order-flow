<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    public function view(User $user, Order $order): bool
    {
        return $user->is_admin || $user->id === $order->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Order $order): bool
    {
        return $user->is_admin || $user->id === $order->user_id;
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->is_admin;
    }
}
