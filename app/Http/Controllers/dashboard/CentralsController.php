<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Central;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class centralsController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:superadministrator|administrator');
        $this->middleware('permission:centrals-read')->only('index', 'show');
        $this->middleware('permission:centrals-create')->only('create', 'store');
        $this->middleware('permission:centrals-update')->only('edit', 'update');
        $this->middleware('permission:centrals-delete|centrals-trash')->only('destroy', 'trashed');
        $this->middleware('permission:centrals-restore')->only('restore');
    }

    public function index()
    {

        $centrals = Central::whenSearch(request()->search)
            ->latest()
            ->paginate(100);

        return view('dashboard.centrals.index')->with('centrals', $centrals);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.centrals.create');
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
            'name_ar' => "required|string|max:255|unique:centrals",
            'name_en' => "required|string|max:255|unique:centrals",
        ]);


        $central = Central::create([
            'name_ar' => $request['name_ar'],
            'name_en' => $request['name_en'],

        ]);

        alertSuccess('central created successfully', 'تم إضافة السنترال بنجاح');
        return redirect()->route('centrals.index');
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
    public function edit($central)
    {
        $central = Central::findOrFail($central);
        return view('dashboard.centrals.edit ')->with('central', $central);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Central $central)
    {
        $request->validate([
            'name_ar' => "required|string|max:255|unique:centrals,name_ar," . $central->id,
            'name_en' => "required|string|max:255|unique:centrals,name_en," . $central->id,

        ]);


        $central->update([
            'name_ar' => $request['name_ar'],
            'name_en' => $request['name_en'],
        ]);



        alertSuccess('central updated successfully', 'تم تعديل السنترال بنجاح');
        return redirect()->route('centrals.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($central)
    {
        $central = Central::withTrashed()->where('id', $central)->first();
        if ($central->trashed() && auth()->user()->hasPermission('centrals-delete')) {
            $central->forceDelete();
            alertSuccess('central deleted successfully', 'تم حذف السنترال بنجاح');
            return redirect()->route('centrals.trashed');
        } elseif (!$central->trashed() && auth()->user()->hasPermission('centrals-trash') && checkCentralForTrash($central)) {
            $central->delete();
            alertSuccess('central trashed successfully', 'تم حذف السنترال مؤقتا');
            return redirect()->route('centrals.index');
        } else {
            alertError('Sorry, you do not have permission to perform this action, or the central cannot be deleted at the moment', 'نأسف ليس لديك صلاحية للقيام بهذا الإجراء ، أو السنترال لا يمكن حذفه حاليا');
            return redirect()->back();
        }
    }

    public function trashed()
    {
        $centrals = Central::onlyTrashed()
            ->whenSearch(request()->search)
            ->latest()
            ->paginate(100);
        return view('dashboard.centrals.index', ['centrals' => $centrals]);
    }

    public function restore($central, Request $request)
    {
        $central = Central::withTrashed()->where('id', $central)->first()->restore();
        alertSuccess('central restored successfully', 'تم استعادة السنترال بنجاح');
        return redirect()->route('centrals.index', ['parent_id' => $request->parent_id]);
    }
}
