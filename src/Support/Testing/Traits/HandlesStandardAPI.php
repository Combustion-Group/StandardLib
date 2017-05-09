<?php

namespace Combustion\StandardLib\Support\Testing\Traits;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

/**
 * Class HandlesStandardAPI
 *
 * @package Combustion\StandardLib\Support\Testing\Traits
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
trait HandlesStandardAPI
{
    protected function fetchError()
    {
        $response   = $this->getResponse()->getContent();
        $body       = json_decode($response, true);

        if (is_array($body) && array_key_exists('messages', $body)) {
            return implode(',', $body['messages']);
        }

        return null;
    }

    /**
     * @return Response|JsonResponse
     */
    abstract protected function getResponse();

}
