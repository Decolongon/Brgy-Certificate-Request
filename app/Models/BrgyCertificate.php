<?php

namespace App\Models;

use App\Models\CertificateRequest;
use App\Observers\BrgyCertificateObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy(BrgyCertificateObserver::class)]
#[Fillable(
    'cert_name',
    'slug',
    'cert_description',
)]
class BrgyCertificate extends Model
{
    public const BRGY_CERTIFICATE_CACHE_KEY = 'brgy_certificates';

    public function requests(): HasMany
    {
        return $this->HasMany(CertificateRequest::class,'brgy_cert_id');
    }
}
