<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table PIVOT Many-to-Many entre job_offers et skills.
     * Une offre peut requérir plusieurs compétences.
     * Une compétence peut apparaître dans plusieurs offres.
     */
    public function up(): void
    {
        Schema::create('job_offer_skill', function (Blueprint $table) {
            // FK → job_offers.id
            $table->foreignId('job_offer_id')
                  ->constrained('job_offers')
                  ->cascadeOnDelete();

            // FK → skills.id
            $table->foreignId('skill_id')
                  ->constrained('skills')
                  ->cascadeOnDelete();

            // Clé primaire composite : empêche les doublons (même offre + même skill)
            $table->primary(['job_offer_id', 'skill_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_offer_skill');
    }
};
