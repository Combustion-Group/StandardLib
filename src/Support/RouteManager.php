<?php

namespace Combustion\StandardLib\Support;

/**
 * Class RouteManager
 *
 * This is not just syntactical sugar, this allows one to rename the
 * controller if needed, and the IDE will also update the name in the Route
 * file given that one calls maps like -> self::maps(MyController::class, 'method').
 *
 * @package Combustion\StandardLib\Support
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
abstract class RouteManager
{
    public static function _($class, $method): string
    {
        if (strpos('\\', $class) !== 0) {
            $class = "\\{$class}";
        }

        return "{$class}@{$method}";
    }
}
