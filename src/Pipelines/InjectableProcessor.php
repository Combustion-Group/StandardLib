<?php

namespace Combustion\StandardLib\Pipelines;

class InjectableProcessor
{
    /**
     * @param array $stages
     * @param mixed $payload
     * @param \Closure $postProcess
     * @return mixed
     */
    public function process(array $stages, $payload, \Closure $postProcess)
    {
        foreach ($stages as $stage) {
            $payload = call_user_func($stage, $payload);
            $payload = $postProcess($payload);
        }

        return $payload;
    }
}
