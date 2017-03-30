<?php

namespace Combustion\StandardLib\Services\DeepLinks;

use Combustion\StandardLib\Traits\ValidatesConfig;
use Combustion\StandardLib\Services\DeepLinks\Exceptions\DeepLinkException;

/**
 * Class DeepLinkServices
 *
 * @package Combustion\StandardLib\Services\DeepLinks
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class DeepLinkService
{
    use ValidatesConfig;

    /**
     * @var array
     */
    private $requiredConfig = [
        'base-url'
    ];

    /**
     * @var array
     */
    private $config = [];

    // const
    const   IPHONE  = 0,
            ANDROID = 1,
            UNKNOWN = 2;

    // bit flags
    const   HTTP_URL_REPLACE        = 1,
            HTTP_URL_JOIN_PATH      = 2,
            HTTP_URL_JOIN_QUERY     = 4,
            HTTP_URL_STRIP_USER     = 8,
            HTTP_URL_STRIP_PASS     = 16,
            HTTP_URL_STRIP_AUTH     = 32,
            HTTP_URL_STRIP_PORT     = 64,
            HTTP_URL_STRIP_PATH     = 128,
            HTTP_URL_STRIP_QUERY    = 256,
            HTTP_URL_STRIP_FRAGMENT = 512,
            HTTP_URL_STRIP_ALL      = 1024;

    /**
     * DeepLinkServices constructor.
     *
     * @param array $config
     */
//    public function __construct(array $config)
//    {
//        $this->config = $this->validateConfig($config);
//    }

    /**
     * @return array
     */
    public function getRequiredConfig() : array
    {
        return $this->requiredConfig;
    }

    /**
     * @param array $params
     * @param array $headers
     * @return mixed
     * @throws DeepLinkException
     */
    public function handle(array $params, array $headers) : array
    {
        $os         = $this->resolveClientOS($headers);
        $req        = ['action'];
        $missing    = array_diff_key($req, array_keys($params));

        if (count($missing)) {
            throw new DeepLinkException("Missing the following required data: " .  implode(', ', $missing));
        }

        $data['app_store_url']  = $this->resolveFallbackUrl($os);
        $data['intended_url']   = $this->resolveIntendedUrl($params['action'], $os, $params);

        return $data;
    }

    /**
     * @param string $action
     * @param array  $params
     */
    public function makeUrl(string $action, array $params)
    {

    }

    /**
     * @param string $action
     * @param        $os
     * @param array  $data
     * @return string
     * @throws DeepLinkException
     */
    private function resolveIntendedUrl(string $action, $os, array $data = []) : string
    {
        if ($action == "APP_INVITE") {
            return $this->resolveFallbackUrl($os);
        }

        $link = $this->fetch($action);

        if (!$link) {
            throw new DeepLinkException("Unknown deep link action: {$action}.");
        }

        return $this->buildUrl($link->getUrl(), $data);
    }

    /**
     * @param string $url
     * @param array  $params
     * @return string
     */
    private function buildUrl(string $url, array $params = []) : string
    {
        $pieces     = parse_url($url);

        if (count($params)) {
            $query = http_build_query($params);

            if (array_key_exists('query', $pieces)) {
                $pieces['query'] .= http_build_query($params);
            } else {
                $pieces['query'] = $query;
            }
        }

        return $this->http_build_url($pieces);
    }

    /**
     * @param string $action
     * @param string $url
     * @return DeepLink
     * @throws DeepLinkException
     */
    public function create(string $action, string $url) : DeepLink
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new DeepLinkException("Cannot create deep link record. Url is invalid/malformed.");
        }

        return DeepLink::create([DeepLink::ACTION => $action, DeepLink::URL => $url]);
    }

    /**
     * @param string $action
     * @return DeepLink|null
     */
    public function fetch(string $action)
    {
        return DeepLink::where(DeepLink::ACTION, '=', $action)->first();
    }

    /**
     * @param array $headers
     * @return int
     */
    private function resolveClientOS(array $headers)
    {
        if (isset($headers['user-agent'])) {
            $userAgent = $headers['user-agent'];
        } else {
            return self::UNKNOWN;
        }

        foreach ($userAgent as $agent)
        {
            if (stripos($agent, 'iphone') !== false) {
                return self::IPHONE;
            } elseif (stripos($agent, 'android') !== false) {
                return self::ANDROID;
            }
        }

        return self::UNKNOWN;
    }

    /**
     * @param $os
     * @return string
     */
    private function resolveFallbackUrl($os) : string
    {
        if ($os == self::IPHONE) {
            return "https://itunes.apple.com/us/app/wavework-live-event-networking/id1074576367?mt=8";
        } elseif ($os === self::ANDROID) {
            return "https://play.google.com/store/apps/details?id=com.combustiongroup.wavework&hl=en";
        }

        return (string)url('');
    }

    /**
     * PHP version of PECL HTTP's http_build_url()
     * @param       $url
     * @param array $parts
     * @param int   $flags
     * @param bool  $new_url
     * @return string
     */
    public function http_build_url($url, $parts = [], $flags = self::HTTP_URL_REPLACE, &$new_url = false)
    {
        $keys = ['user', 'pass', 'port', 'path', 'query', 'fragment'];

        // HTTP_URL_STRIP_ALL becomes all the HTTP_URL_STRIP_Xs
        if ($flags & self::HTTP_URL_STRIP_ALL)
        {
            $flags |= self::HTTP_URL_STRIP_USER;
            $flags |= self::HTTP_URL_STRIP_PASS;
            $flags |= self::HTTP_URL_STRIP_PORT;
            $flags |= self::HTTP_URL_STRIP_PATH;
            $flags |= self::HTTP_URL_STRIP_QUERY;
            $flags |= self::HTTP_URL_STRIP_FRAGMENT;
        }
        // HTTP_URL_STRIP_AUTH becomes HTTP_URL_STRIP_USER and HTTP_URL_STRIP_PASS
        else if ($flags & self::HTTP_URL_STRIP_AUTH)
        {
            $flags |= self::HTTP_URL_STRIP_USER;
            $flags |= self::HTTP_URL_STRIP_PASS;
        }

        // Parse the original URL
        $parse_url = !is_array($url) ? parse_url($url) : $url;

        // Scheme and Host are always replaced
        if (isset($parts['scheme']))
            $parse_url['scheme'] = $parts['scheme'];
        if (isset($parts['host']))
            $parse_url['host'] = $parts['host'];

        // (If applicable) Replace the original URL with it's new parts
        if ($flags & self::HTTP_URL_REPLACE)
        {
            foreach ($keys as $key)
            {
                if (isset($parts[$key]))
                    $parse_url[$key] = $parts[$key];
            }
        }
        else
        {
            // Join the original URL path with the new path
            if (isset($parts['path']) && ($flags & self::HTTP_URL_JOIN_PATH))
            {
                if (isset($parse_url['path']))
                    $parse_url['path'] = rtrim(str_replace(basename($parse_url['path']), '', $parse_url['path']), '/') . '/' . ltrim($parts['path'], '/');
                else
                    $parse_url['path'] = $parts['path'];
            }

            // Join the original query string with the new query string
            if (isset($parts['query']) && ($flags & self::HTTP_URL_JOIN_QUERY))
            {
                if (isset($parse_url['query']))
                    $parse_url['query'] .= '&' . $parts['query'];
                else
                    $parse_url['query'] = $parts['query'];
            }
        }

        // Strips all the applicable sections of the URL
        // Note: Scheme and Host are never stripped
        foreach ($keys as $key)
        {
            if ($flags & (int)constant('self::HTTP_URL_STRIP_' . strtoupper($key)))
                unset($parse_url[$key]);
        }

        $new_url = $parse_url;

        return
            ((isset($parse_url['scheme'])) ? $parse_url['scheme'] . '://' : '')
            .((isset($parse_url['user'])) ? $parse_url['user'] . ((isset($parse_url['pass'])) ? ':' . $parse_url['pass'] : '') .'@' : '')
            .((isset($parse_url['host'])) ? $parse_url['host'] : '')
            .((isset($parse_url['port'])) ? ':' . $parse_url['port'] : '')
            .((isset($parse_url['path'])) ? $parse_url['path'] : '')
            .((isset($parse_url['query'])) ? '?' . $parse_url['query'] : '')
            .((isset($parse_url['fragment'])) ? '#' . $parse_url['fragment'] : '')
            ;
    }
}
