<?php

namespace CombustionGroup\Std;

use CombustionGroup\Std\Log;
use Illuminate\Http\UploadedFile;
use CombustionGroup\Std\Models\RemoteFile;
use CombustionGroup\Std\Traits\ValidatesConfig;
use Illuminate\Contracts\Filesystem\Filesystem;
use CombustionGroup\Std\Exceptions\InvalidStorageTypeFlagException;

/**
 * Class UploadManager
 * @package App\Lib\Std
 * @author Carlos Granados <cgranados@combustiongroup.com>
 */
class UploadManager
{
    use ValidatesConfig;

    /**
     * @var array
     */
    private $config = [];

    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    private $storage;

    /**
     * @var Log
     */
    private $logger;

    /**
     * @var array
     */
    protected $requiredConfig = [
        'file-paths',
        'public-url'
    ];

    /**
     * @var string
     */
    protected $logNamespace = 'UploadManager';

    const   MERCHANT_IMAGE      = 'STORAGE_MERCHANT_IMAGE',
            MENU_ITEM_IMAGE     = 'STORAGE_MENU_ITEM_IMAGE',
            USER_PROFILE_IMAGE  = 'STORAGE_USER_PROFILE_IMAGE',
            DRIVERS_LICENSE     = 'DRIVERS_LICENSE';

    /**
     * UploadManager constructor.
     * @param array $config
     * @param Filesystem $storage
     * @param Log $logger
     */
    public function __construct(array $config, Filesystem $storage, Log $logger)
    {
        $this->logger   = $logger;
        $this->config   = $this->validateConfig($config);
        $this->storage  = $storage;
    }

    /**
     * @return array
     */
    protected function getConfig() : array
    {
        return $this->config;
    }

    /**
     * @return array
     */
    protected function getRequiredConfig() : array
    {
        return $this->requiredConfig;
    }

    /**
     * @param string $type
     * @param UploadedFile $file
     * @param string $visibility
     * @return RemoteFile
     * @throws InvalidStorageTypeFlagException
     */
    public function save(string $type, UploadedFile $file, string $visibility = 'public')
    {
        $savePath = $this->getPathFor($type).$this->getNewFileName($file);

        $this->logger->log(Log::INFO, "Will save {$file->getPath()} to {$savePath} on cloud storage");

        $this->storage->put($savePath, file_get_contents($file), $visibility);

        $this->logger->log(Log::INFO, "Saved successfully");

        if ($visibility == 'public') {
            return $this->getUrl($savePath);
        }

        return $savePath;
    }

    /**
     * @param string $cloudPath
     * @return string
     */
    public function getUrl(string $cloudPath) : string
    {
        return $this->config['public-url'] . ltrim($cloudPath, '/');
    }

    /**
     * @param string $fileType (As defined by the config, uploads.php)
     * @return string
     * @throws InvalidStorageTypeFlagException
     */
    public function getPathFor(string $fileType) : string
    {
        if (!isset($this->config['file-paths'][$fileType])) {
            throw new InvalidStorageTypeFlagException("No storage specified for type of type {$fileType}");
        }

        return $this->config['file-paths'][$fileType];
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    private function getNewFileName(UploadedFile $file) : string
    {
        return md5(time() . $file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
    }
}
