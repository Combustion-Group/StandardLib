<?php

namespace Combustion\StandardLib\Services\Data\Commands;

use Combustion\StandardLib\Services\Data\ModelGenerator\Generator;
use Illuminate\Console\Command;

/**
 * Class ModelGenerator
 *
 * @package Combustion\StandardLib\Services\Data\Commands
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class ModelGenerator extends Command
{
    /**
     * @var Generator
     */
    private $generator;

    /**
     * ModelGenerator constructor.
     *
     * @param Generator $generator
     */
    public function __construct(Generator $generator)
    {
        parent::__construct();

        $this->generator = $generator;
    }

    public function fire()
    {
        $this->generator->process($this->getMigrationPaths());
    }

    /**
     * Get all of the migration paths.
     *
     * @return array
     */
    protected function getMigrationPaths()
    {
        // Here, we will check to see if a path option has been defined. If it has
        // we will use the path relative to the root of this installation folder
        // so that migrations may be run for any path within the applications.
        if ($this->input->hasOption('path') && $this->option('path')) {
            return [$this->laravel->basePath().'/'.$this->option('path')];
        }

        return array_merge(
            [$this->getMigrationPath()], $this->migrator->paths()
        );
    }

    /**
     * Get the path to the migration directory.
     *
     * @return string
     */
    protected function getMigrationPath()
    {
        return $this->laravel->databasePath().DIRECTORY_SEPARATOR.'migrations';
    }
}
