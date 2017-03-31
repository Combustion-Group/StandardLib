<?php

namespace Combustion\StandardLib\Services\Data;

use Illuminate\Database\Connection;
use Combustion\StandardLib\Exceptions\RelationshipGeneratorException;
use Combustion\StandardLib\Services\Data\Contracts\RelationshipGenerator;
use Combustion\StandardLib\Services\Data\Contracts\StructuredDataModelBuilder;

/**
 * Class RelationshipBuilder
 *
 * @package Combustion\StandardLib\Services\Data
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class OneToManyRelationshipGenerator implements RelationshipGenerator
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var StructuredDataModelBuilder
     */
    private $builder;

    /**
     * @var \PDOStatement
     */
    private $stmt;

    /**
     * @var string
     */
    private $sql;

    /**
     * @var array
     */
    private $missing = [
        'query' => 'query'
    ];

    /**
     * OneToManyRelationshipBuilder constructor.
     *
     * @param Connection $connection
     * @param StructuredDataModelBuilder $builder
     */
    public function __construct(Connection $connection, StructuredDataModelBuilder $builder)
    {
        $this->connection   = $connection;
        $this->builder      = $builder;
    }

    /**
     * @param string $sql
     * @return OneToManyRelationshipGenerator
     */
    public function setQuery(string $sql) : OneToManyRelationshipGenerator
    {
        unset($this->missing['query']);

        $this->sql  = $sql;
        $this->stmt = $this->connection->getPdo()->prepare($sql);

        return $this;
    }

    /**
     * @return \PDOStatement
     * @throws RelationshipGeneratorException
     */
    public function stmt() : \PDOStatement
    {
        if (isset($this->missing['query'])) {
            throw new RelationshipGeneratorException("Cannot call stmt() because no query has been set yet.");
        }

        return $this->stmt;
    }

    /**
     * @return \Generator
     */
    public function generate() : \Generator
    {
        $this->stmt()->execute();

        $data = $this->stmt()->fetchAll(\PDO::FETCH_ASSOC);

        return $this->builder->setData($data)->build();
    }
}
