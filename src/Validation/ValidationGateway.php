<?php
/**
 * Created by PhpStorm.
 * User: LaravelDude
 * Date: 1/18/17
 * Time: 11:12 AM
 */

namespace Combustion\StandardLib\Validation;


use Illuminate\Validation\Validator;

class ValidationGateway
{
    /**
     * @var \Illuminate\Validation\Validator
     */
    protected $validator;
    /**
     * @var array
     */
    protected $config;


    /**
     * ValidationGateway constructor.
     *
     * @param array $config
     */
    public function __construct(array $config,Validator $validator)
    {
        $this->config=$config;
        $this->validator=$validator;
    }

    /**
     * @param array $data
     * @param array $rules
     *
     * @return \Illuminate\Validation\Validator
     */
    public function set(array $data, array $rules)
    {
        $this->setData($data);
        $this->setRules($rules);
        return $this->validator;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->validator->setData($data);
    }

    /**
     * @param array $data
     */
    public function setRules(array $data)
    {
        $this->validator->setRules($data);
    }
}