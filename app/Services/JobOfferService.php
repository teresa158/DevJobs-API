<?php

namespace App\Services;

use App\Models\JobOffer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class JobOfferService
{
    /**
     * Liste toutes les offres ouvertes disponibles.
     * with() → Eager Loading : charge les relations en une seule requête SQL
     *          au lieu de faire une requête par offre (N+1 Problem)
     * Cette classe JobOfferService regroupe toutes les actions (la logique métier) qu'on peut effectuer sur les offres d'emploi.
     */
    public function listOpen(int $perPage = 15): LengthAwarePaginator
    {
        return JobOffer::with(['company', 'skills'])
            ->where('status', 'open')
            ->latest() // trie par created_at DESC (les plus récentes en premier)
            ->paginate($perPage);
    }

    /**
     * Récupérer une offre avec ses relations.
     */
    public function findById(int $id): JobOffer
    {
        return JobOffer::with(['company', 'skills'])->findOrFail($id);
        // findOrFail → si l'id n'existe pas → 404 automatiquement
    }

    /**
     * Créer une offre d'emploi.
     * $companyId est passé par le Controller (récupéré depuis le user connecté).
     */
    public function create(array $data, int $companyId): JobOffer
    {
        // 1. Créer l'offre
        $jobOffer = JobOffer::create([
            'company_id'    => $companyId,
            'title'         => $data['title'],
            'description'   => $data['description'],
            'salary_min'    => $data['salary_min'] ?? null,
            'salary_max'    => $data['salary_max'] ?? null,
            'location'      => $data['location'],
            'contract_type' => $data['contract_type'],
            'status'        => $data['status'] ?? 'open',
        ]);

        // 2. Attacher les skills si fournies
        if (!empty($data['skills'])) {
            // sync() → attache les skills fournies et détache les autres
            $jobOffer->skills()->sync($data['skills']);
        }

        // 3. Recharger les relations pour la réponse
        return $jobOffer->load(['company', 'skills']);
    }

    /**
     * Modifier une offre.
     */
    public function update(JobOffer $jobOffer, array $data): JobOffer
    {
        $jobOffer->update($data);

        if (isset($data['skills'])) {
            $jobOffer->skills()->sync($data['skills']);
        }

        return $jobOffer->load(['company', 'skills']);
    }

    /**
     * Supprimer une offre (Soft Delete grâce à SoftDeletes dans le modèle).
     */
    public function delete(JobOffer $jobOffer): void
    {
        $jobOffer->delete(); // → met deleted_at = now(), ne supprime pas vraiment
    }
}
