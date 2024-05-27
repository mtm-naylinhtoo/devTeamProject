<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskDetail extends Model
{
    protected $fillable = ['task_id', 'user_id', 'status', 'actual_finished_date'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function lateTask()
    {
        return $this->hasOne(LateTask::class);
    }

    use HasFactory;
}
