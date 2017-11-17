<?php

namespace Combustion\StandardLib\Hydrators;

/**
 * Class HydratesObject
 *
 * @package Combustion\StandardLib\Hydrators
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
trait HydratesObject
{
    /**
     * @param string $class
     * @param array $data
     * @return Object[]  $data
     */
    public function hydrateMany(string $class, array $data): array
    {
        foreach ($data as &$item) {
            $item = new $class($item);
        }

        return $data;
    }

    /**
     * @param array $data
     */
    public function hydrateProperties(array $data)
    {
        foreach ($data as $key => $value) {
            $key = $this->snakeToCamel($key);

            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }

        return $this;
    }

    /**
     * Convert snake case string to camel case
     *
     * @param $val
     * @return mixed
     */
    private function snakeToCamel($val)
    {
        return count(explode('_', $val)) === 1 ? strtolower($val) : lcfirst(str_replace(' ', '', ucwords(strtolower(str_replace('_', ' ', $val)))));
    }
}