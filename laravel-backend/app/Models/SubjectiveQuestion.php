<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectiveQuestion extends Model
{
    use HasFactory;
    protected $table = 'subjective_questions';
    protected $fillable = ['description', 'question_no', 'correct_answer'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
