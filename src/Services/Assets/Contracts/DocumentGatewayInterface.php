<?php

namespace Combustion\StandardLib\Services\Assets\Contracts;


use Illuminate\Http\UploadedFile;

/**
 * Interface DocumentGatewayInterface
 *
 * @package Combustion\StandardLib\Services\Assets\Contracts
 * @author Luis A. Perez <lperez@combustiongroup.com>
 */
interface DocumentGatewayInterface
{
    /**
     * @param \Illuminate\Http\UploadedFile $file
     * @param array                         $options
     *
     * @return \Combustion\StandardLib\Services\Assets\Contracts\AssetDocumentInterface
     */
    public function create(UploadedFile $file, array $options = []) : AssetDocumentInterface;

    /**
     * @param int $documentId
     *
     * @return \Combustion\StandardLib\Services\Assets\Contracts\AssetDocumentInterface
     */
    public function getOrFail(int $documentId) : AssetDocumentInterface;

    /**
     * @return array
     */
    public function getConfig() : array;
}