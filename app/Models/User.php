<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function isAdmin()
    {
        return in_array($this->role, ['manager','bse', 'leader', 'sub-leader']);
    }

    public function tasks()
    {
        return $this->hasMany(TaskDetail::class);
    }

    public function unfinishedTasks()
    {
        return $this->hasMany(TaskDetail::class)->where('status', '!=', 'completed');
    }

    public function sortedTasks($year = null, $month = null)
    {

        $query = $this->tasks()
                    ->join('tasks', 'task_details.task_id', '=', 'tasks.id')
                    ->select('task_details.*')
                    ->orderBy('tasks.due_date', 'asc');

        if ($year !== null && $month !== null) {
            $query->whereYear('tasks.due_date', $year)
                ->whereMonth('tasks.due_date', $month);
        }

        return $query->get();
    }

    public function sortedUnfinishedTasks($year = null, $month = null)
    {
        $query = $this->unfinishedTasks()
                    ->join('tasks', 'task_details.task_id', '=', 'tasks.id')
                    ->select('task_details.*')
                    ->orderBy('tasks.due_date', 'asc');

        if ($year !== null && $month !== null) {
            $query->whereYear('tasks.due_date', $year)
                ->whereMonth('tasks.due_date', $month);
        }

        return $query->get();
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function hasfeedbacks()
    {
        return $this->tasks()->whereHas('feedbacks')->exists();
    }


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
