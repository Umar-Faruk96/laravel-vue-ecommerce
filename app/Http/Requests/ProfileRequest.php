<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'phone' => ['required', 'max:11', 'min:11'],
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

    public function attributes()
    {
        return [
            'billing.house_number' => 'Billing Present Address',
            'billing.area' => 'Billing Permanent Address',
            'billing.city' => 'city',
            'billing.state' => 'state',
            'billing.zip_code' => 'zip code',
            'billing.country_code' => 'country',
            'shipping.house_number' => 'Shipping Present Address',
            'shipping.area' => 'Shipping Permanent Address',
            'shipping.city' => 'city',
            'shipping.state' => 'state',
            'shipping.zip_code' => 'zip code',
            'shipping.country_code' => 'country',
        ];
    }
}