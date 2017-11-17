<?php

namespace Combustion\StandardLib\Exceptions;

use Combustion\StandardLib\MessageBag;
use Combustion\StandardLib\Traits\ClientReadable;

/**
 * Class ErrorBag
 *
 * A bag of errors. This is a strange wrapper but gets around the
 * lack of multiple inheritance.
 *
 * @package Combustion\StandardLib\Exceptions
 * @author Carlos Granados <cgranados@combustiongroup.com>
 * @method array all()
 * @method void mergeWith(string $key, array $messages)
 * @method void add(string $key, array $message)
 */
class ErrorBag extends \Exception implements \Countable
{
    use ClientReadable;

    /**
     * @var MessageBag
     */
    private $errorBag;

    /**
     * ErrorBag constructor.
     * @param array $messages
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct($messages = [], $code = 0, \Exception $previous = null)
    {
        if (is_array($messages)) {
            $this->errorBag = new MessageBag($messages);
        } elseif ($messages instanceof MessageBag) {
            $this->errorBag = $messages;
        }
        parent::__construct('', $code, $previous);
    }

    /**
     * This is sort of a pseudo multiple inheritance since I couldn't extend both
     * the base Exception class and the MessageBag class.
     *
     * @param $name
     * @param $arguments
     * @return ErrorBag
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->errorBag, $name], $arguments);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->errorBag);
    }


    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        return $this->getMessage();
    }
}
