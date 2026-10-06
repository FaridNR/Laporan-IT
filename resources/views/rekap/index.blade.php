@extends('layouts.app')

@section('title', 'Rekapitulasi Pekerjaan IT - Sistem Laporan Kerja IT')

@section('content')
<div class="space-y-6">

    <!-- Header & Export Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Rekapitulasi Laporan Pekerjaan</h1>
            <p class="text-xs text-slate-500 mt-0.5">Laporan berkala kinerja pengembangan aplikasi untuk Staff IT dan Kepala IT</p>
        </div>

        <div class="flex items-center space-x-2">
            <!-- Print / PDF -->
            <a href="{{ route('rekap.print', request()->query()) }}" target="_blank"
                class="inline-flex items-center px-3.5 py-2 rounded-lg text-xs font-semibold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 shadow-xs transition-colors">
                <svg class="w-3.5 h-3.5 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak / Simpan PDF
            </a>

            <!-- CSV / Excel -->
            <a href="{{ route('rekap.csv', request()->query()) }}"
                class="inline-flex items-center px-3.5 py-2 rounded-lg text-xs font-semibold text-white bg-blue-900 hover:bg-blue-800 shadow-xs transition-colors">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Unduh Excel (CSV)
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-4 space-y-4">
        <form method="GET" action="{{ route('rekap.index') }}" class="space-y-3">
            
            <!-- Period Selector Tabs -->
            <div class="flex items-center space-x-1.5 overflow-x-auto text-xs">
                <span class="text-slate-500 font-semibold mr-2 text-[11px]">Periode:</span>
                <a href="{{ route('rekap.index', array_merge(request()->except(['period', 'start_date', 'end_date']), ['period' => 'this_month'])) }}"
                    class="px-3 py-1.5 rounded-md border text-xs font-medium transition-colors {{ $period === 'this_month' ? 'bg-blue-900 border-blue-900 text-white font-semibold' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                    Bulan Ini
                </a>
                <a href="{{ route('rekap.index', array_merge(request()->except(['period', 'start_date', 'end_date']), ['period' => 'this_week'])) }}"
                    class="px-3 py-1.5 rounded-md border text-xs font-medium transition-colors {{ $period === 'this_week' ? 'bg-blue-900 border-blue-900 text-white font-semibold' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                    Minggu Ini
                </a>
                <a href="{{ route('rekap.index', array_merge(request()->except(['period', 'start_date', 'end_date']), ['period' => 'today'])) }}"
                    class="px-3 py-1.5 rounded-md border text-xs font-medium transition-colors {{ $period === 'today' ? 'bg-blue-900 border-blue-900 text-white font-semibold' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                    Hari Ini
                </a>
                <a href="{{ route('rekap.index', array_merge(request()->except(['period', 'start_date', 'end_date']), ['period' => 'this_year'])) }}"
                    class="px-3 py-1.5 rounded-md border text-xs font-medium transition-colors {{ $period === 'this_year' ? 'bg-blue-900 border-blue-900 text-white font-semibold' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                    Tahun Ini
                </a>
            </div>

            <!-- Granular filters -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 pt-3 border-t border-slate-100">
                <input type="hidden" name="period" value="{{ $period }}">

                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Mulai Tanggal</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                        class="w-full py-1.5 px-3 text-xs bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-hidden focus:border-blue-800">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                        class="w-full py-1.5 px-3 text-xs bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-hidden focus:border-blue-800">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Aplikasi</label>
                    <select name="application_id" class="w-full py-1.5 px-3 text-xs bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-hidden focus:border-blue-800">
                        <option value="">Semua Aplikasi</option>
                        @foreach($applications as $app)
                            <option value="{{ $app->id }}" {{ request('application_id') == $app->id ? 'selected' : '' }}>
                                {{ $app->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Programer</label>
                    <select name="user_id" class="w-full py-1.5 px-3 text-xs bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-hidden focus:border-blue-800">
                        <option value="">Semua Programer</option>
                        @foreach($programmers as $prog)
                            <option value="{{ $prog->id }}" {{ request('user_id') == $prog->id ? 'selected' : '' }}>
                                {{ $prog->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full py-1.5 px-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg text-xs font-semibold transition-colors cursor-pointer">
                        Filter Rekap
                    </button>
                </div>
            </div>

        </form>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-xs">
            <span class="text-xs font-semibold text-slate-500">Total Pekerjaan Periode Ini</span>
            <div class="mt-2 text-2xl font-bold text-slate-900">{{ $totalReports }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">Rentang: {{ $startDate ?? '-' }} s/d {{ $endDate ?? '-' }}</div>
        </div>

        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-xs">
            <span class="text-xs font-semibold text-emerald-700">Fitur Baru Selesai</span>
            <div class="mt-2 text-2xl font-bold text-emerald-700">{{ $totalBikinBaru }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">Pengembangan modul dari awal</div>
        </div>

        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-xs">
            <span class="text-xs font-semibold text-blue-700">Pengubahan &amp; Perbaikan</span>
            <div class="mt-2 text-2xl font-bold text-blue-700">{{ $totalPengubahan }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">Bugfix dan penyempurnaan UI/UX</div>
        </div>
    </div>

    <!-- Breakdown Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Table 1: Per Aplikasi -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-5">
            <h2 class="text-sm font-bold text-slate-900 mb-3">
                Rincian Pekerjaan Berdasarkan Aplikasi
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-slate-50 text-slate-700 border-b border-slate-200">
                        <tr>
                            <th class="py-2.5 px-3 font-semibold">Aplikasi</th>
                            <th class="py-2.5 px-3 text-center font-semibold">Bikin Baru</th>
                            <th class="py-2.5 px-3 text-center font-semibold">Pengubahan</th>
                            <th class="py-2.5 px-3 text-right font-semibold">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($appStats as $stat)
                            <tr class="hover:bg-slate-50">
                                <td class="py-2.5 px-3 font-semibold text-slate-900">
                                    {{ $stat->name }}
                                </td>
                                <td class="py-2.5 px-3 text-center text-emerald-700 font-semibold">
                                    {{ $stat->bikin_baru }}
                                </td>
                                <td class="py-2.5 px-3 text-center text-blue-700 font-semibold">
                                    {{ $stat->pengubahan }}
                                </td>
                                <td class="py-2.5 px-3 text-right font-bold text-slate-900">
                                    {{ $stat->total }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Table 2: Per Programer -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-5">
            <h2 class="text-sm font-bold text-slate-900 mb-3">
                Rincian Output Berdasarkan Pengembang
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-slate-50 text-slate-700 border-b border-slate-200">
                        <tr>
                            <th class="py-2.5 px-3 font-semibold">Nama Pengembang</th>
                            <th class="py-2.5 px-3 text-center font-semibold">Bikin Baru</th>
                            <th class="py-2.5 px-3 text-center font-semibold">Pengubahan</th>
                            <th class="py-2.5 px-3 text-right font-semibold">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($programmerStats as $pStat)
                            <tr class="hover:bg-slate-50">
                                <td class="py-2.5 px-3 font-semibold text-slate-900">
                                    {{ $pStat->name }}
                                </td>
                                <td class="py-2.5 px-3 text-center text-emerald-700 font-semibold">
                                    {{ $pStat->bikin_baru }}
                                </td>
                                <td class="py-2.5 px-3 text-center text-blue-700 font-semibold">
                                    {{ $pStat->pengubahan }}
                                </td>
                                <td class="py-2.5 px-3 text-right font-bold text-slate-900">
                                    {{ $pStat->total }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Table 3: Detailed Raw Logs in Period -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-5">
        <h2 class="text-sm font-bold text-slate-900 mb-3">
            Daftar Detail Pekerjaan dalam Periode ({{ $reports->count() }} Data)
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-700 border-b border-slate-200">
                    <tr>
                        <th class="py-2.5 px-3 font-semibold">Tanggal</th>
                        <th class="py-2.5 px-3 font-semibold">Pengembang</th>
                        <th class="py-2.5 px-3 font-semibold">Aplikasi</th>
                        <th class="py-2.5 px-3 font-semibold">Tipe</th>
                        <th class="py-2.5 px-3 font-semibold">Judul Pekerjaan</th>
                        <th class="py-2.5 px-3 text-center font-semibold">Bukti</th>
                        <th class="py-2.5 px-3 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reports as $r)
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-3 text-slate-600 whitespace-nowrap">
                                {{ $r->work_date->format('d/m/Y') }}
                            </td>
                            <td class="py-3 px-3 font-medium text-slate-900 whitespace-nowrap">
                                {{ $r->user->name }}
                            </td>
                            <td class="py-3 px-3 font-semibold text-slate-800 whitespace-nowrap">
                                {{ $r->application->name }}
                            </td>
                            <td class="py-3 px-3 whitespace-nowrap">
                                @if($r->work_type === 'bikin_baru')
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        Bikin Baru
                                    </span>
                                @else
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-blue-50 text-blue-800 border border-blue-200">
                                        Pengubahan
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-3 max-w-xs truncate text-slate-800 font-medium">
                                {{ $r->title }}
                            </td>
                            <td class="py-3 px-3 text-center whitespace-nowrap">
                                @if($r->image_instruction)
                                    <button type="button" onclick="openImageModal('{{ asset('storage/' . $r->image_instruction) }}', 'Bukti Perintah: {{ addslashes($r->title) }}')"
                                        class="text-slate-700 hover:underline font-semibold cursor-pointer">
                                        Perintah
                                    </button>
                                    <span class="text-slate-300">|</span>
                                @endif
                                <button type="button" onclick="openImageModal('{{ asset('storage/' . $r->image_after) }}', '{{ addslashes($r->title) }}')"
                                    class="text-blue-800 hover:underline font-semibold cursor-pointer">
                                    Hasil
                                </button>
                                @if($r->image_before)
                                    <span class="text-slate-300">|</span>
                                    <button type="button" onclick="openImageModal('{{ asset('storage/' . $r->image_before) }}', 'Sebelum: {{ addslashes($r->title) }}')"
                                        class="text-amber-800 hover:underline font-semibold cursor-pointer">
                                        Awal
                                    </button>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-right whitespace-nowrap">
                                <a href="{{ route('reports.show', $r) }}" class="text-slate-600 hover:text-blue-900 font-semibold">
                                    Rincian
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-xs text-slate-400">
                                Tidak ada catatan pekerjaan pada rentang filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
