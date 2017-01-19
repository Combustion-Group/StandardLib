<?php
namespace Combustion\StandardLib;

use Combustion\StandardLib\Exceptions\InvalidThumbnailFileFormat;
use Combustion\StandardLib\Exceptions\InvalidVideoFileFormat;
use Combustion\StandardLib\Exceptions\LocalDirectoryNotFoundException;
use Combustion\StandardLib\Exceptions\MissingVideoException;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;

/**
 * Class FFMPEGhelper
 * @package Combustion\StandardLib
 */
class FFMPEGhelper
{
    /**
     * @var string
     */
    private $videosPath;
    /**
     * @var null|string
     */
    private $storagePath;
    /**
     * @var null|string
     */
    private $thumbnailsPath;
    /**
     * @var
     */
    private $filesArray;
    /**
     * @var FFMpeg
     */
    private $ffmpeg;
    /**
     * @var
     */
    private $video;
    /**
     * @var
     */
    private $currentVideoPath;


    /**
     * FFMPEGhelper constructor.
     * @param null $videosPath
     * @param null $thumbnailsPath
     * @param null $storagePath
     */
    public function __construct($videosPath = null, $thumbnailsPath = null, $storagePath = null)
    {
        // build files array
        $this->buildFilesArray();
        // get binaries for ffmpeg and ffmprobe
        $this->ffmpeg = FFMpeg::create(array(
            'ffmpeg.binaries'  => exec('which ffmpeg'),
            'ffprobe.binaries' => exec('which ffprobe')
        ));
        // set storage to storage path give or make one in
        $this->storagePath = $storagePath ? $storagePath : storage_path();
        $this->videosPath = $storagePath ? $storagePath : $this->storagePath.'/app/tmp/videos/';
        $this->thumbnailsPath = $storagePath ? $storagePath : $this->storagePath.'/app/tmp/thumbnails/';
        $this->checkForPaths([$this->storagePath,$this->videosPath,$this->thumbnailsPath]);
    }

    /**
     * @param array $driArray
     * @throws LocalDirectoryNotFoundException
     */
    private function checkForPaths(array $driArray)
    {
        // for each directory
        foreach ($driArray as $dir)
        {
            // check if the file exists (It checks for directory)
            if(!file_exists($dir))
            {
                // if not found throw exception
                throw new LocalDirectoryNotFoundException($dir.' was not found, create directory and run again.');
            }
        }
    }

    /**
     * @param $path
     * @return $this
     */
    public function video($path)
    {
        // open video with ffmpeg binary
        $this->video = $this->ffmpeg->open($path);
        // set current video to
        $this->setCurrentVideo($path);
        // return class (Allows for chain)
        return $this;
    }

    /**
     * @param $fileName
     * @param null $finalPath
     * @param null $format
     * @param null $videoFormat
     * @return $this
     */
    public function convert($fileName , $finalPath = null, $format = null , $videoFormat = null)
    {
        $this->checkForCurrentVideo();
        // check if video format is available for converting
        $format = $this->checkVideoFormat($format);
        // get current video
        $videoCurrentPath = $this->currentVideoPath;
        // make final path
        $finalPath = $finalPath ? $finalPath.$fileName.'.'.$format : $this->videosPath.$fileName.'.'.$format;
        // user ffmpeg CLI to convert video to mp4
        exec("ffmpeg -i $videoCurrentPath -vcodec copy -acodec copy $finalPath");
        // push data to files array
        $this->pushConvertedVideo($finalPath);
        // return class (Allows for chain)
        return $this;
    }

    /**
     * @param $second
     * @param $fileName
     * @param null $format
     * @param null $size
     * @return $this
     */
    public function thumbnail($second , $fileName , $format = null , $size = null)
    {
        $this->checkForCurrentVideo();
        // check image format
        $format = $this->checkImageFormat($format);
        // make path of the thumbnail
        $finalPath = $this->thumbnailsPath.$fileName.'.'.$format;
        // make video thumbnail
        $this->video->frame(TimeCode::fromSeconds($second))->save($finalPath);
        // push to files array
        $this->pushThumbnail($finalPath);
        // return class (Allows for chain)
        return $this;
    }

    /**
     * @param $format
     * @return string
     * @throws InvalidThumbnailFileFormat
     */
    private function checkImageFormat($format)
    {
        // throw exception, it will never do it right now but once i work on them
        // ill be able to give this option
//        if(!$format) throw new InvalidThumbnailFileFormat('Has to be jpeg XD. Sorry, just for now tho.');
        return 'jpeg';
    }

    /**
     * @param $format
     * @return string
     * @throws InvalidVideoFileFormat
     */
    private function checkVideoFormat($format)
    {
        // throw exception, it will never do it right now but once i work on them
        // ill be able to give this option
//        if(!$format) throw new InvalidVideoFileFormat('Has to be mp4 XD. Sorry, just for now tho.');
        return 'mp4';
    }

    /**
     * @return mixed
     */
    public function files()
    {
        return $this->filesArray;
    }

    /**
     * @return $this
     */
    public function cleanUp($original = false)
    {
        // clean all converted videos
        foreach ($this->filesArray['converted'] as $file)
        {
            $this->deleteFile($file);
        }
        // clean reference array
        $this->cleanConverted();
        // clean all converted thumbnails
        foreach ($this->filesArray['thumbnails'] as $file)
        {
            $this->deleteFile($file);
        }
        // clean reference array
        $this->cleanThumbnails();
        // if original is set to be deleted
        if($original)
        {
            // clean all original videos
            foreach ($this->filesArray['videos'] as $file)
            {
                $this->deleteFile($file);
            }
            // clean reference array
            $this->cleanVideos();
            // clean up current video
            $this->cleanCurrent();
        }
        $this->buildFilesArray();
        return $this;
    }

    /**
     * @param $file
     */
    private function deleteFile($file)
    {
        exec('rm -rf '.$file);
    }

    /**
     * @throws MissingVideoException
     */
    private function checkForCurrentVideo()
    {
        if(!$this->currentVideoPath)
        {
            throw new MissingVideoException('Use the video function to add a video');
        }
    }

    /**
     * Builds Files array
     */
    private function buildFilesArray()
    {
        $this->cleanCurrent();
        $this->cleanVideos();
        $this->cleanConverted();
        $this->cleanThumbnails();
    }

    /**
     * Clean current reference to the files
     */
    public function cleanCurrent()
    {
        $this->currentVideoPath = null;
        $this->filesArray['current'] = null;
    }

    /**
     * Clean current reference to the files
     */
    public function cleanVideos()
    {
        $this->filesArray['videos'] = [];
    }

    /**
     * Clean current reference to the files
     */
    public function cleanConverted()
    {
        $this->filesArray['converted'] = [];
    }

    /**
     * Clean current reference to the files
     */
    public function cleanThumbnails()
    {
        $this->filesArray['thumbnails'] = [];
    }

    /**
     * @param $path
     */
    private function setCurrentVideo($path)
    {
        $this->currentVideoPath = $path;
        $this->filesArray['current'] = $path;
        $this->pushVideo($path);
    }

    /**
     * @param $path
     */
    private function pushVideo($path)
    {
        array_push($this->filesArray['videos'], $path);
    }

    /**
     * @param $path
     */
    private function pushThumbnail($path)
    {
        array_push($this->filesArray['thumbnails'], $path);
    }

    /**
     * @param $path
     */
    private function pushConvertedVideo($path)
    {
        array_push($this->filesArray['converted'], $path);
    }
}