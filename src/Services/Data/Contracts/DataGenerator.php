<?php

namespace Combustion\StandardLib\Services\Data\Contracts;

/**
 * Interface Slicer
 *
 * @package Combustion\StandardLib\Services\Data\Contracts
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
interface DataGenerator
{
    /**
     * @param array $data
     * @return mixed
     */
    public function slice(array $data) : \Generator;
}
