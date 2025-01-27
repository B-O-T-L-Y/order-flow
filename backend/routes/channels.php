<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('orders.{userId}', function ($user, $userId) {
    return (int)$user->id === (int)$userId && !$user->is_admin;
});

Broadcast::channel('admin.orders', function ($user) {
    return $user->is_admin;
});

Broadcast::channel('admin.exports', function ($user) {
    return $user->is_admin;
});
