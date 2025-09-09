<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\UtilityUsage;
use Carbon\Carbon;

class UtilityUsageChart extends Component
{
    public array $labels = [];
    public array $waterData = [];
    public array $electricData = [];

    public function __construct()
    {
        $startDate = Carbon::now()->subMonths(5)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $usages = UtilityUsage::with('utilityRate')
            ->whereBetween('reading_date', [$startDate, $endDate])
            ->orderBy('reading_date')
            ->get()
            ->groupBy(function($item) {
                return $item->reading_date->format('Y-m');
            });

        foreach ($usages as $month => $monthlyUsages) {
            $this->labels[] = Carbon::createFromFormat('Y-m', $month)->format('M Y');
            
            $waterUsage = $monthlyUsages->sum(function($usage) {
                return $usage->water_meter_end - $usage->water_meter_start;
            });
            $this->waterData[] = round($waterUsage, 2);

            $electricUsage = $monthlyUsages->sum(function($usage) {
                return $usage->electric_meter_end - $usage->electric_meter_start;
            });
            $this->electricData[] = round($electricUsage, 2);
        }
    }

    public function render()
    {
        return view('components.utility-usage-chart');
    }
}
