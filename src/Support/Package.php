<?php

namespace Combustion\StandardLib\Support;

use Combustion\StandardLib\Contracts\Package as PackageInterface;

/**
 * Class Package
 *
 * @package Combustion\StandardLib\Support
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
abstract class Package extends BaseServiceProvider implements PackageInterface
{
    /**
     * @var string[]
     */
    protected $providers = [];

    /**
     * @return string[]
     */
    public function getServiceProviders() : array
    {
        return $this->providers;
    }
}