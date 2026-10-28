<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AntrianUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $jadwalId;
    public $antrianSekarang;

    public function __construct($jadwalId, $antrianSekarang)
    {
        $this->jadwalId = $jadwalId;
        $this->antrianSekarang = $antrianSekarang;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('antrian-channel'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'AntrianUpdated';
    }
}
