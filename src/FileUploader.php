<?php

namespace Combustion\StandardLib;


use Illuminate\Http\UploadedFile;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;

class FileUploader
{
    // the file to be uploaded
    public $file;

    // the storage disk
    public $disk;

    // the path that the file will be stored in
    // e.g. /media/videos
    public $upload_path = '';


    public function __construct(File $file, $disk_type = 's3') {
        // A file must be passed
        if(empty($file)) {
            throw new \Exception('Must provide a valid file');
        }
        // Set FileUploader properties
        $this->file = $file;
        $this->disk = Storage::disk($disk_type);
    }

    // upload the file
    // set the disk upload path and permission
    function uploadFile($upload_path = '') {
        // if no upload path was given, use the class's upload path value
        // otherwise set the class's upload path value
        if(empty($upload_path)) {
            $upload_path = $this->upload_path;
        } else {
            $this->upload_path = $upload_path;
        }

        // upload the file to the disk
        $this->disk->put($upload_path, file_get_contents($this->file));
    }

    // get the upload path that was provided by the user
    // e.g. documents/videos
    // testing a theory of mine

    function getUploadPath(){
        return $this->upload_path;
    }

    // get the default disk path for file upload
    // currently the default upload path is the temp location of the file
    // e.g. tmp/phpiO5v
    function getDefaultUploadPath() {
        return $this->file->getRealPath();
    }

    // clear out the local disk after the file has been uploaded to the target disk
    function clearLocalDisk() {
        $local_file_url = $this->file->getRealPath();
        Storage::disk('local')->delete($local_file_url);
    }

    // get the Storage disk that the file is going to be stored on
    function getDisk() {
        return $this->disk;
    }

    // get the full path to the file on the Disk
    // e.g. https://s3.amazonaws.com/kassir/documents/videos/health_tips.mp4
    function getFileUrlOnDisk() {
        return $this->disk->url("$this->upload_path");
    }




}
