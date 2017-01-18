<?php

namespace Combustion\StandardLib\Assets\Contracts;


use Illuminate\Http\UploadedFile;

interface DocumentGatewayInterface
{
    public function create(UploadedFile $file,array $options=[]):AssetDocumentInterface;
    public function getOrFail(int $documentId):AssetDocumentInterface;
}