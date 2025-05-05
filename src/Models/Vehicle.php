<?php
namespace MarcinJean\LaravelVPic\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $table = 'vpic_vehicles';
    protected $primaryKey = 'vin';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['vin', 'year', 'make', 'model', 'extra'];
    protected $casts = ['extra' => 'array'];
}
