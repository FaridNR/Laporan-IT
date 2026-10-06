@extends('layouts.app')

@section('title', 'Daftar Pekerjaan - Sistem Laporan Kerja IT')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Daftar Pekerjaan Pengembang</h1>
            <p class="text-xs text-slate-500 mt-0.5">Riwayat pembaruan, perbaikan, dan penambahan fitur pada 6 aplikasi utama</p>
        </div>

        @if(auth()->user()->isProgramer())
            <a href="{{ route('reports.create') }}" class="inline-flex items-center px-3.5 py-2 rounded-lg text-xs font-semibold text-white bg-blue-900 hover:bg-blue-800 transition-colors shadow-xs self-start sm:self-auto">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Input Pekerjaan Baru
            </a>
        @endif
    </div>

    <!-- Filter & Search Card -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-4">
        <form method="GET" action="{{ route('reports.index') }}" class="space-y-3">
            
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-3">
                
                <!-- Keyword Search -->
                <div class="md:col-span-2">
                    <label for="q" class="block text-xs font-semibold text-slate-700 mb-1">
                        Pencarian Kata Kunci
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" id="q" name="q" value="{{ request('q') }}" placeholder="Cari judul, deskripsi, atau fitur..."
                            class="w-full pl-9 pr-3 py-1.5 text-xs bg-white border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-hidden focus:border-blue-800 focus:ring-1 focus:ring-blue-800">
                    </div>
                </div>

                <!-- Filter Application -->
                <div>
                    <label for="application_id" class="block text-xs font-semibold text-slate-700 mb-1">
                        Aplikasi
                    </label>
                    <select id="application_id" name="application_id"
                        class="w-full py-1.5 px-3 text-xs bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-hidden focus:border-blue-800 focus:ring-1 focus:ring-blue-800">
                        <option value="">Semua Aplikasi</option>
                        @foreach($applications as $app)
                            <option value="{{ $app->id }}" {{ request('application_id') == $app->id ? 'selected' : '' }}>
                                {{ $app->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Work Type -->
                <div>
                    <label for="work_type" class="block text-xs font-semibold text-slate-700 mb-1">
                        Tipe Pekerjaan
                    </label>
                    <select id="work_type" name="work_type"
                        class="w-full py-1.5 px-3 text-xs bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-hidden focus:border-blue-800 focus:ring-1 focus:ring-blue-800">
                        <option value="">Semua Tipe</option>
                        <option value="bikin_baru" {{ request('work_type') === 'bikin_baru' ? 'selected' : '' }}>Bikin Baru</option>
                        <option value="pengubahan_fitur" {{ request('work_type') === 'pengubahan_fitur' ? 'selected' : '' }}>Pengubahan Fitur</option>
                    </select>
                </div>

                <!-- Filter Programmer -->
                <div>
                    <label for="user_id" class="block text-xs font-semibold text-slate-700 mb-1">
                        Programer
                    </label>
                    <select id="user_id" name="user_id"
                        class="w-full py-1.5 px-3 text-xs bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-hidden focus:border-blue-800 focus:ring-1 focus:ring-blue-800">
                        <option value="">Semua Programer</option>
                        @foreach($programmers as $prog)
                            <option value="{{ $prog->id }}" {{ request('user_id') == $prog->id ? 'selected' : '' }}>
                                {{ $prog->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Start Date -->
                <div>
                    <label for="start_date" class="block text-xs font-semibold text-slate-700 mb-1">
                        Dari Tanggal
                    </label>
                    <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}"
                        class="w-full py-1.5 px-3 text-xs bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-hidden focus:border-blue-800 focus:ring-1 focus:ring-blue-800">
                </div>

                <!-- Filter End Date -->
                <div>
                    <label for="end_date" class="block text-xs font-semibold text-slate-700 mb-1">
                        Sampai Tanggal
                    </label>
                    <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}"
                        class="w-full py-1.5 px-3 text-xs bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-hidden focus:border-blue-800 focus:ring-1 focus:ring-blue-800">
                </div>

                <!-- Actions -->
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 py-1.5 px-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg text-xs font-semibold transition-colors cursor-pointer">
                        Terapkan Filter
                    </button>
                    @if(request()->anyFilled(['q', 'application_id', 'work_type', 'user_id', 'start_date', 'end_date']))
                        <a href="{{ route('reports.index') }}" class="py-1.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-medium transition-colors">
                            Reset
                        </a>
                    @endif
                </div>

            </div>

        </form>
    </div>

    <!-- Results Status Bar -->
    <div class="flex items-center justify-between text-xs text-slate-500">
        <div>
            Ditemukan <strong class="text-slate-900 font-semibold">{{ $reports->total() }}</strong> catatan pekerjaan
        </div>
        <div class="text-[11px]">
            Halaman {{ $reports->currentPage() }} dari {{ $reports->lastPage() }}
        </div>
    </div>

    <!-- Reports Item List -->
    <div class="space-y-3.5">
        @forelse($reports as $rep)
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs hover:border-slate-300 transition-all p-4 sm:p-5">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                    
                    <div class="flex-1 space-y-2">
                        
                        <!-- Header badges & date -->
                        <div class="flex items-center space-x-2 flex-wrap text-xs">
                            <span class="font-bold text-slate-900 bg-slate-100 px-2.5 py-0.5 rounded border border-slate-200">
                                {{ $rep->application->name }}
                            </span>

                            @if($rep->work_type === 'bikin_baru')
                                <span class="font-semibold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 text-[11px]">
                                    Bikin Baru
                                </span>
                            @else
                                <span class="font-semibold text-blue-800 bg-blue-50 px-2 py-0.5 rounded border border-blue-200 text-[11px]">
                                    Pengubahan Fitur
                                </span>
                            @endif

                            <span class="text-slate-400">&bull;</span>
                            <span class="text-slate-500">{{ $rep->work_date->format('d F Y') }}</span>
                            <span class="text-slate-400">&bull;</span>
                            <span class="text-slate-600">Oleh: <strong class="text-slate-800">{{ $rep->user->name }}</strong></span>
                        </div>

                        <!-- Title -->
                        <a href="{{ route('reports.show', $rep) }}" class="text-sm sm:text-base font-bold text-slate-900 hover:text-blue-900 transition-colors block">
                            {{ $rep->title }}
                        </a>

                        <!-- Description Snippet -->
                        <p class="text-xs text-slate-600 leading-relaxed">
                            {{ Str::limit($rep->description, 260) }}
                        </p>

                        <!-- Thumbnail Previews -->
                        <div class="flex items-center gap-3 pt-2">
                            <!-- Bukti Perintah (jika ada) -->
                            @if($rep->image_instruction)
                                <div>
                                    <span class="text-[10px] text-slate-400 font-semibold block mb-1">Bukti Perintah</span>
                                    <button type="button" onclick="openImageModal('{{ asset('storage/' . $rep->image_instruction) }}', 'Bukti Perintah: {{ addslashes($rep->title) }}')"
                                        class="w-20 h-14 rounded-lg bg-slate-100 overflow-hidden border border-slate-300 hover:border-slate-500 transition-all cursor-pointer block" title="Perbesar Bukti Perintah">
                                        <img src="{{ asset('storage/' . $rep->image_instruction) }}" alt="Bukti Perintah" class="w-full h-full object-cover">
                                    </button>
                                </div>
                            @endif

                            <!-- Hasil (Wajib) -->
                            <div>
                                <span class="text-[10px] text-slate-400 font-semibold block mb-1">Hasil Pengerjaan</span>
                                <button type="button" onclick="openImageModal('{{ asset('storage/' . $rep->image_after) }}', 'Hasil: {{ addslashes($rep->title) }}')"
                                    class="w-20 h-14 rounded-lg bg-slate-100 overflow-hidden border border-slate-300 hover:border-blue-700 transition-all cursor-pointer block" title="Perbesar Screenshot">
                                    <img src="{{ asset('storage/' . $rep->image_after) }}" alt="SS Hasil" class="w-full h-full object-cover">
                                </button>
                            </div>

                            <!-- Sebelumnya (jika ada) -->
                            @if($rep->image_before)
                                <div>
                                    <span class="text-[10px] text-slate-400 font-semibold block mb-1">Sebelumnya</span>
                                    <button type="button" onclick="openImageModal('{{ asset('storage/' . $rep->image_before) }}', 'Sebelum: {{ addslashes($rep->title) }}')"
                                        class="w-20 h-14 rounded-lg bg-slate-100 overflow-hidden border border-slate-300 hover:border-amber-600 transition-all cursor-pointer block" title="Perbesar Screenshot">
                                        <img src="{{ asset('storage/' . $rep->image_before) }}" alt="SS Sebelumnya" class="w-full h-full object-cover">
                                    </button>
                                </div>
                            @endif
                        </div>

                    </div>

                    <!-- Right Actions -->
                    <div class="flex md:flex-col items-center md:items-end justify-between md:justify-start gap-2 pt-2 md:pt-0 border-t md:border-t-0 border-slate-100 flex-shrink-0">
                        <a href="{{ route('reports.show', $rep) }}" class="px-3 py-1.5 rounded-md text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
                            Lihat Rincian
                        </a>

                        @if(auth()->user()->isProgramer() && auth()->id() === $rep->user_id)
                            <div class="flex items-center space-x-2 text-xs">
                                <a href="{{ route('reports.edit', $rep) }}" class="text-blue-800 hover:underline font-semibold">
                                    Edit
                                </a>
                                <span class="text-slate-300">&bull;</span>
                                <form method="POST" action="{{ route('reports.destroy', $rep) }}" onsubmit="return confirm('Hapus laporan pekerjaan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-700 hover:underline font-semibold cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-slate-200 p-10 text-center">
                <h3 class="text-sm font-bold text-slate-900">Tidak ada laporan yang sesuai kriteria</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                    Silakan ubah filter kata kunci, aplikasi, atau rentang tanggal untuk menemukan laporan lainnya.
                </p>
                <div class="mt-4">
                    <a href="{{ route('reports.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
                        Reset Filter Pencarian
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $reports->links() }}
    </div>

</div>
@endsection
