<?php

namespace CombustionGroup\StandardLib\Exceptions;

class MissingRequiredDataException extends \Exception {

    private $missing = [];

    /**
     * @param array $missing
     * @return MissingRequiredDataException
     */
    public function setMissingFields(array $missing) : MissingRequiredDataException
    {
        $this->missing = $missing;
        return $this;
    }

    /**
     * @return array
     */
    public function getMissingFields() : array
    {
        return $this->missing;
    }
}
