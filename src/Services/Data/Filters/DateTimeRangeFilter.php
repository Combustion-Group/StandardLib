<?php

namespace Combustion\StandardLib\Services\Data\Filters;

use Illuminate\Database\Query\Builder;
use Combustion\Billing\Support\Structs\Filters\Contracts\TimeFilterInterface;
use Combustion\StandardLib\Support\Installer\Exceptions\InvalidOperationException;

/**
 * Class DateRangeFilter
 *
 * @package Combustion\Billing\Support\Structs
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class DateTimeRangeFilter implements TimeFilterInterface
{
    /**
     * @var \DateTime
     */
    private $starting;

    /**
     * @var \DateTime
     */
    private $end;

    /**
     * @var string
     */
    private $timePeriod;

    /**
     * @var string|string
     */
    private $dateColumn;

    /**
     * @var bool
     */
    private $groups = false;

    const   TIME_PERIOD = [
        'NONE'      => 'NONE',
        'YEARLY'    => 'YEARLY',
        'MONTHLY'   => 'MONTHLY',
        'WEEKLY'    => 'WEEKLY',
        'DAILY'     => 'DAILY'
    ];

    /**
     * DateRangeFilter constructor.
     *
     * @param \DateTime $from
     * @param \DateTime $to
     * @param string $timePeriodGroup
     * @param string $dateColumn
     */
    public function __construct(\DateTime $from, \DateTime $to, string $timePeriodGroup = null, string $dateColumn = 'created_at')
    {
        $this->starting     = $from;
        $this->end          = $to;
        $this->timePeriod   = is_null($timePeriodGroup) ? self::TIME_PERIOD['NONE'] : $timePeriodGroup;
        $this->dateColumn   = $dateColumn;

        if (self::TIME_PERIOD != $this->timePeriod) {
            $this->groups = true;
        }
    }

    /**
     * @param bool $sqlDateFormat
     * @return \DateTime|string
     */
    public function getStartingTime(bool $sqlDateFormat = true)
    {
        return $sqlDateFormat ? $this->starting->format('Y-m-d H:i:s') : $this->starting;
    }

    /**
     * @param bool $sqlDateFormat
     * @return \DateTime|string
     */
    public function getEndingTime(bool $sqlDateFormat = true)
    {
        return $sqlDateFormat ? $this->end->format('Y-m-d H:i:s') : $this->end;
    }

    /**
     * @return string
     */
    public function getTimePeriod() : string
    {
        return $this->timePeriod;
    }

    /**
     * @param Builder $query
     * @throws InvalidOperationException
     * @return Builder
     */
    public function applyFilter(Builder $query) : Builder
    {
        $query->where($this->dateColumn, '>=', $this->getStartingTime())
              ->where($this->dateColumn, '<=', $this->getEndingTime());


        if ($this->getTimePeriod() !== self::TIME_PERIOD['NONE'])
        {
            switch ($this->getTimePeriod())
            {
                case self::TIME_PERIOD['YEARLY']:
                    $filer = "YEAR({$this->dateColumn})";
                    break;
                case self::TIME_PERIOD['MONTHLY']:
                    $filer = "YEAR({$this->dateColumn}), MONTH({$this->dateColumn})";
                    break;
                case self::TIME_PERIOD['DAILY']:
                    $filer = "YEAR({$this->dateColumn}), MONTH({$this->dateColumn}), DAY({$this->dateColumn})";
                    break;
                case self::TIME_PERIOD['WEEKLY']:
                    $filer = "CONCAT(YEAR({$this->dateColumn}), '/', WEEK({$this->dateColumn}))";
                    break;
                default:
                    throw new InvalidOperationException("Invalid time period for filter.");
            }

            $query->groupBy(\DB::raw($filer));
        }

        return $query;
    }

    /**
     * @return bool
     */
    public function groups() : bool
    {
        return $this->groups;
    }
}
