<?php

namespace Combustion\StandardLib\Support\Responses;

use Combustion\StandardLib\Hydrators\HydratesWithSetters;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;

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
     * @param Paginator $paginationObject
     * @param bool $strict Will throw exception if a field in $data has no matching setter
     */
    public function __construct(Paginator $paginationObject = null, bool $strict = false)
    {
        if (!is_null($paginationObject)) {
            $this->fill($this->extractPaginationData($paginationObject), $strict);
        }
    }

    /**
     * @param Paginator $paginationObject
     * @return array
     */
    public function extractPaginationData(Paginator $paginationObject):array
    {
        $total = 0;

        $this->setData($paginationObject->items());

        // the Paginator contract odes not enforce total()
        // some pagination classes will comeback without it
        // this will prevent an exception but we lose the
        // ability to know how many object we have in total
        if(method_exists($paginationObject,'total'))
        {
            $total = $paginationObject->total();
        }

        // make option array
        $options = [
            "total"         => $total,
            "per_page"      => $paginationObject->perPage(),
            "current_page"  => $paginationObject->currentPage(),
            "last_page"     => ceil($total/$paginationObject->perPage()),
            "next_page_url" => $paginationObject->nextPageUrl(),
            "prev_page_url" => $paginationObject->previousPageUrl()
        ];

        return $options;
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