<?php   
namespace App\Models;

use Illuminate\Database\Eloquent\Model;



/**
 * @OA\Schema(
 *      schema="WmiProperty",
 *      type="object",
 *      @OA\Property(
 *          property="wmiclass_id",
 *          type="string",
 *          description="ИД WMI класса",
 *      ),
 *      @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Наименование свойства WMI класса",
 *      ),
 *      @OA\Property(
 *          property="title",
 *          type="string",
 *          description="Наименование (для отображения) свойства WMI класса",
 *      ),
 *      @OA\Property(
 *          property="description",
 *          type="string",
 *          description="Описание свойства WMI класса",
 *      ),
 *      @OA\Property(
 *          property="property_type",
 *          type="string",
 *          description="Тип свойства класса",
 *      ),
 *      @OA\Property(
 *          property="icon",
 *          type="string",
 *          description="Имя файла значка свойства класса",
 *      ),
 * )
 */
class WmiProperty extends Model
{
    protected $table = "wmiproperties";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'wmiclass_id', 'name', 'title', 'description', 'property_type', 'icon',
    ];

    // public $timestamps = false;



    public function wmiclass()
    {
        return $this->belongsTo(WmiClass::class, 'wmiclass_id');
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
