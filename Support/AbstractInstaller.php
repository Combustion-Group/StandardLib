<?php

namespace Combustion\StandardLib\Support;


use Combustion\StandardLib\Log;
use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application;
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
     * Installer constructor.
     * @param Application $application
     * @param Log $log
     */
    public function __construct(Application $application, Log $log)
    {
        parent::__construct();

        $this->kernel       = $application;
        $this->signature    = "c-install:{$this->packageName}";
    }

    protected function before()
    {
        // no op
    }

    protected function after()
    {
        // no op
    }

    protected function handle()
    {
        $this->before();
        $this->install();
        $this->after();
    }

    protected function install()
    {
        $this->export();
        $this->migrate();
        $this->seed();
    }

    protected function export()
    {

    }

    protected function migrate()
    {

    }

    protected function seed()
    {
        $bar = $this->createProgressBar(count($this->seeders));

        foreach ($this->seeders as $table => $seeder) {
            $bar->setMessage('Seeding table: ' . $table);
            $bar->advance();
        }

        $bar->finish();
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
