<?php

namespace Combustion\StandardLib\Traits;

use Combustion\StandardLib\Support\Installer\Exceptions\InvalidOperationException;

/**
 * Class CompositeObjectStorage
 *
 * Helpful when implementing a composite(ish) pattern.
 *
 * @package Combustion\StandardLib\Traits
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
trait StoresObjects
{
    /**
     * @var \stdClass[]
     */
    protected $storage = [];

    /**
     * Why is this method of tracking object types used as opposed to simply
     * storing all in the $storage member as $storage[type][] = $obj? Because
     * when we need to fetch one single object, I want to be able to just supply and ID for for the subset
     * like $compositeItem->fetchItem(int $id) instead of $compositeItem->fetchItem(int $id, string $type)
     *
     * @var array
     */
    protected $tracker = [];

    /**
     * @param          $object
     * @param int|null $key
     * @return int
     * @throws InvalidOperationException
     */
    protected function store($object, int $key = null) : int
    {
        if (!is_object($object)) {
            throw new InvalidOperationException("Cannot use ObjectStorage for storing non objects. Type: " . gettype($object));
        }

        $class              = get_class($object);

        if (!is_null($key)) {
            $this->storage[$key]    = $object;
        } else {
            $this->storage[]        = $object;
        }

        end($this->storage);
        return $this->track($class, key($this->storage));
    }

    /**
     * @param int $key
     * @param     $object
     * @return mixed
     * @throws InvalidOperationException
     */
    protected function replaceObject(int $key, $object)
    {
        if (!is_null($object)) {
            throw new InvalidOperationException("Cannot replace an object with a non object. Type supplied: " . gettype($object));
        }

        $original = $this->fetchItem($key);

        if (!$object instanceof $original) {
            throw new InvalidOperationException("Cannot replace object, class mismatch. Original object is of class: " . get_class($original) . ' and the new objects is of class ' . get_class($object));
        }

        $this->storage[$key] = $object;

        return $original;
    }

    /**
     * @param string $type
     * @param int    $key
     * @return int
     */
    private function track(string $type, int $key)
    {
        if (!array_key_exists($type, $this->tracker)) {
            $this->tracker[$type] = [];
        }

        $this->tracker[$type][$key] = NULL;

        return $key;
    }

    /**
     * @param int $key
     * @return string
     */
    private function hash(int $key) : string
    {
        return md5($key);
    }

    /**
     * Iterates returning $type and $object when used in $key => $value form respectively.
     * @return \Generator
     */
    public function iterate() : \Generator
    {
        foreach ($this->storage as $key => $objects)
        {
            return $this->storage;
        }
    }

    /**
     * @param int $key
     */
    public function drop(int $key)
    {
        unset($this->storage[$key]);
    }

    /**
     * @return array
     */
    public function getAll() : array
    {
        return $this->storage;
    }

    /**
     * @param string $type
     * @return \stdClass
     */
    public function getAllOfType(string $type)
    {
        if (!array_key_exists($type, $this->tracker)) {
            return [];
        }

        return array_intersect_key($this->storage, $this->tracker[$type]);
    }

    /**
     * @param string $type
     * @return bool
     */
    public function hasType(string $type) : bool
    {
        return array_key_exists($type, $this->tracker);
    }

    /**
     * @param string $type
     * @param int    $key
     * @return mixed
     */
    protected function fetchItem(int $key)
    {
        return $this->storage[$key];
    }
}
