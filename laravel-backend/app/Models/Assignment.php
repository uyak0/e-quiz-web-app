<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    use HasFactory;
    protected $table = 'assignments';
    protected $fillable = [
        'title',
        'description',
        'due_date',
        'files',
        'classroom_id',
        //'teacher_id' //column created for showing who created the assignments;
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function comments():HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    /*public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }*/
}
