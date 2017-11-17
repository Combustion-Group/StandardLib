<?php

namespace Combustion\StandardLib;

use Illuminate\Log\Writer;
use Illuminate\Support\Facades\Input;
use Combustion\StandardLib\Contracts\UserInterface;

/**
 * Class Log
 *
 * @package Combustion\StandardLib
 * @author  Carlos Granados <cgranadso@combustiongroup.com>
 */
class Log extends Writer
{
    // Log constants
    const   DEBUG = 'debug',
        INFO = 'info',
        NOTICE = 'notice',
        WARNING = 'warning',
        ERROR = 'error',
        CRITICAL = 'critical',
        ALERT = 'alert',
        EMERGENCY = 'emergency';

    /**
     * @var string
     */
    private $logNamespace = null;

    /**
     * @var UserInterface
     */
    private static $currentUser = null;

    /**
     * @param string $level
     * @param string $message
     * @param array $context
     * @return $this
     */
    public function log($level, $message, array $context = [])
    {
        parent::log($level, $message, $this->prepareContext($context));

        return $this;
    }

    /**
     * @param array $context
     * @return array
     */
    public function prepareContext(array $context)
    {
        $context['_request'] = [];
        $context['_request']['body'] = Input::all();
        $context['_request']['token'] = $this->getToken();
        $context['_request']['user'] = $this->getUserInfo();

        return $context;
    }

    public function getToken()
    {
        try {
            $token = \JWTAuth::getToken();
        } catch (\Exception $e) {
            return '';
        }

        return $token;
    }

    /**
     * @return array|mixed
     */
    public function getUserInfo()
    {
        if (self::$currentUser === false) {
            return [];
        }

        try {
            if (is_null(self::$currentUser)) {
                self::$currentUser = Controller::getAuthenticatedUser();
            }
        } catch (\Exception $e) {
            self::$currentUser = false;
            return [];
        }

        return self::$currentUser->toArray();
    }

    /**
     * @param string $namespace
     * @return $this
     */
    public function setNamespace(string $namespace)
    {
        $this->logNamespace = $namespace;
        return $this;
    }

    /**
     * @return string
     */
    private function resolveNamespace()
    {
        // Attempt to figure out what class called this log
        foreach (debug_backtrace() as $item) {
            if ($item['class'] !== self::class) {
                $class = $item['class'] . '::' . $item['function'];
                break;
            }
        }

        return isset($class) ? $class : '';
    }

    /**
     * @param string $message
     * @param array $context
     * @return Log
     */
    public function debug($message, array $context = [])
    {
        return $this->log(__FUNCTION__, $message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     * @return Log
     */
    public function info($message, array $context = [])
    {
        return $this->log(__FUNCTION__, $message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     * @return Log
     */
    public function notice($message, array $context = [])
    {
        return $this->log(__FUNCTION__, $message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     * @return Log
     */
    public function warning($message, array $context = [])
    {
        return $this->log(__FUNCTION__, $message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     * @return Log
     */
    public function error($message, array $context = [])
    {
        return $this->log(__FUNCTION__, $message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     * @return Log
     */
    public function critical($message, array $context = [])
    {
        return $this->log(__FUNCTION__, $message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     * @return Log
     */
    public function alert($message, array $context = [])
    {
        return $this->log(__FUNCTION__, $message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     * @return Log
     */
    public function emergency($message, array $context = [])
    {
        return $this->log(__FUNCTION__, $message, $context);
    }
}
