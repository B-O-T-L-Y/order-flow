<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat-room', function ($user) {
    return true; // Разрешаем всем
});

Broadcast::channel('orders', function ($user) {
    return $user->id !== null;
});
