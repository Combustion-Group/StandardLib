<?php

namespace Combustion\StandardLib\Iterators;

use Combustion\StandardLib\Contracts\Memento;

/**
 * Class MementoIterator
 *
 * @package Combustion\StandardLib\Iterators
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class MementoIterator implements \Iterator
{
    /**
     * @var array
     */
    private $states;

    /**
     * @var Memento
     */
    private $object;

    /**
     * @var int
     */
    private $key = 0;

    /**
     * MementoIterator constructor.
     *
     * @param Memento $object
     * @param array $states
     */
    public function __construct(Memento $object, array $states)
    {
        $this->object = $object;
        $this->states = $states;
    }

    /**
     * @return Memento
     */
    public function current()
    {
        return $this->object->fromArrayDocument($this->states[$this->key]);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return isset($this->states[$this->key]);
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->key;
    }

    public function next()
    {
        $this->key++;
    }

    public function rewind()
    {
        $this->key = 0;
    }
}
