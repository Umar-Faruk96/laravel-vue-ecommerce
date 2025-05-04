<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class CreateUserRequest extends FormRequest
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
			'name' => ['required', 'string', 'min:3', 'max:50'],
			'email' => ['required', 'email', 'string', 'lowercase', 'max:255', 'unique:users'],
			// 'password' => ['required', Password::min(8)->letters()->numbers()->symbols()],
			'password' => ['required', 'confirmed', Password::min(8)],
			'password_confirmation' => ['required_with:password', Password::min(8)]
		];
	}
}