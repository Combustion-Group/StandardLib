<?php

namespace Combustion\StandardLib\Tools\TypeChecker;

/**
 * Class ChecksType
 *
 * Pretty useless tbh, specially with PHP 7
 *
 * @package Combustion\StandardLib\Tools\TypeChecker
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
trait ChecksType
{
    /**
     * @param string $type
     * @param        $item
     * @return bool
     */
    public function validateType(string $type, $item) : bool
    {
        // Check if we want zend type
        if (array_key_exists($type, ZEND_TYPE::ALL)) {
            return $this->checkZendType($type, $item);
        }

        if (class_exists($type)) {
            return is_a($item, $type);
        }

        throw new TypeValidationException("Type mismatch, expected {$type}, got " . gettype($item) . (is_object($item) ? '; Type: ' . get_class($item) : ''));
    }

    /**
     * @param string $type
     * @param        $item
     * @return bool
     * @throws TypeValidationException
     */
    public function checkZendType(string $type, $item) : bool
    {
        $result = false;

        switch ($type) {
            case ZEND_TYPE::STRING:
                $result = is_string($item);
            case ZEND_TYPE::INT:
                $result = is_int($item);
            case ZEND_TYPE::ARRAY:
                $result = is_array($item);
            case ZEND_TYPE::OBJECT:
                $result = is_object($item);
            case ZEND_TYPE::FLOAT:
                $result = is_float($item);
            default:
                if ($result) return $result;
                throw new TypeValidationException("Item is not correct type. Needed {$type}, received " . gettype($item));
        }
    }
}
