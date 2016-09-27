<?php

namespace CombustionGroup\Std\Contracts;

interface ServiceFactory
{
    public function service(string $name);
}