<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TaskDetail;
use App\Models\User;

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

        // $usersWithCompletedTasks = User::whereHas('tasks', function ($query) {
        //     $query->where('status', 'completed');
        // })->whereDoesntHave('feedbacks', function ($query) {
        //     $query->where('user_id', auth()->id());
        // })->get()->filter(function ($user) {
        //     return permission_allow(auth()->user(), $user);
        // });

        // Fetch users with completed tasks
        $usersWithCompletedTasksQuery = User::whereHas('tasks', function ($query) {
            $query->where('status', 'completed');
        })->whereDoesntHave('feedbacks', function ($query) {
            $query->where('user_id', auth()->id());
        });

        // Conditionally add the where clause for assigned_to based on the user's role
        if ($user->role !== 'manager') {
            $usersWithCompletedTasksQuery->where('assigned_to', auth()->id());
        }

        $usersWithCompletedTasks = $usersWithCompletedTasksQuery->get()->filter(function ($user) {
            return permission_allow(auth()->user(), $user);
        });

        $usersWithoutLeader = User::whereNull('assigned_to')
        ->whereIn('role', ['junior-developer', 'senior-developer'])
        ->get();

        return view('dashboard', compact('task_details', 'completedCount', 'inProgressCount', 'pendingCount', 'usersWithCompletedTasks', 'usersWithoutLeader'));
    }
}
