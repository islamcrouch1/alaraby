<?php

use App\Models\Task;
use App\Models\Central;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

// calculate date
if (!function_exists('interval')) {
    function interval($old)
    {
        $date = Carbon::now();
        return $interval = $old->diffForHumans();
    }
}


// calculate date
if (!function_exists('interval2')) {
    function interval2($old)
    {
        $old = Carbon::parse($old);
        return $interval = $old->diffForHumans();
    }
}



// alert success
if (!function_exists('alertSuccess')) {
    function alertSuccess($en, $ar)
    {
        app()->getLocale() == 'ar' ?
            session()->flash('success', $ar) :
            session()->flash('success', $en);
    }
}


// alert error
if (!function_exists('alertError')) {
    function alertError($en, $ar)
    {
        app()->getLocale() == 'ar' ?
            session()->flash('error', $ar) :
            session()->flash('error', $en);
    }
}



// check user for trash
if (!function_exists('checkUserForTrash')) {
    function checkUserForTrash($user)
    {
        if ($user->tasks()->withTrashed()->count() > '0') {
            return false;
        } else {
            return true;
        }
    }
}


// check user for trash
if (!function_exists('checkCompoundForTrash')) {
    function checkCompoundForTrash($compound)
    {
        if ($compound->tasks()->withTrashed()->count() > '0') {
            return false;
        } else {
            return true;
        }
    }
}


// check user for trash
if (!function_exists('checkCommentForTrash')) {
    function checkCommentForTrash($comment)
    {
        if ($comment->tasks()->withTrashed()->count() > '0') {
            return false;
        } else {
            return true;
        }
    }
}





// check user for trash
if (!function_exists('checkCentralForTrash')) {
    function checkCentralForTrash($central)
    {
        return true;
    }
}



// check phone verification
if (!function_exists('hasVerifiedPhone')) {
    function hasVerifiedPhone($user)
    {
        return !is_null($user->phone_verified_at);
    }
}


// make phone verified
if (!function_exists('markPhoneAsVerified')) {
    function markPhoneAsVerified($user)
    {
        return $user->forceFill([
            'phone_verified_at' => $user->freshTimestamp(),
        ])->save();
    }
}


// check role for trash

if (!function_exists('checkRoleForTrash')) {
    function checkRoleForTrash($role)
    {
        if ($role->users()->withTrashed()->count() > '0') {
            return false;
        } else {
            return true;
        }
    }
}
// check Task for trash

if (!function_exists('checkTaskForTrash')) {
    function checkTaskForTrash($task)
    {
        if ($task->user()->withTrashed()->count() > '0') {
            return false;
        } else {
            return true;
        }
    }
}


if (!function_exists('getCentralsCount')) {
    function getCentralsCount()
    {
        return Central::all()->count();
    }
}


if (!function_exists('getTasksCount')) {
    function getTasksCount()
    {
        return Task::all()->count();
    }
}


// set localizaition in session
if (!function_exists('setLocaleBySession')) {
    function setLocaleBySession()
    {

        if (Auth::check()) {
            $user = User::findOrFail(Auth::id());

            $user->update([
                'lang' => app()->getLocale() == 'ar' ? 'en' : 'ar',
            ]);
        } else {
            app()->getLocale() == 'en' ? session(['lang' => 'ar']) : session(['lang' => 'en']);
        }
    }
}


if (!function_exists('getActiveTasksCount')) {
    function getActiveTasksCount()
    {
        return Task::where('status', 'active')->get()->count();
    }
}

if (!function_exists('getUpdatedTasksCount')) {
    function getUpdatedTasksCount()
    {
        return Task::whereNotNull('cab')->count();
    }
}

if (!function_exists('getName')) {
    function getName($item)
    {
        if (isset($item) && isset($item->name_ar) && isset($item->name_en)) {
            if (app()->getLocale() == 'ar') {
                return $item->name_ar;
            } else {
                return $item->name_en;
            }
        }
    }
}
