<?php

namespace Combustion\StadardLib\Services\ACL\Models;

use Combustion\StandardLib\Models\Model;

/**
 * Class Action
 *
 * @package Combustion\StadardLib\Services\ACL\Models
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class Action extends Model
{
    const LABEL = 'name';

    /**
     * @return int
     */
    public function getId() : int
    {
        return (int)$this->getAttribute(self::ID);
    }

    /**
     * @return string
     */
    public function getLabel() : string
    {
        return (string)$this->getAttribute(self::LABEL);
    }
}
