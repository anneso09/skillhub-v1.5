<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;

class ActivityLogService
{
    public function log(string $event, array $data): void
    {
        try {
            ActivityLog::create(array_merge(
                ['event' => $event, 'timestamp' => now()->toIso8601String()],
                $data
            ));
        } catch (\Exception $e) {
            // Si MongoDB n'est pas disponible, on continue sans planter
            Log::warning('ActivityLog failed: ' . $e->getMessage());
        }
    }
}