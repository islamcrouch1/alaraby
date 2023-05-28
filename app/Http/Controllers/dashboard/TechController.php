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
            'comment' => "required|integer",
            'cab' => "required|string",
            'box' => "required|string",
            'db' => "required|string",
            'cable_type' => "required|string",
            'cable_length' => "required|string",
            'connectors' => "required|string",
            'face_split' => "required|string",
            'type' => "required|string",
        ]);

        $task->update([
            'cab' => $request['cab'],
            'box' => $request['box'],
            'db' => $request['db'],
            'cable_type' => $request['cable_type'],
            'cable_length' => $request['cable_length'],
            'connectors' => $request['connectors'],
            'face_split' => $request['face_split'],
            'comment_id' => $request['comment'],
            'type' => $request['type'],
        ]);



        alertSuccess('task updated successfully', 'تم تعديل المهمة بنجاح');
        return redirect()->route('tech.tasks');
    }
}
