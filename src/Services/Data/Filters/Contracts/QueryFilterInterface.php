<?php

namespace Combustion\StandardLib\Services\Data\Filters\Contracts;

use Illuminate\Database\Query\Builder;

/**
 * Interface QueryFilterInterface
 *
 * @package Combustion\StandardLib\Services\Data\Filters\Contracts
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
interface QueryFilterInterface
{
    public function applyFilter(Builder $query): Builder;
}
