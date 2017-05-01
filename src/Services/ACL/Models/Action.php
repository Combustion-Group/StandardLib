<?php

namespace Combustion\StandardLib\Services\ACL\Models;

use Combustion\StandardLib\Models\Model;

/**
 * Class Action
 *
 * @package Combustion\StandardLib\Services\ACL\Models
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class Action extends Model
{
    /**
     * @var string
     */
    protected $table    = 'acl_actions';

    /**
     * @var bool
     */
    public $timestamps  = false;

    // Columns
    const LABEL = 'label';

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

    /**
     * @param string $label
     * @return Action
     */
    public function setLabel(string $label) : Action
    {
        $this->setAttribute(self::LABEL, $label);
        return $this;
    }
}
