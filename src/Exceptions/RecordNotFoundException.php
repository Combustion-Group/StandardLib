<?php

namespace Combustion\StandardLib\Exceptions;

use Combustion\StandardLib\Traits\ClientReadable;

class RecordNotFoundException extends BaseException { use ClientReadable; }
