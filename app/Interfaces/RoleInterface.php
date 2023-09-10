<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface RoleInterface
{
    public function getCustomerRoleID();
    public function roleListing($id = null);
    public function storeRole(Request $request);
    public function permissionListing($id = null);
    public function getPermissionsByRole($id);
    public function changePermission(Request $request);
}
