<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('true_false_questions', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->boolean('correct_answer');
            $table->integer('question_no');
            $table->foreignId('quiz_id')->references('id')->on('quizzes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
