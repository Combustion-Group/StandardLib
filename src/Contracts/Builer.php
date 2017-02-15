<?php

namespace Combustion\StandardLib\Contracts;

use Combustion\StandardLib\Exceptions\BuilderException;

/**
 * Interface Builder
 *
 * @package Combustion\StandardLib\Contracts
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
abstract class Builder
{
    /**
     * @var array
     */
    protected $params = [];

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
     * @return mixed
     * @throws BuilderException
     */
    public function getParam(string $param)
    {
        if (!array_key_exists($param, $this->params)) {
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
    public function getRequired() : array
    {
        return [];
    }
}
