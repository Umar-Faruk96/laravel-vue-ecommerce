<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Cart;
use App\Models\{User, Customer};
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\{View\View, Http\Request, Support\Facades\Auth, Support\Facades\Hash, Http\RedirectResponse, Auth\Events\Registered, Validation\Rules\Password};

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));

            $customer = new Customer();
            $customerNames = explode(' ', $user->name);
            $customer->user_id = $user->id;
            $customer->first_name = $customerNames[0];
            $customer->last_name = $customerNames[1];
            $customer->email = $user->email;
            $customer->save();

            Auth::login($user);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'Registration failed. Please try again later.']);
        }
        DB::commit();

        Cart::moveCartItemsIntoDB();

        return redirect(route('home', absolute: false));
    }
}
