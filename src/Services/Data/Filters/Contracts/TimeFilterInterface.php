<?php

namespace Combustion\StandardLib\Services\Data\Filters\Contracts;

/**
 * Interface TimeFilterInterface
 *
 * @package Combustion\Billing\Structs\Filters\Contracts
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
interface TimeFilterInterface extends QueryFilterInterface
{
    /**
     * @param bool $sqlDateFormat
     */
    public function getStartingTime(bool $sqlDateFormat = true);

    /**
     * @param bool $sqlDateFormat
     */
    public function getEndingTime(bool $sqlDateFormat = true);
}
