<?php

namespace Combustion\StandardLib\Services\Data;

use Combustion\StandardLib\Models\Model;
use Combustion\StandardLib\Exceptions\BuilderException;
use Combustion\StandardLib\Services\Data\Contracts\DataGenerator;

/**
 * Class Manipulation
 *
 * @package Combustion\StandardLib\Services\Data
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class OneToMany implements DataGenerator
{
    /**
     * @var ModelBuilder
     */
    private $parentBuilder;

    /**
     * @var ModelBuilder
     */
    private $childBuilder;

    /**
     * @var string
     */
    private $parentPrefix;

    /**
     * @var string
     */
    private $childPrefix;

    /**
     * @var array
     */
    private $missing = [
        'parentBuilder' => 'parentBuilder',
        'childBuilder'  => 'childBuilder',
        'parentPrefix'  => 'parentPrefix',
        'childPrefix'   => 'childPrefix'
    ];

    /**
     * OneToMany constructor.
     */
    public function __construct()
    {
        $this->setChildPrefix('b_');
        $this->setParentPrefix('a_');
    }

    /**
     * This function allows you to pass the an inner joined one to many result set,
     * it's expected that the columns are aliased to differentiate otherwise ambiguous fields (e.g. id field).
     *
     * @param array  $data
     * @return \Generator|Model[]
     */
    public function generate(array $data) : \Generator
    {
        $this->checkMissing();

        $totalRecords = count($data);

        if (!$totalRecords) return [];

        $parentIdField  = $this->parentPrefix . 'id';
        $prevId         = array_get(array_get($data, 0, []), $parentIdField, null);

        for ($i = 0; $i < $totalRecords; $i++)
        {
            $record = $data[$i];
            $parent = $this->parentBuilder->build($record);

            for ($j = $i; $j < $totalRecords; $j++, $i++)
            {
                $record = $data[$j];

                if ($prevId !== $record[$parentIdField]) {
                    yield $parent;

                    $prevId = $record[$parentIdField];
                    break;
                }

                $child = $this->childBuilder->build($record);

                $parent->addLineItem($child);

                $prevId = $data[$j][$parentIdField];
            }
        }
    }

    /**
     * @param ModelBuilder $modelFactory
     * @return OneToMany
     */
    public function setParentBuilder(ModelBuilder $modelFactory) : OneToMany
    {
        unset($this->missing['parentBuilder']);
        $this->parentBuilder = $modelFactory;

        $this->parentBuilder->setBuildStyle(ModelBuilder::BUILD_SLICED);
        $this->parentBuilder->setPrefix($this->parentPrefix);
        return $this;
    }

    /**
     * @param ModelBuilder $modelFactory
     * @return OneToMany
     */
    public function setChildBuilder(ModelBuilder $modelFactory) : OneToMany
    {
        unset($this->missing['childBuilder']);
        $this->childBuilder = $modelFactory;

        $this->childBuilder->setBuildStyle(ModelBuilder::BUILD_SLICED);
        $this->childBuilder->setPrefix($this->childPrefix);
        return $this;
    }

    /**
     * @param string $prefix
     * @return OneToMany
     */
    public function setParentPrefix(string $prefix) : OneToMany
    {
        unset($this->missing['parentPrefix']);
        $this->parentPrefix = $prefix;
        return $this;
    }

    /**
     * @param string $prefix
     * @return OneToMany
     */
    public function setChildPrefix(string $prefix) : OneToMany
    {
        unset($this->missing['childPrefix']);
        $this->childPrefix = $prefix;
        return $this;
    }

    /**
     * @throws BuilderException
     */
    private function checkMissing()
    {
        if (count($this->missing)) {
            throw new BuilderException("Cannot create OneToMany relationship. Builder is missing values: " . implode(',', $this->missing));
        }
    }

    /**
     * @param array $data
     * @return array
     */
    public function toList(array $data) : array
    {
        return iterator_to_array($this->generate($data));
    }
}
