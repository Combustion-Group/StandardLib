<?php

namespace Combustion\StandardLib\Support;

/**
 * Class Package
 *
 * @package Combustion\StandardLib\Support
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
abstract class MultiServiceProvider extends BaseServiceProvider
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

    /**
     * @return MultiServiceProvider
     */
    public function register() : MultiServiceProvider
    {
        foreach ($this->getServiceProviders() as $provider) {
            $this->app->register($provider);
        }

        return $this;
    }
}
