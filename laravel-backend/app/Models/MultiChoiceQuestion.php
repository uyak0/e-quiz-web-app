<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultiChoiceQuestion extends Model
{
    use HasFactory;
    protected $table = 'multi_choice_questions';
    protected $fillable = ['description', 'options', 'correct_answer'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
