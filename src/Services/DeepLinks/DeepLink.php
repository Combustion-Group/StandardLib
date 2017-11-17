<?php

namespace Combustion\StandardLib\Services\DeepLinks;

use Combustion\StandardLib\Models\Model;

/**
 * Class DeepLink
 *
 * @package Combustion\StandardLib\Services\DeepLinks
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class DeepLink extends Model
{
    /**
     * @var string
     */
    public $table = 'deep_link_tracker';

    const   ID = 'id',
        ACTION = 'action',
        URL = 'url';

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int)$this->getAttribute(self::ID);
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return (string)$this->getAttribute(self::ACTION);
    }

    /**
     * @param string $code
     * @return DeepLink
     */
    public function setAction(string $code): DeepLink
    {
        $this->setAttribute(self::ACTION, $code);
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return (string)$this->getAttribute(self::URL);
    }

    /**
     * @param string $url
     * @return DeepLink
     */
    public function setUrl(string $url): DeepLink
    {
        $this->setAttribute(self::URL, $url);
        return $this;
    }
}
