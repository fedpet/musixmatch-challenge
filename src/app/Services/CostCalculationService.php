<?php


namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

class CostCalculationService
{
    public static function monthlyCostForUser(User $user, Carbon $date) {
        return $user->logs()
            ->whereBetween('dateOfExit', [$date->copy()->startOfMonth(), $date->copy()->endOfMonth()])
            ->sum('cost');
    }

    public static function monthlyCostForAll(Carbon $date) {
        return User::query()
                ->join('devices', 'devices.user_id', '=', 'users.id')
                ->join('logs', 'logs.device_id', '=', 'devices.id')
                ->whereBetween('logs.dateOfExit', [$date->copy()->startOfMonth(), $date->copy()->endOfMonth()])
                ->groupBy('users.id')
                ->selectRaw('sum(logs.cost) as cost, users.id, users.name')
                ->get();
    }
}
