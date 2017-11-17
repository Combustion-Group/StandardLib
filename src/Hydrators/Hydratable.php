<?php

namespace Combustion\StandardLib\Hydrators;

/**
 * Interface Hydratable
 *
 * @package Combustion\StandardLib\Hydrators
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
interface Hydratable
{
    /**
     * @param array $data
     * @return Hydratable
     */
    public function hydrate(array $data): Hydratable;
}
