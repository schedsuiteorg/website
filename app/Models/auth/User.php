<?php

namespace App\Models\auth;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\vendor\Vendor;
use App\Models\permissions\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, Notifiable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "name",
        "phone",
        "email",
        "otp",
        "password",
        "status",
        "role_id",
        "vendor_id",
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function permissions()
    {
        // Get permissions via role
        return $this->role ? $this->role->permissions : collect();
    }
}
