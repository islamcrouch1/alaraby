<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PhoneVerificationController extends Controller
{
    public function show(Request $request)
    {


        if (hasVerifiedPhone($request->user())) {
            if (auth()->user()->hasRole('superadministrator|adminstrator')) {

                return redirect()->intended(RouteServiceProvider::HOME);
            } else {
                return redirect()->intended(RouteServiceProvider::HOMETECH);
            }
        } else {
            return view('dashboard.auth.verifyphone');
        }
    }

    public function verify(Request $request)
    {


        if ($request->user()->verification_code !== $request->code) {
            if (app()->getLocale() == "en") {
                throw ValidationException::withMessages([
                    'code' => ['The code your provided is wrong. Please try again or request another call.'],
                ]);
            } else {
                throw ValidationException::withMessages([
                    'code' => ['الكود الذي ادخلته غير صحيح , يرجى المحاولة مره اخرى'],
                ]);
            }
        }

        if (hasVerifiedPhone($request->user())) {

            return redirect()->route('login');
        }

        markPhoneAsVerified($request->user());

        // return redirect()->route('home')->with('status', 'Your phone was successfully verified!');

        $request->user()->forceFill([
            'verification_code' => null
        ])->save();


        return redirect()->route('login');
    }


    public function resend(Request $request)
    {
        callToVerify($request->user());
        return redirect()->back();
    }
}
