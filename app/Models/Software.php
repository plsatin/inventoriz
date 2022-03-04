<?php   
namespace App\Models;

use Illuminate\Database\Eloquent\Model;



/**
 * @OA\Schema(
 *      schema="Software",
 *      type="object",
 *      @OA\Property(
 *          property="computer_id",
 *          type="string",
 *          description="ИД компьютера",
 *      ),
 *      @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Наименование ПО",
 *      ),
 *      @OA\Property(
 *          property="version",
 *          type="string",
 *          description="Версия ПО",
 *      ),
 *      @OA\Property(
 *          property="vendor",
 *          type="string",
 *          description="Производитель ПО",
 *      ),
 *      @OA\Property(
 *          property="install_date",
 *          type="string",
 *          format="date",
 *          description="Дата установки",
 *      ),
 *      @OA\Property(
 *          property="identifying_number",
 *          type="string",
 *          description="Уникальный идентификатор",
 *      ),
 *      @OA\Property(
 *          property="uninstall_string",
 *          type="string",
 *          description="Строка команды на удаление",
 *      ),
 * )
 */
class Software extends Model
{
    protected $table = "software";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'computer_id', 'name', 'version', 'vendor', 'install_date', 'identifying_number', 'uninstall_string'
    ];





    public function computer()
    {
        return $this->belongsTo(Computer::class, 'computer_id');
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
