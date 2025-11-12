<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model for the 'routes' table.
 */
class Route extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'routes';

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
        'name', // 'name' thaan logical key, aana 'id' thaan primary key
        'start',
        'end',
        'bus_plate', // This is the foreign key
    ];

    /**
     * Get the bus assigned to this route.
     * (Links routes.bus_plate to buses.plate)
     */
    public function bus()
    {
        // foreignKey: 'bus_plate' (on this Route model)
        // ownerKey: 'plate' (on the related Bus model)
        return $this->belongsTo(Bus::class, 'bus_plate', 'plate');
    }

    /**
     * Get all students assigned to this route.
     * (Links routes.name to students.route_name)
     */
    public function students()
    {
        // foreignKey: 'route_name' (on the related Student model)
        // localKey: 'name' (on this Route model)
        return $this->hasMany(Student::class, 'route_name', 'name');
    }
}