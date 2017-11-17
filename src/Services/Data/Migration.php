<?php

namespace Combustion\StandardLib\Services\Data;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration as BaseMigration;
use Combustion\StandardLib\Services\Data\Exceptions\DatabaseMigrationException;

/**
 * Class Migration
 *
 * @package Combustion\StandardLib\Services\Data
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class Migration extends BaseMigration
{
    /**
     * @var bool
     */
    protected $isService = false;

    /**
     * @var array
     */
    protected $components;

    // Component Generators
    const COMPONENT = [
        'model' => 'model',
        'repo' => 'repo'
    ];

    /**
     * Migration constructor.
     */
    public function __construct()
    {
        $this->components = [
            self::COMPONENT['model'],
            self::COMPONENT['repo']
        ];
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->getTableName(), $this->getCreator());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->getTableName());
    }

    /**
     * @param Blueprint $table
     * @return Blueprint
     * @throws DatabaseMigrationException
     */
    public function table(Blueprint $table)
    {
        $this->undefined(__FUNCTION__);
    }

    /**
     * @return string
     * @throws DatabaseMigrationException
     */
    public function getDestinationPath(): string
    {
        $this->undefined(__FUNCTION__);
    }

    /**
     * @return string
     * @throws DatabaseMigrationException
     */
    public function getTableName(): string
    {
        $this->undefined(__FUNCTION__);
    }

    /**
     * @param string $caller
     * @param string $exceptionClass
     * @throws DatabaseMigrationException
     */
    private function undefined(string $caller, string $exceptionClass = null)
    {
        $class = is_numeric($exceptionClass) ? DatabaseMigrationException::class : $exceptionClass;

        throw new $class("Cannot call Migration::{$caller}() because it's not implemented in the child class.");
    }

    /**
     * @return \Closure
     */
    public function getCreator(): \Closure
    {
        return function (Blueprint $table) {
            return $this->table($table);
        };
    }
}
