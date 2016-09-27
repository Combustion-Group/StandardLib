<?php

namespace CombustionGroup\StandardLib\Exceptions;

use CombustionGroup\StandardLib\Traits\ClientReadable;

class RecordNotFoundException extends \Exception { use ClientReadable; }
