<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SleepLog extends Model
{
    protected $table = 'sleep_logs'; // table name

    protected $primaryKey = 'SleepLogID'; // primary key

    // disable timestamps
    public $timestamps = false;

    protected $dates = ['created_at'];

    protected $casts = [
        'BedTime' => 'datetime:H:i:s',
        'WakeTime' => 'datetime:H:i:s',
    ];    

    // fillable fields
    protected $fillable = [
        'SleepLogID',
        'UserID',
        'SleepDate',
        'BedTime',
        'WakeTime',
        'SleepDurationMinutes',
        'SleepQuality',
        'Notes',
        'created_at',
    ];

    // relationships

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'id');
        //                                                ^ foreign key in sleep_logs
        //                                                                   ^ local key in users
    }

}
