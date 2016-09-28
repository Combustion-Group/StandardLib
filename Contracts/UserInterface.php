<?php

namespace Combustion\StandardLib\Contracts;

interface UserInterface {

    /**
     * @return int
     */
    public function getId() : int;

    /**
     * @return string
     */
    public function getEmail() : string;
}