<?php

namespace Combustion\StandardLib\Models;

/**
 * Class Model
 * @package Combustion\StandardLib\Models
 * @author Carlos Granados <cgranadso@combustiongroup.com>
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
        if (!static::$tableName) {
            static::$tableName = (string)(new static)->getTable();
        }
        return static::$tableName;
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
}
