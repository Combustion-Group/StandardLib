<?php

namespace CombustionGroup\Std\Traits;

use CombustionGroup\Std\Exceptions\RecordNotFoundException;

/**
 * Class HandlesNotFound
 * @package App\Lib\Std\Traits
 * @property array $config
 */
trait HandlesNotFound
{
    /**
     * @return array
     */
    protected abstract function getConfig() : array;

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
