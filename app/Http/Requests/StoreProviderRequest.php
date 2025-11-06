<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProviderRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:providers,email',
            'phone' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ];
    }
}
