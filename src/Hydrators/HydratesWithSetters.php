<?php

namespace Combustion\StandardLib\Hydrators;

use Combustion\StandardLib\Exceptions\UnresolvableSetterException;

/**
 * Class HydratesWithASetters
 * @package Combustion\StandardLib\Traits
 * @author Carlos Granados <cgranados@combustiongroup.com>
 */
trait HydratesWithSetters
{
    protected $setterPrefix = 'set';

    /**
     * @param array $data
     * @param bool $strict
     * @throws UnresolvableSetterException
     */
    public function fill(array $data, bool $strict = true)
    {
        foreach ($data as $key => $value) {
            $setter = $this->resolveSetter($key, $strict);
            if ($setter === false) {
                continue;
            }
            $this->{$setter}($value);
        }
    }

    /**
     * @param string $key
     * @param bool $failIfNotFound
     * @return mixed
     * @throws UnresolvableSetterException
     */
    protected function resolveSetter(string $key, bool $failIfNotFound)
    {
        $expectedSetter = camel_case($this->setterPrefix . '_' . $key);

        if (method_exists($this, $expectedSetter)) {
            return $expectedSetter;
        }

        if ($failIfNotFound) {
            throw new UnresolvableSetterException("Cannot find a setter method for field '{$key}'");
        }

        return false;
    }
}
