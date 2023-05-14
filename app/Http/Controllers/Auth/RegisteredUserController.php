<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Intervention\Image\ImageManagerStatic as Image;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('dashboard.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' =>  ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'password' => ['required', 'confirmed', 'min:8', Rules\Password::defaults()],
            'phone' => ['required', 'numeric', 'unique:users'],
            'gender' => ['required', 'string'],
            'profile' => ['nullable', 'image'],
        ]);


        $profile = $request->profile;

        if (!isset($request->profile)) {
            if ($request->gender == 'male') {
                $profile = 'avatarmale.png';
            } else {
                $profile = 'avatarfemale.png';
            }
        } else {
            Image::make($request->profile)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('storage/images/users/' . $request->profile->hashName()), 80);
        }

        if ($profile !== 'avatarmale.png' && $profile !== 'avatarfemale.png') {
            $profile = $request->profile->hashName();
        }


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'gender' => $request->gender,
            'profile' => $profile,
        ]);


        $role = Role::where('name', 'tech')->first();

        if (!$role) {
            $role = Role::create([
                'name' => 'tech',
                'display_name' => 'tech',
                'description' => 'tech',
            ]);
        }

        $user->attachRole($role);

        event(new Registered($user));

        Auth::login($user);

        if (auth()->user()->hasRole('superadministrator|adminstrator')) {

            return redirect()->intended(RouteServiceProvider::HOME);
        } else {
            return redirect()->intended(RouteServiceProvider::HOMETECH);
        }
    }
}
