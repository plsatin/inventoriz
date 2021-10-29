<?php   
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
