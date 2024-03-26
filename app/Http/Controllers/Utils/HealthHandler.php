<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class HealthHandler extends Controller
{
    public function health(): JsonResponse
    {
        return response()->json([
            'message' => 'Alive and kicking!',
            'time' => now()->toDateTimeString()
        ]);
    }

    public function liveness(): JsonResponse
    {
        return response()->json([
            'mysql' => $this->testMysqlConnection()
        ]);
    }

    protected function testMysqlConnection()
    {
        $results = [];

        $driver =  config('database.connections.mysql');
        $start = microtime(true);

        try {
            DB::connection('mysql')->select("SELECT 'Health check'");
            $results[] = [
                'alive' => true,
                'host' => $driver['host'],
                'duration' => $this->calculateTime($start) . ' milliseconds',
            ];
        } catch (\Throwable $e) {
            $results[] = [
                'alive' => false,
                'host' => $driver['host'],
                'error' => $e->getMessage(),
                'duration' => $this->calculateTime($start) . ' milliseconds',
                ];
        }

        return $results;
    }

    protected function calculateTime(float $start): float
    {
        return round((microtime(true) - $start) * 1000);
    }
}
