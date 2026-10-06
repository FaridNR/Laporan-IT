<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Laporan Kerja IT')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-50 text-slate-900 flex flex-col font-sans">

    <!-- Top Navigation -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Left: Logo & Links -->
                <div class="flex items-center space-x-8">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 text-slate-900 hover:text-blue-900 transition-colors">
                        <div class="w-9 h-9 rounded-lg bg-blue-900 text-white flex items-center justify-center font-bold text-sm tracking-tight shadow-xs">
                            IT
                        </div>
                        <div>
                            <span class="text-sm font-bold tracking-tight text-slate-900 block leading-tight">Laporan Kerja IT</span>
                            <span class="text-[11px] text-slate-500 font-medium">Tracking Pengembangan Aplikasi</span>
                        </div>
                    </a>

                    <nav class="hidden md:flex items-center space-x-1">
                        <a href="{{ route('dashboard') }}" class="px-3 py-1.5 rounded-md text-xs font-semibold transition-colors {{ request()->routeIs('dashboard') || request()->routeIs('home') ? 'bg-slate-100 text-blue-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('reports.index') }}" class="px-3 py-1.5 rounded-md text-xs font-semibold transition-colors {{ request()->routeIs('reports.*') ? 'bg-slate-100 text-blue-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            Daftar Pekerjaan
                        </a>
                        <a href="{{ route('rekap.index') }}" class="px-3 py-1.5 rounded-md text-xs font-semibold transition-colors {{ request()->routeIs('rekap.*') ? 'bg-slate-100 text-blue-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            Rekapitulasi
                        </a>
                    </nav>
                </div>

                <!-- Right: Actions & Profile -->
                <div class="flex items-center space-x-3">
                    
                    @if(auth()->check() && auth()->user()->isProgramer())
                        <a href="{{ route('reports.create') }}" class="hidden sm:inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-blue-900 hover:bg-blue-800 transition-colors shadow-xs">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Input Laporan
                        </a>
                    @endif

                    <!-- User Info & Logout -->
                    <div class="flex items-center space-x-2 pl-3 border-l border-slate-200">
                        <div class="text-right hidden sm:block">
                            <div class="text-xs font-semibold text-slate-800">{{ auth()->user()->name }}</div>
                            <div class="text-[11px] text-slate-500 font-medium">
                                @if(auth()->user()->role === 'programer')
                                    Programer
                                @elseif(auth()->user()->role === 'staff_it')
                                    Staff IT
                                @else
                                    Kepala IT
                                @endif
                            </div>
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" title="Keluar dari akun" class="p-1.5 text-slate-400 hover:text-red-700 hover:bg-slate-100 rounded-md transition-colors cursor-pointer" aria-label="Keluar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <!-- Mobile Navigation Row -->
        <div class="md:hidden border-t border-slate-200 px-4 py-2 bg-slate-50 flex items-center justify-around text-xs font-medium">
            <a href="{{ route('dashboard') }}" class="py-1 px-2 {{ request()->routeIs('dashboard') ? 'text-blue-900 font-bold' : 'text-slate-600' }}">Dashboard</a>
            <a href="{{ route('reports.index') }}" class="py-1 px-2 {{ request()->routeIs('reports.*') ? 'text-blue-900 font-bold' : 'text-slate-600' }}">Pekerjaan</a>
            <a href="{{ route('rekap.index') }}" class="py-1 px-2 {{ request()->routeIs('rekap.*') ? 'text-blue-900 font-bold' : 'text-slate-600' }}">Rekap</a>
            @if(auth()->check() && auth()->user()->isProgramer())
                <a href="{{ route('reports.create') }}" class="py-1 px-2 text-blue-900 font-bold">+ Input</a>
            @endif
        </div>
    </header>

    <!-- Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4">
        @if(session('success'))
            <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-lg flex items-center justify-between text-xs font-medium shadow-xs mb-3">
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4 text-emerald-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 text-base leading-none">&times;</button>
            </div>
        @endif

        @if(session('info'))
            <div class="p-3 bg-blue-50 border border-blue-200 text-blue-900 rounded-lg flex items-center justify-between text-xs font-medium shadow-xs mb-3">
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4 text-blue-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('info') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-blue-700 hover:text-blue-900 text-base leading-none">&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div class="p-3 bg-red-50 border border-red-200 text-red-900 rounded-lg text-xs shadow-xs mb-3">
                <div class="font-semibold mb-1 flex items-center space-x-1.5">
                    <svg class="w-4 h-4 text-red-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Harap periksa kembali inputan:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 pl-2 text-red-800">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 text-xs text-slate-500 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div>
                Sistem Laporan Pekerjaan IT &bull; SPONTAN, Investasi, Antrian-MPP, MPP, SADAP, PetaPotensi
            </div>
            <div class="text-[11px] text-slate-400">
                Hak Akses: <span class="font-semibold text-slate-700">{{ strtoupper(str_replace('_', ' ', auth()->user()->role ?? 'GUEST')) }}</span>
            </div>
        </div>
    </footer>

    <!-- Clean Lightbox Modal -->
    <div id="imageModal" class="fixed inset-0 z-50 bg-slate-900/70 hidden items-center justify-center p-4" onclick="closeImageModal()">
        <div class="relative max-w-5xl max-h-[90vh] bg-white p-3 rounded-xl shadow-lg border border-slate-300" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between pb-2 mb-2 border-b border-slate-200 text-xs text-slate-700 font-semibold" id="imageModalHeader">
                <span id="imageModalTitle">Bukti Screenshot</span>
                <button type="button" onclick="closeImageModal()" class="w-6 h-6 rounded-md hover:bg-slate-100 flex items-center justify-center text-slate-500 hover:text-slate-800 text-sm font-bold cursor-pointer">
                    &times;
                </button>
            </div>
            <img id="imageModalSrc" src="" alt="Screenshot" class="max-h-[75vh] w-auto mx-auto rounded-md object-contain">
        </div>
    </div>

    <script>
        function openImageModal(src, title) {
            document.getElementById('imageModalSrc').src = src;
            if (title) {
                document.getElementById('imageModalTitle').textContent = title;
            }
            const modal = document.getElementById('imageModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeImageModal();
        });
    </script>
    @stack('scripts')
</body>
</html>
