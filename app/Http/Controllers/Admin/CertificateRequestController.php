<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CertificateCreateRequest;
use App\Http\Requests\CertificateUpdateRequest;
use App\Models\CertificateRequest;
use App\Repositories\BrgyCertRepository;
use App\Repositories\CertificateReqRepository;
use App\Services\CertificateReqService;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class CertificateRequestController extends Controller
{
    public function __construct(
        private readonly CertificateReqService $certificateReqService,
        private readonly CertificateReqRepository $certificateReqRepository,
        private readonly BrgyCertRepository $brgyCertRepository
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', CertificateRequest::class);

        return Inertia::render('admin/CertificateRequest/Index', [
            'certificateRequests' => $this->certificateReqRepository->getAllCertificateRequests(),
            'certificates' => $this->brgyCertRepository->getBrgyCerts(),
            'brgyResidents' => $this->certificateReqService->getResidents(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CertificateCreateRequest $request)
    {
        Gate::authorize('create', CertificateRequest::class);
        $this->certificateReqService->createCertificateRequest($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Certificate Request created successfully.')]);

        return redirect()->route('certificate-requests.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(CertificateRequest $certificateRequest)
    {
        Gate::authorize('view', $certificateRequest);
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CertificateRequest $certificateRequest)
    {
        Gate::authorize('update', $certificateRequest);
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CertificateUpdateRequest $request, CertificateRequest $certificateRequest)
    {
        Gate::authorize('update', $certificateRequest);
        $this->certificateReqService->updateCertificateRequest($request->validated(), $certificateRequest);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Certificate Request updated successfully.')]);

        return redirect()->route('certificate-requests.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CertificateRequest $certificateRequest)
    {
        Gate::authorize('delete', $certificateRequest);
        $certificateRequest->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Certificate Request deleted successfully.')]);

        return redirect()->route('certificate-requests.index');
    }
}
