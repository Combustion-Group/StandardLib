<?php

namespace Combustion\StandardLib\Services\Data;

/**
 * Class ParentChildGeneratorMapper
 *
 * Speeds up matching a one to one relationship from two generators
 * by progressively advancing through the parent generator, child generator or both.
 * This can be used when you need to create a relationship between two resources but
 * a matching child won't always be present.
 *
 * NOTE: For this to work properly, BOTH generators will need to generate their items in order of identifier,
 * also you must specified whether the items are being generated in ascending or descending order.
 *
 * @package Combustion\StandardLib\Services\Data
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class ParentChildGeneratorMapper
{
    /**
     * @param \Generator $parent
     * @param \Generator $child
     * @param string     $parentIdentifierGetter
     * @param string     $childParentForeignKeyGetter
     * @param string     $parentChildSetter
     * @param string     $order
     * @return \Generator
     */
    public function map(\Generator $parent, \Generator $child, string $parentIdentifierGetter, string $childParentForeignKeyGetter, string $parentChildSetter, string $order = 'desc') : \Generator
    {
        while ($parent->valid())
        {
            $currentParent = $parent->current();

            if ($child->valid()) {
                $currentChild  = $child->current();

                $parentId = $currentParent->{$parentIdentifierGetter}();
                $childId  = $currentChild->{$childParentForeignKeyGetter}();

                if ($parentId === $childId) {
                    // Has a child, add child to the parent
                    $currentParent->{$parentChildSetter}($currentChild);
                    $parent->next();
                    $child->next();

                } else {
                    if ($childId > $parentId) {
                        if ($order === 'asc')
                            $parent->next();
                        else
                            $child->next();
                    } else {
                        if ($order == 'asc')
                            $child->next();
                        else
                            $parent->next();
                    }
                }
            } else {
                // There are no children to look at.
                $parent->next();
            }

            yield $currentParent;
        }
    }
}
