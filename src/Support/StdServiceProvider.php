<?php

namespace Combustion\StandardLib\Support;

use Combustion\StandardLib\Log;
use Monolog\Logger as Monolog;
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
            return new Log(new Monolog($app->environment()), $app['events']);
        });

        $this->app->singleton(SystemEvents::class, function (Application $app, array $params) {
            return new SystemEvents($app);
        });
    }
}
