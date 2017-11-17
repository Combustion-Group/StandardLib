<?php

namespace Combustion\StandardLib\Tools\Types;

use Combustion\StandardLib\Tools\TypeChecker\ZEND_TYPE;

/**
 * Class PHP_String
 *
 * @package Combustion\StandardLib\Tools\Types
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class PHP_String
{
    /**
     * @var string
     */
    protected $value;

    /**
     * PHP_String constructor.
     *
     * @param string $string
     */
    public function __construct(string $string)
    {
        $this->value = $string;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return ZEND_TYPE::STRING;
    }
}