<?php


namespace Combustion\StandardLib\Services\Assets;


class DocumentsGateway
{
    /**
     * @param \Illuminate\Http\UploadedFile $file
     * @param array                         $options
     *
     * @return \Combustion\StandardLib\Services\Assets\Contracts\AssetDocumentInterface
     */
    public abstract function create(UploadedFile $file, array $options = []) : AssetDocumentInterface;

    /**
     * @param int $documentId
     *
     * @return \Combustion\StandardLib\Services\Assets\Contracts\AssetDocumentInterface
     */
    public abstract function getOrFail(int $documentId) : AssetDocumentInterface;

    /**
     * @return array
     */
    public abstract function getConfig() : array;

    /**
     * @param array|null $options
     *
     * @return \Combustion\StandardLib\Services\Assets\Contracts\Manipulator
     * @throws \Combustion\StandardLib\Services\Assets\Exceptions\ModelMustHaveHasAssetsTrait
     */
    public function getManipulator(array $options=null) : Manipulator
    {
        // if model is not sent return default manipulator
        if(is_null($options) || !in_array('model',$options)) return $this->manipulators[$this->config['manipulators'][$this->config['default_manipulator']]];
        // if it was sent make sure it has the HasAssets trait otherwise throw exception
        if(!$options['model'] instanceof HasAssets) throw new ModelMustHaveHasAssetsTrait(get_class($options['model'])." does not have HasAssets trait");
        // if the method does exist return getManipulator from model
        if(method_exists($options['model'],'getManipulator')) return $this->config['manipulators'][$options['model']->getManipulator()];
        // otherwise return default manipulator again
        return $this->config['manipulators'][$this->config['default_manipulator']];
    }
}