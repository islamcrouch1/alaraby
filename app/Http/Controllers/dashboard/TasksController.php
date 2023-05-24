<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Central;
use App\Models\Comment;
use App\Models\Compound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

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
        $compounds = Compound::all();
        $comments = Comment::all();
        $centrals = Central::all();
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'tech');
        })->get();

        return view('dashboard.tasks.create', compact('compounds', 'comments', 'centrals', 'users'));
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
            'client_name' => "required|string|max:255",
            'client_phone' => "required|string|max:255",
            'service_number' => "required|string|max:255|unique:tasks",
            'address' => "required|string|max:255",
            'compound' => "required|integer",
            'central' => "required|integer",
            'tech' => "required|integer",
            'task_date' => "required|string",
        ]);


        $date = Carbon::parse($request->task_date);
        $date = $date->addDay();


        $task = task::create([
            'client_name' => $request['client_name'],
            'client_phone' => $request['client_phone'],
            'service_number' => $request['service_number'],
            'address' => $request['address'],
            'compound_id' => $request['compound'],
            'user_id' => $request['tech'],
            'central_id' => $request['central'],
            'task_date' => $request['task_date'],
            'end_date' => $date->toDateString()
        ]);

        alertSuccess('task created successfully', 'تم إضافة المهمة بنجاح');
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
        $compounds = Compound::all();
        $comments = Comment::all();
        $centrals = Central::all();
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'tech');
        })->get();
        $task = task::findOrFail($task);
        return view('dashboard.tasks.edit',  compact('compounds', 'comments', 'centrals', 'users', 'task'));
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
            'client_name' => "required|string|max:255",
            'client_phone' => "required|string|max:255",
            'service_number' => "required|string|max:255|unique:tasks,service_number," . $task->id,
            'address' => "required|string|max:255",
            'compound' => "required|integer",
            'central' => "required|integer",
            'tech' => "required|integer",
            'task_date' => "required|string",
        ]);



        $date = Carbon::parse($request->task_date);
        $date = $date->addDay();

        $task->update([
            'client_name' => $request['client_name'],
            'client_phone' => $request['client_phone'],
            'service_number' => $request['service_number'],
            'address' => $request['address'],
            'compound_id' => $request['compound'],
            'user_id' => $request['tech'],
            'central_id' => $request['central'],
            'task_date' => $request['task_date'],
            'end_date' => $date->toDateString()
        ]);



        alertSuccess('task updated successfully', 'تم تعديل المهمة بنجاح');
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
        $task = Task::withTrashed()->where('id', $task)->first();
        if ($task->trashed() && auth()->user()->hasPermission('tasks-delete')) {
            $task->forceDelete();
            alertSuccess('task deleted successfully', 'تم حذف المهمة بنجاح');
            return redirect()->route('tasks.trashed');
        } elseif (!$task->trashed() && auth()->user()->hasPermission('tasks-trash')) {
            $task->delete();
            alertSuccess('task trashed successfully', 'تم حذف المهمة مؤقتا');
            return redirect()->route('tasks.index');
        } else {
            alertError('Sorry, you do not have permission to perform this action, or the task cannot be deleted at the moment', 'نأسف ليس لديك صلاحية للقيام بهذا الإجراء ، أو المهمة لا يمكن حذفه حاليا');
            return redirect()->back();
        }
    }

    public function trashed()
    {
        $tasks = Task::onlyTrashed()
            ->whenSearch(request()->search)
            ->latest()
            ->paginate(100);
        return view('dashboard.tasks.index', ['tasks' => $tasks]);
    }

    public function restore($task, Request $request)
    {
        $task = task::withTrashed()->where('id', $task)->first()->restore();
        alertSuccess('task restored successfully', 'تم استعادة المهمة بنجاح');
        return redirect()->route('tasks.index', ['parent_id' => $request->parent_id]);
    }
}
