<?php

namespace App\Repositories;

use App\Models\BrgyCertificate;
use Illuminate\Database\Eloquent\Collection;

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

    public function getBrgyCerts(): Collection
    {
        return BrgyCertificate::query()
            ->get([
                'id',
                'cert_name',
                'slug',
                'cert_description'
            ]);
    }
}
