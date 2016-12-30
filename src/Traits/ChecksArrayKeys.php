<?php

namespace Combustion\StandardLib\Traits;

use Combustion\StandardLib\Support\Installer\Exceptions\InvalidOperationException;

/**
 * Class ChecksArrayKeys
 * @package Combustion\StandardLib\Traits
 * @author Carlos Granados <cgranados@combustiongroup.com>
 */
trait ChecksArrayKeys
{
    /**
     * @param array $needles
     * @param array $haystack
     * @return $this
     * @throws InvalidOperationException
     */
    public function hasKeys(array $needles, array $haystack)
    {
        $diff = array_diff($needles, array_keys($haystack));

        if (count($diff)) {
            throw new InvalidOperationException("Array is missing the following fields " . implode(',', $diff));
        }

        return $this;
    }
}
