<?php

namespace App\Models;

use App\Models\BrgyCertificate;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(
    'brgy_cert_id',
    'requested_by',
    'status',
    'pick_up_at',

)]
class CertificateRequest extends Model
{
    public function brgyCertRequest(): BelongsTo
    {
        return $this->belongsTo(BrgyCertificate::class,'brgy_cert_id');
    }

    public function requestBy():BelongsTo
    {
        return $this->belongsTo(User::class,'requested_by');
    }

}
