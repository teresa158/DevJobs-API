<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Profil étendu du candidat — lié à un User (One-to-One).
     */
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();

            // FK → users.id : suppression en cascade si le user est supprimé
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->text('bio')->nullable();                    // Présentation du candidat
            $table->string('github_url')->nullable();           // Lien GitHub
            $table->string('portfolio_url')->nullable();        // Lien portfolio
            $table->unsignedInteger('years_experience')->default(0); // Années d'expérience

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
