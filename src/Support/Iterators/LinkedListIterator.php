<?php

namespace Combustion\StandardLib\Support\Iterators;

use Combustion\StandardLib\Support;
use Combustion\StandardLib\Contracts\Node;

/**
 * Class TransactionIterator
 * @package Combustion\Billing\Support
 * @author Carlos Granados <cgranados@combustiongroup.com>
 */
class LinkedListIterator implements \Iterator
{
    /**
     * @var Node
     */
    private $root;

    /**
     * @var Node
     */
    private $current;

    /**
     * @var bool
     */
    private $hasChild = true;

    /**
     * TransactionIterator constructor.
     * @param Node $linkedList
     */
    public function __construct(Node $linkedList)
    {
        $this->root     = $this->head($linkedList);
        $this->current  = $this->root;
    }

    /**
     * Rewinds to parent transaction
     * @param Node $linkedList
     * @return Node
     */
    private function head(Node $linkedList) : Node
    {
        // Check if we're at the head or if there's only one transaction
        if (! $linkedList->hasParent() || (! $linkedList->hasChild() && ! $linkedList->hasParent())) {
            return $linkedList;
        }

        return $this->head($linkedList->getParentNode());
    }

    public function rewind()
    {
        $this->current = $this->root;
    }

    public function next()
    {
        if (!$this->current->hasChild()) {
            $this->hasChild = false;
        } else {
            $this->current = $this->current->getChildNode();
        }
    }

    /**
     * @return bool
     */
    public function valid() : bool
    {
        return $this->hasChild;
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->current->getId();
    }

    /**
     * @return Node
     */
    public function current() : Node
    {
        return $this->current;
    }
}
