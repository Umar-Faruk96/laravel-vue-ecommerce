<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize() : bool
	{
		return true;
	}
	
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules() : array
	{
		return [
			'name' => ['sometimes', 'required', 'string', 'min:3', 'max:55'],
			'email' => ['sometimes', 'required', 'email', 'string', 'lowercase', 'max:255', 'unique:users,email,' . $this->user->id],
			'password' => ['sometimes', 'required', 'confirmed', Password::min(8)->letters()->numbers()->symbols()],
			'password_confirmation' => ['required_with:password', Password::min(8)->letters()->numbers()->symbols()]
		];
	}
}