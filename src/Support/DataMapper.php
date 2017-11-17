<?php

namespace Combustion\StandardLib\Support;

use Illuminate\Database\Connection;
use Illuminate\Contracts\Foundation\Application;
use Combustion\StandardLib\Exceptions\DataMapperException;

/**
 * Class DataMapper
 *
 * A one to one data mapper
 *
 * @package Combustion\StandardLib\Support
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class DataMapper
{
    /**
     * @var array
     */
    private $settings;

    /**
     * @var Connection
     */
    private $database;

    /**
     * @var /Object
     */
    private $gateway;

    /**
     * @var Application
     */
    private $kernel;

    /**
     * @var array
     */
    private $required = [
        'DB' => ['table', ''],
        'GATEWAY' => ['class', 'method']
    ];

    const   METHODS = [
        'DB' => 'DB',
        'GATEWAY' => 'GATEWAY'
    ];

    /**
     * DataMapper constructor.
     * @param array $settings
     * @param Connection $connection
     * @param Application $kernel
     */
    public function __construct(array $settings, Connection $connection, Application $kernel)
    {
        $this->settings = $this->validate($settings);
        $this->database = $connection;
        $this->kernel = $kernel;
    }

    /**
     * @param $gateway
     * @return $this
     * @throws DataMapperException
     */
    public function setGateway($gateway)
    {
        if (!is_object($gateway)) {
            throw new DataMapperException("Gateway must be an object, " . gettype($gateway) . " supplied instead");
        }

        $this->gateway = $gateway;
        return $this;
    }

    /**
     * @param array $settings
     * @return array
     * @throws DataMapperException
     */
    public function validate(array $settings): array
    {
        if (!isset($settings['use'])) {
            throw new DataMapperException("Settings array does not have a 'use' subset. Unable to map data.");
        } elseif (!array_key_exists($settings['use'], static::METHODS)) {
            throw new DataMapperException("'{$settings['use']}' is not a valid mapping method. Unable to map data");
        }

        $missing = array_diff($this->required[$settings['use']], $settings);

        if (count($missing)) {
            throw new DataMapperException("Settings for data mapper are incomplete, using method '{$settings['use']}'. Missing fields: " . implode(', ', $missing));
        }

        return $settings;
    }

    /**
     * @param $identifier
     */
    public function fetch($identifier)
    {
        $method = "fetchFrom{$this->settings['use']}";
        return $method($identifier);
    }

    /**
     * @param $identifier
     */
    protected function fetchFromDb($identifier)
    {
        //
    }

    /**
     * @param $identifier
     * @throws DataMapperException
     */
    protected function fetchFromGateway($identifier)
    {
        $class = $this->settings['class'];
        $method = $this->settings['method'];
        $gateway = $this->kernel->make($class);

        if (!method_exists($gateway, $method)) {
            throw new DataMapperException("Class {$class} does not have method {$method}. Unable to map data.");
        }

        $record = $gateway->{$method}($identifier);

        if (isset($this->settings['returns']) && !is_a($record, $this->settings['returns'])) {
            throw new DataMapperException("Record type mismatch. Expected to receive a {$this->settings['returns']} from gateway {$class}, but received " . gettype($record) . " instead.");
        }

        return $record;
    }
}
