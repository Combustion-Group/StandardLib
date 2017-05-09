<?php

namespace Combustion\StandardLib\Services\Data\StandardGenerator\Structs;

use Combustion\StandardLib\Services\Data\StandardGenerator\Contracts\SchemaTranslator;

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
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $author;

    /**
     * @var SchemaTranslator
     */
    private $translator;

    /**
     * @var string
     */
    private $email;

    /**
     * @var array
     */
    private $components = [];

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
     * @param string $author
     * @return ModelSpecification
     */
    public function setAuthorName(string $author) : ModelSpecification
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthorName() : string
    {
        return (string)$this->author;
    }

    /**
     * @param string $email
     * @return ModelSpecification
     */
    public function setAuthorEmail(string $email) : ModelSpecification
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthorEmail() : string
    {
        return (string)$this->email;
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
     * @param string $namespace
     * @return ModelSpecification
     */
    public function setNamespace(string $namespace) : ModelSpecification
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @param array $component
     * @return ModelSpecification
     */
    public function setComponents(array $component) : ModelSpecification
    {
        $this->components = $component;
        return $this;
    }

    /**
     * @return array
     */
    public function getComponents() : array
    {
        return $this->components;
    }

    /**
     * @return string
     */
    public function getNamespace() : string
    {
        return $this->namespace;
    }
}
