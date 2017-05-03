<?php

namespace Combustion\StandardLib\Services\ACL;

use Combustion\StandardLib\Services\ACL\Contracts\AccessManager;
use Combustion\StandardLib\Services\ACL\Models\Action;
use Combustion\StandardLib\Services\ACL\Models\Group;
use Combustion\StandardLib\Services\ACL\Models\Policy;
use Combustion\StandardLib\Services\ACL\Models\Role;
use Combustion\StandardLib\Services\ACL\Models\UserRole;
use Illuminate\Database\Connection;

/**
 * Class Manager
 *
 * @package Combustion\StandardLib\Services\ACL
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class AclService implements AccessManager
{
    /**
     * @var Connection
     */
    private $database;

    /**
     * Manager constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->database = $connection;
    }

    /**
     * @param int    $userId
     * @param string $action
     * @return bool
     */
    public function hasAccess(int $userId, string $action) : bool
    {
        return (bool)$this->database->table('acl_policies as a')
                                    ->join('acl_actions as c', 'a.action_id', '=', 'c.id')
                                    ->join('acl_user_roles as d', 'a.role_id', '=', 'd.role_id')
                                    ->where('d.user_id', '=', $userId)
                                    ->where('c.label', '=', $action)
                                    ->exists();
    }

    /**
     * @param string $name
     * @return Group
     */
    public function createGroup(string $name) : Group
    {
        $g = new Group();
        $g->setLabel($name)
          ->save();

        return $g;
    }

    /**
     * @param string $name
     * @param int    $groupId
     * @return Role
     */
    public function createRole(string $name, int $groupId) : Role
    {
        $r = new Role;
        $r->setLabel($name)
          ->setGroupId($groupId)
          ->save();

        return $r;
    }

    /**
     * @param int $userId
     * @param int $roleId
     * @return UserRole
     */
    public function assignRole(int $userId, int $roleId) : UserRole
    {
        $ur = new UserRole();
        $ur->setUserId($userId)
           ->setRoleId($roleId)
           ->save();

        return $ur;
    }

    /**
     * @param int $roleId
     * @param int $actionId
     * @return Policy
     */
    public function addToPolicy(int $roleId, int $actionId) : Policy
    {
        $p = new Policy();
        $p->setRoleId($roleId)
          ->setActionId($actionId)
          ->save();

        return $p;
    }

    /**
     * @param int $userId
     * @return array
     */
    public function getUserRoles(int $userId) : array
    {
        return $this->database->table('acl_user_roles as a')
                              ->join('users as b', 'a.user_id', '=', 'b.id')
                              ->join('acl_roles as c', 'c.id', '=', 'a.role_id')
                              ->where('a.user_id', '=', $userId)
                              ->get(['c.id as role_id', 'c.label'])->all();
    }

    /**
     * @param string $label
     * @return Action
     */
    public function createAction(string $label) : Action
    {
        $a = new Action();
        $a->setLabel($label)
          ->save();

        return $a;
    }
}
