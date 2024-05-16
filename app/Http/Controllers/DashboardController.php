<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TaskDetail;

class DashboardController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Retrieve tasks for the authenticated user
        $all_task_details = $user->sortedTasks($currentYear, $currentMonth);
        $task_details = $user->sortedUnfinishedTasks($currentYear, $currentMonth);

        $completedCount = $all_task_details->where('status', 'completed')->count();
        $inProgressCount = $all_task_details->where('status', 'in_progress')->count();
        $pendingCount = $all_task_details->where('status', 'pending')->count();

        return view('dashboard', compact('task_details', 'completedCount', 'inProgressCount', 'pendingCount'));
    }
}
