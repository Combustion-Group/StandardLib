<?php

namespace Combustion\StandardLib\Services\SystemEvents;

use Combustion\StandardLib\Services\SystemEvents\Exceptions\SystemHookRegisterException;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class SystemEvents
 *
 * An observerish helper class.
 *
 * @package Combustion\StandardLib\Services\SystemHooks
 */
class SystemEventsService
{
    /**
     * @var Listener[]|string
     */
    private $listeners = [];

    /**
     * @var Application
     */
    private $kernel;

    /**
     * SystemEvents constructor.
     * @param Application $kernel
     */
    public function __construct(Application $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @param string $event
     * @param $data
     * @return SystemEventsService
     */
    public function fire(string $event, $data = []): SystemEventsService
    {
        $listeners = $this->getListeners($event);

        foreach ($listeners as $listener) {
            if (array_key_exists('l', $listener)) {
                /**
                 * @var Listener[] $listener
                 */
                $listener['l']->fire($data);
            } else {
                $listener($data);
            }
        }

        return $this;
    }

    /**
     * @param $event
     * @return array
     */
    private function getListeners($event): array
    {
        if (array_key_exists($event, $this->listeners)) {
            return $this->listeners[$event];
        }

        return [];
    }

    /**
     * @param string $eventName
     * @param $callback
     * @return SystemEventsService
     * @throws SystemHookRegisterException
     */
    public function on(string $eventName, $callback): SystemEventsService
    {
        if (!array_key_exists($eventName, $this->listeners)) {
            $this->listeners[$eventName] = [];
        }

        $this->listeners[$eventName][] = $this->validateCallback($eventName, $callback);

        return $this;
    }

    /**
     * @param string $eventName
     * @param $callback
     * @return array
     * @throws SystemHookRegisterException
     */
    public function validateCallback(string $eventName, $callback)
    {
        // The array key subsets 'c' and 'l' allow the on() method to accept an
        // anonymous function or an implementation of Listener.
        if ($callback instanceof \Closure) {
            return ['c' => $callback];
        }

        if (is_object($callback) && $callback instanceof Listener) {
            return ['l' => $callback];
        }

        if (is_string($callback)) {
            $listener = $this->kernel->make($callback);

            if (!$listener instanceof Listener) {
                throw new SystemHookRegisterException("Cannot register listener for event {$eventName}. Listener object must implement Listener");
            }

            return ['l' => $listener];
        }

        throw new SystemHookRegisterException("Cannot register listener for event {$eventName}. Listener must be either an anonymous function, a class implementing Listener as a string or as an object.");
    }
}
