<?php

namespace App\Models;

use OpenApi\Annotations as OA;
use Illuminate\Database\Eloquent\Model;


class Permission extends Model
{
    // public $timestamps = false;

    protected $fillable = ['name', 'key', 'action', 'controller', 'method'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
