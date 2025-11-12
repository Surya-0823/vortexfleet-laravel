<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model for the 'buses' table.
 */
class Bus extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'buses';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id'; // 'id' thaan primary key

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'plate', // 'plate' thaan logical key, aana 'id' thaan primary key
        'capacity',
        'photo_path',
        'current_lat',
        'current_lon',
    ];

    /**
     * Get the route assigned to this bus.
     * (Links buses.plate to routes.bus_plate)
     */
    public function route()
    {
        // foreignKey: 'bus_plate' (on the related Route model)
        // localKey: 'plate' (on this Bus model)
        return $this->hasOne(Route::class, 'bus_plate', 'plate');
    }

    /**
     * Get the driver assigned to this bus.
     * (Links buses.plate to drivers.bus_plate)
     */
    public function driver()
    {
        // foreignKey: 'bus_plate' (on the related Driver model)
        // localKey: 'plate' (on this Bus model)
        return $this->hasOne(Driver::class, 'bus_plate', 'plate');
    }
}