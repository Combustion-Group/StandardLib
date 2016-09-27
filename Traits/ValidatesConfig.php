<?php

namespace CombustionGroup\Std\Traits;

use CombustionGroup\Std\Exceptions\ConfigNotFoundException;

/**
 * Class ValidatesConfig
 *
 * @package App\Lib\Support
 * @author Carlos Granados <cgranados@combustiongroup.com>
 */
trait ValidatesConfig
{

    /**
     * @return array
     */
    protected abstract function getRequiredConfig() : array;

    /**
     * @param array $config
     * @param array $required
     * @return array
     * @throws ConfigNotFoundException
     */
    public function validateConfig(array $config, array $required = null)
    {
        // If the required param is null we'll use the $requiredConfig array.
        // In the child class you should add the keys you are looking for in
        // your config.
        $required = $required === null ? $this->getRequiredConfig() : $required;
        $notFound = array_diff($required, array_keys($config));

        if (count($notFound)) {
            throw new ConfigNotFoundException("Missing required configuration: [" . implode(',', $notFound) . "]\nReceived [" . implode(',', $config) . "]");
        }

        return $config;
    }
}
