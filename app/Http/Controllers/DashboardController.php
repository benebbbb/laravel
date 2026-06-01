<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $totalUsers = User::count();
        $totalMedicines = Medicine::where('created_by', $userId)->count();

        $expiringSoon = Medicine::where('created_by', $userId)
            ->where('expiration_date', '>=', now())
            ->where('expiration_date', '<=', now()->addDays(7))
            ->count();

        $expired = Medicine::where('created_by', $userId)
            ->where('expiration_date', '<', now())
            ->count();

        // get medicines grouped by category for pie chart
        $byCategory = Medicine::where('created_by', $userId)
            ->selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        // get medicines per month for bar chart
        $byMonth = Medicine::where('created_by', $userId)
            ->whereYear('created_at', now()->year)
            ->selectRaw('DATE_FORMAT(created_at, "%m") as month, count(*) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $month = str_pad($i, 2, '0', STR_PAD_LEFT);
            $monthlyData[] = $byMonth->get($month, 0);
        }

        return view('dashboard.index', compact(
            'totalUsers',
            'totalMedicines',
            'expiringSoon',
            'expired',
            'byCategory',
            'monthlyData'
        ));
    }
}
