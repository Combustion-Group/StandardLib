<?php

namespace Combustion\StandardLib\Traits;

use Combustion\StandardLib\Exceptions\UnresolvableSetterException;

/**
 * Class HydratesWithASetters
 * @package App\Lib\Std\Traits
 * @author Carlos Granados <cgranados@combustiongroup.com>
 */
trait HydratesWithSetters
{
    protected $setterPrefix = 'set';

    /**
     * @param array $data
     */
    public function fill(array $data)
    {
        foreach($data as $key => $value) {
            $setter = $this->resolveSetter($key);
            $this->{$setter}($value);
        }
    }

    /**
     * @param string $key
     * @return string
     */
    public function resolveSetter(string $key) : string
    {
        $expectedSetter = camel_case($this->setterPrefix . '_' . $key);

        if (method_exists($this, $expectedSetter)) {
            return $expectedSetter;
        }

        throw new UnresolvableSetterException("Cannot find a setter method for field '{$key}'");
    }
}
