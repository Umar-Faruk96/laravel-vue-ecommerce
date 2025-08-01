<?php

namespace App\Http\Requests;

use App\Enums\CustomerStatus;
use App\Models\Customer;
use Illuminate\Validation\Rule;
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
		$customer = $this->route('customer');
		return [
			'first_name' => ['required', 'string', 'min:3', 'max:50'],
			'last_name' => ['required', 'string', 'min:3', 'max:50'],
			'phone' => ['required', 'numeric', 'digits_between:7,13'],
			'email' => ['required', 'email', 'string', 'lowercase', 'max:255', Rule::unique(Customer::class)->ignore($customer->user_id, 'user_id')],
			'status' => ['required', 'boolean', /*Rule::enum(CustomerStatus::class)*/],
			
			'shippingAddress.house_number' => ['required', 'string'],
			'shippingAddress.area' => ['required', 'string'],
			'shippingAddress.city' => ['required', 'string'],
			'shippingAddress.state' => ['required', 'string'],
			'shippingAddress.zip_code' => ['required', 'string'],
			'shippingAddress.country_code' => ['required', 'exists:countries,code'],
			
			'billingAddress.house_number' => ['required', 'string'],
			'billingAddress.area' => ['required', 'string'],
			'billingAddress.city' => ['required', 'string'],
			'billingAddress.state' => ['required', 'string'],
			'billingAddress.zip_code' => ['required', 'string'],
			'billingAddress.country_code' => ['required', 'exists:countries,code'],
		];
	}
	
	public function attributes() : array
	{
		return [
			'billingAddress.house_number' => 'Billing House Number',
			'billingAddress.area' => 'Billing Area',
			'billingAddress.city' => 'Billing City',
			'billingAddress.state' => 'Billing State',
			'billingAddress.zip_code' => 'Billing Zip Code',
			'billingAddress.country_code' => 'Billing Country',
			'shippingAddress.house_number' => 'Shipping House Number',
			'shippingAddress.area' => 'Shipping Area',
			'shippingAddress.city' => 'Shipping City',
			'shippingAddress.state' => 'Shipping State',
			'shippingAddress.zip_code' => 'Shipping Zip Code',
			'shippingAddress.country_code' => 'Shipping Country',
		];
	}
}