<?php
/**
 * Created by PhpStorm.
 * User: LaravelDude
 * Date: 1/17/17
 * Time: 7:36 PM
 */

namespace Combustion\StandardLib\Uploader\src\Classes;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileDeliveryMan
{
    protected $file;
    protected $disk;
    protected $path;
    protected $name;
    protected $extension;

    public function __construct(string $path,string $name,string $extension,string $driver=null)
    {
        $driver=is_null($driver)?'s3':$driver;
        // get storage
        $this->disk = Storage::disk($driver);
        $this->disk = $path;
        $this->name = $name;
        $this->extension = $extension;
        // get files from request
        $this->file = new UploadedFile($path.'/'.$name.'.'.$extension,$name);
    }

    public function moveToCloud()
    {
        // upload to s3 bucket
        $this->disk->put($this->path.'/'.$this->name.'.'.$this->extension, file_get_contents($this->file));
    }
}