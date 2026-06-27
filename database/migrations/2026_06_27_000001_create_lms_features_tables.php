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
        // 1. Tabel Materi Kuliah (LMS Materials)
        Schema::create('lms_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_name')->nullable();
            $table->timestamps();
        });

        // 2. Tabel Tugas (LMS Assignments)
        Schema::create('lms_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('due_date');
            $table->integer('max_score')->default(100);
            $table->timestamps();
        });

        // 3. Tabel Pengumpulan Tugas (LMS Assignment Submissions)
        Schema::create('lms_assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('lms_assignments')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('npm');
            $table->string('name');
            $table->text('submission_text');
            $table->integer('score')->nullable();
            $table->text('feedback')->nullable();
            $table->string('status')->default('SUBMITTED'); // SUBMITTED, GRADED
            $table->timestamps();
        });

        // 4. Tabel Sesi Ujian (LMS Exams)
        Schema::create('lms_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('duration_minutes')->default(60);
            $table->timestamps();
        });

        // 5. Tabel Soal Ujian (LMS Exam Questions)
        Schema::create('lms_exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('lms_exams')->onDelete('cascade');
            $table->text('question_text');
            $table->string('option_a');
            $table->string('option_b');
            $table->string('option_c');
            $table->string('option_d');
            $table->string('correct_option'); // A, B, C, or D
            $table->timestamps();
        });

        // 6. Tabel Percobaan Ujian + Log Proctoring Anti-Cheating (LMS Exam Attempts)
        Schema::create('lms_exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('lms_exams')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('npm');
            $table->string('name');
            $table->integer('score')->default(0);
            $table->text('answers_json')->nullable(); // format JSON jawaban
            $table->integer('tab_switches_count')->default(0); // Deteksi keluar tab
            $table->integer('copy_paste_count')->default(0); // Deteksi copy-paste
            $table->string('status')->default('COMPLETED');
            $table->timestamps();
        });

        // 7. Tabel Rekapitulasi Nilai Akhir Akademik (LMS Academic Grades)
        Schema::create('lms_academic_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('npm');
            $table->string('name');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->double('attendance_percentage')->default(0);
            $table->double('attendance_score')->default(0);
            $table->double('assignment_score')->default(0);
            $table->double('exam_score')->default(0);
            $table->double('final_score')->default(0);
            $table->string('grade_letter')->default('E');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lms_academic_grades');
        Schema::dropIfExists('lms_exam_attempts');
        Schema::dropIfExists('lms_exam_questions');
        Schema::dropIfExists('lms_exams');
        Schema::dropIfExists('lms_assignment_submissions');
        Schema::dropIfExists('lms_assignments');
        Schema::dropIfExists('lms_materials');
    }
};
