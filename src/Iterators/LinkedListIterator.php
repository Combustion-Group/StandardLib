<?php

namespace Combustion\StandardLib\Iterators;

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
    private $hasChild;

    /**
     * TransactionIterator constructor.
     * @param Node $linkedList
     */
    public function __construct(Node $linkedList)
    {
        $this->root = $this->head($linkedList);
        $this->current = $this->root;
    }

    /**
     * Rewinds to parent transaction
     * @param Node $linkedList
     * @return Node
     */
    private function head(Node $linkedList): Node
    {
        // Check if we're at the head or if there's only one transaction
        if (!$linkedList->hasParent() || (!$linkedList->hasChild() && !$linkedList->hasParent())) {
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
        $this->hasChild = $this->current()->hasChild();

        if ($this->hasChild) {
            $this->current = $this->current->getChildNode();
        }
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return $this->hasChild;
    }

    /**
     * @return int
     */
    public function key()
    {
        return spl_object_hash($this->current);
    }

    /**
     * @return Node
     */
    public function current(): Node
    {
        return $this->current;
    }
}
