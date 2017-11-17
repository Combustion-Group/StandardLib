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
     * @param string $message
     * @return $this
     * @throws InvalidOperationException
     */
    public function hasKeys(array $needles, array $haystack, string $message = null)
    {
        $diff = array_diff($needles, array_keys($haystack));
        $missing = implode(',', $diff);
        $message = is_null($message) ? "Array is missing the following fields: :missing" : $message;

        if (count($diff)) {
            throw new InvalidOperationException(str_replace(':missing', $missing, $message));
        }

        return $this;
    }
}
