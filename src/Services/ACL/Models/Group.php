<?php

namespace Combustion\StadardLib\Services\ACL\Models;

use Combustion\StandardLib\Models\Model;

/**
 * Class Group
 *
 * @package Combustion\StadardLib\Services\ACL\Models
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class Group extends Model
{
    protected $table = 'acl_group';

    const   LABEL    = 'label';

    /**
     * @return int
     */
    public function getId() : int
    {
        return (int)$this->getAttribute(self::LABEL);
    }

    /**
     * @return string
     */
    public function getLabel() : string
    {
        return (string)$this->getAttribute(self::LABEL);
    }

    /**
     * @param string $label
     * @return Group
     */
    public function setLabel(string $label) : Group
    {
        $this->setAttribute(self::LABEL, $label);
        return $this;
    }
}
