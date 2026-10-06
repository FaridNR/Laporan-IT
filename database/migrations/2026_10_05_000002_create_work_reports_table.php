<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('work_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->enum('work_type', ['bikin_baru', 'pengubahan_fitur'])->default('pengubahan_fitur');
            $table->string('title');
            $table->text('description');
            $table->string('image_after'); // wajib: screenshot hasil
            $table->string('image_before')->nullable(); // opsional: screenshot sebelumnya
            $table->date('work_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_reports');
    }
};
