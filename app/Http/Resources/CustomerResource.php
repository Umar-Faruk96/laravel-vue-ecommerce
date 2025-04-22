<?php

namespace App\Http\Resources;

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
    public function toArray(Request $request): array
    {
        $shipping = $this->customer->shippingAddress;
        $billing = $this->customer->billingAddress;

        return [
            'id' => $this->customer->user_id,
            'first_name' => $this->customer->first_name,
            'last_name' => $this->customer->last_name,
            'email' => $this->customer->email,
            'phone' => $this->customer->phone,
            'shipping_address' => [
                'id' => $shipping->id,
                'house_number' => $shipping->house_number,
                'area' => $shipping->area,
                'city' => $shipping->city,
                'state' => $shipping->state,
                'zip_code' => $shipping->zip_code,
                'country' => [
                    'name' => $shipping->country->name,
                ],
            ],
            'billing_address' => [
                'id' => $billing->id,
                'house_number' => $billing->house_number,
                'area' => $billing->area,
                'city' => $billing->city,
                'state' => $billing->state,
                'zip_code' => $billing->zip_code,
                'country' => [
                    'name' => $billing->country->name,
                ],
            ],
        ];
    }
}
