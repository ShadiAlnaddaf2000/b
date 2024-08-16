<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable=[
        'name'
    ];
    public function check($param): bool
    {
        $permission =
            Permission::query()
                ->where('name', '=', $param)->first();

        return PermissionRole::query()
            ->where('permission_id', '=', $permission->id)
            ->where('role_id', '=', $this->id)
            ->exists();
    }

}
