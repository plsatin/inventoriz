<?php   
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function wimclass()
    {
        return $this->belongsTo(WmiClass::class, 'wmiclass_id');
    }

    public function wmiproperty()
    {
        return $this->belongsTo(WmiProperty::class, 'wmiproperty_id');
    }




}
