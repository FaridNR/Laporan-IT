<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use App\Models\WorkReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WorkReportController extends Controller
{
    public function index(Request $request)
    {
        $query = WorkReport::with(['user', 'application']);

        // Search text
        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('application', function ($aq) use ($search) {
                        $aq->where('name', 'like', "%{$search}%")
                           ->orWhere('slug', 'like', "%{$search}%");
                    });
            });
        }

        // Filter Application
        if ($appId = $request->input('application_id')) {
            $query->where('application_id', $appId);
        }

        // Filter Work Type
        if ($workType = $request->input('work_type')) {
            $query->where('work_type', $workType);
        }

        // Filter Programmer
        if ($userId = $request->input('user_id')) {
            $query->where('user_id', $userId);
        }

        // Filter Date Range
        if ($startDate = $request->input('start_date')) {
            $query->whereDate('work_date', '>=', $startDate);
        }
        if ($endDate = $request->input('end_date')) {
            $query->whereDate('work_date', '<=', $endDate);
        }

        $reports = $query->latest('work_date')->latest('id')->paginate(10)->withQueryString();

        $applications = Application::orderBy('name')->get();
        $programmers = User::where('role', 'programer')->orderBy('name')->get();

        return view('reports.index', compact('reports', 'applications', 'programmers'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'programer') {
            abort(403, 'Hanya role Programer yang dapat membuat laporan pekerjaan.');
        }

        $applications = Application::orderBy('name')->get();
        return view('reports.create', compact('applications'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'programer') {
            abort(403, 'Hanya role Programer yang dapat menginput laporan pekerjaan.');
        }

        $validated = $request->validate([
            'application_id' => ['required', 'exists:applications,id'],
            'work_type' => ['required', 'in:bikin_baru,pengubahan_fitur'],
            'work_date' => ['required', 'date'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image_after' => ['required', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:5120'],
            'image_before' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:5120'],
            'image_instruction' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:5120'],
        ]);

        $afterPath = $request->file('image_after')->store('reports', 'public');
        $beforePath = null;
        if ($request->hasFile('image_before')) {
            $beforePath = $request->file('image_before')->store('reports', 'public');
        }
        $instructionPath = null;
        if ($request->hasFile('image_instruction')) {
            $instructionPath = $request->file('image_instruction')->store('reports', 'public');
        }

        WorkReport::create([
            'user_id' => Auth::id(),
            'application_id' => $validated['application_id'],
            'work_type' => $validated['work_type'],
            'work_date' => $validated['work_date'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image_after' => $afterPath,
            'image_before' => $beforePath,
            'image_instruction' => $instructionPath,
        ]);

        return redirect()->route('reports.index')->with('success', 'Laporan pekerjaan berhasil disimpan.');
    }

    public function show(WorkReport $report)
    {
        $report->load(['user', 'application']);
        return view('reports.show', compact('report'));
    }

    public function edit(WorkReport $report)
    {
        if (Auth::user()->role !== 'programer' || Auth::id() !== $report->user_id) {
            abort(403, 'Anda hanya dapat mengedit laporan pekerjaan milik Anda sendiri.');
        }

        $applications = Application::orderBy('name')->get();
        return view('reports.edit', compact('report', 'applications'));
    }

    public function update(Request $request, WorkReport $report)
    {
        if (Auth::user()->role !== 'programer' || Auth::id() !== $report->user_id) {
            abort(403, 'Anda hanya dapat mengedit laporan pekerjaan milik Anda sendiri.');
        }

        $validated = $request->validate([
            'application_id' => ['required', 'exists:applications,id'],
            'work_type' => ['required', 'in:bikin_baru,pengubahan_fitur'],
            'work_date' => ['required', 'date'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image_after' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:5120'],
            'image_before' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:5120'],
            'image_instruction' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:5120'],
        ]);

        $data = [
            'application_id' => $validated['application_id'],
            'work_type' => $validated['work_type'],
            'work_date' => $validated['work_date'],
            'title' => $validated['title'],
            'description' => $validated['description'],
        ];

        if ($request->hasFile('image_after')) {
            if ($report->image_after && Storage::disk('public')->exists($report->image_after)) {
                Storage::disk('public')->delete($report->image_after);
            }
            $data['image_after'] = $request->file('image_after')->store('reports', 'public');
        }

        if ($request->hasFile('image_before')) {
            if ($report->image_before && Storage::disk('public')->exists($report->image_before)) {
                Storage::disk('public')->delete($report->image_before);
            }
            $data['image_before'] = $request->file('image_before')->store('reports', 'public');
        }

        if ($request->hasFile('image_instruction')) {
            if ($report->image_instruction && Storage::disk('public')->exists($report->image_instruction)) {
                Storage::disk('public')->delete($report->image_instruction);
            }
            $data['image_instruction'] = $request->file('image_instruction')->store('reports', 'public');
        }

        $report->update($data);

        return redirect()->route('reports.show', $report)->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy(WorkReport $report)
    {
        if (Auth::user()->role !== 'programer' || Auth::id() !== $report->user_id) {
            abort(403, 'Anda hanya dapat menghapus laporan pekerjaan milik Anda sendiri.');
        }

        if ($report->image_after && Storage::disk('public')->exists($report->image_after)) {
            Storage::disk('public')->delete($report->image_after);
        }
        if ($report->image_before && Storage::disk('public')->exists($report->image_before)) {
            Storage::disk('public')->delete($report->image_before);
        }
        if ($report->image_instruction && Storage::disk('public')->exists($report->image_instruction)) {
            Storage::disk('public')->delete($report->image_instruction);
        }

        $report->delete();

        return redirect()->route('reports.index')->with('success', 'Laporan berhasil dihapus.');
    }
}
