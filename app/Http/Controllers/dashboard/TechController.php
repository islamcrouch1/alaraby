<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
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
}
