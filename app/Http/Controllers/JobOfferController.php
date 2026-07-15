<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobOffer\StoreJobOfferRequest;
use App\Http\Requests\JobOffer\UpdateJobOfferRequest;
use App\Http\Resources\JobOfferResource;
use App\Models\JobOffer;
use App\Services\JobOfferService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class JobOfferController extends Controller
{
    public function __construct(
        private JobOfferService $jobOfferService
    ) {}

    /**
     * GET /api/job-offers
     * Liste toutes les offres ouvertes (accessible à tous les users connectés).
     */
    public function index(): AnonymousResourceCollection
    {
        $jobOffers = $this->jobOfferService->listOpen();

        // ::collection() → retourne un tableau JSON de JobOfferResource
        return JobOfferResource::collection($jobOffers);
    }

    /**
     * POST /api/job-offers
     * Créer une offre (réservé aux entreprises via StoreJobOfferRequest::authorize).
     */
    public function store(StoreJobOfferRequest $request): JsonResponse
    {
        // Récupérer l'id de la company du user connecté
        $companyId = $request->user()->company->id;

        $jobOffer = $this->jobOfferService->create($request->validated(), $companyId);

        return (new JobOfferResource($jobOffer))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * GET /api/job-offers/{jobOffer}
     * Voir une offre spécifique.
     */
    public function show(int $id): JobOfferResource
    {
        $jobOffer = $this->jobOfferService->findById($id);

        return new JobOfferResource($jobOffer);
    }

    /**
     * PUT /api/job-offers/{jobOffer}
     * Modifier une offre (entreprise propriétaire ou admin).
     */
    public function update(UpdateJobOfferRequest $request, JobOffer $jobOffer): JobOfferResource
    {
        $jobOffer = $this->jobOfferService->update($jobOffer, $request->validated());

        return new JobOfferResource($jobOffer);
    }

    /**
     * DELETE /api/job-offers/{jobOffer}
     * Supprimer une offre (Soft Delete).
     */
    public function destroy(JobOffer $jobOffer): JsonResponse
    {
        $this->jobOfferService->delete($jobOffer);

        return response()->json([
            'message' => 'Offre supprimée avec succès.',
        ]);
    }
}
