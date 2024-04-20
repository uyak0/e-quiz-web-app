<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class StudentBadge extends Pivot
{
    use HasFactory;
    protected $table = 'student_badges';
    protected $fillable = ['student_id', 'badge_id'];
}
