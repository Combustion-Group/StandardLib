<?php

namespace Combustion\StandardLib\Support;

use Combustion\StandardLib\Contracts\PackageConfig;

/**
 * Class Config
 * @package Combustion\StandardLib\Support
 *
 * DO NOT CHANGE. THESE ARE DEFAULT CONFIGS. IF YOU NEED TO UPDATE A CONFIG
 * USE THE STANDARDLIB.PHP FILE IN YOUR LARAVEL CONFIG FOLDER. IF YOU DON'T HAVE IT
 * RUN:
 *          php artisan vendor:publish --provider="Combustion\StandardLib\Support\StdServiceProvider"
 */
class Config implements PackageConfig
{
    public static function all() : array
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | User Fetch
            |--------------------------------------------------------------------------
            |
            | This can be either a callback that returns the user object, or
            | a method name that should be implemented in your controller.
            |
            | The Standard Lib will reference this setting when it needs to fetch
            | a user. This option is required for most of the combustion packages.
            | Refer to the package resources for more info.
            |
            */

            // todo: this shit needs to not be here at all.
            'user-fetch'    => function () {
                return \JWTAuth::parseToken()->authenticate();
            }
        ];
    }
}