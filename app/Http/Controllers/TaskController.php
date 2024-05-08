<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskDetail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;


class TaskController extends Controller
{
    public function index(Request $request)
    {
        // Get tasks based on search query
        $tasks = Task::query();
        
        if ($request->has('search')) {
            $tasks->where('title', 'like', '%' . $request->search . '%');
        }

        // Get tasks based on selected month
        if ($request->has('month')) {
            $tasks->whereMonth('due_date', $request->month);
        }

        $tasks = $tasks->get();

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        // Get a list of all users to populate the dropdown
        $users = User::pluck('name', 'id'); // This creates an array with user IDs as keys and names as values
    
        return view('tasks.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'nullable|string',
            'users' => 'required|array', // Expecting an array of user IDs
            'due_date' => 'required|date',
        ]);
        // Start transaction to ensure data integrity
        DB::beginTransaction();
        try {
            $task = Task::create([
                'title' => $request->title,
                'description' => $request->description,
                'url' => $request->url,
                'due_date' => $request->due_date,
            ]);
            foreach ($request->users as $user_id) {
                TaskDetail::create([
                    'task_id' => $task->id,
                    'user_id' => $user_id,
                    'status' => 'pending', // Default status
                ]);
            }
    
            // Commit the transaction
            DB::commit();
            return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
        } catch (\Exception $e) {
            // An error occurred; cancel the transaction
            DB::rollBack();
            return back()->withErrors('Failed to create the task.')->withInput();
        }
    }
    
    public function show(Task $task)
    {
        // Eager load the task details and associated users
        $task->load('details.user');
        // Get the details of the current user for this task
        $userDetail = TaskDetail::where('task_id', $task->id)
        ->where('user_id', auth()->user()->id)
        ->first();

        return view('tasks.show', compact('task', 'userDetail'));
    }

    public function edit(Task $task)
    {
        $users = User::all()->pluck('name', 'id');
        $selectedUsers = $task->details->pluck('user_id')->toArray();
    
        return view('tasks.edit', compact('task', 'users', 'selectedUsers'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'description' => 'nullable|string',
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
            'due_date' => 'required|date',
        ]);
    
        $task->update([
            'title' => $request->title,
            'url' => $request->url,
            'description' => $request->description,
            'due_date' => $request->due_date,
        ]);
    
        // Get the existing user IDs associated with the task
        $existingUserIds = $task->details()->pluck('user_id')->toArray();
    
        // Find the user IDs that are new or unchanged
        $newUserIds = array_intersect($existingUserIds, $request->users);
    
        // Delete existing task details for users not in the new list
        $task->details()->whereNotIn('user_id', $newUserIds)->delete();
    
        // Create or update task details for new or existing users
        foreach ($request->users as $userId) {
            $taskDetail = $task->details()->updateOrCreate(
                ['user_id' => $userId],
                ['status' => $task->details()->where('user_id', $userId)->first()->status ?? "pending",
                 'actual_finished_date' => $task->details()->where('user_id', $userId)->first()->actual_finished_date ?? null]
            );
        }
    
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function update_status(Request $request, $task_detail_id)
    {
        // Retrieve the task detail from the database
        $taskDetail = TaskDetail::findOrFail($task_detail_id);
    
        // Update status
        $taskDetail->status = $request->status;
    
        // Set actual_finished_date based on status
        if ($request->status == 'completed') {
            $taskDetail->actual_finished_date = now();
        } elseif (in_array($request->status, ['pending', 'in_progress'])) {
            $taskDetail->actual_finished_date = null;
        }
    
        $taskDetail->save();
    
        // Return a JSON response
        return response()->json(['message' => 'Task status updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
