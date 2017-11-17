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
     * @return \Generator
     */
    public function generate(array $data): \Generator;

    /**
     * @param array $data
     * @return array
     */
    public function toList(array $data): array;
}
