<?php

namespace didpoule\PageBundle\Service;

class RolesHelper
{
    private $rolesHierarchy;

    private $roles;

    public function __construct($rolesHierarchy)
    {
        $this->rolesHierarchy = $rolesHierarchy;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        if ($this->roles) {
            return $this->roles;
        }
        $roles = [];
        array_walk_recursive($this->rolesHierarchy, function($val) use (&$roles) {
            $roles[] = $val;
        });

        $this->roles = array_unique($roles);

        return $this->roles = $this->getRolesNames();
    }

    private function getRolesNames()
    {
        $rolesNames = [];
        $rolesNames['Anonymous'] = 'anonymous';
        foreach($this->roles as $k => $v) {
            $rolesNames[$this->roleToLower($v)] = $v;
        }
        return $rolesNames;
    }

    private function roleToLower($role)
    {
        return ucfirst(strtolower(str_replace('ROLE_', '', $role)));
    }
}