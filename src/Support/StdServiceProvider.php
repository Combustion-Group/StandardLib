<?php

namespace Combustion\StandardLib\Support;

use Combustion\StandardLib\Exceptions\ServiceBuilderException;
use Monolog\Logger as Monolog;
use Combustion\StandardLib\Log;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\DatabaseManager;
use Combustion\StandardLib\UploadManager;
use Illuminate\Filesystem\FilesystemManager;
use Combustion\StandardLib\Services\Data\OneToMany;
use Combustion\StandardLib\Services\DeepLinks\DeepLinkService;
use Combustion\StandardLib\Services\SystemEvents\SystemEventsService;
use Combustion\StandardLib\Services\Data\OneToManyRelationshipGenerator;

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

            $monolog   = $app->make(\Illuminate\Log\Writer::class)->getMonolog();
            $syslog    = new \Monolog\Handler\SyslogUdpHandler("logs5.papertrailapp.com", 11586);
            $formatter = new \Monolog\Formatter\LineFormatter('%channel%.%level_name%: %message% %extra%');

            $syslog->setFormatter($formatter);
            $monolog->pushHandler($syslog);

            $configurator   = new LogConfigurator();
            $log            = new Log($monolog, $app['events']);

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

        $this->app->bind(OneToMany::class, function (Application $app, array $params) {
            return new OneToMany();
        });

        $this->app->bind(OneToManyRelationshipGenerator::class, function (Application $app, array $params)
        {
            if (!array_key_exists('builder', $params)) {
                throw new ServiceBuilderException("Cannot build OneToManyRelationshipBuilder, missing required param 'builder'");
            }

            return new OneToManyRelationshipGenerator($app->make(DatabaseManager::class)->connection(), $params['builder']);
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
