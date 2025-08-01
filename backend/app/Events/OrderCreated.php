<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public readonly Order $order)
    {
    }

     public function broadcastOn(): array
    {
        return [
            new PrivateChannel('orders.' . $this->order->user_id),
            new PrivateChannel('admin.orders'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'order_id' => $this->order->id,
            'amount' => $this->order->amount,
            'status' => $this->order->status,
            'created_at' => $this->order->created_at->toDateTimeString(),
        ];
    }

    public function broadcastAs(): string
    {
        return 'order.created';
    }
}
