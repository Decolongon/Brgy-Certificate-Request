<?php

namespace App\Models;

use App\Models\CertificateRequest;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(
    'cert_name',
    'slug',
    'cert_description',
)]
class BrgyCertificate extends Model
{
    public function requests(): HasMany
    {
        return $this->HasMany(CertificateRequest::class,'brgy_cert_id');
    }
}
