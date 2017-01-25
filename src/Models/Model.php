<?php

namespace Combustion\StandardLib\Models;

/**
 * Class Model
 * @package Combustion\StandardLib\Models
 * @author  Carlos Granados <cgranadso@combustiongroup.com>
 */
abstract class Model extends \Eloquent
{
    /**
     * @var string
     */
    public static $tableName;

    /**
     * @return string
     */
    public static function table()
    {
        $class = get_called_class();
        return (string)(new $class)->getTable();
    }

    /**
     * @param array $cols
     * @return array
     */
    public function only(array $cols)
    {
        $data = $this->toArray();

        return array_intersect_key($data, array_flip($cols));
    }

    /**
     * @param $date
     * @return string
     */
    public function extractDate($date) : string
    {
        return (string) ($date instanceof \DateTime ? $date->format("Y-m-d") : $date);
    }

    /**
     * @param $time
     * @return \DateTime
     */
    public function exportDateTime($time)
    {
        return new \DateTime($time);
    }

    /**
     * @param $dateTime
     * @return string
     */
    public function extractDateTime($dateTime)
    {

    }
}
