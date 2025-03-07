<?php

use ProjectSaturnStudios\EventSourcing\EventCommands\EventCommand;
use ProjectSaturnStudios\EventSourcing\Actions\DispatchEventCommand;
use ProjectSaturnStudios\EventSourcing\Middleware\RetryPersistMiddleware;

if(!function_exists('app_queue'))
{
    function app_queue(string $key) : string
    {
        $results = $key;

        $prefix = env('QS_PREFIX', '');

        if(!empty($prefix))
        {
            $results = "{$prefix}-{$key}";
        }

        return $results;
    }
}

if(!function_exists('event_command'))
{
    function event_command(EventCommand $cmd, array $middleware = [new RetryPersistMiddleware()]) : void
    {
        (new DispatchEventCommand())($cmd, $middleware);
    }
}
