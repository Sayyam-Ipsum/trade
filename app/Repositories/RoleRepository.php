<?php

namespace App\Repositories;

use App\Interfaces\RoleInterface;
use App\Models\Role;

class RoleRepository implements RoleInterface
{
    public function getCustomerRoleID()
    {
        return Role::where("name", "like", "Customer")->first()->id;
    }
}
