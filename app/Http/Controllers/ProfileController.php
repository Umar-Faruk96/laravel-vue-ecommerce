<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\View\View;
use App\Enums\AddressType;
use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $customer = $user->customer;
        // dd($customer);
        $shippingAddress = $customer->shippingAddress ?: new CustomerAddress(['type' => AddressType::Shipping]);
        $billingAddress = $customer->billingAddress ?: new CustomerAddress(['type' => AddressType::Billing]);
        $countries = Country::query()->orderBy('name')->get();

        return view('profile.show', compact('user', 'customer', 'shippingAddress', 'billingAddress', 'countries'));
    }

    public function store(ProfileRequest $request)
    {
        $customerData = $request->validated();
        $shippingData = $customerData['shipping'];
        $billingData = $customerData['billing'];

        // dd($shippingData, $billingData);

        $customer = $request->user()->customer;
        $customer->update($customerData);

        $customer->shippingAddress()->updateOrCreate(['customer_id' => $customer->user_id, 'type' => AddressType::Shipping], $shippingData);
        $customer->billingAddress()->updateOrCreate(['customer_id' => $customer->user_id, 'type' => AddressType::Billing], $billingData);

        /* if ($customer->shippingAddress) {
            $customer->shippingAddress->update($shippingData);
        } else {
            $shippingData['customer_id'] = $customer->user_id;
            $shippingData['type'] = AddressType::Shipping->value;
            CustomerAddress::create($shippingData);
        }
        if ($customer->billingAddress) {
            $customer->billingAddress->update($billingData);
        } else {
            $billingData['customer_id'] = $customer->user_id;
            $billingData['type'] = AddressType::Billing->value;
            CustomerAddress::create($billingData);
        } */

        $request->session()->flash('profile_message', 'Your profile is successfully updated');

        return Redirect::route('profile.show');
    }

    /**
     * Display the user's profile form.
     */
    /* public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    } */

    /**
     * Update the user's profile information.
     */
    /* public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    } */

    /**
     * Delete the user's account.
     */
    /* public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    } */
}
