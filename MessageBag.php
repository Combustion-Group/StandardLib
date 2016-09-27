<?php

namespace CombustionGroup\StandardLib;

use Illuminate\Support\MessageBag as LaravelMessageBag;

class MessageBag extends LaravelMessageBag
{
    /**
     * @param $key
     * @param $messages
     * @return MessageBag
     */
    public function mergeWith($key, $messages) : MessageBag
    {
        if (!isset($this->messages[$key])) {
            $this->messages[$key] = [];
        }

        array_merge($this->messages[$key], $messages);
        ksort($this->messages[$key]);
        return $this;
    }
}
