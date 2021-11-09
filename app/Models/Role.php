<?php

namespace App\Models;

use OpenApi\Annotations as OA;
use Illuminate\Database\Eloquent\Model;



/**
 * @OA\Schema(
 *      schema="Role",
 *      type="object",
 *      @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Наименование роли",
 *      ),
 *      @OA\Property(
 *          property="description",
 *          type="string",
 *          description="Описание роли",
 *      ),
 * )
 */
class Role extends Model
{
    // public $timestamps = false;

    protected $fillable = ['name', 'description'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

}
