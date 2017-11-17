<?php

namespace Combustion\StandardLib\Iterators;

/**
 * Class IteratesFromArray
 *
 * @package Combustion\StandardLib\Iterators
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
trait IteratesFromArray
{
    /**
     * @var int
     */
    private $_needle = 0;

    abstract protected function & getIterable(): array;

    /**
     * @return mixed
     */
    public function current()
    {
        $arr = $this->getIterable();
        return $arr[$this->_needle];
    }

    public function next()
    {
        ++$this->_needle;
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->_needle;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        $arr = $this->getIterable();
        return isset($arr[$this->_needle]);
    }

    public function rewind()
    {
        $this->_needle = 0;
    }

    /**
     * @return int
     */
    public function count()
    {
        return sizeof($this->getIterable());
    }
}
