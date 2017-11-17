<?php

namespace Combustion\StandardLib\Services\ACL\Models;

use Combustion\StandardLib\Models\Model;

/**
 * Class Role
 *
 * @package Combustion\StandardLib\Services\ACL\Models
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class Role extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'acl_roles';

    // Columns
    const   LABEL = 'label',
        GROUP_ID = 'group_id';

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int)$this->getAttribute(self::ID);
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return (string)$this->getAttribute(self::LABEL);
    }

    /**
     * @param string $label
     * @return Role
     */
    public function setLabel(string $label): Role
    {
        $this->setAttribute(self::LABEL, $label);
        return $this;
    }

    /**
     * @return int
     */
    public function getGroupId(): int
    {
        return (int)$this->getAttribute(self::GROUP_ID);
    }

    /**
     * @param int $groupId
     * @return Role
     */
    public function setGroupId(int $groupId): Role
    {
        $this->setAttribute(self::GROUP_ID, $groupId);
        return $this;
    }
}
