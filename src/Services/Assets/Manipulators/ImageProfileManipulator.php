<?php

namespace Combustion\StandardLib\src\Services\Assets\Manipulators;

use Combustion\StandardLib\Services\Assets\Contracts\Manipulator;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;


/**
 * Class ImageProfileManipulator
 *
 * @package Combustion\StandardLib\src\Services\Assets\Manipulators
 * @author  Luis A. Perez <lperez@combustiongroup.com>
 */
class ImageProfileManipulator implements Manipulator
{
    /**
     * @var array
     */
    protected $config;
    /**
     *
     */
    const MANUPULATOR_NAME = 'ImageProfiles';


    /**
     * ImageGateway constructor.
     *
     * @param array                                               $config
     * @param \Combustion\StandardLib\Services\Assets\FileGateway $fileGateway
     * @param \Illuminate\Filesystem\FilesystemAdapter            $localDriver
     */
    public function __construct(array $config)
    {
        $this->config  = $this->validatesConfig($config);
    }


    public function manipulate(UploadedFile $file) : array
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
            })->orientate()->save($imagePath);
        }
        return $imageBag;
    }
}