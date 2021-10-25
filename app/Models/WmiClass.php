<?php   
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
