<?php

namespace App\Http\Controllers\Dashboard;
    
use App\Http\Controllers\Controller;
use App\Models\central;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class CentralsController extends Controller
{
    
        public function __construct()
        {
            $this->middleware('role:superadministrator|administrator');
            $this->middleware('permission:Centrals-read')->only('index', 'show');
            $this->middleware('permission:Centrals-create')->only('create', 'store');
            $this->middleware('permission:Centrals-update')->only('edit', 'update');
            $this->middleware('permission:Centrals-delete|Centrals-trash')->only('destroy', 'trashed');
            $this->middleware('permission:Centrals-restore')->only('restore');
        }
    
        // public function index()
        // {
        //     if (!request()->has('parent_id')) {
        //         request()->merge(['parent_id' => null]);
        //     }
    
            $Centrals = Central::whenSearch(request()->search)
                ->whenCountry(request()->country_id)
                ->whenParent(request()->parent_id)
                ->latest()
                ->paginate(100);
    
        //     $countries = Country::all();
    
        //     return view('Dashboard.Centrals.index')->with('Centrals', $Centrals)->with('countries', $countries);
        // }
    
        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
            $countries = Country::all();
            $Centrals = Central::whereNull('parent_id')->get();
            return view('Dashboard.Centrals.create')->with('countries', $countries)->with('Centrals', $Centrals)->with('parent_id', request()->parent_id);
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
                'name_ar' => "required|string|max:255|unique:Centrals",
                'name_en' => "required|string|max:255|unique:Centrals",
               
                'country' => "required",
                'parent_id' => "nullable|string",
            
            ]);
    
            Image::make($request['image'])->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('storage/images/Centrals/' . $request['image']->hashName()), 60);
    
            $Central = Central::create([
                'name_ar' => $request['name_ar'],
                'name_en' => $request['name_en'],
                'country_id' => $request['country'],
                'parent_id' => isset($request['parent_id']) ? $request['parent_id'] : null,
            ]);
    
            alertSuccess('Central created successfully', 'تم إضافة القسم بنجاح');
            return redirect()->route('Centrals.index', ['parent_id' => $request->parent_id]);
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
        public function edit($Central)
        {
            $countries = Country::all();
            $Central = Central::findOrFail($Central);
            $Centrals = Central::whereNull('parent_id')->get();
            return view('Dashboard.Centrals.edit ')->with('Central', $Central)->with('countries', $countries)->with('Centrals', $Centrals);
        }
    
        /**
         * Update the specified resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function update(Request $request, Central $Central)
        {
            $request->validate([
                'name_ar' => "required|string|max:255|unique:Centrals,name_ar," . $Central->id,
                'name_en' => "required|string|max:255|unique:Centrals,name_en," . $Central->id,
                'country' => "required",
                'parent_id' => "nullable|string",
            ]);
    
            // if ($request->hasFile('image')) {
            //     Storage::disk('public')->delete('/images/Centrals/' . $Central->image);
            //     Image::make($request['image'])->resize(300, null, function ($constraint) {
            //         $constraint->aspectRatio();
            //     })->save(public_path('storage/images/Centrals/' . $request['image']->hashName()), 60);
            //     $Central->update([
            //         'image' => $request['image']->hashName(),
            //     ]);
            // }
    
            $Central->update([
                'name_ar' => $request['name_ar'],
                'name_en' => $request['name_en'],
                'country_id' => $request['country'],
                'parent_id' => isset($request['parent_id']) ? $request['parent_id'] : null,
            ]);
    
            // foreach ($Central->products as $product) {
            //     CalculateProductPrice($product);
            // }
    
            alertSuccess('Central updated successfully', 'تم تعديل القسم بنجاح');
            return redirect()->route('Centrals.index', ['parent_id' => $request->parent_id]);
        }
    
        /**
         * Remove the specified resource from storage.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function destroy($Central)
        {
            $Central = Central::withTrashed()->where('id', $Central)->first();
            if ($Central->trashed() && auth()->user()->hasPermission('Centrals-delete')) {
                Storage::disk('public')->delete('/images/Centrals/' . $Central->image);
                $Central->forceDelete();
                alertSuccess('Central deleted successfully', 'تم حذف القسم بنجاح');
                return redirect()->route('Centrals.trashed');
            } elseif (!$Central->trashed() && auth()->user()->hasPermission('Centrals-trash') && checkCentralForTrash($Central)) {
                $Central->delete();
                alertSuccess('Central trashed successfully', 'تم حذف القسم مؤقتا');
                return redirect()->route('Centrals.index');
            } else {
                alertError('Sorry, you do not have permission to perform this action, or the Central cannot be deleted at the moment', 'نأسف ليس لديك صلاحية للقيام بهذا الإجراء ، أو القسم لا يمكن حذفه حاليا');
                return redirect()->back();
            }
        }
    
        public function trashed()
        {
            $countries = Country::all();
            $Centrals = Central::onlyTrashed()
                ->whenSearch(request()->search)
                ->whenCountry(request()->country_id)
                ->latest()
                ->paginate(100);
            return view('Dashboard.Centrals.index', ['Centrals' => $Centrals])->with('countries', $countries);
        }
    
        public function restore($Central, Request $request)
        {
            $Central = Central::withTrashed()->where('id', $Central)->first()->restore();
            alertSuccess('Central restored successfully', 'تم استعادة القسم بنجاح');
            return redirect()->route('Centrals.index', ['parent_id' => $request->parent_id]);
        }
    }
    
}
