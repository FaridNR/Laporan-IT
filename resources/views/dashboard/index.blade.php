@extends('layouts.app')

@section('title', 'Dashboard - Sistem Laporan Kerja IT')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Dashboard Monitoring</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Selamat bekerja, <strong>{{ auth()->user()->name }}</strong>. 
                @if(auth()->user()->isProgramer())
                    Gunakan sistem ini untuk mendokumentasikan setiap pembaruan aplikasi.
                @elseif(auth()->user()->isStaffIt())
                    Pantau aktivitas seluruh pengembang dan gunakan fitur rekap untuk pelaporan.
                @else
                    Tinjau rekapitulasi kinerja tim pengembang pada 6 aplikasi utama.
                @endif
            </p>
        </div>

        <div class="flex items-center gap-2">
            @if(auth()->user()->isProgramer())
                <a href="{{ route('reports.create') }}" class="inline-flex items-center px-3.5 py-2 rounded-lg text-xs font-semibold text-white bg-blue-900 hover:bg-blue-800 transition-colors shadow-xs">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Input Laporan Baru
                </a>
            @endif
            <a href="{{ route('rekap.index') }}" class="inline-flex items-center px-3.5 py-2 rounded-lg text-xs font-semibold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 transition-colors shadow-xs">
                <svg class="w-3.5 h-3.5 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Rekapitulasi Data
            </a>
        </div>
    </div>

    <!-- 4 Structured KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-xs">
            <span class="text-xs font-semibold text-slate-500">Total Pekerjaan</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-slate-900">{{ $totalReports }}</span>
                <span class="text-[11px] text-slate-500">Log tercatat</span>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-xs">
            <span class="text-xs font-semibold text-emerald-700">Fitur Baru Dibuat</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-emerald-700">{{ $totalBikinBaru }}</span>
                <span class="text-[11px] text-emerald-800">Modul baru</span>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-xs">
            <span class="text-xs font-semibold text-blue-700">Pengubahan Fitur</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-blue-700">{{ $totalPengubahan }}</span>
                <span class="text-[11px] text-blue-800">Perbaikan &amp; refactor</span>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-xs">
            <span class="text-xs font-semibold text-slate-500">Programer Aktif</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-slate-900">{{ $totalProgrammers }}</span>
                <span class="text-[11px] text-slate-500">Personil pengembang</span>
            </div>
        </div>

    </div>

    <!-- Section: 6 Applications Status Matrix -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-5">
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-200">
            <div>
                <h2 class="text-sm font-bold text-slate-900">Distribusi Tugas per Aplikasi (6 Sistem Utama)</h2>
                <p class="text-xs text-slate-500">Klik tombol filter untuk melihat seluruh laporan pada modul tersebut</p>
            </div>
            <a href="{{ route('reports.index') }}" class="text-xs font-semibold text-blue-800 hover:underline">
                Lihat Semua Laporan
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
            @foreach($applications as $app)
                <div class="p-3.5 rounded-lg border border-slate-200 hover:border-slate-300 hover:bg-slate-50/50 transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="font-bold text-xs text-slate-900 tracking-tight">{{ $app->name }}</span>
                            <span class="text-[11px] font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                {{ $app->work_reports_count }} Tugas
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed mb-3">
                            {{ $app->description ?? 'Modul aplikasi internal.' }}
                        </p>
                    </div>

                    <div class="pt-2.5 border-t border-slate-100 flex items-center justify-between text-xs">
                        <div class="flex items-center space-x-2 text-[11px]">
                            <span class="text-emerald-700 font-medium">+{{ $app->bikin_baru_count }} Baru</span>
                            <span class="text-slate-300">|</span>
                            <span class="text-blue-700 font-medium">~{{ $app->pengubahan_count }} Ubah</span>
                        </div>
                        <a href="{{ route('reports.index', ['application_id' => $app->id]) }}" class="text-[11px] font-semibold text-blue-900 hover:underline">
                            Filter Data
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Bottom Row: Recent Activity & Programmers -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Recent Reports List (2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-xs p-5">
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-200">
                <h2 class="text-sm font-bold text-slate-900">Catatan Pekerjaan Terkini</h2>
                <a href="{{ route('reports.index') }}" class="text-xs font-semibold text-blue-800 hover:underline">
                    Buka Seluruhnya
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($recentReports as $rep)
                    <div class="py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-start space-x-3">
                            <button type="button" onclick="openImageModal('{{ asset('storage/' . $rep->image_after) }}', '{{ addslashes($rep->title) }}')"
                                class="w-12 h-12 rounded-lg bg-slate-100 overflow-hidden border border-slate-200 hover:border-blue-700 transition-colors flex-shrink-0 cursor-pointer" title="Klik untuk memperbesar">
                                <img src="{{ asset('storage/' . $rep->image_after) }}" alt="Thumbnail" class="w-full h-full object-cover">
                            </button>

                            <div class="space-y-0.5">
                                <div class="flex items-center space-x-2 flex-wrap text-xs">
                                    <span class="font-bold text-slate-800">{{ $rep->application->name }}</span>
                                    <span class="text-slate-300">&bull;</span>
                                    @if($rep->work_type === 'bikin_baru')
                                        <span class="text-[10px] font-semibold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                            Bikin Baru
                                        </span>
                                    @else
                                        <span class="text-[10px] font-semibold text-blue-800 bg-blue-50 px-2 py-0.5 rounded border border-blue-200">
                                            Pengubahan Fitur
                                        </span>
                                    @endif
                                    <span class="text-slate-300">&bull;</span>
                                    <span class="text-[11px] text-slate-400">{{ $rep->work_date->format('d M Y') }}</span>
                                </div>

                                <a href="{{ route('reports.show', $rep) }}" class="text-xs font-semibold text-slate-900 hover:text-blue-900 block line-clamp-1">
                                    {{ $rep->title }}
                                </a>

                                <p class="text-[11px] text-slate-500">
                                    Oleh: <span class="text-slate-700 font-medium">{{ $rep->user->name }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center self-end sm:self-center">
                            <a href="{{ route('reports.show', $rep) }}" class="px-2.5 py-1 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-md transition-colors">
                                Rincian
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="py-6 text-center text-xs text-slate-400">
                        Belum ada laporan pekerjaan yang tercatat.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right: Programmers Output Summary (1 col) -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-5">
            <h2 class="text-sm font-bold text-slate-900 pb-3 mb-3 border-b border-slate-200">
                Aktivitas Pengembang
            </h2>

            <div class="space-y-2.5">
                @foreach($programmersSummary as $prog)
                    <div class="p-2.5 rounded-lg border border-slate-100 bg-slate-50 flex items-center justify-between">
                        <div class="flex items-center space-x-2.5">
                            <div class="w-8 h-8 rounded-full bg-blue-900 text-white flex items-center justify-center font-bold text-xs">
                                {{ strtoupper(substr($prog->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-slate-900">{{ $prog->name }}</div>
                                <div class="text-[11px] text-slate-400">{{ $prog->email }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-slate-900">{{ $prog->work_reports_count }}</span>
                            <span class="block text-[10px] text-slate-500">laporan</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-5 p-3 rounded-lg bg-slate-100 border border-slate-200 text-xs text-slate-600">
                <span class="font-semibold block text-slate-800 mb-1">Catatan Sistem:</span>
                <p class="text-[11px] text-slate-500 leading-relaxed">
                    Sistem ini mengutamakan pelaporan transparan tanpa approval. Semua laporan yang dimasukkan langsung dapat ditinjau dan diekspor oleh tim.
                </p>
            </div>
        </div>

    </div>

</div>
@endsection
