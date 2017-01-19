<?php
/**
 * Created by PhpStorm.
 * User: LaravelDude
 * Date: 1/18/17
 * Time: 10:21 AM
 */

namespace Combustion\StandardLib\Services\Assets;


use Combustion\StandardLib\Services\Assets\Exceptions\FileCouldNotBeMovedToCloud;
use Combustion\StandardLib\Services\Assets\Models\File;
use Combustion\StandardLib\Validation\ValidationGateway;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Class FileGateway
 *
 * @package Combustion\StandardLib\Services\Assets
 * @author Luis A. Perez <lperez@combustiongroup.com>
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
     * @return \Combustion\StandardLib\Services\Assets\Models\File
     */
    public function createFile(UploadedFile $file):File
    {
        // extract information needed from file
        $file_information = [
            'mime' => $file->getMimeType(),
            'size' => $file->getSize(),
            'original_name' => $file->getClientOriginalName(),
            'url' => $this->buildUrl($this->buildCloudPath($file->getClientOriginalName(),$file->getExtension())),
            'extension' => $file->getExtension(),
        ];
        $this->moveToS3($file);
        $file=new File();
        $file->fill($file_information)->save();
        return $file;
    }

    /**
     * @param \Illuminate\Http\UploadedFile $file
     *
     * @return bool
     * @throws \Combustion\StandardLib\Services\Assets\Exceptions\FileCouldNotBeMovedToCloud
     */
    protected function moveToS3(UploadedFile $file):bool
    {
        $disk=Storage::disk('s3');
        try{
            $disk->put($this->buildCloudPath($file->getClientOriginalName(),$file->getExtension()),file_get_contents($file));
        }catch (\Exception $exception)
        {
            throw new FileCouldNotBeMovedToCloud($exception->getMessage(),$exception->getCode(),$exception);
        }
        if(isset($this->config['keep_local_copy']) && !$this->config['keep_local_copy'])
        {
            exec('rm '.$file->getRealPath());
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

    public function getConfig(): array
    {
        return $this->config;
    }
}