<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ActivityLog extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'activity_logs';

    protected $fillable = [
        'event',
        'user_id',
        'course_id',
        'old_values',
        'new_values',
        'created_by',
        'updated_by',
        'deleted_by',
        'titre',
        'timestamp',
    ];
}