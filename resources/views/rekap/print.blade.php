<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rekapitulasi Laporan Pekerjaan IT - {{ $startDate }} s.d {{ $endDate }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 15mm 15mm 15mm;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                color: #000000 !important;
                font-size: 11pt;
            }
            table {
                page-break-inside: auto;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
    </style>
</head>
<body class="bg-slate-100 py-6 px-4 font-sans text-slate-900">

    <!-- Top Action Floating Bar for screen view -->
    <div class="max-w-4xl mx-auto mb-4 no-print flex items-center justify-between bg-white p-3 rounded-xl border border-slate-200 shadow-xs">
        <div class="text-xs text-slate-600">
            Pratinjau Cetak / Ekspor PDF &bull; Tekan tombol di samping atau gunakan shortcut keyboard <kbd class="bg-slate-100 px-1 py-0.5 rounded border border-slate-300">Cmd/Ctrl + P</kbd>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg text-xs font-semibold shadow-xs transition-colors cursor-pointer flex items-center space-x-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak / Simpan PDF</span>
            </button>
            <button onclick="window.close()" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition-colors cursor-pointer">
                Tutup
            </button>
        </div>
    </div>

    <!-- Printable Paper Container -->
    <div class="max-w-4xl mx-auto bg-white p-8 sm:p-12 rounded-xl border border-slate-200 shadow-xs">
        
        <!-- Kop Surat -->
        <div class="border-b-2 border-slate-900 pb-4 mb-6 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-lg bg-blue-900 flex items-center justify-center text-white font-bold text-lg">
                    IT
                </div>
                <div>
                    <h1 class="text-base font-bold uppercase tracking-wider text-slate-900">
                        DIVISI TEKNOLOGI INFORMASI &amp; PENGEMBANGAN SISTEM
                    </h1>
                    <p class="text-xs text-slate-600 mt-0.5">
                        Rekapitulasi Laporan Pekerjaan Pengembangan Aplikasi
                    </p>
                    <p class="text-[11px] text-slate-500 mt-0.5">
                        Aplikasi: SPONTAN, Investasi, Antrian-MPP, MPP, SADAP, PetaPotensi
                    </p>
                </div>
            </div>
            <div class="text-right text-xs text-slate-600">
                <div>Periode: <strong>{{ $startDate ?? 'Awal' }} s/d {{ $endDate ?? 'Sekarang' }}</strong></div>
                <div class="text-[11px] text-slate-500 mt-0.5">Tanggal Cetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB</div>
            </div>
        </div>

        <!-- Summary Statistics Row -->
        <div class="grid grid-cols-3 gap-3 mb-6 p-3 rounded-lg bg-slate-50 border border-slate-200 text-center">
            <div>
                <span class="text-[11px] text-slate-500 uppercase block font-semibold">Total Pekerjaan</span>
                <span class="text-xl font-bold text-slate-900">{{ $totalReports }}</span>
            </div>
            <div>
                <span class="text-[11px] text-emerald-800 uppercase block font-semibold">Fitur Baru Dibuat</span>
                <span class="text-xl font-bold text-emerald-700">{{ $totalBikinBaru }}</span>
            </div>
            <div>
                <span class="text-[11px] text-blue-800 uppercase block font-semibold">Pengubahan Fitur</span>
                <span class="text-xl font-bold text-blue-700">{{ $totalPengubahan }}</span>
            </div>
        </div>

        <!-- Section 1: Ringkasan per Aplikasi -->
        <div class="mb-6">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-2">
                I. Ringkasan Aktivitas Berdasarkan Aplikasi
            </h2>
            <table class="w-full text-xs border-collapse border border-slate-300">
                <thead class="bg-slate-100 text-slate-900">
                    <tr>
                        <th class="border border-slate-300 p-2 text-left font-semibold">Nama Aplikasi</th>
                        <th class="border border-slate-300 p-2 text-center font-semibold">Bikin Baru</th>
                        <th class="border border-slate-300 p-2 text-center font-semibold">Pengubahan Fitur</th>
                        <th class="border border-slate-300 p-2 text-right font-semibold">Total Tugas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appStats as $stat)
                        <tr>
                            <td class="border border-slate-300 p-2 font-medium">{{ $stat->name }} ({{ $stat->slug }})</td>
                            <td class="border border-slate-300 p-2 text-center text-emerald-800 font-semibold">{{ $stat->bikin_baru }}</td>
                            <td class="border border-slate-300 p-2 text-center text-blue-800 font-semibold">{{ $stat->pengubahan }}</td>
                            <td class="border border-slate-300 p-2 text-right font-bold text-slate-900">{{ $stat->total }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Section 2: Ringkasan per Pengembang -->
        <div class="mb-6">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-2">
                II. Ringkasan Output Berdasarkan Pengembang
            </h2>
            <table class="w-full text-xs border-collapse border border-slate-300">
                <thead class="bg-slate-100 text-slate-900">
                    <tr>
                        <th class="border border-slate-300 p-2 text-left font-semibold">Nama Pengembang</th>
                        <th class="border border-slate-300 p-2 text-left font-semibold">Email</th>
                        <th class="border border-slate-300 p-2 text-center font-semibold">Bikin Baru</th>
                        <th class="border border-slate-300 p-2 text-center font-semibold">Pengubahan</th>
                        <th class="border border-slate-300 p-2 text-right font-semibold">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($programmerStats as $pStat)
                        <tr>
                            <td class="border border-slate-300 p-2 font-medium">{{ $pStat->name }}</td>
                            <td class="border border-slate-300 p-2 text-slate-600">{{ $pStat->email }}</td>
                            <td class="border border-slate-300 p-2 text-center text-emerald-800 font-semibold">{{ $pStat->bikin_baru }}</td>
                            <td class="border border-slate-300 p-2 text-center text-blue-800 font-semibold">{{ $pStat->pengubahan }}</td>
                            <td class="border border-slate-300 p-2 text-right font-bold text-slate-900">{{ $pStat->total }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Section 3: Rincian Catatan Log -->
        <div class="mb-8">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-2">
                III. Catatan Rincian Pekerjaan ({{ $reports->count() }} Data)
            </h2>
            <table class="w-full text-xs border-collapse border border-slate-300">
                <thead class="bg-slate-100 text-slate-900">
                    <tr>
                        <th class="border border-slate-300 p-2 text-center w-8 font-semibold">No</th>
                        <th class="border border-slate-300 p-2 text-left whitespace-nowrap font-semibold">Tanggal</th>
                        <th class="border border-slate-300 p-2 text-left whitespace-nowrap font-semibold">Pengembang</th>
                        <th class="border border-slate-300 p-2 text-left whitespace-nowrap font-semibold">Aplikasi</th>
                        <th class="border border-slate-300 p-2 text-left whitespace-nowrap font-semibold">Tipe</th>
                        <th class="border border-slate-300 p-2 text-left font-semibold">Judul &amp; Uraian Pekerjaan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $idx => $r)
                        <tr>
                            <td class="border border-slate-300 p-2 text-center">{{ $idx + 1 }}</td>
                            <td class="border border-slate-300 p-2 whitespace-nowrap text-[11px]">{{ $r->work_date->format('d/m/Y') }}</td>
                            <td class="border border-slate-300 p-2 whitespace-nowrap font-medium">{{ $r->user->name }}</td>
                            <td class="border border-slate-300 p-2 whitespace-nowrap font-semibold">{{ $r->application->name }}</td>
                            <td class="border border-slate-300 p-2 whitespace-nowrap text-[11px]">
                                {{ $r->work_type === 'bikin_baru' ? 'Bikin Baru' : 'Pengubahan' }}
                            </td>
                            <td class="border border-slate-300 p-2 leading-relaxed">
                                <strong class="block font-semibold text-slate-900">{{ $r->title }}</strong>
                                <span class="text-slate-600 text-[11px] block mt-0.5">{{ $r->description }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="border border-slate-300 p-4 text-center text-slate-500">
                                Tidak ada data pekerjaan pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Signature Block -->
        <div class="pt-6 border-t border-slate-200 grid grid-cols-2 text-xs">
            <div>
                <p class="text-slate-500 font-medium">Keterangan:</p>
                <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                    Dokumen ini merupakan rekapitulasi sah pencatatan pekerjaan pengembangan sistem IT.
                </p>
            </div>
            <div class="text-center ml-auto w-64">
                <p class="text-slate-600">Mengetahui,</p>
                <p class="font-bold text-slate-900 mb-16">Kepala Divisi IT</p>
                <p class="font-bold underline text-slate-900">Dehen Nurwin Adam</p>
                <p class="text-[11px] text-slate-500">NIP. 19820415 200801 1 002</p>
            </div>
        </div>

    </div>

</body>
</html>
