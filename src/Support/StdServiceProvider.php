<?php

namespace Combustion\StandardLib\Support;

use Monolog\Logger as Monolog;
use Combustion\StandardLib\Log;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Combustion\StandardLib\UploadManager;
use Illuminate\Filesystem\FilesystemManager;

use Combustion\StandardLib\Services\SystemHooks\SystemEvents;

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

        $this->app->singleton(SystemEvents::class, function (Application $app, array $params) {
            return new SystemEvents($app);
        });
    }

    public function boot()
    {
        $useLog = \Config::get('standardlib.use-log');

        if ($useLog) {
            $this->app->instance('log', $this->app->make(Log::class));
        }
    }
}
