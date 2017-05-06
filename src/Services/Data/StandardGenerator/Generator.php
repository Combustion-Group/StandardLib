<?php

namespace Combustion\StandardLib\Services\Data\StandardGenerator;

use Illuminate\Database\Migrations\Migrator;
use Combustion\StandardLib\Services\Data\Migration;
use Combustion\StandardLib\Services\Data\Exceptions\DatabaseMigrationException;

/**
 * Class Command
 *
 * @package Combustion\StandardLib\Services\Data\ModelGenerator
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class Generator
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
     * @var Compiler
     */
    private $compiler;

    /**
     * @var array
     */
    private $skipped = [];

    /**
     * Command constructor.
     * @param Parser $parser
     * @param Migrator $migrator
     * @param Compiler $compiler
     */
    public function __construct(Parser $parser, Migrator $migrator, Compiler $compiler)
    {
        $this->parser   = $parser;
        $this->migrator = $migrator;
        $this->compiler = $compiler;
    }

    /**
     * @param string[] $migrationPaths
     * @throws DatabaseMigrationException
     */
    public function process(array $migrationPaths)
    {
        $paths = $this->migrator->getMigrationFiles($migrationPaths);

        foreach ($paths as $migration) {

            try {
                // Resolve the instance of the database migration
                $instance   = $this->resolve($migration);
            } catch (DatabaseMigrationException $e) {
                $this->skipped[] = $migration;
                continue;
            }

            // Generate interpreted fields
            $spec       = $this->parser->parse($instance);

            $this->compiler->run($spec);
        }
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
}
