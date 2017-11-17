<?php

namespace Combustion\StandardLib\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Combustion\StandardLib\Exceptions\PaginationException;
use Combustion\StandardLib\Exceptions\PageOutOfRangeException;
use Illuminate\Pagination\LengthAwarePaginator as LengthAwarePaginatorImpl;

/**
 * Class Paginates
 *
 * @package Combustion\StandardLib\Traits
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
trait Paginates
{
    /**
     * @param array|\Traversable $data
     * @param int $total
     * @param int $perPage
     * @param int $page
     * @return LengthAwarePaginator
     * @throws PageOutOfRangeException
     */
    protected function paginate($data, int $total, int $perPage, int $page): LengthAwarePaginator
    {
        $totalPages = ceil($total / $perPage);

        if ($total === 0) {
            return new LengthAwarePaginatorImpl([], 0, $perPage);
        }

        if ($page > $totalPages) {
            throw new PageOutOfRangeException("Cannot go to page {$page}, because there's only {$totalPages}");
        }

        $data = $this->unpackData($data);

        return new LengthAwarePaginatorImpl($data, $total, $perPage);
    }

    /**
     * @param int $page
     * @param int $perPage
     * @return int[]
     * @throws PaginationException
     */
    public function buildLimits(int $page, int $perPage): array
    {
        if ($page < 1) {
            throw new PaginationException("Page number cannot be negative.");
        }

        $offset = ($page - 1) * $perPage;
        $limit = $offset + $perPage;

        return [$offset, $limit];
    }

    /**
     * @param $data
     * @return mixed
     * @throws PaginationException
     */
    protected function unpackData($data): array
    {
        switch ($data) {
            case is_array($data):
                return $data;
            case $data instanceof \Traversable:
                return iterator_to_array($data);
            default:
                throw new PaginationException("Data passed to paginator is not a Traversable object or an array.");
        }
    }
}
