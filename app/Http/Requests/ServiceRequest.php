<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
	public function authorize() { return true; }

	public function rules()
	{
		return [
			'provider_id' => ['required','exists:providers,id'],
			'name' => ['required','string'],
			'duration_minutes' => ['required','integer','min:1'],
			'price' => ['required','numeric','min:0'],
			'description' => ['nullable','string'],
		];
	}
}
