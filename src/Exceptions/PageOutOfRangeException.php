<?php

namespace Combustion\StandardLib\Exceptions;

use Combustion\StandardLib\Traits\ClientReadable;

class PageOutOfRangeException extends \Exception
{
    use ClientReadable;
}
