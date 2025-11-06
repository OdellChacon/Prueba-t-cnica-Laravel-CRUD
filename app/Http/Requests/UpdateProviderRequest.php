<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProviderRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        $providerId = $this->route('provider')?->id;
        return [
            'name' => 'required|string|max:255',
            'email' => ['nullable','email', Rule::unique('providers','email')->ignore($providerId)],
            'phone' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ];
    }
}
