<?php

namespace App\Services;

use App\Models\CertificateRequest;
use App\Models\User;
use App\Repositories\CertificateReqRepository;
use Illuminate\Database\Eloquent\Collection;

class CertificateReqService
{
    /**
     * Create a new class instance.
     */
    public function __construct(private readonly CertificateReqRepository $certificateReqRepository)
    {
        //
    }

    public function createCertificateRequest(array $data): CertificateRequest
    { 
        if(auth()->user()->hasRole('resident')) {
            $data['requested_by'] = auth()->id();
        }
        return $this->certificateReqRepository->createCertificateRequest($data);
    }

    public function updateCertificateRequest(array $data, CertificateRequest $certificateRequest): void
    {
        $this->certificateReqRepository->updateCertificateRequest($data, $certificateRequest);
    }

    public function getResidents(): Collection
    {
        return User::query()
            ->get([
                'id',
                'name'
            ]);
    }
}
