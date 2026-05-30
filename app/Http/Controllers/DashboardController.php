<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers     = User::count();
        $totalMedicines = Medicine::where('created_by', Auth::id())->count();
        $expiringSoon   = Medicine::where('created_by', Auth::id())
            ->whereBetween('expiration_date', [now(), now()->addDays(7)])
            ->count();
        $expired        = Medicine::where('created_by', Auth::id())
            ->where('expiration_date', '<', now())
            ->count();

        // Medicines per category (for pie chart)
        $byCategory = Medicine::where('created_by', Auth::id())
            ->selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        // Medicines added per month this year (for bar chart)
        $byMonth = Medicine::where('created_by', Auth::id())
            ->whereYear('created_at', now()->year)
            ->selectRaw('DATE_FORMAT(created_at, "%m") as month, count(*) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        // Fill all 12 months
        $monthlyData = collect(range(1, 12))->map(
            fn($m) => $byMonth->get(str_pad($m, 2, '0', STR_PAD_LEFT), 0)
        );

        return view('dashboard.index', compact(
            'totalUsers', 'totalMedicines', 'expiringSoon', 'expired',
            'byCategory', 'monthlyData'
        ));
    }
}
