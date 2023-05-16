<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Task;

class TasksController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:superadministrator|administrator');
        $this->middleware('permission:tasks-read')->only('index', 'show');
        $this->middleware('permission:tasks-create')->only('create', 'store');
        $this->middleware('permission:tasks-update')->only('edit', 'update');
        $this->middleware('permission:tasks-delete|tasks-trash')->only('destroy', 'trashed');
        $this->middleware('permission:tasks-restore')->only('restore');
    }

    public function index()
    {

        $tasks = task::whenSearch(request()->search)
            ->latest()
            ->paginate(100);

        return view('dashboard.tasks.index')->with('tasks', $tasks);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.tasks.create');
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
            'name_ar' => "required|string|max:255|unique:tasks",
            'name_en' => "required|string|max:255|unique:tasks",
        ]);


        $task = task::create([
            'name_ar' => $request['name_ar'],
            'name_en' => $request['name_en'],

        ]);

        alertSuccess('task created successfully', 'تم إضافة السنترال بنجاح');
        return redirect()->route('tasks.index');
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
    public function edit($task)
    {
        $task = task::findOrFail($task);
        return view('dashboard.tasks.edit ')->with('task', $task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, task $task)
    {
        $request->validate([
            'name_ar' => "required|string|max:255|unique:tasks,name_ar," . $task->id,
            'name_en' => "required|string|max:255|unique:tasks,name_en," . $task->id,

        ]);


        $task->update([
            'name_ar' => $request['name_ar'],
            'name_en' => $request['name_en'],
        ]);



        alertSuccess('task updated successfully', 'تم تعديل السنترال بنجاح');
        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($task)
    {
        $task = task::withTrashed()->where('id', $task)->first();
        if ($task->trashed() && auth()->user()->hasPermission('tasks-delete')) {
            $task->forceDelete();
            alertSuccess('task deleted successfully', 'تم حذف السنترال بنجاح');
            return redirect()->route('tasks.trashed');
        } elseif (!$task->trashed() && auth()->user()->hasPermission('tasks-trash') && checkTaskForTrash($task)) {
            $task->delete();
            alertSuccess('task trashed successfully', 'تم حذف السنترال مؤقتا');
            return redirect()->route('tasks.index');
        } else {
            alertError('Sorry, you do not have permission to perform this action, or the task cannot be deleted at the moment', 'نأسف ليس لديك صلاحية للقيام بهذا الإجراء ، أو السنترال لا يمكن حذفه حاليا');
            return redirect()->back();
        }
    }

    public function trashed()
    {
        $tasks = task::onlyTrashed()
            ->whenSearch(request()->search)
            ->latest()
            ->paginate(100);
        return view('dashboard.tasks.index', ['tasks' => $tasks]);
    }

    public function restore($task, Request $request)
    {
        $task = task::withTrashed()->where('id', $task)->first()->restore();
        alertSuccess('task restored successfully', 'تم استعادة السنترال بنجاح');
        return redirect()->route('tasks.index', ['parent_id' => $request->parent_id]);
    }
}


