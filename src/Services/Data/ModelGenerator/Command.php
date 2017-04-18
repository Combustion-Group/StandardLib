<?php

namespace Combustion\StandardLib\Services\Data\ModelGenerator;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Console\Command as ArtisanCommand;
use Combustion\StandardLib\Services\Data\Migration;
use Combustion\StandardLib\Services\Data\Exceptions\DatabaseMigrationException;

/**
 * Class Command
 *
 * @package Combustion\StandardLib\Services\Data\ModelGenerator
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class Command extends ArtisanCommand
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var Migrator
     */
    private $migrator;

    /**
     * Command constructor.
     * @param Parser $parser
     * @param Migrator $migrator
     */
    public function __construct(Parser $parser, Migrator $migrator)
    {
        parent::__construct();

        $this->parser   = $parser;
        $this->migrator = $migrator;
    }

    public function fire()
    {
        $paths = $this->migrator->getMigrationFiles($this->getMigrationPaths());

        foreach ($paths as $migration) {

            // Resolve the instance of the database migration
            $instance   = $this->resolve($migration);

            // Get blueprint of database table
            $table      = $instance->getBlueprint(new Blueprint(''));

            // Generate interpreted fields
            $spec       = $this->parser->parse($table);

        }
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

    /**
     * @param string $file
     * @return Migration
     * @throws DatabaseMigrationException
     */
    private function resolve(string $file) : Migration
    {
        $instance = $this->migrator->resolve($file);

        if ($instance instanceof Migration) {
            return $instance;
        }

        throw new DatabaseMigrationException("Cannot generate model for migration {$file} because file does not extend " . Migration::class);
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
}
