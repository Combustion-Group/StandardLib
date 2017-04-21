<?php

namespace Combustion\StandardLib\Services\Data\Filters\Contracts;

use Combustion\StandardLib\Tools\TypeSafeObjectStorage;

/**
 * Class QueryFilterContainer
 *
 * @package Combustion\Billing\Support\Structs\Filters\Contracts
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class QueryFilterContainer extends TypeSafeObjectStorage
{
    /**
     * QueryFilterContainer constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->setContainerType(QueryFilterContainer::class);
        parent::__construct($data);
    }
}
