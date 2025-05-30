<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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

    function isAdmin(): bool
    {
        return $this->role === 'Admin';
    }

    public function forumPosts()
    {
        return $this->hasMany(ForumPost::class, 'UserID', 'id');
    }

    public function forumReplies()
    {
        return $this->hasMany(ForumReply::class, 'UserID', 'id');
    }

    public function moodLogs()
    {
        // Order by MoodDate in descending order
        return $this->hasMany(MoodLog::class, 'UserID', 'id')->orderBy('MoodDate', 'desc');
    }

    public function sleepLogs()
    {
        // Order by SleepDate in descending order
        return $this->hasMany(SleepLog::class, 'UserID', 'id')->orderBy('SleepDate', 'desc');
    }

    public function goals() // ACTIVE GOALS
    {
        // Order by GoalTargetDate in descending order
        return $this->hasMany(Goal::class, 'UserID', 'id')->orderBy('GoalTargetDate', 'desc');
    }

    public function goalLogs() // COMPLETED GOALS
    {
        // Order by GoalLogDate in descending order
        return $this->hasMany(GoalLog::class, 'UserID', 'id')->orderBy('GoalLogDate', 'desc');
    }

    public function exerciseLogs()
    {
        // Order by ExerciseDateTime in descending order
        return $this->hasMany(ExerciseLog::class, 'UserID', 'id')->orderBy('ExerciseDateTime', 'desc');
    }

    public function exercisePlans()
    {
        // Order by ExerciseDateTime in descending order
        return $this->hasMany(ExercisePlan::class, 'UserID', 'id')->orderBy('ExerciseDateTime', 'desc');
    }
}
