<?php

namespace Combustion\StandardLib\Services\Data;

use Combustion\StandardLib\Contracts\Builder;
use Combustion\StandardLib\Models\Model;
use Combustion\StandardLib\Contracts\Prototype;

/**
 * Class ModelFactory
 *
 * @package Combustion\StandardLib\Services\Data
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class ModelBuilder extends Builder
{
    /**
     * @var Model|Prototype
     */
    private $prototype;

    /**
     * @var Model[]|Prototype[]
     */
    private $prototypes = [];

    /**
     * @var string
     */
    private $prototypeTypeColumn = null;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var int
     */
    private $buildStyle = self::BUILD_SLICED;

    const   BUILD_LINEAR = 1,
            BUILD_SLICED = 2;

    /**
     * ModelFactory constructor.
     *
     * @param Model|null $prototype
     * @param string $prefix
     */
    public function __construct(Model $prototype = null, string $prefix = "")
    {
        $this->prototype = $prototype;
        $this->prefix    = $prefix;
    }

    /**
     * @param array $prototypes
     * @param string $column
     * @param string $prefix
     * @return ModelBuilder
     */
    public function setPrototypes(array $prototypes, string $column = 'type', string $prefix = "") : ModelBuilder
    {
        $this->prototypes           = $prototypes;
        $this->prototypeTypeColumn  = $column;
        $this->prefix               = $prefix;

        return $this;
    }

    /**
     * @param string $prefix
     * @return ModelBuilder
     */
    public function setPrefix(string $prefix) : ModelBuilder
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * @param int $style
     * @return ModelBuilder
     */
    public function setBuildStyle(int $style) : ModelBuilder
    {
        $this->buildStyle = $style;
        return $this;
    }

    /**
     * @param array $data
     * @param int $style
     * @return Model
     * @throws ModelFactoryException
     */
    public function build(array $data = [])
    {
        if (!is_null($this->prototype))
        {
            $model = clone $this->prototype;

            return $this->hydrate($model, $data, $this->buildStyle);
        }
        elseif (count($this->prototypes))
        {
            $column = $this->prefix . $this->prototypeTypeColumn;

            if (array_key_exists($column, $data))
            {
                if (array_key_exists($data[$column], $this->prototypes)) {
                    $model = clone $this->prototypes[$data[$column]];
                    return $this->hydrate($model, $data, $this->buildStyle);
                }

                throw new ModelFactoryException("There is no prototype set in the list with name {$data[$column]}");
            }

            throw new ModelFactoryException("Cannot build from list of prototypes because the data passed does not have a \"{$column}\" subset.");
        }

        throw new ModelFactoryException("There is no prototypes to build from.");
    }

    /**
     * @param Model $model
     * @param array $data
     * @param int   $style
     * @return Model $model
     * @throws ModelFactoryException
     */
    private function hydrate(Model $model, array $data = [], int $style) : Model
    {
        switch ($style) {
            case self::BUILD_LINEAR:
                return $model->fill($data);
            case self::BUILD_SLICED:
                return $model->fillSliced($this->prefix, $data);
        }

        throw new ModelFactoryException("Invalid build style. Only linear or sliced is allowed.");
    }
}
