<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LateTask extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'task_detail_id',
        'read_status',
        'reason',
    ];

    /**
     * Get the user that owns the late task.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the task detail that is associated with the late task.
     */
    public function taskDetail()
    {
        return $this->belongsTo(TaskDetail::class);
    }
}
