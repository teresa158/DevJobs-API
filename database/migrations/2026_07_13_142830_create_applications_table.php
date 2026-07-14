<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table des candidatures : lien enrichi entre un candidat et une offre.
     * Ce n'est PAS une simple pivot car elle possède ses propres attributs
     * (cover_letter, status) et son propre cycle de vie.
     */
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();

            // Qui postule ? FK → candidates.id
            $table->foreignId('candidate_id')
                  ->constrained('candidates')
                  ->cascadeOnDelete();

            // À quelle offre ? FK → job_offers.id
            $table->foreignId('job_offer_id')
                  ->constrained('job_offers')
                  ->cascadeOnDelete();

            $table->text('cover_letter')->nullable(); // Lettre de motivation

            // Statut de la candidature
            $table->enum('status', ['pending', 'accepted', 'rejected'])
                  ->default('pending');

            $table->timestamps();

            // RÈGLE MÉTIER N°1 : un candidat ne peut postuler qu'une seule fois par offre
            $table->unique(['candidate_id', 'job_offer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
