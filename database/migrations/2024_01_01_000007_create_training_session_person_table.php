<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_session_person', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_session_id')->constrained('training_sessions')->cascadeOnDelete();
            $table->foreignId('person_id')->constrained('persons')->cascadeOnDelete();
            $table->string('role');
            $table->string('status')->nullable();
            $table->timestamps();

            $table->unique(['training_session_id', 'person_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_session_person');
    }
};
