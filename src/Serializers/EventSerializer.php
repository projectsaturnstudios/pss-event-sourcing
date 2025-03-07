<?php

namespace ProjectSaturnStudios\EventSourcing\Serializers;

use ProjectSaturnStudios\EventSourcing\Events\DataEvent;
use Spatie\EventSourcing\EventSerializers\JsonEventSerializer;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class EventSerializer extends JsonEventSerializer
{
    public function serialize(DataEvent|ShouldBeStored $event): string
    {
        if ($event instanceof DataEvent) {
            return $event->toJson();
        }

        return parent::serialize($event);
    }

    public function deserialize(string $eventClass, string $json, int $version, ?string $metadata = null): ShouldBeStored
    {
        if (is_subclass_of($eventClass, DataEvent::class)) {
            return $eventClass::from(json_decode($json, true));
        }

        return parent::deserialize($eventClass, $json, $version, $metadata);
    }


}

