<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('person_role', function (Blueprint $table) {
            $table->foreignId('person_id')->constrained('persons')->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->timestamps();

            $table->primary(['person_id', 'role_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('person_role');
    }
};
