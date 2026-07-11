<?php

namespace App\Repositories;

use App\Models\BrgyCertificate;
use Illuminate\Support\Facades\Cache;

class BrgyCertRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function brgyCertCreate(array $data): BrgyCertificate
    {
        return BrgyCertificate::create($data);
    }

    public function brgyCertUpdate(BrgyCertificate $brgyCertificate, array $data): void
    {
        $brgyCertificate->update($data);
    }

    public function getBrgyCerts(): array
    {
        return Cache::rememberForever(BrgyCertificate::BRGY_CERTIFICATE_CACHE_KEY, function () {
            return BrgyCertificate::query()
                ->get([
                    'id',
                    'cert_name',
                    'slug',
                    'cert_description'
                ])
                ->toArray();
        });
    }
}
