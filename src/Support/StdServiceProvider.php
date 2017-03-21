<?php

namespace Combustion\StandardLib\Support;

use Monolog\Logger as Monolog;
use Combustion\StandardLib\Log;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Combustion\StandardLib\UploadManager;
use Illuminate\Filesystem\FilesystemManager;
use Combustion\StandardLib\Services\Data\Slicer;
use Combustion\StandardLib\Services\DeepLinks\DeepLinkService;
use Combustion\StandardLib\Services\SystemEvents\SystemEventsService;

class StdServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(UploadManager::class, function (Application $app, array $params = []) {
            $config     = $app['config']['uploads.upload-manager'];
            $storage    = $app->make(FilesystemManager::class);

            return new UploadManager($config, $storage->cloud(), $app->make(Log::class));
        });

        $this->app->singleton(Log::class, function (Application $app, array $params = []) {
            $configurator   = new LogConfigurator();
            $log            = new Log(new Monolog($this->app->environment()), $app['events']);

            return $configurator->configure($log, $app);
        });

        $this->app->singleton(SystemEventsService::class, function (Application $app, array $params) {
            $e = new SystemEventsService($app);
            $l = $app['config']['events'] ?: [];

            // Registering listeners for events in the cart manager
            foreach ($l as $event => $listeners) {
                foreach ($listeners as $listener) {
                    $e->on($event, $listener);
                }
            }

            return $e;
        });

        $this->app->singleton(DeepLinkService::class, function (Application $app, array $params) {
            return new DeepLinkService();
        });

        $this->app->singleton(Slicer::class, function (Application $app, array $params) {
            return new Slicer();
        });
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Services/DeepLinks/Support/Migrations');

        $useLog = \Config::get('standardlib.use-log');

        if ($useLog) {
            $this->app->instance('log', $this->app->make(Log::class));
        }
    }
}
