<?php

namespace App\Services;

use App\Models\ActivityLog;

class ActivityLogService
{
    public function log(string $event, array $data): void
    {
        try {
            // Tente d'écrire le log dans MongoDB
            ActivityLog::create(array_merge(
                ['event' => $event, 'timestamp' => now()->toIso8601String()],
                $data
            ));
        } catch (\Exception $e) {
            // Si MongoDB n'est pas disponible, on continue sans planter
            // Les logs sont optionnels — ils ne doivent jamais bloquer l'app
            \Log::warning('ActivityLog failed: ' . $e->getMessage());
        }
    }
}