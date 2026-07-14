<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Insère toutes les compétences tech de référence.
     * On utilise insert() avec des données fixes (pas de Factory)
     * car les compétences sont connues à l'avance.
     */
    public function run(): void
    {
        $skills = [
            // Langages
            'PHP', 'JavaScript', 'TypeScript', 'Python', 'Java', 'Go', 'Rust',
            // Frameworks Backend
            'Laravel', 'Symfony', 'Node.js', 'Express.js', 'Django', 'Spring Boot',
            // Frameworks Frontend
            'Vue.js', 'React', 'Angular', 'Next.js', 'Nuxt.js',
            // Mobile
            'Flutter', 'React Native', 'Swift', 'Kotlin',
            // Base de données
            'MySQL', 'PostgreSQL', 'MongoDB', 'Redis', 'SQLite',
            // DevOps & Cloud
            'Docker', 'Kubernetes', 'AWS', 'Git', 'Linux', 'CI/CD',
            // Autres
            'REST API', 'GraphQL', 'Tailwind CSS', 'Bootstrap',
        ];

        foreach ($skills as $skill) {
            // firstOrCreate évite les doublons si on re-seed
            Skill::firstOrCreate(['name' => $skill]);
        }
    }
}
