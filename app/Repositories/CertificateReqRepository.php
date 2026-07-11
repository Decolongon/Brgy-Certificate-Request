<?php

namespace App\Repositories;

use App\Models\CertificateRequest;
use Illuminate\Database\Eloquent\Collection;

class CertificateReqRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function createCertificateRequest(array $data): CertificateRequest
    {

        return CertificateRequest::create($data);
    }

    public function updateCertificateRequest(array $data, CertificateRequest $certificateRequest): void
    {
        $certificateRequest->update($data);
    }

    public function getAllCertificateRequests(): Collection
    {
        if (auth()->user()->hasRole('resident')) {
            return $this->getCert(auth()->user()->id);
        }
        return $this->getCert();
    }

    private function getCert(int $id = null): Collection
    {
        return CertificateRequest::query()
            ->with(['brgyCertRequest:id,cert_name', 'requestBy:id,name,email'])
            ->when($id, function ($query, $id) {
                $query->where('requested_by', $id);
            })
            ->get([
                'id',
                'brgy_cert_id',
                'requested_by',
                'status',
                'pick_up_at',
                'purpose',
            ]);
    }
}
