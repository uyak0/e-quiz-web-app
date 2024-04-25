<?php

namespace App\Models;

use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Quiz extends Model
{
    use HasFactory;
    protected $table = 'quizzes';
    protected $fillable = ['classroom_id', 'title', 'due_date'];

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
