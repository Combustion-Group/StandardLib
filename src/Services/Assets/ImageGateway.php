<?php
namespace Combustion\StandardLib\Services\Assets;

use Combustion\StandardLib\Services\Assets\Contracts\AssetDocumentInterface;
use Combustion\StandardLib\Services\Assets\Contracts\DocumentGatewayInterface;
use Combustion\StandardLib\Services\Assets\Exceptions\ValidationFailed;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;
use Combustion\StandardLib\Services\Assets\Models\Image as ImageModel;

/**
 * Class ImageGateway
 *
 * @package Combustion\StandardLib\Services\Assets
 * @author Luis A. Perez <lperez@combustiongroup.com>
 */
class ImageGateway implements DocumentGatewayInterface
{
    /**
     * @var array
     */
    protected $config;
    /**
     * @var \Combustion\StandardLib\Services\Assets\FileGateway
     */
    protected $fileGateway;
    /**
     * @var \Illuminate\Filesystem\FilesystemAdapter
     */
    protected $localDriver;
    /**
     *
     */
    const DOCUMENT_TYPE = 'image';


    /**
     * ImageGateway constructor.
     *
     * @param array                                               $config
     * @param \Combustion\StandardLib\Services\Assets\FileGateway $fileGateway
     * @param \Illuminate\Filesystem\FilesystemAdapter            $localDriver
     */
    public function __construct(array $config, FileGateway $fileGateway, FilesystemAdapter $localDriver)
    {
        $this->fileGateway  = $fileGateway;
        $this->localDriver  = $localDriver;
        $this->config       = $this->validatesConfig($config);
    }

    /**
     * @param \Illuminate\Http\UploadedFile $image
     * @param array                         $options
     *
     * @return \Combustion\StandardLib\Services\Assets\Contracts\AssetDocumentInterface
     */
    public function create(UploadedFile $image, array $options = []) : AssetDocumentInterface
    {
        $imageBag = $this->makeImageIntoCorrectSizes($this->moveToLocalDisk($image));
        foreach ($imageBag as $size => $imageData)
        {
            $file = new UploadedFile($imageData['folder'].'/'.$imageData['name'].'.'.$imageData['extension'],$imageData['name']);
            $imageBag[$size]['model'] = $this->fileGateway->createFile($file);
        }
        $imageModelData = [
            'title'      => $imageBag['original']['name'],
            'slug'       => time().$imageBag['original']['name'],
            'image_id'   => $imageBag['original']['model']->id,
            'small_id'   => $imageBag['small']['model']->id,
            'medium_id'  => $imageBag['medium']['model']->id,
        ];
        return ImageModel::create($imageModelData);
    }


    /**
     * @param \Illuminate\Http\UploadedFile $file
     *
     * @return \Illuminate\Http\UploadedFile
     */
    public function moveToLocalDisk(UploadedFile $file) : UploadedFile
    {
        $disk = $this->localDriver;
        $fileDestination = $this->fileGateway->getConfig()['local_document_folder_name'].'/'.$file->getClientOriginalName().'.'.$file->extension();
        $fileLocation = $this->fileGateway->getConfig()['local_document_folder'].'/'.$file->getClientOriginalName().'.'.$file->extension();
        $disk->put($fileDestination, file_get_contents($file));
        return new UploadedFile($fileLocation,$file->getClientOriginalName());
    }


    /**
     * @param int $imageId
     *
     * @return \Combustion\StandardLib\Services\Assets\Contracts\AssetDocumentInterface
     */
    public function getOrFail(int $imageId) :  AssetDocumentInterface
    {
        // TODO: Implement getOrFail() method.
    }

    /**
     * @param \Illuminate\Http\UploadedFile $file
     *
     * @return array
     */
    public function makeImageIntoCorrectSizes(UploadedFile $file) : array
    {
        // get name
        $name = $file->getClientOriginalName();
        $path = $file->getPath();
        $extension = $file->getExtension();
        // create image bag and add original data
        $imageBag = [
            'original' => ['folder' => $path,'name' => $name,'extension' => $extension]
        ];
        $image = Image::make($path.'/'.$name.'.'.$extension);
        foreach ($this->config['sizes'] as $size => $imageSize)
        {
            // get name
            $sizeName = md5(time().$size.'-'.$file->getClientOriginalName());
            // append size to the name
            $imagePath = $path.'/'.$sizeName.'.'.$extension;
            // make data for array
            $imageData = [$size => ['folder' => $path,'name' => $sizeName,'extension' => $extension]];
            // push data in
            $imageBag = array_merge($imageBag,$imageData);
            // manipulate image
            $image->fit($imageSize['x'],$imageSize['y'], function (Constraint $constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
                // save once done
            })->save($imagePath);
        }
        return $imageBag;
    }

    /**
     * @return array
     */
    public function getConfig() :  array
    {
        return $this->config;
    }

    /**
     * @param array $config
     *
     * @return array
     * @throws \Combustion\StandardLib\Services\Assets\Exceptions\ValidationFailed
     */
    public function validatesConfig(array $config) : array
    {
        $validationRules = [
            "mimes"     => "required|array",
            "mimes.*"   => "required|string",
            "sizes"     => "required|array",
            "sizes.*"   => "required|array",
            "sizes.*.x" => "required|nullable|int",
            "sizes.*.y" => "required|nullable|int",
        ];
        $validation = Validator::make($config,$validationRules);
        if($validation->fails())
        {
            throw new ValidationFailed("Validation for ImageGateway config array failed.");
        }
        return $config;
    }
}