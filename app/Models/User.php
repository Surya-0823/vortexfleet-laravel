<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Inga namma Hash facade-ah import panna theva illa, 'casts' eh paathukkum

/**
 * Model for the 'users' table (Admin Users).
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable; // HasApiTokens trait inga theva illa

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users'; // Make sure table name is correct

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone', // Added from old project
        'institution_name', // Added from old project
        'subscription_plan', // Added from old project
        'subscription_type', // Added from old project
        'payment_amount', // Added from old project
        'payment_status', // Added from old project
        'status', // Added from old project
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // Automatically hashes password on create/update
        ];
    }

    // === Palaya Project-la irunthu migrate panna STATIC METHODS ===
    // (Aana ippo namma Eloquent use panrom)

    /**
     * Find a user by their email address.
     *
     * @param string $email
     * @return \App\Models\User|null
     */
    public static function findByEmail($email)
    {
        // Use Eloquent's 'where' method
        return self::where('email', $email)->first();
    }

    /**
     * Find a user by their ID.
     *
     * @param int $id
     * @return \App\Models\User|null
     */
    public static function findById($id)
    {
        // Use Eloquent's 'find' method
        return self::find($id);
    }

    /**
     * Create a new admin user.
     *
     * @param array $data
     * @return int The ID of the new user
     */
    public static function createUser(array $data)
    {
        // Note: Password hashing is now AUTOMATIC
        // The 'password_hash' line is removed
        // because of the 'password' => 'hashed' in casts() method.
        
        $user = self::create($data);
        return $user->id; // Return the new user's ID
    }

    /**
     * Update payment status for a user (simulated).
     *
     * @param int $userId
     * @param array $paymentData
     * @return bool
     */
    public static function updatePayment($userId, $paymentData)
    {
        $user = self::find($userId);
        if ($user) {
            $user->payment_status = 'completed'; // Mark as completed
            $user->payment_amount = $paymentData['amount'];
            $user->subscription_type = $paymentData['subscription_type'];
            $user->status = 'active'; // Activate the user
            return $user->save(); // Returns true on success
        }
        return false;
    }
}