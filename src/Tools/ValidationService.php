<?php

namespace Combustion\StandardLib\Tools;

use Illuminate\Contracts\Validation\Factory;
use Combustion\StandardLib\Exceptions\ErrorBag;

/**
 * Class ValidationService
 *
 * @package Combustion\StandardLib\Tools
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class ValidationService
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
     * @return ValidationService
     */
    public function with(array $rules) : ValidationService
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
