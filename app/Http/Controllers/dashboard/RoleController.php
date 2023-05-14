<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        // $this->middleware('role:superadministrator|administrator');
        // $this->middleware('permission:roles-read')->only('index', 'show');
        // $this->middleware('permission:roles-create')->only('create', 'store');
        // $this->middleware('permission:roles-update')->only('edit', 'update');
        // $this->middleware('permission:roles-delete|roles-trash')->only('destroy', 'trashed');
        // $this->middleware('permission:roles-restore')->only('restore');
    }


    public function index()
    {
        $roles = Role::WhereRoleNot(['Administrator', 'user', 'vendor', 'affiliate'])
            ->whenSearch(request()->search)
            ->with('permissions')
            ->withCount('users')
            ->latest()
            ->paginate(100);
        return view('dashboard.roles.index')->with('roles', $roles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => "required|string|unique:roles,name",
            'description' => "string",
            'permissions' => "required|array|min:1",
        ]);

        foreach ($request->permissions as $permission) {
            if (Permission::where('name', $permission)->first() == null) {
                Permission::create([
                    'name' => $permission,
                    'display_name' => $permission,
                    'description' => $permission
                ]);
            }
        }

        $role = Role::create($request->all());
        $role->attachPermissions($request->permissions);

        alertSuccess('Role created successfully', 'تم إنشاء الدور بنجاح');
        return redirect()->route('roles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($role)
    {
        $role = Role::find($role);
        return view('dashboard.roles.edit')->with('role', $role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => "required|string|unique:roles,name," . $role->id,
            'description' => "string",
            'permissions' => "required|array|min:1",
        ]);

        foreach ($request->permissions as $permission) {
            if (Permission::where('name', $permission)->first() == null) {
                Permission::create([
                    'name' => $permission,
                    'display_name' => $permission,
                    'description' => $permission
                ]);
            }
        }

        $role->update($request->all());
        $role->syncPermissions($request->permissions);

        alertSuccess('Role updated successfully', 'تم تعديل الدور بنجاح');
        return redirect()->route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($role)
    {
        $role = Role::withTrashed()->where('id', $role)->first();
        if ($role->trashed() && auth()->user()->hasPermission('roles-delete')) {
            $role->forceDelete();
            alertSuccess('role deleted successfully', 'تم حذف الدور بنجاح');
            return redirect()->route('roles.trashed');
        } elseif (!$role->trashed() && auth()->user()->hasPermission('roles-trash') && checkRoleForTrash($role)) {
            $role->delete();
            alertSuccess('role trashed successfully', 'تم حذف الدور مؤقتا');
            return redirect()->route('roles.index');
        } else {
            alertError('Sorry, you do not have permission to perform this action, or the role cannot be deleted at the moment', 'نأسف ليس لديك صلاحية للقيام بهذا الإجراء ، أو الدور لا يمكن حذفها حاليا');
            return redirect()->back();
        }
    }



    public function trashed()
    {
        $roles = Role::onlyTrashed()->paginate(100);
        return view('dashboard.roles.index', ['roles' => $roles]);
    }

    public function restore($role)
    {
        $role = Role::withTrashed()->where('id', $role)->first()->restore();
        alertSuccess('Role restored successfully', 'تم إستعادة الدور بنجاح');
        return redirect()->route('roles.index');
    }
}
