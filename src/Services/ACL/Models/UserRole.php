<?php

namespace Combustion\StandardLib\Services\ACL\Models;

use Combustion\StandardLib\Models\Model;

/**
 * Class UserRole
 *
 * @package Combustion\StandardLib\Services\ACL\Models
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class UserRole extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'acl_user_roles';

    // Columns
    const   USER_ID = 'user_id',
        ROLE_ID = 'role_id';

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int)$this->getAttribute(self::ID);
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return (int)$this->getAttribute(self::USER_ID);
    }

    /**
     * @param int $userId
     * @return UserRole
     */
    public function setUserId(int $userId): UserRole
    {
        $this->setAttribute(self::USER_ID, $userId);
        return $this;
    }

    /**
     * @return int
     */
    public function getRoleId(): int
    {
        $this->getAttribute(self::ROLE_ID);
    }

    /**
     * @param int $roleId
     * @return UserRole
     */
    public function setRoleId(int $roleId): UserRole
    {
        $this->setAttribute(self::ROLE_ID, $roleId);
        return $this;
    }
}
