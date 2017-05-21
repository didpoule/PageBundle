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

    public function checkAccess(Page $page)
    {
        if (
            ($this->security->isGranted('IS_AUTHENTICATED_ANONYMOUSLY') && $page->getRole() === 'anonymous') ||
            ($this->security->isGranted($page->getRole()))
        ) {
            return true;
        } else {
            throw new AccessDeniedException('You are not allowed to access to this page : ' . $page->getTitle());
        }
    }
}