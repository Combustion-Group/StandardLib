<?php

namespace Combustion\StandardLib\Support;

use \Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

/**
 * Class ServiceProvider
 *
 * @package Combustion\StandardLib\Support
 * @author Carlos Granados <cgranados@combustiongroup.com>
 */
abstract class BaseServiceProvider extends LaravelServiceProvider
{
    /**
     * @var array
     */
    protected static $configuration = [];

    /**
     * ServiceProvider constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    /**
     * @param array $config
     * @return $this
     */
    protected function setConfig(array $config)
    {
        static::$configuration = $config;
        return $this;
    }

    /**
     * @return mixed
     */
    protected function getConfig(): array
    {
        return static::$configuration;
    }

    /**
     * @param array $runtimeConfig
     * @param bool $recursive
     * @return array
     */
    public static function config(array $runtimeConfig, bool $recursive = false)
    {
        if ($recursive) {
            return array_merge_recursive($runtimeConfig, static::$configuration);
        }

        return array_merge($runtimeConfig, static::$configuration);
    }
}
