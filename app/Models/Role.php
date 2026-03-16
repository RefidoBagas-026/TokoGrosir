<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
     protected $table = 'roles';

    protected $fillable = [
        'name'
    ];

    public function rolePermissions()
    {
        return $this->hasMany(RolePermission::class);
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'role_permissions');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
