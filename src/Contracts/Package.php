<?php

namespace Combustion\StandardLib\Contracts;

/**
 * Interface Package
 *
 * @package Combustion\StandardLib\Contracts
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
interface Package
{
    /**
     * @return string
     */
    public static function name() : string;

    /**
     * @return array
     */
    public function getServiceProviders() : array;

    /**
     * @return string
     */
    public static function getPackagePath() : string;
}