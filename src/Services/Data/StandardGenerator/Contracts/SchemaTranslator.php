<?php

namespace Combustion\StandardLib\Services\Data\StandardGenerator\Contracts;

/**
 * Interface SchemaTranslator
 *
 * @package Combustion\StandardLib\Services\Data\ModelGenerator\Contracts
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
interface SchemaTranslator
{
    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @param string $name
     * @return string
     */
    public function translateType(string $name): string;
}
