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
        $authUser = auth()->user();
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Retrieve tasks for the authenticated user
        $all_task_details = $authUser->sortedTasks($currentYear, $currentMonth);
        $task_details = $authUser->sortedUnfinishedTasks($currentYear, $currentMonth);

        $completedCount = $all_task_details->where('status', 'completed')->count();
        $inProgressCount = $all_task_details->where('status', 'in_progress')->count();
        $pendingCount = $all_task_details->where('status', 'pending')->count();
        $lateTasksCount = $authUser->lateTasks()->count();
        
        // Fetch users with completed tasks but without feedback from the authenticated user for those tasks
        $usersWithCompletedTasksQuery = User::whereHas('tasks', function ($query) use ($authUser) {
            $query->where('status', 'completed')
                ->whereDoesntHave('feedbacks', function ($feedbackQuery) use ($authUser) {
                    $feedbackQuery->where('user_id', $authUser->id);
                });
        });

        // Conditionally add the where clause for assigned_to based on the user's role
        if ($authUser->role !== 'manager') {
            $usersWithCompletedTasksQuery->where('assigned_to', $authUser->id);
        }

        // Execute the query
        $allUsers = $usersWithCompletedTasksQuery->get();

        // Filter users based on permissions
        $usersWithCompletedTasks = $allUsers->filter(function ($user) use ($authUser) {
            return permission_allow($authUser, $user);
        });

        $usersWithoutLeader = User::whereNull('assigned_to')
        ->whereIn('role', ['junior-developer', 'senior-developer'])
        ->get();

        return view('dashboard', compact('task_details', 'completedCount', 'inProgressCount', 'pendingCount', 'lateTasksCount', 'usersWithCompletedTasks', 'usersWithoutLeader'));
    }
}
