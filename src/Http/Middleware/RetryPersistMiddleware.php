<?php

namespace ProjectSaturnStudios\EventSourcing\Http\Middleware;

use Closure;
use PDOException;
use Illuminate\Support\Facades\DB;
use Spatie\EventSourcing\Commands\Middleware;
use Spatie\EventSourcing\Commands\Exceptions\CommandFailed;
use Spatie\EventSourcing\AggregateRoots\Exceptions\CouldNotPersistAggregate;
use Symfony\Component\VarDumper\VarDumper;

class RetryPersistMiddleware implements Middleware
{
    private int $currentTries = 0;

    public function __construct(private int $maximumTries = 3) {

    }

    public function handle(object $command, Closure $next): mixed
    {
        $rand = rand(5, 10);
        try {
            $this->currentTries += 1;
            return $next($command);
        } catch (CouldNotPersistAggregate $e) {
            if ($this->currentTries >= $this->maximumTries) {
                VarDumper::dump($e);
                throw new CommandFailed($command, $this->currentTries);
            }

            return $this->handle($command, $next);
        }
        catch (PDOException $e) {
            if ($this->currentTries >= $this->maximumTries) {
                VarDumper::dump($e);
                throw new CommandFailed($command, $this->currentTries);
            }

            //sleep($rand);
            DB::disconnect();
            sleep($rand);
            return $this->handle($command, $next);
        }
    }
}
