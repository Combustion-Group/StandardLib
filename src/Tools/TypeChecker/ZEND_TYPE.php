<?php

namespace Combustion\StandardLib\Tools\TypeChecker;

/**
 * Class ZEND_TYPE
 *
 * I was bored, leave me alone.
 *
 * @package Combustion\StandardLib\Tools\TypeChecker
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class ZEND_TYPE
{
    const   STRING = '<*' . ('string') . '*>',
        INT = '<*' . ('int') . '*>',
        FLOAT = '<*' . ('float') . '*>',
        ARRAY = '<*' . ('array') . '*>',
        OBJECT = '<*' . ('object') . '*>';

    const   ALL = [
        self::STRING => self::STRING,
        self::INT => self::INT,
        self::FLOAT => self::FLOAT,
        self::ARRAY => self::ARRAY,
        self::OBJECT => self::OBJECT
    ];
}
