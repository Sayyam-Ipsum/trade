<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Permission\Contracts\Permission as PermissionInterface;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\RefreshesPermissionCache;

class Permission extends SpatiePermission implements PermissionInterface
{
    use HasFactory;
    use HasPermissions;
    use RefreshesPermissionCache;

    protected $guarded = [];
}
