<?php

namespace Combustion\StandardLib\Services\ACL\Models;

use Combustion\StandardLib\Models\Model;

/**
 * Class Policy
 *
 * @package Combustion\StandardLib\Services\ACL\Models
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class Policy extends Model
{
    /**
     * @var bool
     */
    public $timestamps  = false;

    /**
     * @var string
     */
    protected $table    = 'acl_policies';

    // Columns
    const   ROLE_ID     = 'role_id',
            ACTION_ID   = 'action_id';

    public function getId() : int
    {
        return (int)$this->getAttribute(self::ID);
    }

    /**
     * @return int
     */
    public function getRoleId() : int
    {
        return (int)$this->getAttribute(self::ROLE_ID);
    }

    /**
     * @param int $roleId
     * @return Policy
     */
    public function setRoleId(int $roleId) : Policy
    {
        $this->setAttribute(self::ROLE_ID, $roleId);
        return $this;
    }

    /**
     * @return int
     */
    public function getActionId() : int
    {
        return (int)$this->getAttribute(self::ACTION_ID);
    }

    /**
     * @param int $actionId
     * @return Policy
     */
    public function setActionId(int $actionId) : Policy
    {
        $this->setAttribute(self::ACTION_ID, $actionId);
        return $this;
    }
}
