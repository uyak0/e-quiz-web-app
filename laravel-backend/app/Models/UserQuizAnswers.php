<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuizAnswers extends Model
{
    use HasFactory;
    protected $table = 'user_quiz_answers';
    protected $fillable = [
        'user_answers',
        'rewarded',
        'quiz_id',
        'user_id',
    ];
}
