<?php
namespace Combustion\StandardLib\Services\Assets;
use Combustion\StandardLib\Services\Assets\Contracts\DocumentGatewayInterface;
use Combustion\StandardLib\Services\Assets\Contracts\HasAssetsInterface;
use Combustion\StandardLib\Services\Assets\Exceptions\AssetDriverNotFound;
use Combustion\StandardLib\Services\Assets\Exceptions\ValidationFailed;
use Combustion\StandardLib\Services\Assets\Models\Asset;
use Illuminate\Http\UploadedFile;

/**
 * Class AssetsGateway
 *
 * @package Combustion\StandardLib\Services\Assets
 * @author Luis A. Perez <lperez@combustiongroup.com>
 */
class AssetsGateway
{
    /**
     * @var array
     */
    protected $config;
    /**
     * @var DocumentGatewayInterface array
     */
    protected $drivers;

    /**
     * AssetsGateway constructor.
     *
     * @param array $config
     * @param array $drivers
     */
    public function __construct(array $config, array $drivers)
    {
        $this->config  = $this->validatesConfig($config);
        $this->drivers = $this->validateDrivers($drivers);
    }

    /**
     * @param \Illuminate\Http\UploadedFile $file
     *
     * @return \Combustion\StandardLib\Services\Assets\Models\Asset
     */
    public function createAsset(UploadedFile $file) : Asset
    {
        // what type of asset is it
        $driver = $this->getDriver($file);
        // call create on gateway for whatever
        $document = $driver->create($file);
        // get fresh asset
        $asset = $this->newAsset();
        // attach document to asset
        $document->asset()->save($asset);
        return $asset;
    }


    /**
     * @param \Combustion\StandardLib\Services\Assets\Contracts\HasAssetsInterface $model
     * @param \Illuminate\Http\UploadedFile                                        $file
     *
     * @return HasAssetsInterface
     */
    public function attachPrimaryAssetTo(HasAssetsInterface $model, UploadedFile $file) : HasAssetsInterface
    {
        $asset = $this->createAsset($file);
        $model->attachAsset($asset,true);
        return $model;
    }

    /**
     * @param array $attributes
     *
     * @return \Combustion\StandardLib\Services\Assets\Models\Asset
     */
    private function newAsset(array $attributes = []) : Asset
    {
        return Asset::create($attributes);
    }

    /**
     * @param \Illuminate\Http\UploadedFile $file
     *
     * @return \Combustion\StandardLib\Services\Assets\Contracts\DocumentGatewayInterface
     * @throws \Combustion\StandardLib\Services\Assets\Exceptions\AssetDriverNotFound
     */
    private function getDriver(UploadedFile $file) : DocumentGatewayInterface
    {
        $mimeType = $file->getMimeType();
        foreach ($this->drivers as $driver)
        {
            if(in_array($mimeType,$driver->getConfig()['mimes']))return $driver;
        }
        throw new AssetDriverNotFound("Driver for mime type $mimeType was not found.");
    }

    /**
     * @param array $config
     *
     * @return array
     */
    public function validatesConfig(array $config) : array
    {
        // no need to validate for now
        return $config;
    }


    /**
     * @param array $drivers
     *
     * @return array
     * @throws \Combustion\StandardLib\Services\Assets\Exceptions\ValidationFailed
     */
    public function validateDrivers(array $drivers) : array
    {
        foreach ($drivers as $driverName => $driver)
        {
            if(!$driver instanceof DocumentGatewayInterface)
            {
                throw new ValidationFailed("Driver $driverName needs to implement the DocumentGatewayInterface");
            }
        }
        return $drivers;
    }
}