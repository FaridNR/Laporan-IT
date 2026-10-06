# Design Direction: Sistem Laporan Pekerjaan IT

Dial: ENERGY 1 / RHYTHM 2 / MOTION 1

## Identity & Personality
- Karakter: Profesional, fungsional, bersih, efisien, khas perkakas kerja internal teknik/pemerintahan.
- Bukan landing page startup AI: Tidak ada gradien ungu-biru neon, tidak ada fake terminal window, tidak ada dot berkedip (pulsing), tidak ada badge pil dekoratif, tidak ada font monospace untuk judul.
- Fokus: Keterbacaan cepat, komparasi visual before-after yang gamblang, pencarian instan, dan kemudahan rekap data.

## Tipografi
- Utama (Body & Headings): Plus Jakarta Sans (Google Fonts) dengan fallback system-ui (-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif).
- Monospace: Hanya digunakan secara proporsional untuk ID angka, tanggal format ISO, atau path file. Tidak digunakan untuk judul aplikasi atau label menu.
- Hirarki:
  * H1: 22px - 26px, font-bold, text-slate-900, tracking-tight.
  * H2: 16px - 18px, font-semibold, text-slate-900.
  * H3 / Subtitle: 13px - 14px, font-medium, text-slate-600.
  * Body: 13px - 14px, text-slate-700, leading-relaxed.
  * Metadata / Label: 11px - 12px, font-medium, text-slate-500.

## Palet Warna
- Base Background: #F8FAFC (slate-50)
- Surface / Cards: #FFFFFF (putih bersih) dengan border halus #E2E8F0 (slate-200)
- Primary Text: #0F172A (slate-900)
- Secondary Text: #475569 (slate-600)
- Muted Text: #64748B (slate-500)
- Border Standard: #E2E8F0 (slate-200), hover: #CBD5E1 (slate-300)
- Primary Brand / Action: #1E40AF (blue-800 / navy) - tegas dan resmi, bukan biru neon elektrik.
- Status "Bikin Baru": #047857 (emerald-700) dengan background #ECFDF5 (emerald-50) dan border #A7F3D0.
- Status "Pengubahan Fitur": #1D4ED8 (blue-700) dengan background #EFF6FF (blue-50) dan border #BFDBFE.
- Destructive / Hapus: #B91C1C (red-700) dengan background #FEF2F2 (red-50).

## Struktur & Elemen
- Border radius seragam: `rounded-lg` (8px) untuk tombol dan input, `rounded-xl` (12px) untuk container kartu utama. Hindari bentuk pil (`rounded-full`) berlebihan.
- Shadow minimalis: Gunakan `shadow-sm` atau flat border. Hindari bayangan lembut mengambang (`shadow-xl`/`shadow-2xl`) di komponen standar.
- Navigasi: Sederhana, horizontal, tab dengan garis aktif di bawah atau background tipis, tanpa ikon berlebihan.
- Kontrol dan Tombol: Memiliki state hover dan focus ring 2px yang jelas untuk aksesibilitas keyboard (WCAG AA).
