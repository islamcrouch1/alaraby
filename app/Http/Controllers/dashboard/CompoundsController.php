<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Compound;
use Illuminate\Http\Request;

class CompoundsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:superadministrator|administrator');
        $this->middleware('permission:compounds-read')->only('index', 'show');
        $this->middleware('permission:compounds-create')->only('create', 'store');
        $this->middleware('permission:compounds-update')->only('edit', 'update');
        $this->middleware('permission:compounds-delete|compounds-trash')->only('destroy', 'trashed');
        $this->middleware('permission:compounds-restore')->only('restore');
    }

    public function index()
    {

        $compounds = Compound::whenSearch(request()->search)
            ->latest()
            ->paginate(100);

        return view('dashboard.compounds.index')->with('compounds', $compounds);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.compounds.create');
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
            'name_ar' => "required|string|max:255|unique:compounds",
            'name_en' => "required|string|max:255|unique:compounds",
        ]);


        $compound = Compound::create([
            'name_ar' => $request['name_ar'],
            'name_en' => $request['name_en'],

        ]);

        alertSuccess('compound created successfully', 'تم إضافة السنترال بنجاح');
        return redirect()->route('compounds.index');
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
    public function edit($compound)
    {
        $compound = Compound::findOrFail($compound);
        return view('dashboard.compounds.edit ')->with('compound', $compound);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Compound $compound)
    {
        $request->validate([
            'name_ar' => "required|string|max:255|unique:compounds,name_ar," . $compound->id,
            'name_en' => "required|string|max:255|unique:compounds,name_en," . $compound->id,

        ]);


        $compound->update([
            'name_ar' => $request['name_ar'],
            'name_en' => $request['name_en'],
        ]);



        alertSuccess('compound updated successfully', 'تم تعديل السنترال بنجاح');
        return redirect()->route('compounds.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($compound)
    {
        $compound = Compound::withTrashed()->where('id', $compound)->first();
        if ($compound->trashed() && auth()->user()->hasPermission('compounds-delete')) {
            $compound->forceDelete();
            alertSuccess('compound deleted successfully', 'تم حذف السنترال بنجاح');
            return redirect()->route('compounds.trashed');
        } elseif (!$compound->trashed() && auth()->user()->hasPermission('compounds-trash') && checkCompoundForTrash($compound)) {
            $compound->delete();
            alertSuccess('compound trashed successfully', 'تم حذف السنترال مؤقتا');
            return redirect()->route('compounds.index');
        } else {
            alertError('Sorry, you do not have permission to perform this action, or the compound cannot be deleted at the moment', 'نأسف ليس لديك صلاحية للقيام بهذا الإجراء ، أو السنترال لا يمكن حذفه حاليا');
            return redirect()->back();
        }
    }

    public function trashed()
    {
        $compounds = Compound::onlyTrashed()
            ->whenSearch(request()->search)
            ->latest()
            ->paginate(100);
        return view('dashboard.compounds.index', ['compounds' => $compounds]);
    }

    public function restore($compound, Request $request)
    {
        $compound = Compound::withTrashed()->where('id', $compound)->first()->restore();
        alertSuccess('compound restored successfully', 'تم استعادة السنترال بنجاح');
        return redirect()->route('compounds.index', ['parent_id' => $request->parent_id]);
    }
}
