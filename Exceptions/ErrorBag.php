<?php

namespace CombustionGroup\Std\Exceptions;

use CombustionGroup\Std\MessageBag;
use CombustionGroup\Std\Traits\ClientReadable;

/**
 * Class ErrorBag
 *
 * A bag of errors.
 *
 * @package App\Lib\Menu\Exceptions
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
    public function __construct(array $messages = [], $code = 0, \Exception $previous = null)
    {
        parent::__construct('', $code, $previous);
        $this->errorBag = new MessageBag($messages);
    }

    /**
     * This is sort of a pseudo multiple inheritance since we couldn't extend both
     * the base Exception class and the MessageBag class.
     *
     * @param $name
     * @param $arguments
     * @return ErrorBag
     */
    public function __call($name, $arguments) : ErrorBag
    {
        call_user_func_array([$this->errorBag, $name], $arguments);
        return $this;
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
    public function __toString() :string
    {
        return $this->getMessage();
    }
}
