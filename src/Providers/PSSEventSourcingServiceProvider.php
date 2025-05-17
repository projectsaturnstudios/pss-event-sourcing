<?php

namespace ProjectSaturnStudios\EventSourcing\Providers;

use Illuminate\Support\ServiceProvider;

class PSSEventSourcingServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/event-sourcing.php', 'event-sourcing');
    }

    public function boot() : void
    {

    }
}
