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
    const DOCUMENT_TYPE='image';

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
        $imageBag=$this->makeImageIntoCorrectSizes($image);
        foreach ($imageBag as $size=>$imageData)
        {

        }
    }


    public function getOrFail(int $imageId): AssetDocumentInterface
    {
        // TODO: Implement getOrFail() method.
    }

    public function moveFileToLocalFolder(string $localDestinationFolder,UploadedFile $file)
    {
        $localDisk=Storage::disk('local');
        $localDisk->put($localDestinationFolder.$file->getClientOriginalName().$file->getExtension(),file_get_contents($file));
    }

    public function makeImageIntoCorrectSizes(UploadedFile $file):array
    {
        // get name
        $name=$file->getClientOriginalName();
        $path=$file->getPath();
        $extension=$file->getExtension();
        // create image bag and add original data
        $imageBag=[
            'original'=>['folder'=>$path,'name'=>$name,'extension'=>$extension]
        ];
        $image=Image::make($file->path());
        foreach ($this->config['sizes'] as $size=>$imageSize)
        {
            // get name
            $name=md5($size.'-'.$file->getClientOriginalName());
            $path=$file->path();
            $extension=$file->getExtension();
            // append size to the name
            $imagePath=$path.'/'.$name.'.'.$extension;
            // make data for array
            $imageData=[$size=>['folder'=>$path,'name'=>$name,'extension'=>$extension]];
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