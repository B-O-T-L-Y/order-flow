<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public string $message;

    public function __construct(string $message)
    {
        $this->message = $message;

        \Log::info('Message sent: ' . $message);
    }

    // Указываем, на какой канал будет уходить событие
    public function broadcastOn(): Channel
    {
        return new Channel('chat-room');
    }

    // (Необязательный) метод, задающий имя события
    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        return ['message' => $this->message, 'timestamp' => now()];
    }
}
