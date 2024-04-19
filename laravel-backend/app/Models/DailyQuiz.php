<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyQuiz extends Model
{
    use HasFactory;
    protected $table = 'daily_quizzes';
    protected $fillable = [
        'student_id',
        'quiz_id'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
