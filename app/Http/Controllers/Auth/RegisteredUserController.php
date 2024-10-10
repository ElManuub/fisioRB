<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        Gate::authorize('register-employees');

        $offices = Office::all(); //offices

        return view('auth.register')->with('offices', $offices);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        Gate::authorize('register-employees');
        // Validación de la solicitud
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'office_id' => ['required', 'in:1,2'],
            'role' => ['required', 'in:1,2'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Creación del nuevo usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'office_id' => $request->office_id,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        // Disparar el evento de registro
        event(new Registered($user));

        // Redirigir al dashboard
        return redirect(route('dashboard', absolute: false));
    }
}
