<?php

namespace App\Events;

use App\Models\Export;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExportProgress implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly Export $export,
        public readonly int $progress,
        public readonly int $total,
    )
    {
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('admin.exports');
    }

    public function broadcastAs(): string
    {
        return 'export.progress';
    }
}
