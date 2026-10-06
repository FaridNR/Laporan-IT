<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use App\Models\WorkReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->getFilteredData($request);

        return view('rekap.index', $data);
    }

    public function printPdf(Request $request)
    {
        $data = $this->getFilteredData($request);

        return view('rekap.print', $data);
    }

    public function exportCsv(Request $request)
    {
        $data = $this->getFilteredData($request);
        $reports = $data['reports'];

        $fileName = 'rekap_pekerjaan_it_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($reports) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header row
            fputcsv($handle, [
                'ID',
                'Tanggal Pengerjaan',
                'Nama Programer',
                'Aplikasi',
                'Tipe Pekerjaan',
                'Judul Pekerjaan',
                'Deskripsi',
                'Ada Bukti Perintah',
                'Ada SS Sebelumnya',
                'Dibuat Pada'
            ]);

            foreach ($reports as $r) {
                fputcsv($handle, [
                    $r->id,
                    $r->work_date->format('d/m/Y'),
                    $r->user->name,
                    $r->application->name,
                    $r->work_type === 'bikin_baru' ? 'Bikin Baru' : 'Pengubahan Fitur',
                    $r->title,
                    $r->description,
                    $r->image_instruction ? 'Ya' : 'Tidak',
                    $r->image_before ? 'Ya' : 'Tidak',
                    $r->created_at->format('d/m/Y H:i')
                ]);
            }

            fclose($handle);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    private function getFilteredData(Request $request): array
    {
        $query = WorkReport::with(['user', 'application']);

        $period = $request->input('period', 'this_month');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($period === 'today') {
            $query->whereDate('work_date', Carbon::today());
            $startDate = Carbon::today()->toDateString();
            $endDate = Carbon::today()->toDateString();
        } elseif ($period === 'this_week') {
            $startDate = Carbon::now()->startOfWeek()->toDateString();
            $endDate = Carbon::now()->endOfWeek()->toDateString();
            $query->whereBetween('work_date', [$startDate, $endDate]);
        } elseif ($period === 'this_month') {
            $startDate = Carbon::now()->startOfMonth()->toDateString();
            $endDate = Carbon::now()->endOfMonth()->toDateString();
            $query->whereBetween('work_date', [$startDate, $endDate]);
        } elseif ($period === 'this_year') {
            $startDate = Carbon::now()->startOfYear()->toDateString();
            $endDate = Carbon::now()->endOfYear()->toDateString();
            $query->whereBetween('work_date', [$startDate, $endDate]);
        } elseif ($period === 'custom' && $startDate && $endDate) {
            $query->whereBetween('work_date', [$startDate, $endDate]);
        }

        if ($appId = $request->input('application_id')) {
            $query->where('application_id', $appId);
        }

        if ($userId = $request->input('user_id')) {
            $query->where('user_id', $userId);
        }

        if ($workType = $request->input('work_type')) {
            $query->where('work_type', $workType);
        }

        $reports = $query->orderBy('work_date', 'desc')->get();

        $totalReports = $reports->count();
        $totalBikinBaru = $reports->where('work_type', 'bikin_baru')->count();
        $totalPengubahan = $reports->where('work_type', 'pengubahan_fitur')->count();

        // Breakdown per application in current filtered set
        $apps = Application::all();
        $appStats = $apps->map(function ($app) use ($reports) {
            $appReports = $reports->where('application_id', $app->id);
            return (object) [
                'name' => $app->name,
                'slug' => $app->slug,
                'total' => $appReports->count(),
                'bikin_baru' => $appReports->where('work_type', 'bikin_baru')->count(),
                'pengubahan' => $appReports->where('work_type', 'pengubahan_fitur')->count(),
            ];
        });

        // Breakdown per programmer in current filtered set
        $programmers = User::where('role', 'programer')->get();
        $programmerStats = $programmers->map(function ($prog) use ($reports) {
            $progReports = $reports->where('user_id', $prog->id);
            return (object) [
                'name' => $prog->name,
                'email' => $prog->email,
                'total' => $progReports->count(),
                'bikin_baru' => $progReports->where('work_type', 'bikin_baru')->count(),
                'pengubahan' => $progReports->where('work_type', 'pengubahan_fitur')->count(),
            ];
        });

        return [
            'reports' => $reports,
            'totalReports' => $totalReports,
            'totalBikinBaru' => $totalBikinBaru,
            'totalPengubahan' => $totalPengubahan,
            'appStats' => $appStats,
            'programmerStats' => $programmerStats,
            'applications' => $apps,
            'programmers' => $programmers,
            'period' => $period,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
    }
}
