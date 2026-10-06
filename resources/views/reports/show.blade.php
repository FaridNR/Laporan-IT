@extends('layouts.app')

@section('title', $report->title . ' - Sistem Laporan Kerja IT')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Action -->
    <div class="flex items-center justify-between pb-3 border-b border-slate-200">
        <a href="{{ route('reports.index') }}" class="inline-flex items-center text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar Pekerjaan
        </a>

        @if(auth()->user()->isProgramer() && auth()->id() === $report->user_id)
            <div class="flex items-center space-x-2">
                <a href="{{ route('reports.edit', $report) }}" class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                    Edit Laporan
                </a>
                <form method="POST" action="{{ route('reports.destroy', $report) }}" onsubmit="return confirm('Hapus laporan pekerjaan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-700 text-xs font-semibold transition-colors cursor-pointer">
                        Hapus
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- Main Detail Card -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-6 sm:p-8 space-y-6">
        
        <!-- Header Info -->
        <div>
            <div class="flex items-center space-x-2 flex-wrap text-xs mb-2">
                <span class="font-bold text-slate-900 bg-slate-100 px-2.5 py-0.5 rounded border border-slate-200">
                    {{ $report->application->name }} ({{ $report->application->slug }})
                </span>

                @if($report->work_type === 'bikin_baru')
                    <span class="font-semibold text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded border border-emerald-200 text-[11px]">
                        Bikin Baru
                    </span>
                @else
                    <span class="font-semibold text-blue-800 bg-blue-50 px-2.5 py-0.5 rounded border border-blue-200 text-[11px]">
                        Pengubahan Fitur
                    </span>
                @endif

                <span class="text-slate-400">&bull;</span>
                <span class="text-slate-500">Tanggal Pengerjaan: <strong class="text-slate-800">{{ $report->work_date->format('d F Y') }}</strong></span>
            </div>

            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight leading-snug">
                {{ $report->title }}
            </h1>

            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center space-x-3 text-xs text-slate-500">
                <div>
                    Pengembang: <strong class="text-slate-800">{{ $report->user->name }}</strong> ({{ $report->user->email }})
                </div>
                <div>&bull;</div>
                <div>
                    Dicatat pada: {{ $report->created_at->format('d M Y, H:i') }}
                </div>
            </div>
        </div>

        <!-- Description Box -->
        <div class="p-4 rounded-lg bg-slate-50 border border-slate-200">
            <h2 class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">
                Uraian Teknis Pekerjaan
            </h2>
            <div class="text-sm text-slate-800 leading-relaxed whitespace-pre-line font-sans">
                {{ $report->description }}
            </div>
        </div>

        <!-- Screenshots Comparison -->
        <div class="space-y-3 pt-2">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Dokumentasi &amp; Bukti Visual</h2>
                    <p class="text-xs text-slate-500">Klik gambar untuk melihat tampilan ukuran penuh</p>
                </div>
            </div>

            <div class="grid grid-cols-1 {{ ($report->image_instruction && $report->image_before) ? 'md:grid-cols-3' : (($report->image_instruction || $report->image_before) ? 'md:grid-cols-2' : 'max-w-2xl') }} gap-4">
                
                <!-- 1. Bukti Perintah (Jika ada) -->
                @if($report->image_instruction)
                    <div class="border border-slate-200 rounded-lg overflow-hidden bg-white shadow-xs">
                        <div class="px-3 py-2 bg-slate-100 border-b border-slate-200 text-xs font-semibold text-slate-700 flex items-center justify-between">
                            <span>Bukti Perintah</span>
                            <span class="text-[10px] text-slate-500 bg-white px-1.5 py-0.5 rounded border border-slate-200">Disposisi</span>
                        </div>
                        <div class="p-3 bg-slate-50 flex items-center justify-center">
                            <button type="button" onclick="openImageModal('{{ asset('storage/' . $report->image_instruction) }}', 'Bukti Perintah: {{ addslashes($report->title) }}')"
                                class="w-full h-56 rounded border border-slate-200 hover:border-slate-400 transition-all cursor-pointer block overflow-hidden bg-slate-900" title="Klik untuk memperbesar">
                                <img src="{{ asset('storage/' . $report->image_instruction) }}" alt="Bukti Perintah" class="w-full h-full object-contain">
                            </button>
                        </div>
                    </div>
                @endif

                <!-- 2. Kondisi Sebelumnya (Jika ada) -->
                @if($report->image_before)
                    <div class="border border-slate-200 rounded-lg overflow-hidden bg-white shadow-xs">
                        <div class="px-3 py-2 bg-slate-100 border-b border-slate-200 text-xs font-semibold text-slate-700 flex items-center justify-between">
                            <span>Kondisi Sebelumnya</span>
                            <span class="text-[10px] text-slate-500 bg-white px-1.5 py-0.5 rounded border border-slate-200">Kondisi Awal</span>
                        </div>
                        <div class="p-3 bg-slate-50 flex items-center justify-center">
                            <button type="button" onclick="openImageModal('{{ asset('storage/' . $report->image_before) }}', 'Sebelum: {{ addslashes($report->title) }}')"
                                class="w-full h-56 rounded border border-slate-200 hover:border-slate-400 transition-all cursor-pointer block overflow-hidden bg-slate-900" title="Klik untuk memperbesar">
                                <img src="{{ asset('storage/' . $report->image_before) }}" alt="Screenshot Kondisi Awal" class="w-full h-full object-contain">
                            </button>
                        </div>
                    </div>
                @endif

                <!-- 3. Hasil Pengerjaan (Wajib) -->
                <div class="border border-slate-200 rounded-lg overflow-hidden bg-white shadow-xs">
                    <div class="px-3 py-2 bg-slate-100 border-b border-slate-200 text-xs font-semibold text-slate-700 flex items-center justify-between">
                        <span>Hasil Pengerjaan</span>
                        <span class="text-[10px] text-emerald-800 font-bold bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">Selesai</span>
                    </div>
                    <div class="p-3 bg-slate-50 flex items-center justify-center">
                        <button type="button" onclick="openImageModal('{{ asset('storage/' . $report->image_after) }}', 'Hasil: {{ addslashes($report->title) }}')"
                            class="w-full h-56 rounded border border-slate-200 hover:border-blue-700 transition-all cursor-pointer block overflow-hidden bg-slate-900" title="Klik untuk memperbesar">
                            <img src="{{ asset('storage/' . $report->image_after) }}" alt="Screenshot Hasil Akhir" class="w-full h-full object-contain">
                        </button>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>
@endsection
