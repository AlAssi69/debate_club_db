<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('debate_person', function (Blueprint $table) {
            $table->id();
            $table->foreignId('debate_id')->constrained('debates')->cascadeOnDelete();
            $table->foreignId('person_id')->constrained('persons')->cascadeOnDelete();
            $table->string('role');
            $table->timestamps();

            $table->unique(['debate_id', 'person_id', 'role']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('debate_person');
    }
};
