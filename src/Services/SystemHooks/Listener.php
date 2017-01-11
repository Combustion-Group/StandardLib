<?php

namespace Combustion\StandardLib\Services\SystemHooks;

interface Listener
{
    public function fire($data);
}