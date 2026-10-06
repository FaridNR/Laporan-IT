<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\User;
use App\Models\WorkReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WorkReportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    public function test_login_page_renders_cleanly(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Sistem Laporan Kerja IT');
        $response->assertDontSee('quick-login');
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'samsul@it.local',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertEquals('programer', auth()->user()->role);
        $this->assertEquals('Samsul Bahri', auth()->user()->name);
    }

    public function test_dashboard_displays_clean_empty_state_and_applications(): void
    {
        $user = User::where('role', 'programer')->first();
        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('SPONTAN');
        $response->assertSee('Antrian-MPP');
        $response->assertSee('Investasi');
        $response->assertSee('PetaPotensi');
        $response->assertSee('Samsul Bahri');
        $response->assertSee('Belum ada laporan pekerjaan yang tercatat.');
    }

    public function test_programer_can_create_work_report(): void
    {
        Storage::fake('public');
        $prog = User::where('role', 'programer')->first();
        $app = Application::where('slug', 'antrian-mpp')->first();

        $response = $this->actingAs($prog)->post(route('reports.store'), [
            'application_id' => $app->id,
            'work_type' => 'pengubahan_fitur',
            'work_date' => '2026-10-05',
            'title' => 'Implementasi penambahan filter tanggal antrian',
            'description' => 'Memastikan query database dan filter tanggal bekerja optimal.',
            'image_after' => UploadedFile::fake()->image('hasil.jpg', 800, 600),
            'image_before' => UploadedFile::fake()->image('sebelum.jpg', 800, 600),
            'image_instruction' => UploadedFile::fake()->image('bukti_perintah.jpg', 800, 600),
        ]);

        $response->assertRedirect(route('reports.index'));
        $this->assertDatabaseHas('work_reports', [
            'title' => 'Implementasi penambahan filter tanggal antrian',
            'work_type' => 'pengubahan_fitur',
            'user_id' => $prog->id,
        ]);
        
        $report = WorkReport::first();
        $this->assertNotNull($report->image_instruction);
    }

    public function test_staff_it_cannot_create_work_report(): void
    {
        $staff = User::where('role', 'staff_it')->first();
        $this->assertEquals('Natalia Sarah Pahlevie', $staff->name);

        $response = $this->actingAs($staff)->get(route('reports.create'));
        $response->assertStatus(403);
    }

    public function test_kepala_it_cannot_create_work_report(): void
    {
        $kepala = User::where('role', 'kepala_it')->first();
        $this->assertEquals('Dehen Nurwin Adam', $kepala->name);

        $response = $this->actingAs($kepala)->get(route('reports.create'));
        $response->assertStatus(403);
    }

    public function test_reports_search_and_filter(): void
    {
        $prog = User::where('role', 'programer')->first();
        $app = Application::where('slug', 'investasi')->first();

        WorkReport::create([
            'user_id' => $prog->id,
            'application_id' => $app->id,
            'work_type' => 'bikin_baru',
            'title' => 'Modul Kalkulator Retribusi Investasi',
            'description' => 'Pengembangan fitur kalkulator simulasi.',
            'image_after' => 'reports/sample.jpg',
            'work_date' => '2026-10-05',
        ]);

        $kepala = User::where('role', 'kepala_it')->first();

        // Search by keyword
        $response = $this->actingAs($kepala)->get(route('reports.index', ['q' => 'Kalkulator']));
        $response->assertStatus(200);
        $response->assertSee('Kalkulator');

        // Filter by work type
        $responseType = $this->actingAs($kepala)->get(route('reports.index', ['work_type' => 'bikin_baru']));
        $responseType->assertStatus(200);
        $responseType->assertSee('Bikin Baru');
    }

    public function test_rekapitulasi_page_and_export(): void
    {
        $staff = User::where('role', 'staff_it')->first();

        // Rekap index
        $response = $this->actingAs($staff)->get(route('rekap.index'));
        $response->assertStatus(200);
        $response->assertSee('Rincian Pekerjaan Berdasarkan Aplikasi');

        // Print PDF view (must contain signature of Dehen Nurwin Adam)
        $responsePrint = $this->actingAs($staff)->get(route('rekap.print'));
        $responsePrint->assertStatus(200);
        $responsePrint->assertSee('DIVISI TEKNOLOGI INFORMASI');
        $responsePrint->assertSee('Dehen Nurwin Adam');

        // CSV export
        $responseCsv = $this->actingAs($staff)->get(route('rekap.csv'));
        $responseCsv->assertStatus(200);
        $this->assertEquals('text/csv; charset=UTF-8', $responseCsv->headers->get('Content-Type'));
    }
}
