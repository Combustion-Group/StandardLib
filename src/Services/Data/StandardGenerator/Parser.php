<?php

namespace Combustion\StandardLib\Services\Data\StandardGenerator;

use Combustion\StandardLib\Traits\ValidatesConfig;
use Illuminate\Support\Fluent;
use Illuminate\Database\Schema\Blueprint;
use Combustion\StandardLib\Services\Data\Migration;
use Combustion\StandardLib\Services\Data\StandardGenerator\Structs\Columns;
use Combustion\StandardLib\Services\Data\StandardGenerator\Structs\ModelSpecification;
use Combustion\StandardLib\Services\Data\StandardGenerator\Contracts\SchemaTranslator;

/**
 * Class Parser
 *
 * @package Combustion\StandardLib\Services\Data\ModelGenerator
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class Parser
{
    use ValidatesConfig;

    /**
     * @var SchemaTranslator
     */
    private $translator;

    /**
     * @var array
     */
    private $config;

    /**
     * Parser constructor.
     *
     * @param array $config
     * @param SchemaTranslator $translator
     */
    public function __construct(array $config, SchemaTranslator $translator)
    {
        $this->translator = $translator;
        $this->config = $this->validateConfig($config);
    }

    /**
     * @return mixed
     */
    public function getRequiredConfig(): array
    {
        return ['author_name', 'author_email'];
    }

    /**
     * @param Migration $migration
     * @return ModelSpecification
     */
    public function parse(Migration $migration): ModelSpecification
    {
        $spec = $this->getSpec();
        $table = $migration->table(new Blueprint($migration->getTableName()));

        foreach ($table->getColumns() as $column) {
            /**
             * @var Fluent $column
             */
            $spec->addColumn($column->get('type'), $column->get('name'));
        }

        $spec->setName($table->getTable())
            ->setAuthorName($this->config['author_name'])
            ->setAuthorEmail($this->config['author_email']);

        return $spec;
    }

    /**
     * @return ModelSpecification
     */
    private function getSpec(): ModelSpecification
    {
        return new ModelSpecification($this->translator, new Columns);
    }
}
