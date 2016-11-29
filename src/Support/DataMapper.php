<?php

namespace Combustion\StandardLib\Support;
use Combustion\StandardLib\Exceptions\DataMapperException;
use Illuminate\Database\Connection;

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
     * @var array
     */
    private $required = [
        'DB'        => [],
        'GATEWAY'   => []
    ];

    const   METHODS = [
        'DB'        => 'DB',
        'GATEWAY'   => 'GATEWAY'
    ];

    /**
     * DataMapper constructor.
     * @param array $settings
     * @param Connection $connection
     */
    public function __construct(array $settings, Connection $connection)
    {
        $this->settings = $this->validate($settings);
        $this->database = $connection;
    }

    /**
     * @param array $settings
     * @return array
     * @throws DataMapperException
     */
    public function validate(array $settings) : array
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

    }

    /**
     * @param $identifier
     */
    protected function fetchFromGateway($identifier)
    {

    }
}
