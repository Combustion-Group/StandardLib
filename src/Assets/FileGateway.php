<?php
/**
 * Created by PhpStorm.
 * User: LaravelDude
 * Date: 1/18/17
 * Time: 10:21 AM
 */

namespace Combustion\StandardLib\Assets;


use Combustion\StandardLib\Assets\Exceptions\FileCouldNotBeMovedToCloud;
use Combustion\StandardLib\Assets\Models\File;
use Combustion\StandardLib\Validation\ValidationGateway;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Class FileGateway
 *
 * @package Combustion\StandardLib\Assets
 */
class FileGateway
{
    /**
     * @var array
     */
    protected $config;

    /**
     * FileGateway constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config=$config;
    }

    /**
     * @param \Illuminate\Http\UploadedFile $file
     *
     * @return \Combustion\StandardLib\Assets\Models\File
     */
    public function createFile(UploadedFile $file):File
    {
        $this->moveToS3($file);
        // extract information needed from file
        $file_information = [
            'mime' => $file->getMimeType(),
            'size' => $file->getSize(),
            'original_name' => $file -> getClientOriginalName(),
            'url' => $this->buildUrl($file->getExtension()),
            'extension' => $file -> getExtension(),
        ];
        $file=(new File())->fill($file_information)->save();
        return $file;
    }

    /**
     * @param \Illuminate\Http\UploadedFile $file
     *
     * @return bool
     * @throws \Combustion\StandardLib\Assets\Exceptions\FileCouldNotBeMovedToCloud
     */
    protected function moveToS3(UploadedFile $file):bool
    {
        $disk=Storage::disk('s3');
        try{
            $disk->put($this->buildCloudPath($file->getClientOriginalName(),$file->getExtension()));
        }catch (\Exception $exception)
        {
            throw new FileCouldNotBeMovedToCloud($exception->getMessage(),$exception->getCode(),$exception);
        }
        return true;
    }

    /**
     * @param string $fileName
     * @param string $fileExtension
     *
     * @return string
     */
    public function buildCloudPath(string $fileName, string $fileExtension):string
    {
        $url=$this->getStorageFolder();
        $url.='/'.$fileName;
        $url.='.'.$fileExtension;
        return $url;
    }

    /**
     * @param string $couldPath
     *
     * @return string
     */
    public function buildUrl(string $couldPath)
    {
        return $this->getBaseUrl().'/'.$couldPath;
    }

    /**
     * @return string
     */
    public function getBaseUrl():string
    {
        if(isset($this->config['cloud_base_url']))return $this->config['cloud_base_url'];
        if(!is_null(env('CLOUD_STORAGE_BASE_URL',null)))return env('CLOUD_STORAGE_BASE_URL');
        return 'https://checkConfigForFileGateway.now';
    }

    /**
     * @return string
     */
    public function getStorageFolder():string
    {
        if(isset($this->config['cloud_folder']))return $this->config['cloud_folder'];
        if(!is_null(env('CLOUD_FOLDER',null)))return env('CLOUD_FOLDER');
        return 'documents';
    }
}