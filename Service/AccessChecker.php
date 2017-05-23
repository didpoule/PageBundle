<?php

namespace didpoule\PageBundle\Service;

use didpoule\PageBundle\Entity\Page;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AccessChecker
{

    private $security;

    public function __construct(AuthorizationChecker $security)
    {
        $this->security = $security;
    }

    public function checkAccess($role)
    {
        if (
            ($this->security->isGranted('IS_AUTHENTICATED_ANONYMOUSLY') && $role === 'anonymous') ||
            ($this->security->isGranted($role))
        ) {
            return true;
        } else {
            throw new AccessDeniedException('You are not allowed to access to this page.');
        }
    }
}