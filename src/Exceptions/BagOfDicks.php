<?php

namespace Combustion\StandardLib\Exceptions;

use Combustion\StandardLib\Tools\TypeSafeObjectStorage;

class BagOfDicks extends TypeSafeObjectStorage
{
    public function __construct(array $data)
    {
        $this->setContainerType(Dick::class, self::CONCRETE);
        parent::__construct($data);
    }
}
