<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class UserController extends Controller
{
  
    public function __construct()
    {
        $this->middleware('role:superadministrator|administrator');
        $this->middleware('permission:users-read')->only('index', 'show', 'trashed');
        $this->middleware('permission:users-create')->only('create', 'store');
        $this->middleware('permission:users-update')->only('edit', 'update');
        $this->middleware('permission:users-delete|users-trash')->only('destroy', 'trashed');
        $this->middleware('permission:users-restore')->only('restore');
    }


    public function index(Request $request)
    {

        if (!$request->has('from') || !$request->has('to')) {

            $request->merge(['from' => Carbon::now()->subDay(365)->toDateString()]);
            $request->merge(['to' => Carbon::now()->toDateString()]);
        }


       

        $roles = Role::WhereRoleNot('superadministrator')->get();
        $users = User::whereDate('created_at', '>=', request()->from)
            ->whereDate('created_at', '<=', request()->to)
            ->whereRoleNot('superadministrator')
            ->whenSearch(request()->search)
            ->whenRole(request()->role_id)
            ->whenCountry(request()->country_id)
            ->whenStatus(request()->status)
            ->with('roles')
            ->latest()
            ->paginate(100);


        return view('Dashboard.users.index', compact('users', 'roles', 'countries'));
    }




}
