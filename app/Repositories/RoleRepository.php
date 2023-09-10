<?php

namespace App\Repositories;

use App\Interfaces\RoleInterface;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleRepository implements RoleInterface
{
    public function getCustomerRoleID()
    {
        return Role::where("name", "like", "Customer")->first()->id;
    }

    public function roleListing()
    {
        return Role::where("name", "<>", "Super Admin")->get();
    }

    public function storeRole(Request $request)
    {
        $res["status"] = false;
        try {
            DB::beginTransaction();
            $role = isset($request->id) ? Role::find($request->id) : new Role();
            $role->name = $request->name;
            $role->guard_name = 'web';
            $role->save();
            DB::commit();
            $res["status"] = true;
        } catch (\Exception $e) {
            DB::rollBack();
        }

        return $res;
    }
}
