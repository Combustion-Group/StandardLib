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

    /**
     * @return string
     */
    public function getFirstName() : string;

    /**
     * @return string
     */
    public function getLastName() : string;

    /**
     * @return string
     */
    public function getFullName() : string;

    /**
     * @return mixed
     */
    public function toArray();
}
