<?php

namespace Combustion\StadardLib\Services\ACL;

use Combustion\StadardLib\Services\ACL\Contracts\AccessManager;
use Combustion\StadardLib\Services\ACL\Models\Action;
use Combustion\StadardLib\Services\ACL\Models\Group;
use Combustion\StadardLib\Services\ACL\Models\Role;

/**
 * Class Manager
 *
 * @package Combustion\StadardLib\Services\ACL
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class Manager implements AccessManager
{
    public function __construct()
    {

    }

    public function hasAccess(int $userId, string $action) : bool
    {

    }

    public function createGroup(string $name) : Group
    {
        return new Group;
    }

    public function createRole(string $name, int $groupId) : Role
    {
        return new Role;
    }

    public function createAction(string $label) : Action
    {
        return new Action;
    }
}
