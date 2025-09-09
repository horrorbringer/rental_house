<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $currentMonth = $now->format('Y-m');

        // Get total revenue stats
        $monthlyRevenue = Room::whereHas('rentals', function($query) {
            $query->where('status', 'active');
        })->sum('monthly_rent');

        // Get building and room stats
        $buildings = Building::count();
        $rooms = Room::count();
        $vacantRooms = Room::where('status', 'vacant')->count();
        $occupiedRooms = $rooms - $vacantRooms;
        $occupancyRate = $rooms > 0 ? round(($occupiedRooms / $rooms) * 100) : 0;

        // Get recent activities
        $recentActivities = collect();

        // Get recent tenants
        $recentTenants = Tenant::with('rentals.room.building')
            ->latest()
            ->take(5)
            ->get()
            ->map(function($tenant) {
                return [
                    'type' => 'tenant',
                    'message' => "New tenant registered",
                    'subject' => $tenant->name,
                    'date' => $tenant->created_at,
                    'room' => $tenant->rentals->first()?->room->room_number ?? 'N/A',
                    'building' => $tenant->rentals->first()?->room->building->name ?? 'N/A',
                    'color' => 'blue'
                ];
            });

        // Sort activities
        $recentActivities = $recentTenants
            ->sortByDesc('date')
            ->take(5);

        // Get upcoming rent payments
        $upcomingPayments = Invoice::with('rental.tenant', 'rental.room')
            ->where('due_date', '>=', now())
            ->orderBy('due_date')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'monthlyRevenue',
            'buildings',
            'rooms',
            'vacantRooms',
            'occupiedRooms',
            'occupancyRate',
            'recentActivities',
            'upcomingPayments'
        ));
    }
}
