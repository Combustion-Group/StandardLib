<?php

namespace CombustionGroup\Std\Exceptions;

use CombustionGroup\Std\Traits\ClientReadable;

class RecordNotFoundException extends \Exception { use ClientReadable; }
