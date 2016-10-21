<?php

namespace Combustion\StandardLib\Traits;

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

    public function current()
    {
        return $this->_disc[$this->_needle];
    }

    public function next()
    {
        ++$this->_needle;
    }

    public function key()
    {
        return $this->_needle;
    }

    public function valid()
    {
        return isset($this->_disc[$this->_needle]);
    }

    public function rewind()
    {
        $this->_needle = 0;
    }

    public function count()
    {
        return sizeof($this->_disc);
    }
}