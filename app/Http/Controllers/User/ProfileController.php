<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class ProfileController extends Controller
{
    public function edit()
    {
        $countries = Country::all();
        $roles = Role::WhereRoleNot(['superadministrator', 'administrator'])->get();
        $user = Auth::user();
        return view('Dashboard.users.profile ', compact('user', 'countries', 'roles'));
    }


    public function update(Request $request)
    {

        $user = Auth::user();

        $request->validate([
            'name' => "required|string|max:255",
            'email' => "required|string|email|max:255|unique:users,email," . $user->id,
            'profile' => "image",
        ]);


        if ($request->hasFile('profile')) {

            if ($user->profile == 'avatarmale.png' || $user->profile == 'avatarfemale.png') {

                Image::make($request['profile'])->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(public_path('storage/images/users/' . $request['profile']->hashName()), 60);
            } else {
                Storage::disk('public')->delete('/images/users/' . $user->profile);

                Image::make($request['profile'])->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(public_path('storage/images/users/' . $request['profile']->hashName()), 60);
            }

            $user->update([
                'profile' => $request['profile']->hashName(),
            ]);
        }

        $user->update([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => isset($request->password) ? Hash::make($request['password']) : $user->password,
        ]);

        alertSuccess('Your profile updated successfully', 'تم تحديث حسابك بنجاح');
        return redirect()->route('user.edit');
    }


    public function updateStore(Request $request)
    {

        $user = Auth::user();

        $request->validate([
            'store_description' => "nullable|string|max:255",
            'store_name' => "nullable|string|max:255|unique:users,store_name," . $user->id,
            'store_profile' => "nullable|image",
            'store_cover' => "nullable|image",
        ]);


        if ($request->hasFile('store_profile')) {

            if ($user->store_profile == null) {

                Image::make($request['store_profile'])->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(public_path('storage/images/users/' . $request['store_profile']->hashName()), 60);
            } else {
                Storage::disk('public')->delete('/images/users/' . $user->store_profile);

                Image::make($request['store_profile'])->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(public_path('storage/images/users/' . $request['store_profile']->hashName()), 60);
            }

            $user->update([
                'store_profile' => $request['store_profile']->hashName(),
            ]);
        }

        if ($request->hasFile('store_cover')) {

            if ($user->store_cover == null) {

                Image::make($request['store_cover'])->save(public_path('storage/images/users/' . $request['store_cover']->hashName()), 60);
            } else {
                Storage::disk('public')->delete('/images/users/' . $user->store_cover);

                Image::make($request['store_cover'])->save(public_path('storage/images/users/' . $request['store_cover']->hashName()), 60);
            }

            $user->update([
                'store_cover' => $request['store_cover']->hashName(),
            ]);
        }

        $user->update([
            'store_description' => $request['store_description'] == null ? $user->store_description : $request['store_description'],
            'store_name' => $request['store_name'] == null ? $user->store_name : $request['store_name'],
        ]);

        alertSuccess('Your store information updated successfully', 'تم تحديث بيانات متجرك بنجاح');
        return redirect()->route('user.edit');
    }
}
