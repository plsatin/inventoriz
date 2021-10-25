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


    public function wmiProperties()
    {
        return $this->hasMany(WmiProperty::class);
    }




}
