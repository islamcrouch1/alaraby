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
        return view('dashboard.tech.update',  compact('comments', 'task'));
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
            'comment' => "required|integer",
            'cab' => "required|string",
            'box' => "required|string",
            'db' => "required|string",
            'cable_type' => "required|string",
            'cable_length' => "required|string",
            'connector' => "required|string",
            'face_split' => "required|string",
            'status' => "required|string",
            'type' => "required|string",


           
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
            'end_date' => $date->toDateString(),
            'cab' => $request['cab'],
            'box' => $request['box'],
            'db' => $request['db'],
            'cable_type' => $request['cable_type'],
            'cable_length' => $request['cable_length'],
            'connector' => $request['connector'],
            'face_split' => $request['face_split'],
            'status' => $request['status'],
            'comment_id' => $request['comment'],
            'type' => $request['type'],



            
        ]);



        alertSuccess('task updated successfully', 'تم تعديل المهمة بنجاح');
        return redirect()->route('tech.tasks');
    }
}
