<?php

namespace Combustion\StandardLib\Services\SystemEvents;

use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class Async
 *
 * @package Combustion\StandardLib\Services\SystemEvents
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
final class AsyncWorker implements ShouldQueue
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function handle(Listener $listener)
    {

    }
}
