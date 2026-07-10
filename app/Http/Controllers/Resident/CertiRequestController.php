<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Http\Requests\CertificateCreateRequest;
use App\Models\CertificateRequest;
use App\Repositories\BrgyCertRepository;
use App\Repositories\CertificateReqRepository;
use App\Services\CertificateReqService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class CertiRequestController extends Controller
{

    public function __construct(
        private readonly CertificateReqRepository $certificateReqRepository,
        private readonly BrgyCertRepository $brgyCertRepository,
        private readonly CertificateReqService $certificateReqService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', CertificateRequest::class);
        return Inertia::render('resident/CertificateRequest/Index', [
            'certificateRequests' => $this->certificateReqRepository->getAllCertificateRequests(),
            'brgyCertificates' => $this->brgyCertRepository->getBrgyCerts()
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

        return redirect()->route('resident.certificate-requests.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(CertificateRequest $certificateRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CertificateRequest $certificateRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CertificateRequest $certificateRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CertificateRequest $certificateRequest)
    {
        Gate::authorize('delete',$certificateRequest);
        $certificateRequest->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Certificate Request created successfully.')]);
        return redirect()->route('resident.certificate-requests.index');
    }
}
