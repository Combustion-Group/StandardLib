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
     * @var array
     */
    private $_disc      = [];

    /**
     * @var int
     */
    private $_needle    = 0;

    /**
     * @param $array
     */
    protected function setIterable(&$array)
    {
        $this->_disc = &$array;
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->_disc[$this->_needle];
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
        return isset($this->_disc[$this->_needle]);
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
        return sizeof($this->_disc);
    }
}
