<?php

namespace ProjectSaturnStudios\EventSourcing\Providers;

use Carbon\Laravel\ServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PSSEventSourcingServiceProvider extends PackageServiceProvider
{
    protected array $merge_configs = [
        'event-sourcing' => '/../config/event-sourcing.php',
    ];

    public function configurePackage(Package $package): void
    {
        $package
            ->name('pss-event-sourcing')
            ;
    }

    public function packageRegistered(): void
    {

    }

    public function packageBooted(): void
    {
        $this->mergeConfigs();
    }

    public function mergeConfigs(): void
    {
        foreach ($this->merge_configs as $key => $config_path) {
            $this->mergeConfigFrom($this->package->basePath($config_path), $key);
        }
    }
}
