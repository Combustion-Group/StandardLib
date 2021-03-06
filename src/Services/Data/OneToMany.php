<?php

namespace Combustion\StandardLib\Services\Data;

use Combustion\StandardLib\Models\Model;
use Combustion\StandardLib\Services\Data\ModelBuilder;
use Combustion\StandardLib\Exceptions\BuilderException;
use Combustion\StandardLib\Services\Data\Contracts\Relationship;
use Combustion\StandardLib\Services\Data\Contracts\DataGenerator;
use Combustion\StandardLib\Tools\TypeChecker\ChecksType;
use Combustion\StandardLib\Tools\TypeChecker\ZEND_TYPE;

/**
 * Class Manipulation
 *
 * @package Combustion\StandardLib\Services\Data
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class OneToMany implements DataGenerator, Relationship
{
    use ChecksType;

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
        'parentBuilder' => ModelBuilder::class,
        'childBuilder' => ModelBuilder::class,
        'parentPrefix' => ZEND_TYPE::STRING,
        'childPrefix' => ZEND_TYPE::STRING
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
     * @param array $data
     * @return \Generator|Model[]
     */
    public function generate(array $data): \Generator
    {
        $this->checkMissing();

        $totalRecords = count($data);

        if (!$totalRecords) return [];

        $parentIdField = $this->parentPrefix . 'id';

        // Runs in O(n), this is not a quadratic approach. Both loops run
        // on the same control variable.
        for ($i = 0; $i < $totalRecords; $i++) {
            $record = $data[$i];
            $parent = $this->parentBuilder->build($record);

            for (; $i < $totalRecords; $i++) {
                $record = $data[$i];

                $child = $this->childBuilder->build($record);

                $parent->addLineItem($child);

                $curId = $data[$i][$parentIdField];
                $next = $i + 1;

                if ((array_key_exists($next, $data) && $curId != $data[$next][$parentIdField]) || !array_key_exists($next, $data)) {
                    yield $parent;
                    break;
                }
            }
        }
    }

    /**
     * @param ModelBuilder $modelFactory
     * @return OneToMany
     */
    public function setParentBuilder(ModelBuilder $modelFactory): OneToMany
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
    public function setChildBuilder(ModelBuilder $modelFactory): OneToMany
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
    public function setParentPrefix(string $prefix): OneToMany
    {
        unset($this->missing['parentPrefix']);
        $this->parentPrefix = $prefix;
        return $this;
    }

    /**
     * @param string $prefix
     * @return OneToMany
     */
    public function setChildPrefix(string $prefix): OneToMany
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
    public function toList(array $data): array
    {
        return iterator_to_array($this->generate($data));
    }
}
