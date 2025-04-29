<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
			'first_name' => ['required', 'string', 'min:3', 'max:50'],
			'last_name' => ['required', 'string', 'min:3', 'max:50'],
			'phone' => ['required', 'numeric', 'digits_between:7,11'],
			'email' => ['required', 'email'],
			
			'shipping.house_number' => ['required'],
			'shipping.area' => ['required'],
			'shipping.city' => ['required'],
			'shipping.state' => ['required'],
			'shipping.zip_code' => ['required'],
			'shipping.country_code' => ['required', 'exists:countries,code'],
			
			'billing.house_number' => ['required'],
			'billing.area' => ['required'],
			'billing.city' => ['required'],
			'billing.state' => ['required'],
			'billing.zip_code' => ['required'],
			'billing.country_code' => ['required', 'exists:countries,code'],
		
		];
	}
	
	public function attributes() : array
	{
		return [
			'billing.house_number' => 'Billing Present Address',
			'billing.area' => 'Billing Permanent Address',
			'billing.city' => 'Billing City',
			'billing.state' => 'Billing State',
			'billing.zip_code' => 'Billing Zip Code',
			'billing.country_code' => 'Billing Country',
			'shipping.house_number' => 'Shipping Present Address',
			'shipping.area' => 'Shipping Permanent Address',
			'shipping.city' => 'Shipping City',
			'shipping.state' => 'Shipping State',
			'shipping.zip_code' => 'Shipping Zip Code',
			'shipping.country_code' => 'Shipping Country',
		];
	}
}