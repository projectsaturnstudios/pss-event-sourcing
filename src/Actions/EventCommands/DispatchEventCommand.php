<?php

namespace ProjectSaturnStudios\EventSourcing\Actions\EventCommands;

use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\EventSourcing\Commands\CommandBus;
use ProjectSaturnStudios\EventSourcing\EventCommands\EventCommand;

class DispatchEventCommand
{
    use AsAction;

    /**
     * @param EventCommand $arguments
     * @param array $middleware
     * @return void
     */
    public function __invoke(EventCommand $arguments, array $middleware = []): void
    {
        $this->handle($arguments, $middleware);
    }

    /**
     * @param EventCommand $command
     * @param array $middleware
     * @return void
     */
    public function handle(EventCommand $command, array $middleware = []): void
    {
        $command_bus = (new CommandBus());

        if (count($middleware) > 0) {
            $command_bus = $command_bus->middleware(...$middleware);
        }

        $command_bus->dispatch($command);
    }
}
