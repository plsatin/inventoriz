<?php   
namespace App\Models;

use Illuminate\Database\Eloquent\Model;



/**
 * @OA\Schema(
 *      schema="Computer",
 *      type="object",
 *      @OA\Property(
 *          property="computertargetid",
 *          type="string",
 *          description="GUID компьютера",
 *      ),
 *      @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Наименование компьютера",
 *      ),
 *      @OA\Property(
 *          property="last_inventory_start",
 *          type="string",
 *          format="date",
 *          description="Дата и время начала последней инвентаризации",
 *      ),
 *      @OA\Property(
 *          property="last_inventory_end",
 *          type="string",
 *          format="date",
 *          description="Дата и время окончания последней инвентаризации",
 *      ),
 *      @OA\Property(
 *          property="last_inventory_index",
 *          type="integer",
 *          description="ИД последней инвентаризации",
 *      ),
 * )
 */
class Computer extends Model
{
    protected $table = "computers";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'computertargetid', 'name', 'last_inventory_start', 'last_inventory_end', 'last_inventory_index',
    ];

    // public $timestamps = false;


    public function properties()
    {
        return $this->hasMany(ComputerProperties::class, 'computer_id');
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
