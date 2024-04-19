<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use CanResetPassword, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mode',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function classrooms(): BelongsToMany
    {
        return $this->belongsToMany(Classroom::class, 'user_classrooms');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class);
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function unseenMessages()
    {
        return $this->hasMany(Chat::class, 'sender_id')
                ->where('seen', 1)
                ->where('receiver_id', auth()->user()->id);
    }

    public static function modeValidationRules()
    {
        return [
            'mode' => 'required|string|in:normal,do not disturb,invisible',
        ];
    }

    public function setModeAttribute($value)
    {
        if (in_array($value, ['normal', 'do not disturb', 'invisible'])) {
            $this->attributes['mode'] = $value;
        } else {
            // Handle invalid mode value, e.g., set to default or throw an exception
            $this->attributes['mode'] = 'normal'; // Default value
        }
    }

    public function scopeOfMode($query, $mode)
    {
        return $query->where('mode', $mode);
    }
}
