<?php

namespace Combustion\StandardLib\Support\Responses;

/**
 * Interface CustomResponse
 *
 * @package Combustion\StandardLib\Support\Responses
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
interface CustomResponse
{
    /**
     * @return array
     */
    public function getData(): array;

    /**
     * @return array
     */
    public function getTopLevel(): array;
}