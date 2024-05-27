<?php

namespace App\Jobs;

use App\Models\Task;
use App\Models\TaskDetail;
use App\Models\LateTask;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessLateTasks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $yesterday = now()->subDay()->toDateString();

        $tasks = Task::where('due_date', $yesterday)->get();
        foreach ($tasks as $task) {
            // Retrieve task details where status is not 'completed' 
            // or status is 'completed' but actual_finished_date is greater(later) than due_date
            $taskDetails = TaskDetail::where('task_id', $task->id)
                ->where(function($query) use ($task) {
                    $query->where('status', '!=', 'completed')
                          ->orWhere(function($query) use ($task) {
                              $query->where('status', 'completed')
                                    ->where('actual_finished_date', '>', $task->due_date);
                          });
                })
                ->get();
                
            foreach ($taskDetails as $taskDetail) {
                $exists = LateTask::where('task_detail_id', $taskDetail->id)
                                    ->where('user_id', $taskDetail->user_id)
                                    ->exists();

                if (!$exists) {
                    LateTask::create([
                        'task_detail_id' => $taskDetail->id,
                        'user_id' => $taskDetail->user_id,
                    ]);
                }
            }
        }
    }
}
