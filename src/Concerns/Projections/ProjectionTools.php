<?php

namespace ProjectSaturnStudios\EventSourcing\Concerns\Projections;

trait ProjectionTools
{
    public static function loadModel(string $uuid) : static | null
    {
        return (new self)->writeable()->where(self::$key, '=', $uuid)->first();
    }

    public function updateDate(?string $date  = null) : void
    {
        if($date)
        {
            $this->created_at = $date;
            $this->save();
        }
    }
}
