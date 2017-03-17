<?php

namespace Combustion\StandardLib\Services\Data;

use Combustion\StandardLib\Models\Model;

/**
 * Class Manipulation
 *
 * @package Combustion\StandardLib\Services\Data
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class Slicer
{
    /**
     * This function allows you to pass the results of an inner joined one to many relationship result set,
     * it's expected that the columns are aliased to differentiate otherwise ambiguous fields (e.g. id field).
     *
     * @param array  $data
     * @param ModelFactory  $parentFactory
     * @param ModelFactory  $childFactory
     * @param string $parentPrefix
     * @param string $childPrefix
     * @return \Generator|Model[]
     */
    public function oneToMany(array $data, ModelFactory $parentFactory, ModelFactory $childFactory, string $parentPrefix = 'a_', string $childPrefix = 'b_') : \Generator
    {
        $totalRecords = count($data);

        if ($totalRecords) return [];

        $prevId       = array_get('a_id', array_get($data, 0, []), null);

        for ($i = 0; $i < $totalRecords; $i++)
        {
            $record = $data[$i];
            $parent = $parentFactory->build($record, ModelFactory::BUILD_SLICED);

            for ($j = $i; $j < $totalRecords; $j++, $i++)
            {
                if ($prevId !== $record[$parentPrefix]) {
                    yield $parent;

                    $prevId = $record[$parentPrefix];
                    break;
                }

                $child = $childFactory->build($data, ModelFactory::BUILD_SLICED);

                $parent->addLineItem($child);

                $prevId = $record[$parentPrefix];
            }
        }
    }
}