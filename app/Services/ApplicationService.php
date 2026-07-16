<?php

namespace App\Services;

use App\Models\Application;
use App\Models\JobOffer;
use Illuminate\Validation\ValidationException;

class ApplicationService
{
    /**
     * Récupère les candidatures d'un candidat.
     */
    public function getForCandidate(int $candidateId)
    {
        // On charge l'offre d'emploi liée et son entreprise pour l'affichage
        return Application::with(['jobOffer.company'])
            ->where('candidate_id', $candidateId)
            ->latest()
            ->get();
    }

    /**
     * Récupère les candidatures reçues par une entreprise (sur toutes ses offres).
     */
    public function getForCompany(int $companyId)
    {
        // whereHas permet de filtrer selon une relation
        // Ici : on cherche les candidatures dont l'offre appartient à cette entreprise
        return Application::with(['candidate.user', 'jobOffer'])
            ->whereHas('jobOffer', function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->latest()
            ->get();
    }

    /**
     * Un candidat postule à une offre.
     */
    public function apply(int $candidateId, int $jobOfferId, ?string $coverLetter): Application
    {
        $jobOffer = JobOffer::findOrFail($jobOfferId);

        // 1. L'offre doit être ouverte
        if ($jobOffer->status !== 'open') {
            throw ValidationException::withMessages(['job_offer' => 'Cette offre n\'est plus ouverte aux candidatures.']);
        }

        // 2. Le candidat ne peut postuler qu'une seule fois
        $alreadyApplied = Application::where('candidate_id', $candidateId)
            ->where('job_offer_id', $jobOfferId)
            ->exists();

        if ($alreadyApplied) {
            throw ValidationException::withMessages(['job_offer' => 'Vous avez déjà postulé à cette offre.']);
        }

        // 3. Création de la candidature
        return Application::create([
            'candidate_id' => $candidateId,
            'job_offer_id' => $jobOfferId,
            'cover_letter' => $coverLetter,
        ]);
    }

    /**
     * Une entreprise met à jour le statut d'une candidature.
     */
    public function updateStatus(Application $application, string $status, int $companyId): Application
    {
        // Sécurité : Vérifier que l'entreprise est bien propriétaire de l'offre
        if ($application->jobOffer->company_id !== $companyId) {
            throw ValidationException::withMessages(['application' => 'Vous n\'avez pas les droits sur cette candidature.']);
        }

        $application->update(['status' => $status]);

        return $application;
    }
}
