<?php
/**
 * Created by PhpStorm.
 * User: LaravelDude
 * Date: 1/18/17
 * Time: 10:22 AM
 */

namespace Combustion\StandardLib\Assets;


use Combustion\StandardLib\Assets\Contracts\AssetDocumentInterface;
use Combustion\StandardLib\Assets\Contracts\DocumentGatewayInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;

class ImageGateway implements DocumentGatewayInterface
{
    protected $config;
    protected $fileGateway;

    public function __construct(array $config,FileGateway $fileGateway)
    {
//        $this->config=$config;
        $this->fileGateway=$fileGateway;
        $this->config=[
            'sizes'=>[
                "large"=>[
                    "x"=>700,
                    "y"=>null
                ],
                "medium"=>[
                    "x"=>344,
                    "y"=>null
                ],
                "small"=>[
                    "x"=>100,
                    "y"=>null
                ],
            ]
        ];
    }
    public function create(UploadedFile $image,array $options =[]): AssetDocumentInterface
    {
        // TODO: Implement create() method.
    }


    public function getOrFail(int $imageId): AssetDocumentInterface
    {
        // TODO: Implement getOrFail() method.
    }

    public function moveFileToLocalFolder(string $localDestinationFolder,UploadedFile $file)
    {
        $localDisk=Storage::disk('local');
    }

    public function makeImageIntoCorrectSizes(UploadedFile $file):array
    {
        // create image bag and add original data
        $imageBag=[
            'original'=>$file
        ];
        $image=Image::make($file->path());
        foreach ($this->config['sizes'] as $size=>$imageSize)
        {
            // get name
            $name=md5($size.'-'.$file->getClientOriginalName());
            // append size to the name
            $imagePath=$this->buildPath($this-,$name,$fileExtension);
            // make data for array
            $imageData=[$size=>['folder'=>$fileExtension,'name'=>$name,'extension'=>$fileExtension]];
            // push data in
            array_push($imageBag,$imageData);
            // manipulate image
            $image->fit($imageSize['x'],$imageSize['y'], function (Constraint $constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
                // save once done
            })->save($imagePath);
        }
        return $imageBag;
    }

}