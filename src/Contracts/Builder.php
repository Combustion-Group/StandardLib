<?php

namespace Combustion\StandardLib\Contracts;

use Combustion\StandardLib\Exceptions\BuilderException;
use Combustion\StandardLib\Hydrators\HydratesWithSetters;

/**
 * Interface Builder
 *
 * @package Combustion\StandardLib\Contracts
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
abstract class Builder
{
    use HydratesWithSetters;

    /**
     * @var array
     */
    private $params = [];

    abstract public function build();

    /**
     * @param string $param
     * @param        $value
     * @return $this
     */
    public function addParam(string $param, $value)
    {
        $this->params[$param] = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function all() : array
    {
        return $this->params;
    }

    /**
     * @param string $param
     * @return bool
     */
    protected function hasParam(string $param) : bool
    {
        return array_key_exists($param, $this->params);
    }

    /**
     * @param string $param
     * @return mixed
     * @throws BuilderException
     */
    public function getParam(string $param)
    {
        if (!$this->hasParam($param)) {
            throw new BuilderException(get_called_class() . ": Invalid parameter, \"{$param}\" is not in parameter bag.");
        }

        return $this->params[$param];
    }

    /**
     * @throws BuilderException
     */
    protected function validateParams()
    {
        $missing = array_intersect_key($this->getRequired(), array_keys($this->all()));

        if (count($missing)) {
            throw new BuilderException(get_called_class() . ": Unable to build, missing required parameters: " . implode(', ', $missing));
        }
    }

    /**
     * @return array
     */
    protected function getRequired() : array
    {
        if (defined('self::PARAMS')) {
            return self::PARAMS;
        }

        return [];
    }
}
