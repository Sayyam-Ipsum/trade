<?php

namespace App\Repositories;

use App\Interfaces\RoleInterface;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleRepository implements RoleInterface
{
    public function getCustomerRoleID()
    {
        return Role::where("name", "like", "Customer")->first()->id;
    }

    public function roleListing($id = null)
    {
        if (isset($id)) {
            return Role::find($id);
        }

        return Role::where("name", "<>", "Super Admin")->select("id", "name")->orderBy("id")->get();
    }

    public function permissionListing($id = null)
    {
        if (isset($id)) {
            return Permission::find($id);
        }

        return Permission::select("id", "name")->orderBy("id")->get();
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

    public function getPermissionsByRole($id)
    {
        return DB::table("role_has_permissions")
            ->where("role_id", $id)
            ->select("permission_id", "role_id")
            ->get()
            ->toArray();
    }

    public function changePermission(Request $request)
    {
        $res["success"] = "error";
        $role = $this->roleListing($request->role_id);
        $permission = $this->permissionListing($request->perm_id);

        try {
            if ($role->hasPermissionTo($permission)) {
                $role->revokePermissionTo($permission);
                $res["message"] = "Permission Revoked";
            } else {
                $role->givePermissionTo($permission);
                $res["message"] = "Permission Given";
            }
            $res["success"] = "success";
        } catch (\Exception $e) {
            $res["message"] = "Internal Server Error";
        }

        return $res;
    }
}
