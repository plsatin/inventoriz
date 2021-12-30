<?php   
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *      schema="WmiClass",
 *      type="object",
 *      @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Наименование WMI класса",
 *      ),
 *      @OA\Property(
 *          property="namespace",
 *          type="string",
 *          description="Пространство имен WMI",
 *      ),
 *      @OA\Property(
 *          property="title",
 *          type="string",
 *          description="Наименование WMI класса",
 *      ),
 *      @OA\Property(
 *          property="description",
 *          type="string",
 *          description="Описание WMI класса",
 *      ),
 *      @OA\Property(
 *          property="icon",
 *          type="string",
 *          description="Имя файла значка класса",
 *      ),
 *      @OA\Property(
 *          property="enabled",
 *          type="integer",
 *          description="Признак включен/выключен/другое",
 *      ),
 * )
 */
class WmiClass extends Model
{
    protected $table = "wmiclasses";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'namespace', 'title', 'description', 'icon',
    ];

    // public $timestamps = false;


    public function properties()
    {
        return $this->hasMany(WmiProperty::class, 'wmiclass_id');
    }


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];


}
