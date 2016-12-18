<?php

namespace Combustion\StandardLib\Hydrators;

use Combustion\StandardLib\Contracts\Node;
use Combustion\StandardLib\Contracts\Hydrator;
use Combustion\Billing\Integrations\Exceptions\InvalidOperationException;

/**
 * Class LinkedListHydrator
 * @package Combustion\StandardLib\Hydrators
 * @author Carlos Granados <cgranaddos@combustiongroup.com>
 */
class LinkedListHydrator implements Hydrator
{
    /**
     * @var Node[]
     */
    private $prototypes = [];

    /**
     * @param string $prototype
     * @param array $data
     * @param string $generate
     * @param \Closure $callback
     * @return Node|\Generator|array
     * @throws InvalidOperationException
     */
    public function hydrate(string $prototype, array $data, string $generate = true, \Closure $callback = null)
    {
        $implements = class_implements($prototype);

        if (!in_array(Node::class, $implements))
        {
            throw new InvalidOperationException("Linked list hydrator cannot continue. Prototype give does not implement Node. The following interfaces found: " . implode(', ', $implements));
        }

        $first      = $this->getPrototype($prototype);
        $current    = $first;
        $totalRecs  = count($data);

        for ($i = 0; $i < $totalRecs; $i++)
        {
            $record = $data[$i];

            $current->fill($record);

            if (is_callable($callback)) {
                $callback($current);
            }

            $generate && yield $current;

            if ($i < ($totalRecs - 1)) {
                $current->setChildNode($this->getPrototype($prototype));
                $current->getChildNode()->setParentNode($current);
                $current = $current->getChildNode();
            }
        }

        return $totalRecs ? $first : [];
    }

    /**
     * @param string $key
     * @return Node
     */
    private function getPrototype(string $key) : Node
    {
        if (!array_key_exists($key, $this->prototypes)) {
            $this->prototypes[$key] = new $key;
        }

        return clone $this->prototypes[$key];
    }
}