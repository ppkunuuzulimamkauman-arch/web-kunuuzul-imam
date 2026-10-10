<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class SlideChanged implements ShouldBroadcastNow
{
    public function __construct(
        public string $mode, // 'tahan' | 'auto'
        public int $index = 0, // 0-4, dipakai saat mode tahan
    ) {}

    public function broadcastOn(): array
    {
        return [new Channel('pondok-hero')];
    }

    public function broadcastAs(): string
    {
        return 'slide.changed';
    }

    public function broadcastWith(): array
    {
        return [
            'mode' => $this->mode,
            'index' => $this->index,
            'at' => (int) (microtime(true) * 1000),
        ];
    }
}
