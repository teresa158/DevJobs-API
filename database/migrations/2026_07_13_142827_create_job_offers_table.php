<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Offres d'emploi publiées par les entreprises.
     * Utilise SoftDeletes pour archiver au lieu de supprimer physiquement.
     */
    public function up(): void
    {
        Schema::create('job_offers', function (Blueprint $table) {
            $table->id();

            // FK → companies.id : suppression en cascade si l'entreprise est supprimée
            $table->foreignId('company_id')
                  ->constrained('companies')
                  ->cascadeOnDelete();

            $table->string('title');                     // Intitulé du poste
            $table->text('description');                  // Description détaillée
            $table->unsignedInteger('salary_min')->nullable(); // Salaire min (€/an)
            $table->unsignedInteger('salary_max')->nullable(); // Salaire max (€/an)
            $table->string('location');                   // Ville ou "remote"

            // Type de contrat limité aux valeurs autorisées
            $table->enum('contract_type', ['CDI', 'CDD', 'freelance', 'internship']);

            // Statut de l'offre : open (visible) ou closed (fermée)
            $table->enum('status', ['open', 'closed'])->default('open');

            // Soft Delete : marque deleted_at au lieu de supprimer la ligne
            $table->softDeletes();

            $table->timestamps();

            // Index sur les colonnes fréquemment utilisées dans les filtres/recherches
            $table->index('status');
            $table->index('contract_type');
            $table->index('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_offers');
    }
};
