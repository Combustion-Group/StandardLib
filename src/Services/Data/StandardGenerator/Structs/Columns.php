<?php

namespace Combustion\StandardLib\Services\Data\StandardGenerator\Structs;

use \Iterator;
use Combustion\StandardLib\Iterators\IteratesFromArray;

/**
 * Class SpecTree
 *
 * @package Combustion\StandardLib\Services\Data\ModelGenerator\Structs
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class Columns implements Iterator
{
    use IteratesFromArray;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @return &array
     */
    protected function & getIterable() : array
    {
        return $this->data;
    }

    /**
     * @param string $type
     * @param string $name
     * @return Columns
     */
    public function add(string $type, string $name) : Columns
    {
        $this->data[$name] = $type;
        return $this;
    }
}
