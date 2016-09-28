<?php

namespace Combustion\StandardLib\Support;

use Illuminate\Database\Seeder;

/**
 * Class BaseSeeder
 * @package Combustion\StandardLib\Support
 * @author Carlos Granados <cgranados@combustiongroup.com>
 */
abstract class BaseSeeder extends Seeder
{
    /**
     * @param array $source String list
     * @param array $data Associative array
     * @param string $column Column to unique on in the $data array
     * @return array
     */
    protected function arrayFilterDictionary(array $source, array $data, string $column) : array
    {
        $new 	= array_column($data, $column);
        $keep 	= array_diff($new, $source);

        return array_intersect_key($data, $keep);
    }
}
