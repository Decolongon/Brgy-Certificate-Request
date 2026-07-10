<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrgyCertificateCreateRequest;
use App\Http\Requests\BrgyCertificateUpdateRequest;
use App\Models\BrgyCertificate;
use App\Repositories\BrgyCertRepository;
use App\Services\BrgyCertService;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class BrgyCertificateController extends Controller
{

    public function __construct(private readonly BrgyCertService $brgyService, private readonly BrgyCertRepository $brgyRepository) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', BrgyCertificate::class);

        return Inertia::render('admin/brgyCertificate/Index', [
            'certificates' => $this->brgyRepository->getBrgyCerts()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', BrgyCertificate::class);

        return Inertia::render('admin/brgyCertificate/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrgyCertificateCreateRequest $request)
    {
        Gate::authorize('create', BrgyCertificate::class);

        $this->brgyService->brgyCertCreate($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Certificate created successfully.')]);
        return redirect()->route('brgy-certificates.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(BrgyCertificate $brgyCertificate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BrgyCertificate $brgyCertificate)
    {
        Gate::authorize('update', $brgyCertificate);

        return Inertia::render('admin/brgyCertificate/Edit', [
            'certificate' => $brgyCertificate
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrgyCertificateUpdateRequest $request, BrgyCertificate $brgyCertificate)
    {
        Gate::authorize('update', $brgyCertificate);

        $this->brgyService->brgyCertUpdate($brgyCertificate, $request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Certificate updated successfully.')]);
        return redirect()->route('brgy-certificates.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BrgyCertificate $brgyCertificate)
    {
        Gate::authorize('delete', $brgyCertificate);
        $brgyCertificate->delete();
        Inertia::flash('toast', ['type' => 'success', 'message' => __('Certificate deleted successfully.')]);

        return redirect()->route('brgy-certificates.index');
    }
}
