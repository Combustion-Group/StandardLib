<?php

namespace Combustion\StandardLib\Assets\Contracts;


use Illuminate\Database\Eloquent\Relations\MorphMany;

interface AssetDocumentInterface
{
    public function asset():MorphMany;
    public function getId():int;
}