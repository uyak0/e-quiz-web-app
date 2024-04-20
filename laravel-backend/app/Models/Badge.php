<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Badge extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'condition'];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_badges')->using(StudentBadge::class);
    }
}
