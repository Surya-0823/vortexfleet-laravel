<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * Model for the 'students' table.
 */
class Student extends Model
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'students';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'photo_path',
        'app_username',
        'app_password',
        'route_name', // This is the foreign key
        'is_verified',
        'otp_code',
        'otp_expires_at',
        'otp_attempt_count',
        'otp_sent_count',
        'otp_last_sent_at',
        'otp_locked_until',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'app_password',
        'otp_code', // Hide sensitive OTP data
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'app_password' => 'hashed', // Automatically hash on save
            'is_verified' => 'boolean',
            'otp_expires_at' => 'datetime',
            'otp_last_sent_at' => 'datetime',
        ];
    }

    /**
     * Get all of the student's API tokens.
     * (This is the inverse of the polymorphic relation)
     */
    public function tokens()
    {
        return $this->morphMany(ApiToken::class, 'tokenable');
    }

    /**
     * Get the route that this student is assigned to.
     * (Links students.route_name to routes.name)
     */
    public function route()
    {
        // foreignKey: 'route_name' (on this Student model)
        // ownerKey: 'name' (on the related Route model)
        return $this->belongsTo(Route::class, 'route_name', 'name');
    }
}