<?php

namespace Combustion\StandardLib\Services\Data;

use Combustion\StandardLib\Models\Model;
use Combustion\StandardLib\Contracts\Builder;
use Combustion\StandardLib\Contracts\Prototype;

/**
 * Class ModelFactory
 *
 * ModelBuilder::$prototype   - for when there is only one implementation of the object.
 * ModelBuilder::prototype(s) - for when this builds an abstract and there are one or more implementations
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
    private $prefix = "";

    /**
     * @var int
     */
    private $buildStyle = self::BUILD_SLICED;

    const   // Builds one dimensional object.
        BUILD_LINEAR = 1,

        // Build object that has children objects.
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
        $this->prefix = $prefix;
    }

    /**
     * @param array $prototypes
     * @param string $column
     * @param string $prefix
     * @return ModelBuilder
     */
    public function setPrototypes(array $prototypes, string $column = 'type', string $prefix = ""): ModelBuilder
    {
        $this->prototypes = $prototypes;
        $this->prototypeTypeColumn = $column;
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * @param string $prefix
     * @return ModelBuilder
     */
    public function setPrefix(string $prefix): ModelBuilder
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * @param int $style
     * @return ModelBuilder
     */
    public function setBuildStyle(int $style): ModelBuilder
    {
        $this->buildStyle = $style;
        return $this;
    }

    /**
     * @param array $data
     * @param int $style
     * @return Model
     * @throws ModelBuilderException
     */
    public function build(array $data = [])
    {
        // prototype    - for when there is only one implementation of this object
        // prototype(s) - for when this builds an abstract and there are one or more implementations
        if (!is_null($this->prototype)) {
            $model = clone $this->prototype;

            return $this->hydrate($model, $data, $this->buildStyle);
        } elseif (count($this->prototypes)) {
            $column = $this->prefix . $this->prototypeTypeColumn;

            if (array_key_exists($column, $data)) {
                if (array_key_exists($data[$column], $this->prototypes)) {
                    $model = clone $this->prototypes[$data[$column]];
                    return $this->hydrate($model, $data, $this->buildStyle);
                }

                throw new ModelBuilderException("There is no prototype set in the list with name {$data[$column]}");
            }

            throw new ModelBuilderException("Cannot build from list of prototypes because the data passed does not have a \"{$column}\" subset.");
        }

        throw new ModelBuilderException("There is no prototypes to build from.");
    }

    /**
     * @param Model $model
     * @param array $data
     * @param int $style
     * @return Model $model
     * @throws ModelBuilderException
     */
    private function hydrate(Model $model, array $data = [], int $style): Model
    {
        switch ($style) {
            case self::BUILD_LINEAR:
                return $model->fill($data);
            case self::BUILD_SLICED:
                return $model->fillSliced($this->prefix, $data);
        }

        throw new ModelBuilderException("Invalid build style. Only linear or sliced is allowed.");
    }
}
