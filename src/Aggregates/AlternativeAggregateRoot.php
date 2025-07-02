<?php

namespace ProjectSaturnStudios\EventSourcing\Aggregates;

use Spatie\EventSourcing\AggregateRoots\AggregateRoot;
use Spatie\EventSourcing\Snapshots\SnapshotRepository;
use ProjectSaturnStudios\EventSourcing\Concerns\Aggregates\AggyHasNotes;
use Spatie\EventSourcing\StoredEvents\Repositories\StoredEventRepository;

class AlternativeAggregateRoot extends AggregateRoot
{
    use AggyHasNotes;
    protected string $event_repo;
    protected string $snapshot_repo;

    protected function getStoredEventRepository(): StoredEventRepository
    {
        return app()->make(
            abstract: $this->event_repo
        );
    }

    protected function getSnapshotRepository(): SnapshotRepository
    {
        $repo = app()->make(
            abstract: $this->snapshot_repo
        );

        return$repo;
    }
}
