<?php
/**
 * Created by PhpStorm.
 * User: LaravelDude
 * Date: 1/17/17
 * Time: 8:32 PM
 */

namespace Combustion\StandardLib\Uploader\src\Classes;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class LocalFile
{
    protected $file;
    protected $disk;

    public function __construct(string $localPath,string $driver=null)
    {
        // get storage
        $this->disk = Storage::disk('local');
        // get files from request
        $this->file = new UploadedFile($localPath,'todo');
    }

    public function getName(string $path,string $name,string $extension)
    {
        // upload to s3 bucket
        $this->disk->put($path.$name.'.'.$extension, file_get_contents($this->file));
    }
}