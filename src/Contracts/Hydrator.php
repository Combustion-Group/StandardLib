<?php

namespace Combustion\StandardLib\Contracts;

/**
 * Interface Hydrator
 * @package Combustion\Billing\Contracts
 * @author Carlos Granados <cgranados@combustiongroup.com>
 */
interface Hydrator
{
    /**
     * @param string $prototype
     * @param array $data
     * @param string $generate
     * @param \Closure $callback
     * @return mixed
     */
    public function hydrate(string $prototype, array $data, string $generate = true, \Closure $callback = null);
}
