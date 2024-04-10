<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubjectiveQuestion extends Model
{
    use HasFactory;
    protected $table = 'subjective_questions';
    protected $fillable = ['description', 'question_no', 'correct_answers', 'case_sensitive'];
    public $timestamps = false;

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }
}
