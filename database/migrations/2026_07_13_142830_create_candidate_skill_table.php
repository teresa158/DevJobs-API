<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table PIVOT Many-to-Many entre candidates et skills.
     * Un candidat peut maîtriser plusieurs compétences.
     * Une compétence peut être maîtrisée par plusieurs candidats.
     */
    public function up(): void
    {
        Schema::create('candidate_skill', function (Blueprint $table) {
            // FK → candidates.id
            $table->foreignId('candidate_id')
                  ->constrained('candidates')
                  ->cascadeOnDelete();

            // FK → skills.id
            $table->foreignId('skill_id')
                  ->constrained('skills')
                  ->cascadeOnDelete();

            // Clé primaire composite : empêche les doublons (même candidat + même skill)
            $table->primary(['candidate_id', 'skill_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_skill');
    }
};
