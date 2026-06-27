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
        // Modifikasi tabel users bawaan untuk menambahkan kolom npm dan role
        Schema::table('users', function (Blueprint $table) {
            $table->string('npm')->unique()->nullable();
            $table->string('role')->default('mahasiswa'); // mahasiswa, dosen, admin
        });

        // Tabel Kelas Kuliah
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('class_name');
            $table->string('subject_code')->unique();
            $table->double('target_latitude', 9, 6);
            $table->double('target_longitude', 9, 6);
            $table->integer('geofence_radius_meters')->default(50);
            $table->timestamps();
        });

        // Tabel Log Presensi (Menyimpan data mentah sensor)
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('npm');
            $table->string('name');
            $table->foreignId('class_id')->nullable()->constrained('classes')->onDelete('cascade');
            $table->string('class_name');
            $table->double('latitude', 9, 6);
            $table->double('longitude', 9, 6);
            $table->string('ip_address');
            $table->text('user_agent');
            $table->string('device_fingerprint');
            $table->double('distance_meters');
            $table->string('status')->default('PRESENT'); // PRESENT, ABSENT, FRAUD_SUSPECTED
            $table->timestamps();
        });

        // Tabel Alert Kecurangan (Fraud Alerts)
        Schema::create('fraud_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_log_id')->nullable()->constrained('attendance_logs')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('npm');
            $table->string('name');
            $table->string('class_name');
            $table->double('anomaly_score');
            $table->string('fraud_type'); // GEOLOCATION_MISMATCH, VELOCITY_ANOMALY, DEVICE_SHARING
            $table->text('evidence'); // detail bukti dalam format JSON String
            $table->string('status')->default('PENDING'); // PENDING, APPROVED, REJECTED
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fraud_alerts');
        Schema::dropIfExists('attendance_logs');
        Schema::dropIfExists('classes');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['npm', 'role']);
        });
    }
};
