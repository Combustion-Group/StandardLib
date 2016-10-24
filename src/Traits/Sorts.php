<?php

namespace Combustion\StandardLib\Traits;
use Combustion\StandardLib\Exceptions\BadConfigurationException;

/**
 * Class Sorts
 * @package Combustion\StandardLib\Traits
 * @author Carlos Granados <cgranados@combusiongroup.com>
 *
 * Sort algorithms in this class:
 *
 * - Quick Sort: "quickSort()" Calls the PHP sort() function
 * - Heap Sort: "heapSort()"
 * - Radix Sort: "radixSort()"
 */
trait Sorts
{
    protected abstract function getConfig() : array;

    /**
     * @param array & $data
     * @throws BadConfigurationException
     */
    protected function sort(array &$data)
    {
        $config = $this->getConfig();

        if (isset($config['sort-algo'])) {
            $algorithm = $config['sort-algo'];
            if (!method_exists($this, $algorithm)) {
                throw new BadConfigurationException("Sort algorithm {$config['sort-algo']} does not exist.");
            }

            $this->{$algorithm}($data);
        }

        $this->quickSort($data);
    }

    /**
     * In the order of O(n log(n))
     * @param array $array
     */
    public function heapSort(array &$array)
    {
        $init = (int)floor((count($array) - 1) / 2);

        for($i = $init; $i >= 0; $i--)
        {
            $count = count($array) - 1;

            $this->buildHeap($array, $i, $count);
        }

        for ($i = (count($array) - 1); $i >= 1; $i--)
        {
            $tmp_var    = $array[0];
            $array[0]   = $array[$i];
            $array[$i]  = $tmp_var;

            $this->buildHeap($array, 0, $i - 1);
        }
    }

    /**
     * In the order of O(nk)
     * @param $array
     */
    public function radixSort(&$array) {
        // Find the number of passes needed to complete the sort
        $passes     = strlen((string)max($array));
        $buckets    = [];

        // Start the passes
        for($i = 1; $i <= $passes; $i++) {
            // Create - reinitialize some buckets
            for ($b = 0; $b <= 9; $b++) {
                $buckets[$b] = [];
            }

            for ($j = 0; $j < count($array); $j++) {
                // Drop into the proper bucket based on the significant digit
                $numStr = (string)$array[$j];
                if (strlen($numStr) < $i) {
                    $bucketsIndex = 0;
                } else {
                    $bucketsIndex = $numStr[strlen($numStr) - $i];
                }
                array_push($buckets[$bucketsIndex], $array[$j]);
            }

            // Repopulate our array by pulling out of our buckets
            $k = 0;

            foreach ($buckets as $bucket) {
                foreach ($bucket as $value) {
                    $array[$k] = $value;
                    $k++;
                }
            }
        }
    }

    /**
     * @param $array
     */
    public function quickSort(&$array)
    {
        sort($array);
    }

    /**
     * @param $array
     * @param $i
     * @param $t
     */
    protected function buildHeap(&$array, $i, $t)
    {
        $tmp_var    = $array[$i];
        $j          = $i * 2 + 1;

        while ($j <= $t)  {
            if($j < $t)
                if($array[$j] < $array[$j + 1]) {
                    $j = $j + 1;
                }
            if($tmp_var < $array[$j]) {
                $array[$i]  = $array[$j];
                $i          = $j;
                $j          = 2 * $i + 1;
            } else {
                $j = $t + 1;
            }
        }

        $array[$i] = $tmp_var;
    }
}