<?php

namespace Combustion\StandardLib\Traits;

/**
 * Trait HasResponse
 * @package Combustion\StandardLib\Traits
 * @author Carlos Granados <cgranados@combustiongroup.com>
 */
trait HasResponse
{
    /**
     * @var array
     */
    protected $response = [];

    /**
     * @var bool
     */
    protected $hasResponse = false;

    /**
     * @return bool
     */
    public function hasResponse(): bool
    {
        return $this->hasResponse;
    }

    /**
     * @return array
     */
    public function getResponse(): array
    {
        return $this->response;
    }

    /**
     * @param $body
     * @param $status
     * @param $message
     * @param $code
     * @return HasResponse
     */
    public function response($body, $status, $message, $code): self
    {
        $this->hasResponse = true;
        $this->response = [
            'body' => $body,
            'status' => $status,
            'message' => $message,
            'code' => $code
        ];

        return $this;
    }

    /**
     * @param string $key
     * @return null|int|string
     */
    private function pull(string $key)
    {
        return isset($this->response[$key]) ? $this->response[$key] : null;
    }

    /**
     * @return int|null|string
     */
    public function getBody()
    {
        return $this->pull('body');
    }

    /**
     * @return int|null|string
     */
    public function getStatus()
    {
        return $this->pull('status');
    }

    /**
     * @return int|null|string
     */
    public function getMessage()
    {
        return $this->pull('message');
    }

    /**
     * @return int|null|string
     */
    public function getCode()
    {
        return $this->pull('code');
    }
}
