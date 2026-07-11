<?php

namespace App\Concerns;

use App\Enums\CertReqEnum;
use Illuminate\Validation\Rule;

trait CertificateRequestValidationRules
{
    protected function validateCertificateRequest(): array
    {
        return [
            'brgy_cert_id' => [
                'required',
                'exists:brgy_certificates,id',
            ],
            'requested_by' => [
                'nullable',
                'exists:users,id',
            ],
            'status' => [
                'nullable',
                Rule::enum(CertReqEnum::class),
            ],
            'pick_up_at' => [
                'nullable',
                'date',
            ],
            'purpose' => [
                'required',
                'string',
                'max:255',
                'min:10',
            ],
        ];
    }
}
