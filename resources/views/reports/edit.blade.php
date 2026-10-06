@extends('layouts.app')

@section('title', 'Edit Laporan - Sistem Laporan Kerja IT')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Edit Laporan Pekerjaan</h1>
            <p class="text-xs text-slate-500 mt-0.5">Perbarui rincian tugas atau berkas screenshot jika diperlukan</p>
        </div>
        <a href="{{ route('reports.show', $report) }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900">
            &larr; Batal &amp; Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('reports.update', $report) }}" enctype="multipart/form-data" class="bg-white rounded-xl border border-slate-200 shadow-xs p-6 space-y-5">
        @csrf
        @method('PUT')

        <!-- Judul Pekerjaan -->
        <div>
            <label for="title" class="block text-xs font-semibold text-slate-800 mb-1">
                Judul Pekerjaan <span class="text-red-600">*</span>
            </label>
            <input type="text" id="title" name="title" value="{{ old('title', $report->title) }}" required
                class="w-full px-3 py-2 text-sm bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-hidden focus:border-blue-800 focus:ring-1 focus:ring-blue-800">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            
            <!-- Aplikasi yang diubah -->
            <div>
                <label for="application_id" class="block text-xs font-semibold text-slate-800 mb-1">
                    Aplikasi yang Dikerjakan <span class="text-red-600">*</span>
                </label>
                <select id="application_id" name="application_id" required
                    class="w-full px-3 py-2 text-sm bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-hidden focus:border-blue-800 focus:ring-1 focus:ring-blue-800">
                    @foreach($applications as $app)
                        <option value="{{ $app->id }}" {{ old('application_id', $report->application_id) == $app->id ? 'selected' : '' }}>
                            {{ $app->name }} ({{ $app->slug }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tanggal Pengerjaan -->
            <div>
                <label for="work_date" class="block text-xs font-semibold text-slate-800 mb-1">
                    Tanggal Pengerjaan <span class="text-red-600">*</span>
                </label>
                <input type="date" id="work_date" name="work_date" value="{{ old('work_date', $report->work_date->format('Y-m-d')) }}" required
                    class="w-full px-3 py-2 text-sm bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-hidden focus:border-blue-800 focus:ring-1 focus:ring-blue-800">
            </div>

        </div>

        <!-- Tipe Pekerjaan -->
        <div>
            <label class="block text-xs font-semibold text-slate-800 mb-2">
                Tipe Pekerjaan <span class="text-red-600">*</span>
            </label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <label class="flex items-start p-3 border rounded-lg cursor-pointer transition-all hover:bg-slate-50 {{ old('work_type', $report->work_type) === 'pengubahan_fitur' ? 'border-blue-800 bg-blue-50/40 ring-1 ring-blue-800' : 'border-slate-200' }}">
                    <input type="radio" name="work_type" value="pengubahan_fitur" {{ old('work_type', $report->work_type) === 'pengubahan_fitur' ? 'checked' : '' }} class="mt-0.5 text-blue-900 focus:ring-0 mr-2.5">
                    <div>
                        <div class="text-xs font-bold text-slate-900">Pengubahan Fitur</div>
                        <div class="text-[11px] text-slate-500 mt-0.5">Perbaikan bug, refactoring query, atau optimasi UI existing.</div>
                    </div>
                </label>

                <label class="flex items-start p-3 border rounded-lg cursor-pointer transition-all hover:bg-slate-50 {{ old('work_type', $report->work_type) === 'bikin_baru' ? 'border-emerald-700 bg-emerald-50/40 ring-1 ring-emerald-700' : 'border-slate-200' }}">
                    <input type="radio" name="work_type" value="bikin_baru" {{ old('work_type', $report->work_type) === 'bikin_baru' ? 'checked' : '' }} class="mt-0.5 text-emerald-800 focus:ring-0 mr-2.5">
                    <div>
                        <div class="text-xs font-bold text-slate-900">Bikin Baru</div>
                        <div class="text-[11px] text-slate-500 mt-0.5">Pembuatan modul baru atau pengembangan sistem baru dari awal.</div>
                    </div>
                </label>
            </div>
        </div>

        <!-- Deskripsi Pekerjaan -->
        <div>
            <label for="description" class="block text-xs font-semibold text-slate-800 mb-1">
                Deskripsi Teknis Pekerjaan <span class="text-red-600">*</span>
            </label>
            <textarea id="description" name="description" rows="4" required
                class="w-full px-3 py-2 text-sm bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-hidden focus:border-blue-800 focus:ring-1 focus:ring-blue-800 leading-relaxed">{{ old('description', $report->description) }}</textarea>
        </div>

        <!-- Ganti Screenshots -->
        <div class="space-y-2 pt-1">
            <span class="block text-xs font-semibold text-slate-800">
                Dokumentasi &amp; Berkas Bukti (Kosongkan jika tidak diganti)
            </span>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                
                <!-- 1. SS Bukti Perintah -->
                <div class="space-y-1.5">
                    <label for="image_instruction" class="block text-[11px] font-semibold text-slate-700">
                        Bukti Perintah
                    </label>
                    <div class="p-3 border border-slate-200 rounded-lg bg-slate-50 text-center space-y-2">
                        @if($report->image_instruction)
                            <img src="{{ asset('storage/' . $report->image_instruction) }}" alt="Current SS Perintah" class="max-h-20 mx-auto rounded border border-slate-300">
                        @else
                            <div class="text-[11px] text-slate-400 py-2 italic">Belum ada berkas</div>
                        @endif
                        <input type="file" id="image_instruction" name="image_instruction" accept="image/*" class="w-full text-[11px] text-slate-500 file:mr-1.5 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-semibold file:bg-slate-200 file:text-slate-800 hover:file:bg-slate-300">
                    </div>
                </div>

                <!-- 2. SS Sebelumnya -->
                <div class="space-y-1.5">
                    <label for="image_before" class="block text-[11px] font-semibold text-slate-700">
                        Kondisi Sebelumnya
                    </label>
                    <div class="p-3 border border-slate-200 rounded-lg bg-slate-50 text-center space-y-2">
                        @if($report->image_before)
                            <img src="{{ asset('storage/' . $report->image_before) }}" alt="Current SS Sebelumnya" class="max-h-20 mx-auto rounded border border-slate-300">
                        @else
                            <div class="text-[11px] text-slate-400 py-2 italic">Belum ada berkas</div>
                        @endif
                        <input type="file" id="image_before" name="image_before" accept="image/*" class="w-full text-[11px] text-slate-500 file:mr-1.5 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-semibold file:bg-slate-200 file:text-slate-800 hover:file:bg-slate-300">
                    </div>
                </div>

                <!-- 3. SS Hasil -->
                <div class="space-y-1.5">
                    <label for="image_after" class="block text-[11px] font-semibold text-slate-800">
                        Hasil Pengerjaan
                    </label>
                    <div class="p-3 border border-slate-200 rounded-lg bg-slate-50 text-center space-y-2">
                        <img src="{{ asset('storage/' . $report->image_after) }}" alt="Current SS Hasil" class="max-h-20 mx-auto rounded border border-slate-300">
                        <input type="file" id="image_after" name="image_after" accept="image/*" class="w-full text-[11px] text-slate-500 file:mr-1.5 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-semibold file:bg-blue-50 file:text-blue-900 hover:file:bg-blue-100">
                    </div>
                </div>

            </div>
        </div>

        <!-- Buttons -->
        <div class="pt-4 border-t border-slate-200 flex items-center justify-end space-x-3">
            <a href="{{ route('reports.show', $report) }}" class="px-3.5 py-2 rounded-lg border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 rounded-lg bg-blue-900 hover:bg-blue-800 text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer">
                Simpan Perubahan
            </button>
        </div>

    </form>

</div>
@endsection
