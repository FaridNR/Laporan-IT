@extends('layouts.app')

@section('title', 'Input Laporan Pekerjaan - Sistem Laporan Kerja IT')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Input Laporan Pekerjaan</h1>
            <p class="text-xs text-slate-500 mt-0.5">Dokumentasikan progres pengerjaan fitur atau perbaikan aplikasi</p>
        </div>
        <a href="{{ route('reports.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900">
            &larr; Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data" class="bg-white rounded-xl border border-slate-200 shadow-xs p-6 space-y-5">
        @csrf

        <!-- Judul Pekerjaan -->
        <div>
            <label for="title" class="block text-xs font-semibold text-slate-800 mb-1">
                Judul Pekerjaan <span class="text-red-600">*</span>
            </label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required placeholder="Contoh: Penambahan filter tanggal pada laporan antrian loket"
                class="w-full px-3 py-2 text-sm bg-white border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-hidden focus:border-blue-800 focus:ring-1 focus:ring-blue-800">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            
            <!-- Aplikasi yang diubah -->
            <div>
                <label for="application_id" class="block text-xs font-semibold text-slate-800 mb-1">
                    Aplikasi yang Dikerjakan <span class="text-red-600">*</span>
                </label>
                <select id="application_id" name="application_id" required
                    class="w-full px-3 py-2 text-sm bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-hidden focus:border-blue-800 focus:ring-1 focus:ring-blue-800">
                    <option value="">-- Pilih Aplikasi --</option>
                    @foreach($applications as $app)
                        <option value="{{ $app->id }}" {{ old('application_id') == $app->id ? 'selected' : '' }}>
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
                <input type="date" id="work_date" name="work_date" value="{{ old('work_date', date('Y-m-d')) }}" required
                    class="w-full px-3 py-2 text-sm bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-hidden focus:border-blue-800 focus:ring-1 focus:ring-blue-800">
            </div>

        </div>

        <!-- Tipe Pekerjaan (2 Pilihan) -->
        <div>
            <label class="block text-xs font-semibold text-slate-800 mb-2">
                Tipe Pekerjaan <span class="text-red-600">*</span>
            </label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                
                <label class="flex items-start p-3 border rounded-lg cursor-pointer transition-all hover:bg-slate-50 {{ old('work_type', 'pengubahan_fitur') === 'pengubahan_fitur' ? 'border-blue-800 bg-blue-50/40 ring-1 ring-blue-800' : 'border-slate-200' }}">
                    <input type="radio" name="work_type" value="pengubahan_fitur" {{ old('work_type', 'pengubahan_fitur') === 'pengubahan_fitur' ? 'checked' : '' }} class="mt-0.5 text-blue-900 focus:ring-0 mr-2.5">
                    <div>
                        <div class="text-xs font-bold text-slate-900">Pengubahan Fitur</div>
                        <div class="text-[11px] text-slate-500 mt-0.5">Perbaikan bug, refactoring query, penyesuaian tata letak UI, atau flow existing.</div>
                    </div>
                </label>

                <label class="flex items-start p-3 border rounded-lg cursor-pointer transition-all hover:bg-slate-50 {{ old('work_type') === 'bikin_baru' ? 'border-emerald-700 bg-emerald-50/40 ring-1 ring-emerald-700' : 'border-slate-200' }}">
                    <input type="radio" name="work_type" value="bikin_baru" {{ old('work_type') === 'bikin_baru' ? 'checked' : '' }} class="mt-0.5 text-emerald-800 focus:ring-0 mr-2.5">
                    <div>
                        <div class="text-xs font-bold text-slate-900">Bikin Baru</div>
                        <div class="text-[11px] text-slate-500 mt-0.5">Pembuatan modul baru, integrasi fitur baru, atau penambahan halaman dari nol.</div>
                    </div>
                </label>

            </div>
        </div>

        <!-- Deskripsi Pekerjaan -->
        <div>
            <label for="description" class="block text-xs font-semibold text-slate-800 mb-1">
                Deskripsi Teknis Pekerjaan <span class="text-red-600">*</span>
            </label>
            <textarea id="description" name="description" rows="4" required placeholder="Uraikan apa yang dikerjakan, perubahan yang dilakukan, atau kendala yang berhasil diselesaikan..."
                class="w-full px-3 py-2 text-sm bg-white border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-hidden focus:border-blue-800 focus:ring-1 focus:ring-blue-800 leading-relaxed">{{ old('description') }}</textarea>
        </div>

        <!-- Upload Screenshots Grid -->
        <div class="space-y-2 pt-1">
            <span class="block text-xs font-semibold text-slate-800">
                Dokumentasi &amp; Bukti Visual
            </span>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                
                <!-- 1. SS Bukti Perintah (Opsional) -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label for="image_instruction" class="block text-[11px] font-semibold text-slate-700">
                            Bukti Perintah
                        </label>
                        <span class="text-[10px] text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded">Opsional</span>
                    </div>
                    <div class="p-3 border border-dashed border-slate-300 hover:border-slate-500 rounded-lg bg-slate-50 text-center transition-colors">
                        <input type="file" id="image_instruction" name="image_instruction" accept="image/*" class="hidden" onchange="previewImage(this, 'preview_instruction')">
                        <label for="image_instruction" class="cursor-pointer block">
                            <div id="preview_instruction" class="mb-2 hidden">
                                <img src="" alt="Preview Bukti Perintah" class="max-h-24 mx-auto rounded border border-slate-300 object-cover">
                            </div>
                            <div id="placeholder_instruction" class="py-2.5">
                                <span class="text-xs font-semibold text-slate-700 block">Pilih Bukti Perintah</span>
                                <span class="text-[10px] text-slate-400 block mt-0.5">Surat / chat permintaan fitur</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- 2. SS Sebelumnya (Opsional) -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label for="image_before" class="block text-[11px] font-semibold text-slate-700">
                            Kondisi Sebelumnya
                        </label>
                        <span class="text-[10px] text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded">Opsional</span>
                    </div>
                    <div class="p-3 border border-dashed border-slate-300 hover:border-slate-500 rounded-lg bg-slate-50 text-center transition-colors">
                        <input type="file" id="image_before" name="image_before" accept="image/*" class="hidden" onchange="previewImage(this, 'preview_before')">
                        <label for="image_before" class="cursor-pointer block">
                            <div id="preview_before" class="mb-2 hidden">
                                <img src="" alt="Preview Sebelumnya" class="max-h-24 mx-auto rounded border border-slate-300 object-cover">
                            </div>
                            <div id="placeholder_before" class="py-2.5">
                                <span class="text-xs font-semibold text-slate-700 block">Pilih Kondisi Awal</span>
                                <span class="text-[10px] text-slate-400 block mt-0.5">Tampilan bug / sebelum diubah</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- 3. SS Hasil (Wajib) -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label for="image_after" class="block text-[11px] font-semibold text-slate-800">
                            Hasil Selesai <span class="text-red-600">*</span>
                        </label>
                        <span class="text-[10px] text-emerald-800 font-bold bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">Wajib</span>
                    </div>
                    <div class="p-3 border border-dashed border-slate-300 hover:border-blue-800 rounded-lg bg-slate-50 text-center transition-colors">
                        <input type="file" id="image_after" name="image_after" accept="image/*" required class="hidden" onchange="previewImage(this, 'preview_after')">
                        <label for="image_after" class="cursor-pointer block">
                            <div id="preview_after" class="mb-2 hidden">
                                <img src="" alt="Preview Hasil" class="max-h-24 mx-auto rounded border border-slate-300 object-cover">
                            </div>
                            <div id="placeholder_after" class="py-2.5">
                                <span class="text-xs font-semibold text-blue-900 block">Pilih Screenshot Hasil</span>
                                <span class="text-[10px] text-slate-400 block mt-0.5">Tampilan fitur yang sudah jadi</span>
                            </div>
                        </label>
                    </div>
                </div>

            </div>
        </div>

        <!-- Buttons -->
        <div class="pt-4 border-t border-slate-200 flex items-center justify-end space-x-3">
            <a href="{{ route('reports.index') }}" class="px-3.5 py-2 rounded-lg border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 rounded-lg bg-blue-900 hover:bg-blue-800 text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer">
                Simpan Laporan Pekerjaan
            </button>
        </div>

    </form>

</div>

<script>
    function previewImage(input, previewId) {
        const previewContainer = document.getElementById(previewId);
        const placeholderId = previewId.replace('preview', 'placeholder');
        const placeholder = document.getElementById(placeholderId);
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewContainer.querySelector('img').src = e.target.result;
                previewContainer.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
