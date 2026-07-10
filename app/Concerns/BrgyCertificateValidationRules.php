<?php

namespace App\Concerns;

use Illuminate\Validation\Rule;

trait BrgyCertificateValidationRules
{
    protected function validateBrgyCert(): array
    {
        // Get the certificate ID from the URL (e.g., /brgy-certificates/{id})
        $brgyCertificate = $this->route('brgy_certificate');

        return [
            'cert_name' => [
                'required',
                Rule::unique('brgy_certificates')->ignore($brgyCertificate ? $brgyCertificate->id : null),
            ],
            'cert_description' => [
                'nullable',
                'min:10',
                'max:1000',
            ]
        ];
    }

    protected function validateBrgyCertMessages(): array
    {
        return [
            'cert_name.required' => 'The certificate name is required.',
            'cert_name.unique' => 'The certificate name has already been taken.',
            'cert_description.min' => 'The certificate description must be at least :min characters.',
            'cert_description.max' => 'The certificate description may not be greater than :max characters.',
        ];
    }
}
