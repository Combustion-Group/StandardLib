<?php

namespace Combustion\StandardLib\Support\Installer;


use Combustion\StandardLib\Log;
use Combustion\StandardLib\Support\Installer\Exceptions\InvalidOperationException;
use Combustion\StandardLib\Support\Installer\Exceptions\MigrationNotFoundException;
use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Connection;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Helper\ProgressBar;

/**
 * Class Installer
 *
 * Utility for facilitating the installation of an in-house
 * software package for laravel.
 *
 * @package CombustionGroup\StandardLib\Support
 * @author Carlos Granados <cgranados@combustiongroup.com>
 */
abstract class AbstractInstaller extends Command
{
    /**
     * @var string
     */
    protected $signature    = null;

    /**
     * @var string
     */
    protected $packageName  = null;

    /**
     * @var array
     */
    protected $seeders = [];

    /**
     * @var array
     */
    protected $exports = [];

    /**
     * @var array
     */
    protected $migrations = [];

    /**
     * @var Application
     */
    protected $kernel;

    /**
     * @var Connection
     */
    private $database;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var array
     */
    private $beforeCallbacks = [];

    /**
     * @var array
     */
    private $afterCallbacks = [];

    /**
     * @var array
     */
    private $stack = [
        'boot',
        'setup',
        'migrate',
        'export',
        'seed',
        'finish',
        'sleep'
    ];

    /**
     * Installer constructor.
     * @param Application $application
     * @param Log $log
     * @param Filesystem $filesystem
     * @param Connection $database
     */
    public function __construct(
        Application $application,
        Log $log,
        Filesystem $filesystem,
        Connection $database)
    {
        parent::__construct();

        $this->kernel       = $application;
        $this->database     = $database;
        $this->filesystem   = $filesystem;
        $this->signature    = "c-install:{$this->packageName}";
    }

    public function handle()
    {
        $this->runStack($this->stack);
    }

    protected function boot()
    {
        // no op
    }

    protected function sleep()
    {
        // no op
    }

    /**
     * @param array $stack
     * @throws InvalidOperationException
     */
    private function runStack(array $stack)
    {
        foreach ($stack as $operation)
        {
            if ($operation instanceof \Closure) {
                $operation();
            } elseif (method_exists($this, $operation)) {
                if (isset($this->beforeCallbacks[$operation])) {
                    $this->beforeCallbacks[$operation]();
                }

                $this->{$operation}();

                if (isset($this->afterCallbacks[$operation])) {
                    $this->beforeCallbacks[$operation]();
                }
            } else {
                throw new InvalidOperationException("Cannot execute item in stack: " . serialize($operation));
            }
        }
    }

    private function setup()
    {
        $this->database->beginTransaction();

        if (is_string($this->migrations)) {
            $this->migrations = $this->filesystem->allFiles($this->migrations);
        }
    }

    private function finish()
    {
        $this->database->commit();
    }

    /**
     * @return $this
     * @throws MigrationNotFoundException
     */
    private function migrate()
    {
        $appPath = $this->kernel->basePath();

        // We can add these migrations to the queue to be exported
        foreach ($this->migrations as $migration) {
            $this->queueExport($migration, $appPath . '/database/migrations/' . basename($migration));
        }

        // After the migrations are exported we want to call the migrator.
        $this->after('export', function () {
            if (!count($this->migrations)) return;

            $this->info('Migrating...');
            $this->call('migrate');
        });

        return $this;
    }

    /**
     * @param array $files
     * @return $this
     * @throws MigrationNotFoundException
     */
    private function export(array $files = null)
    {
        $missing = [];
        $bar     = $this->createProgressBar(count($this->exports));

        $bar->display();

        // First we'll validate this step of the installation, by making
        // sure that the migration files actually exist.
        foreach ($this->exports as $file) {
            $bar->setMessage("Validating file to export: {$file}");
            if (!$this->filesystem->exists($file)) {
                $missing[] = $file;
            }
            $bar->advance();
        }

        // We have an error
        if (count($missing)) {
            throw new MigrationNotFoundException("The following files could not be found: " . implode(', ', $missing));
        }

        $bar     = $this->createProgressBar(count($this->exports));

        $bar->display();

        $this->info("Copying installation files");

        foreach ($this->exports as $origin => $destination)
        {
            $this->filesystem->copy($origin, $destination);
            $bar->advance();
        }

        return $this;
    }

    /**
     * @return $this
     */
    private function seed()
    {
        $bar = $this->createProgressBar(count($this->seeders));

        foreach ($this->seeders as $seeder) {
            $bar->setMessage('Seeding: ' . class_basename($seeder));
            $this->call($seeder);
            $bar->advance();
        }

        $bar->finish();

        return $this;
    }

    /**
     * @param string $origin
     * @param string $target
     * @return $this
     */
    private function queueExport(string $origin, string $target)
    {
        $this->exports[$origin] = $target;
        return $this;
    }

    /**
     * @param string $action
     * @param \Closure $callback
     * @return $this
     */
    protected function before(string $action, \Closure $callback)
    {
        $this->beforeCallbacks[$action] = $callback;
        return $this;
    }

    /**
     * @param string $action
     * @param \Closure $callback
     * @return $this
     */
    protected function after(string $action, \Closure $callback)
    {
        $this->afterCallbacks[$action] = $callback;
        return $this;
    }

    /**
     * @param int $numberOfItems
     * @return \Symfony\Component\Console\Helper\ProgressBar
     */
    private function createProgressBar(int $numberOfItems) : ProgressBar
    {
        $bar = $this->output->createProgressBar($numberOfItems);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %message%');

        return $bar;
    }
}
