<?php

namespace Combustion\StandardLib\Models;

use Combustion\StandardLib\Contracts\Prototype;
use Combustion\StandardLib\Exceptions\ErrorBag;
use Illuminate\Database\Eloquent\Builder;
use Combustion\StandardLib\Services\Data\ModelBuilder;
use Combustion\StandardLib\Services\Data\ModelBuilderException;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;

/**
 * Class Model
 * @package Combustion\StandardLib\Models
 * @author  Carlos Granados <cgranadso@combustiongroup.com>
 */
abstract class Model extends Eloquent implements Prototype
{
    const   ID = 'id';

    /**
     * @var string
     */
    public static $tableName;

    /**
     * @var array
     */
    private $lineItemStorage = [];

    /**
     * @var array
     */
    private $validationRules = [];

    /**
     * @var null
     */
    protected $validation = null;

    /**
     * @var ModelBuilder
     */
    private static $modelBuilder = null;

    /**
     * @return array
     */
    public function getValidationRules() : array
    {
        return $this->validationRules;
    }

    /**
     * @var array
     */
    private $selectStatement = [];

    /**
     * @return string
     */
    public static function table()
    {
        $class = get_called_class();
        return (string)(new $class)->getTable();
    }

    /**
     * @return ModelBuilder
     * @throws ModelBuilderException
     */
    public static function builder() : ModelBuilder
    {
        if (is_null(self::$modelBuilder)) {
            throw new ModelBuilderException("No builder has been set for this class.");
        }

        return self::$modelBuilder;
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
            if (strpos($key, $prefix) === 0){
                $key = substr($key, strlen($prefix));
                $this->setAttribute($key, $item);
            }
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

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->getAttribute(self::CREATED_AT);
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->getAttribute(self::UPDATED_AT);
    }


    /**
     * Add a string to the Select Statement
     * @param string $select
     *
     * @return $this
     */
    public function appendToSelect(string $select)
    {
        array_push($this->selectStatement ,$select);
        return $this;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $select
     */
    public function scopeAppendToSelect(Builder $query, string $select)
    {
        $this->appendToSelect($select);
    }

    /**
     * Use at the end to append a final Select Statement
     * @param $query
     * @param bool $raw
     */
    public function scopePullSelectInQuery(Builder $query, $raw = true)
    {
        $selectString = implode(',',$this->selectStatement);
        if($raw)
        {
            $query->select(DB::raw($selectString));
        }
        else
        {
            $query->select($selectString);
        }
    }

    /**
     * @param array|null $only
     *
     * @return array|mixed
     */
    public function fetchValidationRules(array $only = null) : array
    {
        // get all validation rules if only was not sent
        if(is_null($only)) return $this->validation;
        // if only array wa sent
        $rules = [];
        // check all string sent in theo nly array
        foreach($only as $ruleName)
        {
            if(isset($this->validation[$ruleName]))
            {
                $rules[$ruleName] = $this->validation[$ruleName];
            }
        }
        return $rules;
    }

}
