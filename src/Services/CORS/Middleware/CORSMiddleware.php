<?php

namespace Combustion\StandardLib\Services\CORS\Middleware;

use Closure;

/**
 * Class CORSMiddleware
 *
 * @package Combustion\StandardLib\Services\CORS\Middleware
 * @author  Luis A. Perez <lperez@combustiongroup.com>
 */
class CORSMiddleware
{
    /**
     * @param          $request
     * @param \Closure $next
     * @param string $origin
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $origin = '*')
    {
        $response = $next($request);
        $http = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : $origin;
        $headers = [
            "Access-Control-Allow-Origin" => $http,
            "Access-Control-Allow-Credentials" => "true",
            "Access-Control-Allow-Methods" => "GET, POST, PUT, DELETE, OPTIONS",
            "Access-Control-Allow-Headers" => "Accept,Authorization,Cache-Control,Content-Type,DNT,If-Modified-Since,Keep-Alive,Origin,User-Agent,X-Mx-ReqToken,X-Requested-With,X-CSRF-Token,Accept-language",

            // tells browser to allow calls for 20 before checking Access Control again
            "Access-Control-Max-Age" => "1728000",
        ];

        $response->headers->add($headers);

        return $response;
    }
}