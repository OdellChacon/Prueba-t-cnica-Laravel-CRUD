<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProviderRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
			'name'  => ['required', 'string', 'min:3'],
			'email' => ['nullable', 'email'],
			'phone' => ['nullable', 'string', 'max:30'],
		];
	}
}
