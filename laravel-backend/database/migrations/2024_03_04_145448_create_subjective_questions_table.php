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
        Schema::create('subjective_questions', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->string('correct_answers')->nullable();
            $table->integer('question_no');
            $table->boolean('case_sensitive')->default(false);
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
