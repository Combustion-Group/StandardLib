<?php

namespace Combustion\StandardLib\Services\Data;

/**
 * Class TableAliasResolver
 *
 * @package Combustion\StandardLib\Services\Data
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class TableAliasResolver
{
    public function __construct()
    {

    }

    public function resolve(string $table) : string
    {
        return $table;
    }
}
