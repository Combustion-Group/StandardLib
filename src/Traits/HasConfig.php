<?php

namespace Combustion\StandardLib\Traits;

trait HasConfig
{
    abstract protected function getConfig() : array;
}