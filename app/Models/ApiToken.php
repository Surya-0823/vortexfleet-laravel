<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model for the 'api_tokens' table.
 * This handles polymorphic relationships for API authentication.
 */
class ApiToken extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'api_tokens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'token',
        'tokenable_id',   // Stores the ID of the Driver or Student
        'tokenable_type', // Stores 'App\Models\Driver' or 'App\Models\Student'
        'expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'token', // The token itself should be hidden when serialized
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Get the parent tokenable model (Driver or Student).
     *
     * This is the polymorphic relationship.
     * It will automatically fetch the Driver or Student model
     * based on 'tokenable_type' and 'tokenable_id'.
     */
    public function tokenable()
    {
        return $this->morphTo();
    }
}