<?php

namespace Combustion\StandardLib\Traits;

/**
 * Class Listener
 *
 * @package Combustion\StandardLib\Traits
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
trait Listener
{
    /**
     * @var bool
     */
    protected $async = false;

    /**
     * @return bool
     */
    public function async() : bool
    {
        return (bool)$this->async;
    }
}
