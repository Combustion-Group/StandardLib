<?php

namespace Combustion\StandardLib\Tools;

use Illuminate\Contracts\Support\Arrayable;
use Combustion\StandardLib\Exceptions\ObjectStorageException;

/**
 * Class TypeSafeObjectStorage
 *
 * @package Combustion\StandardLib\Tools
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
abstract class TypeSafeObjectStorage extends \SplObjectStorage implements Arrayable
{
    /**
     * @var null|object
     */
    protected $containerType = null;

    /**
     * @var string
     */
    protected $checkType = null;

    const   INTERFACE = 1,
            CONCRETE  = 2,
            SUBCLASS  = 3,
            TRAIT     = 4;

    /**
     * TypeSafeObjectStorage constructor.
     *
     * @param array $data
     */
    protected function __construct(array $data = [])
    {
        foreach ($data as $item)
        {
            $this->attach($item);
        }
    }

    /***
     * @return array
     */
    public function toArray() : array
    {
        $data = [];

        foreach ($data as $obj) {
            if ($this->is_arrayable($obj)) {
                $data[] = $obj->toArray();
            } else {
                $data[] = $obj;
            }
        }

        return $data;
    }

    /**
     * @param $obj
     * @return bool
     */
    protected function is_arrayable($obj) : bool
    {
        return in_array(Arrayable::class, class_implements($obj));
    }

    /**
     * @param object $object
     * @param null   $data
     * @return TypeSafeObjectStorage
     */
    public function attach($object, $data = null) : TypeSafeObjectStorage
    {
        $this->validateType($object);

        parent::attach($object, $data);

        return $this;
    }

    /**
     * @param string $type
     * @param int    $checkType
     * @return $this
     */
    protected function setContainerType(string $type, int $checkType = self::INTERFACE)
    {
        $this->checkType        = $checkType;
        $this->containerType    = $type;

        return $this;
    }

    /**
     * @return string
     * @throws ObjectStorageException
     */
    public function getContainerType() : string
    {
        if (is_null($this->containerType)) {
            throw new ObjectStorageException("The type to check for has not been set in this container.");
        }

        return $this->containerType;
    }

    /**
     * @param $object
     * @throws ObjectStorageException
     */
    private function validateType($object)
    {
        switch ($this->checkType) {
            case self::INTERFACE:
                if (in_array($this->containerType, class_implements($object))) return;

                $message = "Object does not implement interface {$this->containerType}";
                break;
            case self::CONCRETE:
                if (is_a($object, $this->containerType)) return;

                $message = "Object is not of type {$this->containerType}";
                break;
            case self::SUBCLASS:
                if (is_subclass_of($object, $this->containerType)) return;

                $message = "Object does not inherit {$this->containerType}";
                break;
            case self::TRAIT:
                if (in_array($this->containerType, class_uses($object))) return;

                $message = "Object does not use trait {$this->containerType}";
                break;
            default:
                throw new ObjectStorageException("Invalid check type set in container. Only TypeSafeObjectStorage::INTERFACE, CONCRETE, or SUBCLASS");
        }

        throw new ObjectStorageException($message);
    }
}
