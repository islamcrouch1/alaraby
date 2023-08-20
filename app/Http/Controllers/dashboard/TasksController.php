<?php

namespace App\Http\Controllers\dashboard;

use App\Exports\TasksExports;
use App\Http\Controllers\Controller;
use App\Imports\TasksImport;
use App\Models\Central;
use App\Models\Comment;
use App\Models\Compound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;


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

    public function index(Request $request)
    {


        if (!$request->has('from') || !$request->has('to')) {
            $request->merge(['from' => Carbon::now()->subDay(30)->toDateString()]);
            $request->merge(['to' => Carbon::now()->toDateString()]);
        }


        if (!$request->has('activation_from') || !$request->has('activation_to')) {
            $request->merge(['activation_from' => Carbon::now()->subDay(30)->toDateString()]);
            $request->merge(['activation_to' => Carbon::now()->toDateString()]);
        }

        // dd(request()->from, request()->to);

        $tasks = Task::whereDate('task_date', '>=', request()->from)
            ->whereDate('task_date', '<=', request()->to)
            ->where(function ($q) {
                $q->whereDate('activation_date', '>=', request()->activation_from)
                    ->orWhereNull('activation_date');
            })
            ->where(function ($q) {
                $q->whereDate('activation_date', '<=', request()->activation_to)
                    ->orWhereNull('activation_date');
            })
            ->whenSearch(request()->search)
            ->whenStatus(request()->status)
            ->whenCentral(request()->central_id)
            ->whenComment(request()->comment_id)
            ->whenCompound(request()->compound_id)
            ->whenPaymentStatus(request()->payment_status)
            ->latest()
            ->paginate(200);

        $centrals = Central::all();
        $commmnets = Comment::all();
        $compounds = Compound::all();

        return view('dashboard.tasks.index', compact('centrals', 'tasks', 'commmnets', 'compounds'));
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
            'end_date' => $date->toDateTimeString(),

        ]);

        alertSuccess('task created successfully', 'تم إفة المهمة بنجاح');
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
            'status' => "nullable|string",
            'payment_status' => "nullable|integer",
        ]);



        $date = Carbon::parse($request->task_date);
        $date = $date->addDay();

        if (isset($request['status']) &&  $request['status'] == 'active' && $task->status != 'active') {
            $activation_date = Carbon::now()->toDateTimeString();
        } elseif ($task->status == 'active' && $request['status'] != 'inactive') {
            $activation_date = $task->activation_date;
        } else {
            $activation_date = null;
        }



        $task->update([
            'client_name' => $request['client_name'],
            'client_phone' => $request['client_phone'],
            'service_number' => $request['service_number'],
            'address' => $request['address'],
            'compound_id' => $request['compound'],
            'user_id' => $request['tech'],
            'central_id' => $request['central'],
            'task_date' => $request['task_date'],
            'end_date' => $date->toDateTimeString(),
            'status' => $request['status'] ? $request['status'] : $task->status,
            'activation_date' => $activation_date,
            'payment_status' => $request['payment_status'] == null ? 2 : $request['payment_status'],

        ]);



        alertSuccess('task updated successfully', 'م تعديل المهمة بنجاح');
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
            alertSuccess('task deleted successfully', 'تم حذف المهمة بجح');
            return redirect()->route('tasks.trashed');
        } elseif (!$task->trashed() && auth()->user()->hasPermission('tasks-trash')) {
            $task->delete();
            alertSuccess('task trashed successfully', 'تم حذف الممة مؤقتا');
            return redirect()->route('tasks.index');
        } else {
            alertError('Sorry, you do not have permission to perform this action, or the task cannot be deleted at the moment', 'نأف ليس لديك صلاحية للقام بهذا الإجاء ، و المهمة لا يمكن ذفه حاليا');
            return redirect()->back();
        }
    }

    public function trashed(Request $request)
    {



        if (!$request->has('from') || !$request->has('to')) {
            $request->merge(['from' => Carbon::now()->subDay(30)->toDateString()]);
            $request->merge(['to' => Carbon::now()->toDateString()]);
        }


        if (!$request->has('activation_from') || !$request->has('activation_to')) {
            $request->merge(['activation_from' => Carbon::now()->subDay(30)->toDateString()]);
            $request->merge(['activation_to' => Carbon::now()->toDateString()]);
        }


        $tasks = Task::onlyTrashed()
            ->whereDate('task_date', '>=', request()->from)
            ->whereDate('task_date', '<=', request()->to)
            ->where(function ($q) {
                $q->whereDate('activation_date', '>=', request()->activation_from)
                    ->orWhereNull('activation_date');
            })
            ->where(function ($q) {
                $q->whereDate('activation_date', '<=', request()->activation_to)
                    ->orWhereNull('activation_date');
            })
            ->whenSearch(request()->search)
            ->whenStatus(request()->status)
            ->whenCentral(request()->central_id)
            ->whenComment(request()->comment_id)
            ->whenCompound(request()->compound_id)
            ->whenPaymentStatus(request()->payment_status)
            ->latest()
            ->paginate(200);;


        $centrals = Central::all();
        $commmnets = Comment::all();
        $compounds = Compound::all();


        return view('dashboard.tasks.index', compact('centrals', 'tasks', 'commmnets', 'compounds'));
    }

    public function restore($task, Request $request)
    {
        $task = task::withTrashed()->where('id', $task)->first()->restore();
        alertSuccess('task restored successfully', 'تم استعادة المهة بنجاح');
        return redirect()->route('tasks.index', ['parent_id' => $request->parent_id]);
    }

    public function import(Request $request)
    {
        $file = $request->file('file')->store('import');

        $import = new TasksImport;
        $import->import($file);

        if ($import->failures()->isNotEmpty()) {
            return back()->withFailures($import->failures());
        }

        if (!session('error')) {
            alertSuccess('The file has been uploaded successfully.', 'تم رفع الملف بنجح.');
            return redirect()->back();
        } else {
            return redirect()->back();
        }
    }

    public function export(Request $request)
    {
        $response = Excel::download(new TasksExports($request->status, $request->payment_status, $request->from, $request->to, $request->activation_from, $request->activation_to), 'tasks.xlsx');
        ob_end_clean();
        return $response;
    }


    public function bulkAction(Request $request)
    {
        $request->validate([
            'bulk_option' => "nullable|string|max:255",
            'items' => "nullable|array",
            'task_date' => "nullable|string",
        ]);





        if ($request->task_date && ($request->bulk_option != 'trash' || $request->bulk_option != 'delete')) {
            foreach ($request->items as $item) {
                $task = Task::findOrFail($item);
                $date = Carbon::parse($request->task_date);
                $date = $date->addDay();
                $task->update([
                    'task_date' => $request['task_date'],
                    'end_date' => $date->toDateTimeString(),
                ]);
            }
        }

        if ($request->bulk_option) {



            if ($request->bulk_option == 'trash' || $request->bulk_option == 'delete') {

                foreach ($request->items as $item) {
                    $task = Task::withTrashed()->where('id', $item)->first();
                    if ($task->trashed() && auth()->user()->hasPermission('tasks-delete')) {
                        $task->forceDelete();
                    } elseif (!$task->trashed() && auth()->user()->hasPermission('tasks-trash')) {
                        $task->delete();
                    } else {
                        alertError('Sorry, you do not have permission to perform this action, or the task cannot be deleted at the moment', 'نأسف لس لديك صلاحية للقيام بهذا الجراء  أو المهمة ل يمك حذفه حاليا');
                    }
                }
            } else {
                foreach ($request->items as $item) {

                    $task = Task::findOrFail($item);

                    if ($task->cab != null && ($request['bulk_option'] == 'active' || $request['bulk_option'] == 'inactive')) {

                        if (isset($request['bulk_option']) &&  $request['bulk_option'] == 'active' && $task->status != 'active') {
                            $activation_date = Carbon::now()->toDateTimeString();
                        } elseif ($task->status == 'active' && $request['bulk_option'] != 'inactive') {
                            $activation_date = $task->activation_date;
                        } else {
                            $activation_date = null;
                        }


                        $task->update([

                            'status' => $request['bulk_option'],
                            'activation_date' => $activation_date,
                            // 'payment_status' => $request['payment_status'],

                        ]);
                    } elseif ($task->cab != null && ($request['bulk_option'] == 'paid' || $request['bulk_option'] == 'unpaid')) {
                        $task->update([
                            'payment_status' => $request['bulk_option'] == 'paid' ? 1 : 2,
                        ]);
                    }
                }
            }
        }

        alertSuccess('tasks updated successfully', 'تم التعديل بنجاح');
        return redirect()->route('tasks.index');
    }
}
