<?php

namespace Combustion\StandardLib\Services\ACL\Middleware;

use \Closure;
use Combustion\StandardLib\Services\ACL\Exceptions\AclAccessDeniedException;
use Combustion\StandardLib\Services\ACL\Exceptions\AclException;
use Combustion\StandardLib\Services\ACL\Manager;
use Combustion\StandardLib\Controller;

/**
 * Class Acl
 *
 * @package Combustion\StandardLib\Services\ACL\Middleware
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class ACL
{
    /**
     * @var Manager
     */
    private $manager;

    /**
     * Acl constructor.
     *
     * @param Manager $manager
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param         $request
     * @param Closure $next
     * @return mixed
     * @throws AclAccessDeniedException
     * @throws AclException
     * @throws \Combustion\StandardLib\Support\Installer\Exceptions\InvalidOperationException
     */
    public function handle($request, Closure $next)
    {
        $user       = Controller::getAuthenticatedUser();
        $label      = array_slice(func_get_args(), 2);

        if (!$label) {
            throw new AclException("Cannot continue because the route being accessed has not been labeled with an action name.");
        }

        $label      = $label[0];

        if ($this->manager->hasAccess($user->getId(), $label)) {
            return $request($next);
        }

        throw new AclAccessDeniedException("User does not have access to this area.");
    }
}
