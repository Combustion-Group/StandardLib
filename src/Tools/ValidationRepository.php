<?php

namespace Combustion\StandardLib\Tools;

use Illuminate\Validation\Factory;
use Combustion\StandardLib\Exceptions\ErrorBag;

/**
 * Class ValidationRepository
 *
 * @package Combustion\StandardLib\Tools
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class ValidationRepository
{
    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @var Factory
     */
    private $factory;

    /**
     * ValidationRepository constructor.
     *
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param array $rules
     * @return ValidationRepository
     */
    public function with(array $rules) : ValidationRepository
    {
        $this->rules = $rules;
        return $this;
    }

    /**
     * @param array $data
     * @throws ErrorBag
     */
    public function validate(array $data)
    {
        $validator = $this->factory->make($data, $this->rules);

        if ($validator->fails()) {
            throw new ErrorBag($validator->getMessageBag());
        }
    }
}
