<?php

namespace Combustion\StandardLib\Contracts;

use \IteratorAggregate;
use Combustion\StandardLib\Contracts\Prototype;

/**
 * Interface Node
 * @package Combustion\StandardLib\Contracts
 * @author Carlos Granados <cgranados@combustiongroup.com<
 */
interface Node extends IteratorAggregate
{
    /**
     * @return Node
     */
    public function getParentNode() : Node;

    /**
     * @return Node
     */
    public function getChildNode() : Node;

    /**
     * @return bool
     */
    public function hasParent() : bool;

    /**
     * @return bool
     */
    public function hasChild() : bool;

    /**
     * @param Node $node
     * @return Node
     */
    public function setChildNode(Node $node) : Node;

    /**
     * @param Node $node
     * @return Node
     */
    public function setParentNode(Node $node) : Node;
}
