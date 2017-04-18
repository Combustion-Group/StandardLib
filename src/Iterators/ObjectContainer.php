<?php

namespace Combustion\StandardLib\Iterators;

/**
 * Class ObjectContainer
 *
 * @package Combustion\StandardLib\Iterators
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
abstract class ObjectContainer implements \Iterator, \ArrayAccess
{
    use IteratesFromArray;

    /**
     * @var array
     */
    private $data = [];

    /**
     * ObjectContainer constructor.
     *
     * @param array $objects
     */
    public function __construct(array $objects)
    {
        $this->data = $objects;

        $this->setIterable($this->data);
    }

    /**
     * @return array
     */
    protected function & getIterable() : array
    {
        return $this->data;
    }
}
