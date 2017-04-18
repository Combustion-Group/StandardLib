<?php

namespace Combustion\StandardLib\Services\Data\ModelGenerator\Structs;

use Combustion\StandardLib\Services\Data\ModelGenerator\Contracts\SchemaTranslator;

/**
 * Class ModelSpecification
 *
 * @package Combustion\StandardLib\Services\Data\ModelGenerator\Structs
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class ModelSpecification
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Columns
     */
    private $columns;

    /**
     * @var array
     */
    private $meta = [];

    /**
     * @var SchemaTranslator
     */
    private $translator;

    /**
     * ModelSpecification constructor.
     *
     * @param SchemaTranslator $translator
     * @param Columns $columns
     */
    public function __construct(SchemaTranslator $translator, Columns $columns)
    {
        $this->translator   = $translator;
        $this->columns      = $columns;
    }

    /**
     * @param string $type
     * @param string $name
     * @return ModelSpecification
     */
    public function addColumn(string $type, string $name) : ModelSpecification
    {
        $this->columns->add(
            $this->translator->translateType($type), $name
        );

        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage() : string
    {
        return (string)$this->translator;
    }

    /**
     * @param string $name
     * @return ModelSpecification
     */
    public function setName(string $name) : ModelSpecification
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return (string)$this->name;
    }

    /**
     * @param Columns $columns
     * @return ModelSpecification
     */
    public function setColumns(Columns $columns) : ModelSpecification
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * @return Columns
     */
    public function getColumns() : Columns
    {
        return $this->columns;
    }

    /**
     * @param string $key
     * @param string $val
     * @return ModelSpecification
     */
    public function addMeta(string $key, string $val) : ModelSpecification
    {
        $this->meta[$key] = $val;
        return $this;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasMeta(string $key) : bool
    {
        return array_key_exists($key, $this->meta);
    }
}
