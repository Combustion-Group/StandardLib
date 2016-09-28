<?php

namespace Combustion\StandardLib\Contracts;

interface ServiceFactory
{
    public function service(string $name);
}