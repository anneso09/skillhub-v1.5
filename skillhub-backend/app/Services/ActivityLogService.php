<?php

namespace App\Services;

use App\Models\ActivityLog;

class ActivityLogService
{
    public function log(string $event, array $data): void
    {
        ActivityLog::create(array_merge(
            ['event' => $event, 'timestamp' => now()->toIso8601String()],
            $data
        ));
    }
}