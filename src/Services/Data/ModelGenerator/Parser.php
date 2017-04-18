<?php

namespace Combustion\StandardLib\Services\Data\ModelGenerator;

use Illuminate\Support\Fluent;
use Illuminate\Database\Schema\Blueprint;
use Combustion\StandardLib\Services\Data\ModelGenerator\Structs\Columns;
use Combustion\StandardLib\Services\Data\ModelGenerator\Structs\ModelSpecification;
use Combustion\StandardLib\Services\Data\ModelGenerator\Contracts\SchemaTranslator;

/**
 * Class Parser
 *
 * @package Combustion\StandardLib\Services\Data\ModelGenerator
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class Parser
{
    /**
     * @var SchemaTranslator
     */
    private $translator;

    /**
     * Parser constructor.
     *
     * @param SchemaTranslator $translator
     */
    public function __construct(SchemaTranslator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param Blueprint $table
     * @return ModelSpecification
     */
    public function parse(Blueprint $table) : ModelSpecification
    {
        $spec = $this->getSpec();

        foreach ($table->getColumns() as $column)
        {
            /**
             * @var Fluent $column
             */
            $spec->addColumn($column->get('type'), $column->get('name'));
        }

        return $spec;
    }

    /**
     * @return ModelSpecification
     */
    private function getSpec() : ModelSpecification
    {
        return new ModelSpecification($this->translator, new Columns);
    }
}
