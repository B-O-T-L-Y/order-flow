<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
        \Log::info("📡 Broadcast MessageSent: " . $message);
    }

    // Указываем, на какой канал будет уходить событие
    public function broadcastOn()
    {
        return new Channel('chat-room');
    }

    // (Необязательный) метод, задающий имя события
    public function broadcastAs()
    {
        return 'message.sent';
    }
}
