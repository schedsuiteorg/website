<?php

namespace App\Models\permissions;

use App\Models\auth\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    protected $table = 'roles';

    protected $fillable = ['role_name', 'role_slug', 'status'];

    /**
     * The permissions that belong to the role.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');
    }

    /**
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
