<?php

namespace Combustion\StandardLib\Exceptions;

/**
 * Class RuntimeException
 *
 * You should never make this inherit ClientReadable. This exception
 * should just be logged instead.
 *
 * @package Combustion\StandardLib\Exceptions
 */
class RuntimeException extends \Exception
{
}
