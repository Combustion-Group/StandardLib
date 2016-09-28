<?php

namespace Combustion\StandardLib\Models;

/**
 * Class RemoteFile
 * @package App\Lib\Std\Models
 * @author Carlos Granados <cgranadso@combustiongroup.com>
 */
class RemoteFile implements \JsonSerializable
{
    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $fileName;

    /**
     * @var string
     */
    private $extension;

    /**
     * @var string
     */
    private $fullUrl;

    /**
     * @var string
     */
    private $disk;

    /**
     * @var string
     */
    private $baseUrl;

    public function __construct()
    {
    }

    public function __toString() : string
    {
        return $this->getFullUrl();
    }

    public function jsonSerialize() : string
    {
        return (string)$this;
    }

    public function getSlug() : string
    {
        return (string)$this->slug;
    }

    public function setFileName() : RemoteFile
    {
        return (string)$this->fileName;
    }

    public function getExtension() : string
    {
        return (string)$this->extension;
    }

    public function setExtension(string $ext) : RemoteFile
    {
        $this->extension = $ext;
        return $this;
    }

    public function setFullUrl(string $url) : RemoteFile
    {
        $this->fullUrl = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getFullUrl() : string
    {
        return (string)$this->fullUrl;
    }

    public function setDisk(string $disk) : RemoteFile
    {
        $this->disk = $disk;
        return $this;
    }

    public function getDisk() : string
    {
        return (string)$this->disk;
    }

    public function setBaseUrl(string $baseUrl) : RemoteFile
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }
}
