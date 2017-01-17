<?php

namespace Combustion\StandardLib\Support\Responses;

use Combustion\StandardLib\Hydrators\HydratesWithSetters;

/**
 * Class PaginatedResponse
 *
 * @package Combustion\StandardLib\Support\Responses
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class PaginatedResponse implements CustomResponse
{
    use HydratesWithSetters;

    /**
     * @var array
     */
    private $data;

    /**
     * @var int
     */
    private $total;

    /**
     * @var int
     */
    private $perPage;

    /**
     * @var int
     */
    private $currentPage;

    /**
     * @var int
     */
    private $lastPage;

    /**
     * @var string
     */
    private $nextPageUrl;

    /**
     * @var string
     */
    private $prevPageUrl;

    /**
     * PaginatedResponse constructor.
     *
     * @param array $data
     * @param bool $strict Will throw exception if a field in $data has no matching setter
     */
    public function __construct(array $data, bool $strict = false)
    {
        $this->fill($data, $strict);
    }

    /**
     * @param string $url
     * @return PaginatedResponse
     */
    public function setPrevPageUrl($url) : PaginatedResponse
    {
        $this->prevPageUrl = $url;
        return $this;
    }

    /**
     * @param string $url
     * @return PaginatedResponse
     */
    public function setNextPageUrl($url): PaginatedResponse
    {
        $this->nextPageUrl = $url;
        return $this;
    }

    /**
     * @param int $lastPage
     * @return PaginatedResponse
     */
    public function setLastPage($lastPage): PaginatedResponse
    {
        $this->lastPage = $lastPage;
        return $this;
    }

    /**
     * @param int $currentPage
     * @return PaginatedResponse
     */
    public function setCurrentPage($currentPage) : PaginatedResponse
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    /**
     * @param int $perPage
     * @return PaginatedResponse
     */
    public function setPerPage($perPage) : PaginatedResponse
    {
        $this->perPage = $perPage;
        return $this;
    }

    /**
     * @param array $data
     * @return PaginatedResponse
     */
    public function setData(array $data) : PaginatedResponse
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param int $total
     * @return PaginatedResponse
     */
    public function setTotal(int $total) : PaginatedResponse
    {
        $this->total = $total;
        return $this;
    }

    /**
     * @return array
     */
    public function getData() : array
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getTopLevel() : array
    {
        return [
            'pagination'    => [
                'total'         => $this->total,
                'per_page'      => $this->perPage,
                'current_page'  => $this->currentPage,
                'last_page'     => $this->lastPage,
                'next_page_url' => $this->nextPageUrl,
                'prev_page_url' => $this->prevPageUrl
            ]
        ];
    }
}