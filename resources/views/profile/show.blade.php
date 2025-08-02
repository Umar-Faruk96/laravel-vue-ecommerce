<x-app-layout>
    <div x-data="{
        flashMessage: '{{ \Illuminate\Support\Facades\Session::get('profile_message') }}',
        init() {
            if (this.flashMessage) {
                setTimeout(() => this.$dispatch('notify', { message: this.flashMessage }), 200)
            }
        }
    }" class="container mx-auto p-5 lg:w-2/3">
        <div class="grid grid-cols-1 items-start gap-6 md:grid-cols-3">
            <div class="rounded-lg bg-white p-3 shadow md:col-span-2">
                @session('errors')
                <div class="mb-4 rounded-md bg-red-500 text-gray-100 p-3">
                    <p>{{ session('errors')->first() }}</p>
                </div>
                @endsession

                <form x-data="{
                    countries: {{ json_encode($countries) }},

                    billingAddress: {{ json_encode([
                        'house_number' => old('billing.house_number', $billingAddress->house_number ?? null),
                        'area' => old('billing.area', $billingAddress->area ?? null),
                        'city' => old('billing.city', $billingAddress->city ?? null),
                        'state' => old('billing.state', $billingAddress->state ?? null),
                        'country_code' => old('billing.country_code', $billingAddress->country_code ?? null),
                        'zip_code' => old('billing.zip_code', $billingAddress->zip_code ?? null),
                    ]) }},

                    shippingAddress: {{ json_encode([
                        'house_number' => old('shipping.house_number', $shippingAddress->house_number ?? null),
                        'area' => old('shipping.area', $shippingAddress->area ?? null),
                        'city' => old('shipping.city', $shippingAddress->city ?? null),
                        'state' => old('shipping.state', $shippingAddress->state ?? null),
                        'country_code' => old('shipping.country_code', $shippingAddress->country_code ?? null),
                        'zip_code' => old('shipping.zip_code', $shippingAddress->zip_code ?? null),
                    ]) }},

                    get billingCountryStates() {
                        const country = this.countries.find(c => c.code === this.billingAddress.country_code)
                        if (country && country.states) {
                            return JSON.parse(country.states);
                        }
                        return null;
                    },

                    get shippingCountryStates() {
                        const country = this.countries.find(c => c.code === this.shippingAddress.country_code)
                        if (country && country.states) {
                            return JSON.parse(country.states);
                        }
                        return null;
                    }
                }" action="{{ route('profile.update') }}" method="post">
                    @csrf
                    <h2 class="mb-2 text-xl font-semibold">Profile Details</h2>

                    <div class="mb-3 grid grid-cols-2 gap-3">
                        <x-text-input name="first_name" value="{{ old('first_name', $customer->first_name ?? null) }}"
                                      placeholder="First Name"/>

                        <x-text-input name="last_name" value="{{ old('last_name', $customer->last_name ?? null) }}"
                                      placeholder="Last Name"/>
                    </div>

                    <div class="mb-3">
                        <x-text-input type="email" name="email" value="{{ old('email', $customer->email ?? null) }}"
                                      placeholder="Your Email"/>
                    </div>

                    <div>
                        <x-text-input type="tel" name="phone" value="{{ old('phone', $customer->phone ?? null) }}"
                                      placeholder="Your Phone"/>
                    </div>

                    <h2 class="mb-2 mt-6 text-xl font-semibold">Billing Address</h2>

                    <div class="mb-3 grid grid-cols-2 gap-3">
                        <div>
                            <x-text-input name="billing[house_number]" x-model="billingAddress.house_number"
                                          placeholder="Address 1"/>
                        </div>

                        <div>
                            <x-text-input name="billing[area]" x-model="billingAddress.area" placeholder="Address 2"/>
                        </div>
                    </div>

                    <div class="mb-3 grid grid-cols-2 gap-3">
                        <div>
                            <x-text-input name="billing[city]" x-model="billingAddress.city" placeholder="City"/>
                        </div>

                        <div>
                            <x-text-input name="billing[zip_code]" x-model="billingAddress.zip_code"
                                          placeholder="ZipCode"/>
                        </div>
                    </div>

                    <div class="mb-3 grid grid-cols-2 gap-3">
                        <div>
                            <x-text-input type="select" name="billing[country_code]"
                                          x-model="billingAddress.country_code">
                                <option value="">Select Country</option>

                                <template x-for="country of countries" :key="country.code">
                                    <option :selected="country.code === billingAddress.country_code"
                                            :value="country.code" x-text="country.name"></option>
                                </template>
                            </x-text-input>
                        </div>

                        <div>
                            <template x-if="billingCountryStates">
                                <x-text-input type="select" name="billing[state]" x-model="billingAddress.state">
                                    <option value="">Select State</option>

                                    <template x-for="[code, state] of Object.entries(billingCountryStates)"
                                              :key="code">
                                        <option :selected="code === billingAddress.state" :value="code"
                                                x-text="state"></option>
                                    </template>
                                </x-text-input>
                            </template>

                            <template x-if="!billingCountryStates">
                                <x-text-input name="billing[state]" x-model="billingAddress.state"
                                              placeholder="State"/>
                            </template>
                        </div>
                    </div>

                    <div class="mb-2 mt-6 flex justify-between">
                        <h2 class="text-xl font-semibold">Shipping Address</h2>

                        <label for="sameAsBillingAddress" class="text-gray-700">
                            <input @change="$event.target.checked ? shippingAddress = {...billingAddress} : ''"
                                   id="sameAsBillingAddress" type="checkbox"
                                   class="mr-2 text-purple-600 focus:ring-purple-600"> Same as Billing
                        </label>
                    </div>

                    <div class="mb-3 grid grid-cols-2 gap-3">
                        <div>
                            <x-text-input name="shipping[house_number]" x-model="shippingAddress.house_number"
                                          placeholder="Address 1"/>
                        </div>

                        <div>
                            <x-text-input name="shipping[area]" x-model="shippingAddress.area"
                                          placeholder="Address 2"/>
                        </div>
                    </div>

                    <div class="mb-3 grid grid-cols-2 gap-3">
                        <div>
                            <x-text-input name="shipping[city]" x-model="shippingAddress.city" placeholder="City"/>
                        </div>

                        <div>
                            <x-text-input name="shipping[zip_code]" x-model="shippingAddress.zip_code"
                                          placeholder="ZipCode"/>
                        </div>
                    </div>

                    <div class="mb-3 grid grid-cols-2 gap-3">
                        <div>
                            <x-text-input type="select" name="shipping[country_code]"
                                          x-model="shippingAddress.country_code">
                                <option value="">Select Country</option>

                                <template x-for="country of countries" :key="country.code">
                                    <option :selected="country.code === shippingAddress.country_code"
                                            :value="country.code" x-text="country.name"></option>
                                </template>
                            </x-text-input>
                        </div>

                        <div>
                            <template x-if="shippingCountryStates">
                                <x-text-input type="select" name="shipping[state]" x-model="shippingAddress.state">
                                    <option value="">Select State</option>

                                    <template x-for="[code, state] of Object.entries(shippingCountryStates)"
                                              :key="code">
                                        <option :selected="code === shippingAddress.state" :value="code"
                                                x-text="state"></option>
                                    </template>
                                </x-text-input>
                            </template>

                            <template x-if="!shippingCountryStates">
                                <x-text-input name="shipping[state]" x-model="shippingAddress.state"
                                              placeholder="State"/>
                            </template>
                        </div>
                    </div>

                    <button type="submit"
                            class="btn-primary w-full bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700">Update
                    </button>
                </form>
            </div>

            <div class="rounded-lg bg-white p-3 shadow">
                <form action="{{ route('profile.password.update') }}" method="post">
                    @csrf
                    <h2 class="mb-2 text-xl font-semibold">Update Password</h2>
                    <div class="mb-3">
                        <x-text-input type="password" name="old_password" placeholder="Your Current Password"/>
                    </div>

                    <div class="mb-3">
                        <x-text-input type="password" name="new_password" placeholder="New Password"/>
                    </div>

                    <div class="mb-3">
                        <x-text-input type="password" name="new_password_confirmation"
                                      placeholder="Repeat New Password"/>
                    </div>
                    <button type="submit"
                            class="btn-primary w-full bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700">Update
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
