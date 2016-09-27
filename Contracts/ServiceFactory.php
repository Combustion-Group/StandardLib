<?php

namespace CombustionGroup\StandardLib\Contracts;

interface ServiceFactory
{
    public function service(string $name);
}