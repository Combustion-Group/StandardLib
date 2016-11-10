<?php
/**
 * Created by PhpStorm.
 * User: LaravelDude
 * Date: 11/10/16
 * Time: 11:13 AM
 */

namespace Combustion\StandardLib;


use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Illuminate\Support\Facades\Storage;

class FFMPEGhelper
{
    /**
     * @var string
     */
    public $disk;

    /**
     * FFMPEGhelper constructor.
     */
    public function __construct()
    {

    }

    public function video($path)
    {

    }

    /**
     * @param $video
     * @param $second
     * @param string $disk
     * @return string
     */
    public function getFrame($video, $second, $disk = 's3')
    {
        // get video from disk
        $this -> disk = $disk;
        $videoLocation = str_replace(env('S3_BUCKET'),'',$video);
        $video = Storage::disk($this -> disk) -> get($videoLocation);
        // if video is in the cloud
        if($this -> disk == 's3')
        {
            // pull video to the local disk
            Storage::disk('local') -> put($videoLocation,$video);
        }
        return $this -> useFFMPEGToExtractImageFile($videoLocation,$second);
    }

    /**
     * @param $path
     * @param $second
     * @return string
     */
    public function useFFMPEGToExtractImageFile($path, $second)
    {
        // open video with ffmpeg
        $ffmpeg = FFMpeg::create(array(
            'ffmpeg.binaries'  => exec('which ffmpeg'),
            'ffprobe.binaries' => exec('which ffprobe')
        ));
        // folder in the app
        $basePath = storage_path().'/app/';
        $tempFilePath = '/var/www/kaplan-api/storage/app/';
        $video = $ffmpeg -> open($basePath.$path);
        // get frame image
        $tempStorage = 'temp/thumbnails/frame.jpg';
        $video -> frame(TimeCode::fromSeconds($second)) -> save($tempFilePath.$tempStorage);
        // delete video after getting frame
        Storage::disk('local')->delete($path);
        // return location of frame image
        return $tempStorage;
    }

    /**
     * @param $path
     * @param $second
     * @return string
     */
    public function convertVideo($path)
    {
        $videoName = $this -> getVideoName($path);
        $ffmpeg = FFMpeg::create([
            'ffmpeg.binaries'  => exec('which ffmpeg'),
            'ffprobe.binaries' => exec('which ffprobe')
        ]);
        $video = $ffmpeg -> open($path);
        // get new location path
        $newVideoName = $videoName['name'].'.mp4';
        $finalPath = str_replace($videoName['file_name'],$newVideoName,$path);
        // never got ffmpeg to work with the library
        //$video ->save(new X264(),$finalPath);
        //exec("ffmpeg -i $path -vcodec h264 -acodec aac -strict -2 $finalPath");
        exec("ffmpeg -i $path -vcodec copy -acodec copy $finalPath");
        // get frame image
        $tempFilePath = '/var/www/kaplan-api/storage/app/';
        $tempStorage = 'temp/thumbnails/frame.jpg';
        $video -> frame(TimeCode::fromSeconds(2)) -> save($tempFilePath.$tempStorage);
        // delete both videos
        $diskLocal = Storage::disk('local');
        $diskLocal->delete([$path]);
        // return location of frame image
        return ['location' => $finalPath,'name' => $newVideoName,'thumbnail'=>$tempStorage];
    }

    public function getVideoName($path)
    {
        // explode all the path
        $exploded = explode('/',$path);
        // return the string before the .
        return ['name'=>explode('.',end($exploded))[0],'file_name'=>end($exploded)];
    }
}