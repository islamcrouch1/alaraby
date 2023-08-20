<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Task;
use App\Models\TaskImage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

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
            // ->whereDate('end_date', '>=', $date)
            // ->whereDate('task_date', '<=',  $date)
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
            'cab' => "nullable|string",
            'box' => "nullable|string",
            'db' => "nullable|string",
            'cable_type' => "nullable|string",
            'cable_length' => "nullable|string",
            'connectors' => "nullable|string",
            'face_split' => "nullable|string",
            'type' => "nullable|string",
        ]);


        if ($request->hasFile('images') && $files = $request->file('images')) {
            foreach ($files as $file) {
                Image::make($file)->save(public_path('storage/images/tasks/' . $file->hashName()), 80);
                TaskImage::create([
                    'task_id' => $task->id,
                    'image' => $file->hashName(),
                ]);
            }
        }

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
        if (Auth::user()->hasRole('tech')) {
            return redirect()->route('tech.tasks');
        } else {
            return redirect()->route('tasks.index');
        }
    }
}
