<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;
    protected $table = 'quizzes';
    protected $fillable = ['title', 'due_date'];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function multiChoiceQuestions()
    {
        return $this->hasMany(MultiChoiceQuestion::class);
    }

    public function subjectiveQuestions()
    {
        return $this->hasMany(SubjectiveQuestion::class);
    }

    public function trueFalseQuestions()
    {
        return $this->hasMany(TrueFalseQuestion::class);
    }
}
