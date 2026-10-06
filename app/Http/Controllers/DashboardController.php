<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use App\Models\WorkReport;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalReports = WorkReport::count();
        $totalBikinBaru = WorkReport::where('work_type', 'bikin_baru')->count();
        $totalPengubahan = WorkReport::where('work_type', 'pengubahan_fitur')->count();
        $totalProgrammers = User::where('role', 'programer')->count();

        // Application breakdown with counts
        $applications = Application::withCount([
            'workReports',
            'workReports as bikin_baru_count' => function ($query) {
                $query->where('work_type', 'bikin_baru');
            },
            'workReports as pengubahan_count' => function ($query) {
                $query->where('work_type', 'pengubahan_fitur');
            },
        ])->get();

        // Recent 6 work reports
        $recentReports = WorkReport::with(['user', 'application'])
            ->latest('work_date')
            ->latest('id')
            ->take(6)
            ->get();

        // Programmers summary
        $programmersSummary = User::where('role', 'programer')
            ->withCount('workReports')
            ->get();

        return view('dashboard.index', compact(
            'totalReports',
            'totalBikinBaru',
            'totalPengubahan',
            'totalProgrammers',
            'applications',
            'recentReports',
            'programmersSummary'
        ));
    }
}
