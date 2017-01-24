<?php

namespace Combustion\StandardLib\Support;

use Illuminate\Database\Seeder;
use Combustion\Billing\Exceptions\BadSeederException;

/**
 * Class BaseSeeder
 * @package Combustion\StandardLib\Support
 * @author Carlos Granados <cgranados@combustiongroup.com>
 */
abstract class BaseSeeder extends Seeder
{
    /**
     * @return string
     */
    abstract public function getPackage() : string;

    /**
     * @param array $source String list
     * @param array $data Associative array
     * @param string $column Column to unique on in the $data array
     * @return array
     */
    protected function dictionaryFilterList(array $source, array $data, string $column) : array
    {
        $new 	= array_column($data, $column);
        $keep 	= array_diff($new, $source);

        return array_intersect_key($data, $keep);
    }

    /**
     * @return array
     * @throws BadSeederException
     */
    protected function getEntries() : array
    {
        $seeder     = get_called_class();
        $seeder     = explode('\\', $seeder);
        $seeder     = array_pop($seeder);
        $package    = $this->getPackage();
        $data       = \Config::get("{$package}.seeders.{$seeder}");

        if (!$data) {
            throw new BadSeederException("There is no data configured for seeder {$seeder}");
        }

        return $data;
    }
}
