<?php   
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
