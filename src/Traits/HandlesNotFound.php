<?php

namespace Combustion\StandardLib\Traits;

use Combustion\StandardLib\Exceptions\RecordNotFoundException;

/**
 * Class HandlesNotFound
 * @package Combustion\StandardLib\Traits
 * @property array $config
 */
trait HandlesNotFound
{
    use HasConfig;

    /**
     * @param string $message
     * @return null
     * @throws RecordNotFoundException
     */
    protected function notFound($message = "Record could not be found")
    {
        $config = $this->getConfig();
        if (isset($config[RecordNotFoundException::class]) && $config[RecordNotFoundException::class]) {
            throw new RecordNotFoundException($message);
        }
        return null;
    }
}
