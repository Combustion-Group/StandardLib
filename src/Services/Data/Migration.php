<?php

namespace Combustion\StandardLib\Services\Data;

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
     * @param Blueprint $table
     * @return Blueprint
     * @throws DatabaseMigrationException
     */
    public function table(Blueprint $table) : Blueprint
    {
        $this->undefined(__FUNCTION__);
    }

    /**
     * @return string
     * @throws DatabaseMigrationException
     */
    public function getDestinationPath() : string
    {
        $this->undefined(__FUNCTION__);
    }

    /**
     * @return string
     * @throws DatabaseMigrationException
     */
    public function getTableName() : string
    {
        $this->undefined(__FUNCTION__);
    }

    /**
     * @param string $caller
     * @throws DatabaseMigrationException
     */
    private function undefined(string $caller)
    {
        throw new DatabaseMigrationException("Cannot call Migration::{$caller}() because it's not implemented in the child class.");
    }
}
