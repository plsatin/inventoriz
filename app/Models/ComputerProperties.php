<?php   
namespace App\Models;

use Illuminate\Database\Eloquent\Model;



/**
 * @OA\Schema(
 *      schema="ComputerProperties",
 *      type="object",
 *      @OA\Property(
 *          property="computer_id",
 *          type="integer",
 *          description="ИД компьютера",
 *      ),
 *      @OA\Property(
 *          property="wmiclass_id",
 *          type="integer",
 *          description="ИД WMI класса",
 *      ),
 *      @OA\Property(
 *          property="wmiproperty_id",
 *          type="integer",
 *          description="ИД свойства WMI класса",
 *      ),
 *      @OA\Property(
 *          property="value",
 *          type="string",
 *          description="Значение",
 *      ),
 *      @OA\Property(
 *          property="instance_id",
 *          type="integer",
 *          description="ИД экземпляра свойства",
 *      ),
 * )
 */
class ComputerProperties extends Model
{
    protected $table = "computer_properties";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'computer_id', 'wmiclass_id', 'wmiproperty_id', 'value', 'instance_id',
    ];

    // public $timestamps = false;

   

/* Связи с таблицами, обратные к hasMany */
    public function computer()
    {
        return $this->belongsTo(Computer::class, 'computer_id');
    }

    public function wmiclass()
    {
        return $this->belongsTo(WmiClass::class, 'wmiclass_id');
    }

    public function wmiproperty()
    {
        return $this->belongsTo(WmiProperty::class, 'wmiproperty_id');
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
