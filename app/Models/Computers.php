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
        'computertargetid', 'name', 'last_inventory_report',
    ];

    // public $timestamps = false;


    public function properties()
    {
        return $this->hasMany(ComputerProperties::class, 'computer_id');
    }


}
