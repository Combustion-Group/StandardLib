<?php

namespace Combustion\StandardLib\Services\Data\StandardGenerator\Translators;

use Combustion\StandardLib\Services\Data\StandardGenerator\Contracts\SchemaTranslator;
use Combustion\StandardLib\Services\Data\StandardGenerator\Exceptions\TranslationException;

/**
 * Class EloquentTranslator
 *
 * @package Combustion\StandardLib\Services\Data\ModelGenerator\Translators
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class EloquentTranslator implements SchemaTranslator
{
    protected $dictionary = [
        'bigIncrements' => 'int',
        'bigInteger' => 'int',
        'binary' => null,
        'boolean' => 'bool',
        'char' => 'string',
        'date' => 'string',
        'dateTime' => 'string',
        'dateTimeTz' => 'string',
        'decimal' => 'float',
        'double' => 'float',
        'enum' => null,
        'float' => 'float',
        'ipAddress' => 'string',
        'json' => 'string',
        'string' => 'string',
        'text' => 'text',
        'time' => 'string',
        'timestamp' => 'string'
    ];

    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'eloquent';
    }

    /**
     * @param string $name
     * @return string
     */
    public function translateType(string $name): string
    {
        if (!array_key_exists($name, $this->dictionary)) {
            throw new TranslationException("Invalid/unrecognized type {$name}");
        }

        return $this->dictionary[$name];
    }
}
