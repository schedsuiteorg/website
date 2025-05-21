<?php

namespace App\Models\permissions;

use App\Models\auth\User;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{

    protected $table = 'role_permissions';

    protected $fillable = ['role_id', 'permission_id'];


    /**
     * Get the role that owns the RolePermission.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Get the permission that owns the RolePermission.
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
