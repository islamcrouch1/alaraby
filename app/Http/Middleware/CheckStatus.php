<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if ($user->status == 1) {
            alertError('Your account has been banned for violating our usage policy. Please check with technical support to find out the reason for the ban', 'تم حظر حسابك لمخالفة سياسية الاستخدام لدينا يرجى مراجعة الدعم الفني لمعرفة سبب الحظر');
            Auth::logout();
            return redirect()->route('login');
        }
        return $next($request);
    }
}
