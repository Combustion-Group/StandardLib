<?php

namespace Combustion\StandardLib\Services\SystemEvents;

interface Listener
{
    public function fire($data);
}