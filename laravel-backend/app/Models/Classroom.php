<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    use HasFactory;
    protected $table = 'classrooms';
    protected $fillable = ['name', 'description', 'code', 'type','max_members'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_classrooms');
    }

    public function students()
    {
        return $this->through('users')->has('student');
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function getMemberCountAttribute()  //dynamic member count
    {
        return $this->users()->count();
    }
}
