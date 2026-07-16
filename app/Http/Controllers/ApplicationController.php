<?php

namespace App\Http\Controllers;

use App\Http\Requests\Application\StoreApplicationRequest;
use App\Http\Requests\Application\UpdateApplicationStatusRequest;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Services\ApplicationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApplicationController extends Controller
{
    public function __construct(
        private ApplicationService $applicationService
    ) {}

    /**
     * GET /api/applications
     * Liste les candidatures intelligemment (selon qui est connecté).
     */
    public function index(Request $request)
    {
        if ($request->user()->isCandidate()) {
            $applications = $this->applicationService->getForCandidate($request->user()->candidate->id);
        } elseif ($request->user()->isCompany()) {
            $applications = $this->applicationService->getForCompany($request->user()->company->id);
        } else {
            return response()->json(['message' => 'Action non autorisée.'], 403);
        }

        return ApplicationResource::collection($applications);
    }

    /**
     * POST /api/job-offers/{jobOfferId}/apply
     * Un candidat postule.
     */
    public function store(StoreApplicationRequest $request, int $jobOfferId): JsonResponse
    {
        $candidateId = $request->user()->candidate->id;

        $application = $this->applicationService->apply(
            $candidateId,
            $jobOfferId,
            $request->validated('cover_letter')
        );

        return (new ApplicationResource($application))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * PUT /api/applications/{application}/status
     * L'entreprise met à jour le statut.
     */
    public function update(UpdateApplicationStatusRequest $request, Application $application): ApplicationResource
    {
        $companyId = $request->user()->company->id;

        $application = $this->applicationService->updateStatus(
            $application, 
            $request->validated('status'),
            $companyId
        );

        return new ApplicationResource($application);
    }
}
