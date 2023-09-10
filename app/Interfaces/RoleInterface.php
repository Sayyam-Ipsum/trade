<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface RoleInterface
{
    public function getCustomerRoleID();
    public function roleListing();
    public function storeRole(Request $request);
}
