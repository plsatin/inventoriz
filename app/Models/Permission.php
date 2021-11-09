<?php

namespace App\Models;

use OpenApi\Annotations as OA;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *      schema="Permission",
 *      type="object",
 *      @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Наименование разрешения",
 *      ),
 *      @OA\Property(
 *          property="key",
 *          type="string",
 *          description="Ключ",
 *      ),
 *      @OA\Property(
 *          property="action",
 *          type="string",
 *          description="Действие (функция)",
 *      ),
 *      @OA\Property(
 *          property="controller",
 *          type="string",
 *          description="Контроллер",
 *      ),
 *      @OA\Property(
 *          property="method",
 *          type="string",
 *          description="HTTP метод",
 *      ),
 * )
 */
class Permission extends Model
{
    // public $timestamps = false;

    protected $fillable = ['name', 'key', 'action', 'controller', 'method'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
