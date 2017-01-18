<?php
namespace Combustion\StandardLib\Uploader\src;


use Combustion\StandardLib\Uploader\src\Classes\FileDeliveryMan;
use Combustion\StandardLib\Uploader\src\Models\File;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;

class ImageUploader
{

    protected $config;

    public function __construct(array $config)
    {
//        $this->config=$config;
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
            ],
            'tmp_folder'=>storage_path('app/documents'),
            's3_bucket'=>env('S3_BUCKET'),
            's3_folder'=>env('ASSET_FOLDER'),
        ];
    }

    public function uploadImage(UploadedFile $image):\Combustion\StandardLib\Uploader\src\Models\Image
    {
        $imageBag=$this->makeImageIntoCorrectSizes($image->getPath(),$image->getClientOriginalName(),$image->getExtension(),$this->config['tmp_folder']);
        $fileBag=array();
        foreach ($imageBag as $size=>$data)
        {
            $deliveryGuy=new FileDeliveryMan($data['folder'],$data['name'],$data['extension']);
            $deliveryGuy->moveToCloud();
            $file = new UploadedFile($this->buildPath($data['folder'],$data['name'],$data['extension']),$data['name']);
            // get destination inside of the bucket
            $destination = $this->config['s3_bucket'].$this->config['s3_folder'];
            // extract information needed from file
            $file_information = [
                'mime' => $file->getMimeType(),
                'size' => $file->getSize(),
                'original_name' => $file -> getClientOriginalName(),
                'url' => $destination.$data['name'].'.'.$data['extension'],
                'extension' => $file -> getExtension(),
            ];
            // push file into uploaded array
            $file = File::create($file_information);
            array_push($fileBag,[$size=>$file->toArray()]);
        }
        $image=new \Combustion\StandardLib\Uploader\src\Models\Image([
           'name'=>$image->getClientOriginalName(),
           'slug'=>time().$image->getClientOriginalName(),
           'small_id'=>$fileBag['small']['id'],
           'image_id'=>$fileBag['original']['id'],
           'medium_id'=>$fileBag['medium']['id']
        ]);
        $image->save();
        return $image;
    }

    public function makeImageIntoCorrectSizes(string $folder,string $fileName,string $fileExtension,string $destination):array
    {
        // create image bag and add original data
        $imageBag=[
            'original'=>[
                'folder'=>$folder,
                'name'=>$fileName,
                'extension'=>$fileExtension,
            ]
        ];
        $image=Image::make($this->buildPath($folder,$fileName,$fileExtension));
        foreach ($this->config['sizes'] as $size=>$imageSize)
        {
            // get name
            $name=md5($size.'-'.$fileName);
            // append size to the name
            $imagePath=$this->buildPath($destination,$name,$fileExtension);
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

    public function buildPath(string $folder,string $fileName,string $fileExtension)
    {
        return $folder.'/'.$fileName.'.'.$fileExtension;
    }
}