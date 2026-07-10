<?php

namespace App\Services;

use App\Models\BrgyCertificate;
use App\Repositories\BrgyCertRepository;
use Illuminate\Support\Str;

class BrgyCertService
{
    /**
     * Create a new class instance.
     */
    public function __construct(private readonly BrgyCertRepository $brgyCertRepository) {}

    public function brgyCertCreate(array $data): BrgyCertificate
    {
        $data['slug'] = Str::slug($data['cert_name']);
        return $this->brgyCertRepository->brgyCertCreate($data);
    }

    public function brgyCertUpdate(BrgyCertificate $brgyCertificate, array $data): void
    {
        $this->brgyCertRepository->brgyCertUpdate($brgyCertificate, $data);
    }
}
