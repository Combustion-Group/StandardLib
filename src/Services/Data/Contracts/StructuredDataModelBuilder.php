<?php

namespace Combustion\StandardLib\Services\Data\Contracts;

use Combustion\StandardLib\Contracts\BuilderInterface;

/**
 * Interface StructuredDataModelBuilder
 *
 * @package Combustion\StandardLib\Services\Data\Contracts
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
interface StructuredDataModelBuilder extends BuilderInterface
{
    public function setData(array $data) : StructuredDataModelBuilder;
}
