<?php

namespace Combustion\StandardLib\Services\SystemEvents;

/**
 * Interface Listener
 *
 * @package Combustion\StandardLib\Services\SystemEvents
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
interface Listener
{
    /**
     * @param $data
     */
    public function fire($data);

    /**
     * @return bool
     */
    //public function async() : bool;
}
