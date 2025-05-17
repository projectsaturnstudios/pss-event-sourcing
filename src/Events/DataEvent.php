<?php

namespace ProjectSaturnStudios\EventSourcing\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Spatie\LaravelData\Concerns\BaseData;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Spatie\LaravelData\Concerns\TransformableData;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;
use Spatie\LaravelData\Contracts\BaseData as BaseDataContract;
use Spatie\LaravelData\Contracts\TransformableData as TransformableDataContract;

abstract class DataEvent extends ShouldBeStored implements BaseDataContract, TransformableDataContract//, ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    use BaseData, TransformableData;

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn() : Channel
    {
        return new Channel('entity-events');
    }

    public function broadcastQueue() : string
    {
        return app_queue('events');
    }
}
