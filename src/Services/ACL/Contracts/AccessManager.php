<?php

namespace Combustion\StadardLib\Services\ACL\Contracts;

use Combustion\StadardLib\Services\ACL\Models\Role;
use Combustion\StadardLib\Services\ACL\Models\Group;
use Combustion\StadardLib\Services\ACL\Models\Action;

/**
 * Interface AccessManager
 *
 * @package Combustion\StadardLib\Services\ACL\Contracts
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
interface AccessManager
{
    /**
     * @param int    $userId
     * @param string $action
     * @return bool
     */
    public function hasAccess(int $userId, string $action) : bool;

    /**
     * @param string $name
     * @return Group
     */
    public function createGroup(string $name) : Group;

    /**
     * @param string $name
     * @param int    $groupId
     * @return Role
     */
    public function createRole(string $name, int $groupId) : Role;
}
