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
    public function getBlueprint(Blueprint $table) : Blueprint
    {
        throw new DatabaseMigrationException("Cannot call Migration::" . __FUNCTION__ . "() because it's not implemented in the child class.");
    }

    /**
     * @return string
     * @throws DatabaseMigrationException
     */
    public function getTableName() : string
    {
        throw new DatabaseMigrationException("Cannot call Migration::" . __FUNCTION__ . "() because it's not implemented in the child class.");
    }
}
