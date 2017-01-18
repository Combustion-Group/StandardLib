<?php
/**
 * Created by PhpStorm.
 * User: LaravelDude
 * Date: 1/18/17
 * Time: 9:57 AM
 */

namespace Combustion\StandardLib\Assets;


use Combustion\StandardLib\Assets\Contracts\AssetDocumentInterface;
use Combustion\StandardLib\Assets\Contracts\DocumentGatewayInterface;
use Combustion\StandardLib\Assets\Exceptions\AssetDriverNotFound;
use Illuminate\Http\UploadedFile;

class AssetsGateway
{
    protected $config;
    protected $drivers;

    public function __construct(array $config,array $drivers)
    {
        $this->config->$config;
        $this->drivers=$drivers;
    }

    public function createAssets(UploadedFile $file)
    {
        // what type of asset is it
        $driver=$this->getDriver($file);
        // call create on gateway for whatever
        $document=$driver->create($file);
        // type of file it is
    }

    public function getDriver(UploadedFile $file):DocumentGatewayInterface
    {
        $mimeType=$file->getClientMimeType();
        foreach ($this->drivers as $driver)
        {
            if(in_array($mimeType,$driver->config['mime']))return $driver;
        }
        throw new AssetDriverNotFound("Driver for mime type $mimeType was not found.");
    }
}