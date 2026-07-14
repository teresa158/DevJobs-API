<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Profil étendu de l'entreprise — lié à un User (One-to-One).
     */
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            // FK → users.id : suppression en cascade si le user est supprimé
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('company_name');              // Raison sociale
            $table->string('siret', 14)->nullable();     // Numéro SIRET (14 chiffres)
            $table->string('website')->nullable();        // Site web
            $table->text('description')->nullable();      // Présentation de l'entreprise
            $table->string('address')->nullable();        // Adresse postale
            $table->string('city', 100)->nullable();      // Ville

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
