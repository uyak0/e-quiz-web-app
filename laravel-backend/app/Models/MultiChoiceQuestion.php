<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MultiChoiceQuestion extends Model
{
    use HasFactory;
    protected $table = 'multi_choice_questions';
    protected $fillable = ['description', 'options', 'question_no', 'correct_answers'];
    protected $casts = ['options' => 'array'];
    public $timestamps = false;

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }
}
