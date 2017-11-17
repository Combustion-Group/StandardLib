<?php

namespace Combustion\StandardLib\Services\Assets;

use Combustion\StandardLib\Services\Assets\Contracts\AssetDocumentInterface;
use Combustion\StandardLib\Services\Assets\Contracts\Manipulator;
use Combustion\StandardLib\Services\Assets\Exceptions\ModelMustHaveHasAssetsTrait;
use Combustion\StandardLib\Services\Assets\Traits\HasAssets;
use Illuminate\Http\UploadedFile;

/**
 * Class DocumentsGateway
 *
 * @package Combustion\StandardLib\Services\Assets
 * @author  Luis A. Perez <lperez@combustiongroup.com>
 */
abstract class DocumentsGateway
{
    /**
     * @var
     */
    protected $manipulators;

    /**
     * @param \Illuminate\Http\UploadedFile $file
     * @param array $options
     *
     * @return \Combustion\StandardLib\Services\Assets\Contracts\AssetDocumentInterface
     */
    public abstract function create(UploadedFile $file, array $options = []): AssetDocumentInterface;

    /**
     * @param int $documentId
     *
     * @return \Combustion\StandardLib\Services\Assets\Contracts\AssetDocumentInterface
     */
    public abstract function getOrFail(int $documentId): AssetDocumentInterface;

    /**
     * @return array
     */
    public abstract function getConfig(): array;

    /**
     * @param array|null $options
     *
     * @return \Combustion\StandardLib\Services\Assets\Contracts\Manipulator
     * @throws \Combustion\StandardLib\Services\Assets\Exceptions\ModelMustHaveHasAssetsTrait
     */
    public function getManipulator(array $options = []): Manipulator
    {
        // if model is not sent return default manipulator
        if (!isset($options['model'])) {
            return $this->manipulators[$this->config['default_manipulator']];
        } // if it was sent make sure it has the HasAssets trait otherwise throw exception
        elseif (!isset(class_uses($options['model'])[HasAssets::class])) {
            throw new ModelMustHaveHasAssetsTrait(get_class($options['model']) . " does not have HasAssets trait");
        } // if the method does exist return getManipulator from model
        elseif (method_exists($options['model'], 'getManipulator')) {
            return $this->manipulators[$options['model']->getManipulator()];
        } else {
            // otherwise return default manipulator again
            return $this->manipulators[$this->config['default_manipulator']];
        }
    }

    /**
     * @param \Illuminate\Http\UploadedFile $file
     *
     * @return \Illuminate\Http\UploadedFile
     */
    public function moveToLocalDisk(UploadedFile $file): UploadedFile
    {
        $disk = $this->localDriver;
        $newFileName = md5(time() . $file->getClientOriginalName());
        $fileDestination = $this->fileGateway->getConfig()['local_document_folder_name'] . '/' . $newFileName . '.' . $file->extension();
        $fileLocation = $this->fileGateway->getConfig()['local_document_folder'] . '/' . $newFileName . '.' . $file->extension();
        $disk->put($fileDestination, file_get_contents($file));
        return new UploadedFile($fileLocation, $newFileName);
    }
}