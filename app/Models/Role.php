<?php

namespace App\Models;

use OpenApi\Annotations as OA;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    // public $timestamps = false;

    protected $fillable = ['name', 'description'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

}
