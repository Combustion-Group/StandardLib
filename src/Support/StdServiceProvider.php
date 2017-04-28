<?php

namespace Combustion\StandardLib\Support;

use Combustion\StandardLib\Log;
use Illuminate\Validation\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\DatabaseManager;
use Combustion\StandardLib\UploadManager;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Contracts\Filesystem\Filesystem;
use Combustion\StandardLib\Services\Data\OneToMany;
use Combustion\StandardLib\Tools\ValidationService;
use Combustion\StandardLib\Services\Data\TableAliasResolver;
use Combustion\StandardLib\Exceptions\ServiceBuilderException;
use Combustion\StandardLib\Services\DeepLinks\DeepLinkService;
use Combustion\StandardLib\Services\Data\ModelGenerator\Parser;
use Combustion\StandardLib\Services\Data\ModelGenerator\Compiler;
use Combustion\StandardLib\Services\Data\ModelGenerator\Generator;
use Combustion\StandardLib\Services\SystemEvents\SystemEventsService;
use Combustion\StandardLib\Services\Data\OneToManyRelationshipGenerator;
use Combustion\StandardLib\Services\Data\ModelGenerator\Contracts\SchemaTranslator;
use Combustion\StandardLib\Services\Data\ModelGenerator\Translators\EloquentTranslator;

class StdServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(UploadManager::class, function (Application $app, array $params = []) : UploadManager
        {
            $config     = $app['config']['uploads.upload-manager'];
            $storage    = $app->make(FilesystemManager::class);

            return new UploadManager($config, $storage->cloud(), $app->make(Log::class));
        });

        $this->app->singleton(Log::class, function (Application $app, array $params = []) : Log
        {
            $monolog   = $app->make(\Illuminate\Log\Writer::class)->getMonolog();
            $syslog    = new \Monolog\Handler\SyslogUdpHandler("logs5.papertrailapp.com", 11586);
            $formatter = new \Monolog\Formatter\LineFormatter('%channel%.%level_name%: %message% %extra%');

            $syslog->setFormatter($formatter);
            $monolog->pushHandler($syslog);

            $configurator   = new LogConfigurator();
            $log            = new Log($monolog, $app['events']);

            return $configurator->configure($log, $app);
        });

        $this->app->singleton(SystemEventsService::class, function (Application $app, array $params) : SystemEventsService
        {
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

        $this->app->singleton(DeepLinkService::class, function (Application $app, array $params) : DeepLinkService
        {
            return new DeepLinkService();
        });

        $this->app->bind(OneToMany::class, function (Application $app, array $params) : OneToMany
        {
            return new OneToMany();
        });

        $this->app->bind(OneToManyRelationshipGenerator::class, function (Application $app, array $params) : OneToManyRelationshipGenerator
        {
            if (!array_key_exists('builder', $params)) {
                throw new ServiceBuilderException("Cannot build OneToManyRelationshipBuilder, missing required param 'builder'");
            }

            return new OneToManyRelationshipGenerator($app->make(DatabaseManager::class)->connection(), $params['builder']);
        });

        $this->app->bind(Parser::class, function (Application $app, array $params = []) : Parser
        {
            $config = $app['config']['standardlib.author'];
            $trans  = $app->make(SchemaTranslator::class);

            return new Parser($config, $trans);
        });

        $this->app->bind(SchemaTranslator::class, function (Application $app, array $params = []) : SchemaTranslator
        {
            return new EloquentTranslator();
        });

        $this->app->bind(Compiler::class, function (Application $app, array $params = []) : Compiler
        {
            $config = $app['config']['standardlib.temp_path'];
            $fs     = $app->make(Filesystem::class);

            return new Compiler($config, $fs);
        });

        $this->app->bind(Generator::class, function (Application $app, array $params = []) : Generator
        {
            $parser     = $app->make(Parser::class);
            $migrator   = $app->make(Migrator::class);
            $compiler   = $app->make(Compiler::class);

            return new Generator($parser, $migrator, $compiler);
        });

        $this->app->bind(ValidationService::class, function (Application $app, array $params = []) : ValidationService
        {
            $factory = $app->make(Factory::class);

            return new ValidationService($factory);
        });

        $this->app->bind(TableAliasResolver::class, function (Application $app, array $params = [])
        {
            return new TableAliasResolver();
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
