<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model for the 'jobs' table (used for the email queue).
 */
class Job extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jobs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'queue',
        'payload',
        'attempts',
        'available_at',
        'created_at',
    ];

    /**
     * Indicates if the model should be timestamped.
     * (We set this to false because we manually handle created_at
     * and do not have an updated_at column).
     *
     * @var bool
     */
    public $timestamps = false;
}