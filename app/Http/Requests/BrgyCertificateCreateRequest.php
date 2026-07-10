<?php

namespace App\Http\Requests;

use App\Concerns\BrgyCertificateValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class BrgyCertificateCreateRequest extends FormRequest
{
    use BrgyCertificateValidationRules;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->validateBrgyCert();
    }

    public function messages(): array
    {
        return $this->validateBrgyCertMessages();
    }
}
