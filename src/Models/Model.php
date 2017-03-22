<?php

namespace Combustion\StandardLib\Models;

use Combustion\StandardLib\Contracts\Prototype;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Model
 * @package Combustion\StandardLib\Models
 * @author  Carlos Granados <cgranadso@combustiongroup.com>
 */
abstract class Model extends Eloquent implements Prototype
{
    /**
     * @var string
     */
    public static $tableName;

    /**
     * @var array
     */
    private $lineItemStorage = [];

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
     * @param string $prefix
     * @param array $data
     * @return Model
     */
    public function fillSliced(string $prefix, array $data) : Model
    {
        foreach ($data as $key => $item)
        {
            $key = substr($key, strlen($prefix));
            $this->setAttribute($key, $item);
        }

        return $this;
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

    /**
     * @param Model[] $lineItems
     * @return $this
     */
    public function setLineItems(array $lineItems)
    {
        $this->lineItemStorage = $lineItems;
        return $this;
    }

    /**
     * @return Model[]
     */
    public function getLineItems() : array
    {
        return $this->lineItemStorage;
    }

    /**
     * @param Model $lineItem
     * @return $this
     */
    public function addLineItem(Model $lineItem)
    {
        $this->lineItemStorage[] = $lineItem;
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->getAttribute(self::CREATED_AT);
    }

    public function getUpdatedAt()
    {
        return $this->getAttribute(self::UPDATED_AT);
    }
}
