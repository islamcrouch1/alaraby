<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TechController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('dashboard.home', compact('user'));
    }


    public function myTasks()
    {
        $date = Carbon::now();
        $tasks = Task::where('user_id', Auth::id())
            ->whereDate('end_date', '>=', $date)
            ->whereDate('task_date', '<=',  $date)
            ->latest()->paginate();
        return view('dashboard.tech.index', compact('tasks'));
    }



    public function edit($task)
    {
        $comments = Comment::all();
        $task = task::findOrFail($task);
        return view('dashboard.tech.edit',  compact('comments', 'task'));
    }


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
        return redirect()->route('tech.tasks');
    }
}
