<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - Sistem Laporan Kerja IT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex items-center justify-center p-4 text-slate-900 font-sans">

    <div class="max-w-md w-full">
        <!-- Logo & Header -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-blue-900 text-white font-bold text-lg mb-3 shadow-xs">
                IT
            </div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Sistem Laporan Kerja IT</h1>
            <p class="text-xs text-slate-500 mt-1">Pencatatan &amp; Pemantauan Tugas Pengembangan Aplikasi</p>
        </div>

        <!-- Main Login Card -->
        <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8 shadow-xs">
            
            @if(session('info'))
                <div class="mb-4 p-3 bg-blue-50 border border-blue-200 text-blue-900 text-xs rounded-lg">
                    {{ session('info') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-900 text-xs rounded-lg">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">
                        Alamat Email
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@instansi.id"
                        class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-hidden focus:border-blue-800 focus:ring-1 focus:ring-blue-800">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">
                        Kata Sandi
                    </label>
                    <input id="password" type="password" name="password" required placeholder="Masukkan kata sandi"
                        class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-hidden focus:border-blue-800 focus:ring-1 focus:ring-blue-800">
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-blue-900 focus:ring-0 mr-2">
                        <span>Ingat sesi masuk</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 bg-blue-900 hover:bg-blue-800 text-white font-semibold text-xs rounded-lg shadow-xs transition-colors cursor-pointer">
                    Masuk ke Akun
                </button>
            </form>

        </div>

        <div class="mt-4 text-center text-xs text-slate-400">
            Internal IT &bull; SPONTAN, Investasi, Antrian-MPP, MPP, SADAP, PetaPotensi
        </div>
    </div>

</body>
</html>
