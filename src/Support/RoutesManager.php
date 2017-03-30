<?php

namespace Combustion\StandardLib\Support;

/**
 * Class RoutesManager
 *
 * This is not just syntactical sugar, this allows one to rename the
 * controller if needed, and the IDE will also update the name in the Routes
 * file given that one calls maps like -> self::maps(MyController::class, 'method').
 *
 * @package Combustion\StandardLib\Support
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
abstract class RoutesManager
{
    public static function maps($class, $method) : string
    {
        if (strpos('\\', $class) !== 0) {
            $class = "\\{$class}";
        }

        return "{$class}@{$method}";
    }
}
