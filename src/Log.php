<?php

namespace Combustion\StandardLib;

use Illuminate\Log\Writer;

/**
 * Class Log
 * @package Combustion\StandardLib
 * @author Carlos Granados <cgranadso@combustiongroup.com>
 */
class Log extends Writer
{
    // Log constants
    const   DEBUG       = 'debug',
            INFO        = 'info',
            NOTICE      = 'notice',
            WARNING     = 'warning',
            ERROR       = 'error',
            CRITICAL    = 'critical',
            ALERT       = 'alert',
            EMERGENCY   = 'emergency';

    /**
     * @var string
     */
    private $logNamespace = null;

    /**
     * @param string $level
     * @param string $message
     * @param array $context
     * @return $this
     */
    public function log($level, $message, array $context = [])
    {
        $namespace = $this->getNamespace();
        parent::log($level, "{$namespace}{$message}", $context);

        return $this;
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
    private function getNamespace()
    {
        if (!$this->logNamespace) {
            return $this->resolveNamespace();
        }

        return $this->formatNamespace($this->logNamespace);
    }

    /**
     * @return string
     */
    private function resolveNamespace()
    {
        // Attempt to figure out what class called this log
        $bt = debug_backtrace();

        if (isset($bt[1]) && isset($bt[1]['class']) && strlen($bt[1]['class'])) {
            return $this->formatNamespace(class_basename($bt[1]['class']));
        }

        return '';
    }

    /**
     * @param string $namespace
     * @return string
     */
    private function formatNamespace(string $namespace)
    {
        return '[' . $namespace . ']';
    }
}
