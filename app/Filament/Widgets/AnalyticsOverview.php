<?php

namespace App\Filament\Widgets;

use App\Models\Plan;
use App\Models\Photo;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
class AnalyticsOverview extends BaseWidget
{
   protected function getStats(): array
    {
        $totalUsers = User::count();
        $totalStorage = Photo::sum('file_size');
        $usersPerPlan = Plan::withCount('users')->get()->map(function ($plan) {
            return "{$plan->name}: {$plan->users_count}";
        })->join(', ');

        return [
            Stat::make('Total Users', $totalUsers),
            Stat::make('Total Storage Used', number_format($totalStorage, 2) . ' MB'),
            Stat::make('Users Per Plan', $usersPerPlan)
                ->description('Breakdown by plan'),
        ];
    }
}
