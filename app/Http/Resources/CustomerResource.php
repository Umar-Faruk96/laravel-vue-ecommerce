<?php

namespace App\Http\Resources;

use App\Enums\CustomerStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
	public static $wrap = false;
	
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request) : array
	{
		$shipping = $this->shippingAddress;
		$billing = $this->billingAddress;
		
		return [
			'id' => $this->user_id,
			'first_name' => $this->first_name,
			'last_name' => $this->last_name,
			'email' => $this->email,
			'phone' => $this->phone,
			'status' => $this->status === CustomerStatus::Active->value,
			'shipping_address' => [
				'id' => $shipping?->id,
				'house_number' => $shipping?->house_number,
				'area' => $shipping?->area,
				'city' => $shipping?->city,
				'state' => $shipping?->state,
				'zip_code' => $shipping?->zip_code,
				'country_code' => $shipping?->country_code,
				'country' => $shipping?->country
			],
			'billing_address' => [
				'id' => $billing?->id,
				'house_number' => $billing?->house_number,
				'area' => $billing?->area,
				'city' => $billing?->city,
				'state' => $billing?->state,
				'zip_code' => $billing?->zip_code,
				'country_code' => $billing?->country_code,
				'country' => $billing?->country
			],
		];
	}
}