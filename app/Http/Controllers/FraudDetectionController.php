<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FraudDetectionController extends Controller
{
    /**
     * API: Autentikasi Pengguna (Mahasiswa / Dosen / Admin)
     */
    public function login(Request $request)
    {
        $request->validate([
            'npm' => 'required|string',
            'password' => 'required|string',
        ]);

        $npm = $request->input('npm');
        $password = $request->input('password');

        // Cari user berdasarkan NPM atau Email
        $user = DB::table('users')
            ->where('npm', $npm)
            ->orWhere('email', $npm)
            ->first();

        // Verifikasi keberadaan user dan kecocokan hash password
        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'NPM/Username atau Password salah'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'npm' => $user->npm,
                'name' => $user->name,
                'role' => $user->role,
                'email' => $user->email,
            ]
        ]);
    }

    /**
     * Helper: Menghitung jarak menggunakan formula Haversine (meter)
     */

    private function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Radius bumi dalam meter

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }

    /**
     * Helper: Memverifikasi apakah request berasal dari Dosen atau Admin yang sah
     */
    private function checkAcademicAuthority(Request $request)
    {
        $npm = $request->header('X-User-NPM');
        $role = $request->header('X-User-Role');

        if (!$npm || !$role) {
            return false;
        }

        // Verifikasi keabsahan identitas dan peran di database
        $user = DB::table('users')->where('npm', $npm)->first();
        if (!$user || $user->role !== $role || !in_array($role, ['dosen', 'admin'])) {
            return false;
        }

        return true;
    }

    /**
     * 1. Ambil data Dashboard (Stats, Alert, dan Log Kehadiran) - Terproteksi
     */
    public function getDashboardData(Request $request)
    {
        // Validasi otoritas backend
        if (!$this->checkAcademicAuthority($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak: Anda tidak memiliki otoritas akademik.'
            ], 403);
        }

        $stats = [
            'total_students' => DB::table('users')->where('role', 'mahasiswa')->count(),
            'total_classes' => DB::table('classes')->count(),
            'total_attendance' => DB::table('attendance_logs')->count(),
            'total_alerts' => DB::table('fraud_alerts')->where('status', 'PENDING')->count(),
            'fraud_by_type' => [
                'GEOLOCATION_MISMATCH' => DB::table('fraud_alerts')->where('fraud_type', 'GEOLOCATION_MISMATCH')->count(),
                'VELOCITY_ANOMALY' => DB::table('fraud_alerts')->where('fraud_type', 'VELOCITY_ANOMALY')->count(),
                'DEVICE_SHARING' => DB::table('fraud_alerts')->where('fraud_type', 'DEVICE_SHARING')->count(),
            ]
        ];

        $recentLogs = DB::table('attendance_logs')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $activeAlerts = DB::table('fraud_alerts')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'stats' => $stats,
            'recent_logs' => $recentLogs,
            'active_alerts' => $activeAlerts
        ]);
    }

    /**
     * 2. Dapatkan Daftar Kelas
     */
    public function getClasses()
    {
        $classes = DB::table('classes')->get();
        return response()->json($classes);
    }

    /**
     * 3. Dapatkan Daftar Mahasiswa
     */
    public function getStudents()
    {
        $students = DB::table('users')->where('role', 'mahasiswa')->get();
        return response()->json($students);
    }

    /**
     * 4. Proses Presensi & AI Fraud Detection Engine
     */
    public function submitAttendance(Request $request)
    {
        $request->validate([
            'npm' => 'required|string',
            'class_id' => 'required|integer',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'ip_address' => 'required|string',
            'user_agent' => 'required|string',
            'device_fingerprint' => 'required|string',
        ]);

        $npm = $request->input('npm');
        $classId = $request->input('class_id');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $ipAddress = $request->input('ip_address');
        $userAgent = $request->input('user_agent');
        $deviceFingerprint = $request->input('device_fingerprint');

        // 1. Cari Mahasiswa dan Kelas
        $user = DB::table('users')->where('npm', $npm)->where('role', 'mahasiswa')->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'NPM Mahasiswa tidak terdaftar'], 404);
        }

        $class = DB::table('classes')->where('id', $classId)->first();
        if (!$class) {
            return response()->json(['success' => false, 'message' => 'Kelas kuliah tidak ditemukan'], 404);
        }

        // 2. Geofencing Check
        $distance = $this->haversineDistance($latitude, $longitude, $class->target_latitude, $class->target_longitude);
        $isOutOfGeofence = $distance > $class->geofence_radius_meters;

        // 3. Velocity Check (Kecepatan tidak rasional dalam 24 jam terakhir)
        $isVelocityAnomaly = false;
        $velocityEvidence = null;
        
        $lastLog = DB::table('attendance_logs')
            ->where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastLog) {
            $lastLogTime = Carbon::parse($lastLog->created_at);
            $timeDiffHours = Carbon::now()->diffInMinutes($lastLogTime) / 60;

            if ($timeDiffHours > 0 && $timeDiffHours < 4) { // Pengecekan jika ada log dalam 4 jam terakhir
                $distanceBetweenLogs = $this->haversineDistance($latitude, $longitude, $lastLog->latitude, $lastLog->longitude) / 1000; // kilometer
                $speedKmh = $distanceBetweenLogs / $timeDiffHours;

                if ($speedKmh > 80) { // Indikasi fraud jika kecepatan > 80 km/jam
                    $isVelocityAnomaly = true;
                    $velocityEvidence = [
                        'previous_log' => [
                            'timestamp' => $lastLog->created_at,
                            'coords' => ['lat' => $lastLog->latitude, 'lon' => $lastLog->longitude],
                            'class_name' => $lastLog->class_name
                        ],
                        'current_log' => [
                            'timestamp' => Carbon::now()->toIso8601String(),
                            'coords' => ['lat' => $latitude, 'lon' => $longitude],
                            'class_name' => $class->class_name
                        ],
                        'time_difference_minutes' => round($timeDiffHours * 60),
                        'distance_kilometers' => round($distanceBetweenLogs, 2),
                        'calculated_speed_kmh' => round($speedKmh, 2)
                    ];
                }
            }
        }

        // 4. Device Sharing Check (Joki Massal dalam 1 jam terakhir)
        $isDeviceSharing = false;
        $deviceEvidence = null;

        $sharedDevices = DB::table('attendance_logs')
            ->select('npm', 'name')
            ->where('device_fingerprint', $deviceFingerprint)
            ->where('created_at', '>=', Carbon::now()->subHour())
            ->where('npm', '!=', $npm)
            ->distinct()
            ->get();

        if ($sharedDevices->count() >= 1) {
            $isDeviceSharing = true;
            $sharedNpms = $sharedDevices->pluck('npm')->toArray();
            $sharedNpms[] = $npm;
            $sharedNames = $sharedDevices->pluck('name')->toArray();
            $sharedNames[] = $user->name;

            $deviceEvidence = [
                'shared_fingerprint' => $deviceFingerprint,
                'shared_by_npms' => $sharedNpms,
                'shared_by_names' => $sharedNames,
                'ip_address' => $ipAddress
            ];
        }

        // 5. Tentukan Skor Anomali, Status Kehadiran, dan Tipe Fraud
        $anomalyScore = 0.0;
        $isFraud = false;
        $fraudType = '';
        $evidence = '';

        if ($isVelocityAnomaly) {
            $anomalyScore = 0.99;
            $isFraud = true;
            $fraudType = 'VELOCITY_ANOMALY';
            $evidence = json_encode($velocityEvidence);
        } elseif ($isDeviceSharing) {
            $anomalyScore = 0.90;
            $isFraud = true;
            $fraudType = 'DEVICE_SHARING';
            $evidence = json_encode($deviceEvidence);
        } elseif ($isOutOfGeofence) {
            $anomalyScore = 0.85;
            $isFraud = true;
            $fraudType = 'GEOLOCATION_MISMATCH';
            $evidence = json_encode([
                'class_coords' => ['lat' => $class->target_latitude, 'lon' => $class->target_longitude],
                'user_coords' => ['lat' => $latitude, 'lon' => $longitude],
                'distance_meters' => round($distance, 1),
                'allowed_radius_meters' => $class->geofence_radius_meters
            ]);
        }

        $attendanceStatus = $isFraud ? 'FRAUD_SUSPECTED' : 'PRESENT';

        // 6. Simpan Log Presensi ke Database
        $logId = DB::table('attendance_logs')->insertGetId([
            'user_id' => $user->id,
            'npm' => $npm,
            'name' => $user->name,
            'class_id' => $class->id,
            'class_name' => $class->class_name,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'device_fingerprint' => $deviceFingerprint,
            'distance_meters' => $distance,
            'status' => $attendanceStatus,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // 7. Simpan Alarm Kecurangan jika terdeteksi fraud
        if ($isFraud) {
            DB::table('fraud_alerts')->insert([
                'attendance_log_id' => $logId,
                'user_id' => $user->id,
                'npm' => $npm,
                'name' => $user->name,
                'class_name' => $class->class_name,
                'anomaly_score' => $anomalyScore,
                'fraud_type' => $fraudType,
                'evidence' => $evidence,
                'status' => 'PENDING',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            return response()->json([
                'success' => true,
                'fraud_flagged' => true,
                'status' => 'FRAUD_SUSPECTED',
                'message' => 'Presensi memerlukan verifikasi keamanan tambahan oleh Dosen. Terdeteksi anomali pada sistem.',
                'details' => [
                    'fraud_type' => $fraudType,
                    'score' => $anomalyScore
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'fraud_flagged' => false,
            'status' => 'PRESENT',
            'message' => 'Presensi berhasil tercatat. Selamat belajar.',
            'details' => [
                'distance' => round($distance, 1)
            ]
        ]);
    }

    /**
     * 5. Resolusi Alert oleh Dosen (Abaikan atau Batalkan Kehadiran)
     */
    public function resolveAlert(Request $request)
    {
        // Validasi otoritas backend
        if (!$this->checkAcademicAuthority($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak: Anda tidak memiliki otoritas akademik.'
            ], 403);
        }

        $request->validate([
            'alert_id' => 'required|integer',
            'decision' => 'required|string|in:APPROVED,REJECTED'
        ]);

        $alertId = $request->input('alert_id');
        $decision = $request->input('decision'); // APPROVED (void presensi/sanksi) atau REJECTED (sahkan)

        $alert = DB::table('fraud_alerts')->where('id', $alertId)->first();
        if (!$alert) {
            return response()->json(['success' => false, 'message' => 'Peringatan kecurangan tidak ditemukan'], 404);
        }

        $logStatus = ($decision === 'APPROVED') ? 'ABSENT' : 'PRESENT';

        DB::beginTransaction();
        try {
            // Update status alarm
            DB::table('fraud_alerts')
                ->where('id', $alertId)
                ->update([
                    'status' => $decision,
                    'updated_at' => Carbon::now()
                ]);

            // Update status kehadiran log asli
            DB::table('attendance_logs')
                ->where('id', $alert->attendance_log_id)
                ->update([
                    'status' => $logStatus,
                    'updated_at' => Carbon::now()
                ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => "Alarm berhasil diselesaikan dengan keputusan: {$decision}"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui status: ' . $e->getMessage()], 500);
        }
    }

    /**
     * 6. Reset Database Demo ke Kondisi Semula
     */
    public function resetDemo(Request $request)
    {
        // Validasi otoritas backend
        if (!$this->checkAcademicAuthority($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak: Anda tidak memiliki otoritas akademik.'
            ], 403);
        }

        try {
            $seeder = new \Database\Seeders\FraudSystemSeeder();
            $seeder->run();

            return response()->json(['success' => true, 'message' => 'Database demo berhasil di-reset ke kondisi semula.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mereset database: ' . $e->getMessage()], 500);
        }
    }

    /**
     * =========================================================================
     * MODUL LMS TAMBAHAN (MATERI, TUGAS, UJIAN PROCTORING, REKAPITULASI NILAI)
     * =========================================================================
     */

    // 1. Ambil Semua Materi Kuliah
    public function getMaterials(Request $request)
    {
        $materials = DB::table('lms_materials')
            ->join('classes', 'lms_materials.class_id', '=', 'classes.id')
            ->select('lms_materials.*', 'classes.class_name', 'classes.subject_code')
            ->orderBy('lms_materials.created_at', 'desc')
            ->get();

        return response()->json($materials);
    }

    // 2. Upload/Buat Materi Kuliah Baru (Dosen/Admin)
    public function uploadMaterial(Request $request)
    {
        if (!$this->checkAcademicAuthority($request)) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak: Hanya dosen/admin.'], 403);
        }

        $request->validate([
            'class_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_name' => 'nullable|string'
        ]);

        DB::table('lms_materials')->insert([
            'class_id' => $request->input('class_id'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'file_name' => $request->input('file_name', 'Materi_Kuliah.pdf'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        return response()->json(['success' => true, 'message' => 'Materi kuliah berhasil diunggah.']);
    }

    // 3. Ambil Daftar Tugas
    public function getAssignments(Request $request)
    {
        $assignments = DB::table('lms_assignments')
            ->join('classes', 'lms_assignments.class_id', '=', 'classes.id')
            ->select('lms_assignments.*', 'classes.class_name', 'classes.subject_code')
            ->orderBy('lms_assignments.due_date', 'asc')
            ->get();

        // Jika mahasiswa, ambil juga status pengumpulannya
        $npm = $request->header('X-User-NPM');
        if ($npm) {
            foreach ($assignments as $a) {
                $sub = DB::table('lms_assignment_submissions')
                    ->where('assignment_id', $a->id)
                    ->where('npm', $npm)
                    ->first();
                $a->submission = $sub;
            }
        }

        return response()->json($assignments);
    }

    // 4. Buat Tugas Baru (Dosen/Admin)
    public function createAssignment(Request $request)
    {
        if (!$this->checkAcademicAuthority($request)) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak: Hanya dosen/admin.'], 403);
        }

        $request->validate([
            'class_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|string',
            'max_score' => 'required|integer'
        ]);

        DB::table('lms_assignments')->insert([
            'class_id' => $request->input('class_id'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'due_date' => Carbon::parse($request->input('due_date')),
            'max_score' => $request->input('max_score'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        return response()->json(['success' => true, 'message' => 'Tugas baru berhasil diterbitkan.']);
    }

    // 5. Kumpul Tugas (Mahasiswa)
    public function submitAssignment(Request $request)
    {
        $request->validate([
            'assignment_id' => 'required|integer',
            'npm' => 'required|string',
            'name' => 'required|string',
            'submission_text' => 'required|string'
        ]);

        $user = DB::table('users')->where('npm', $request->input('npm'))->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak terdaftar.'], 404);
        }

        $existing = DB::table('lms_assignment_submissions')
            ->where('assignment_id', $request->input('assignment_id'))
            ->where('npm', $request->input('npm'))
            ->first();

        if ($existing) {
            DB::table('lms_assignment_submissions')
                ->where('id', $existing->id)
                ->update([
                    'submission_text' => $request->input('submission_text'),
                    'updated_at' => Carbon::now()
                ]);
        } else {
            DB::table('lms_assignment_submissions')->insert([
                'assignment_id' => $request->input('assignment_id'),
                'user_id' => $user->id,
                'npm' => $request->input('npm'),
                'name' => $request->input('name'),
                'submission_text' => $request->input('submission_text'),
                'score' => null,
                'feedback' => null,
                'status' => 'SUBMITTED',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Tugas berhasil dikumpulkan.']);
    }

    // 6. Ambil Pengumpulan Tugas (Dosen/Admin)
    public function getAssignmentSubmissions(Request $request)
    {
        if (!$this->checkAcademicAuthority($request)) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak: Hanya dosen/admin.'], 403);
        }

        $submissions = DB::table('lms_assignment_submissions')
            ->join('lms_assignments', 'lms_assignment_submissions.assignment_id', '=', 'lms_assignments.id')
            ->select('lms_assignment_submissions.*', 'lms_assignments.title as assignment_title')
            ->orderBy('lms_assignment_submissions.created_at', 'desc')
            ->get();

        return response()->json($submissions);
    }

    // 7. Berikan Nilai Tugas (Dosen)
    public function gradeAssignmentSubmission(Request $request)
    {
        if (!$this->checkAcademicAuthority($request)) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak: Hanya dosen/admin.'], 403);
        }

        $request->validate([
            'submission_id' => 'required|integer',
            'score' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string'
        ]);

        DB::table('lms_assignment_submissions')
            ->where('id', $request->input('submission_id'))
            ->update([
                'score' => $request->input('score'),
                'feedback' => $request->input('feedback'),
                'status' => 'GRADED',
                'updated_at' => Carbon::now()
            ]);

        $sub = DB::table('lms_assignment_submissions')->where('id', $request->input('submission_id'))->first();
        if ($sub) {
            $this->recalculateStudentGrades($sub->user_id, $sub->npm, $sub->name);
        }

        return response()->json(['success' => true, 'message' => 'Tugas berhasil dinilai.']);
    }

    // 8. Ambil Semua Ujian
    public function getExams(Request $request)
    {
        $exams = DB::table('lms_exams')
            ->join('classes', 'lms_exams.class_id', '=', 'classes.id')
            ->select('lms_exams.*', 'classes.class_name', 'classes.subject_code')
            ->orderBy('lms_exams.created_at', 'desc')
            ->get();

        $npm = $request->header('X-User-NPM');
        if ($npm) {
            foreach ($exams as $e) {
                $attempt = DB::table('lms_exam_attempts')
                    ->where('exam_id', $e->id)
                    ->where('npm', $npm)
                    ->first();
                $e->attempt = $attempt;
            }
        }

        return response()->json($exams);
    }

    // 9. Buat Sesi Ujian Baru (Dosen/Admin)
    public function createExam(Request $request)
    {
        if (!$this->checkAcademicAuthority($request)) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak: Hanya dosen/admin.'], 403);
        }

        $request->validate([
            'class_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer',
            'questions' => 'required|array'
        ]);

        DB::beginTransaction();
        try {
            $examId = DB::table('lms_exams')->insertGetId([
                'class_id' => $request->input('class_id'),
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'duration_minutes' => $request->input('duration_minutes'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            foreach ($request->input('questions') as $q) {
                DB::table('lms_exam_questions')->insert([
                    'exam_id' => $examId,
                    'question_text' => $q['question_text'],
                    'option_a' => $q['option_a'],
                    'option_b' => $q['option_b'],
                    'option_c' => $q['option_c'],
                    'option_d' => $q['option_d'],
                    'correct_option' => $q['correct_option'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Sesi ujian dan soal berhasil diterbitkan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menerbitkan ujian: ' . $e->getMessage()], 500);
        }
    }

    // 10. Ambil Soal Ujian (Sesuai ID Ujian)
    public function getExamQuestions(Request $request, $id)
    {
        $questions = DB::table('lms_exam_questions')
            ->where('exam_id', $id)
            ->get();
        return response()->json($questions);
    }

    // 11. Kirim Jawaban Ujian + Hitung Skor + Simpan Log Proctoring Keamanan
    public function submitExamAttempt(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|integer',
            'npm' => 'required|string',
            'name' => 'required|string',
            'answers' => 'required|array',
            'tab_switches' => 'required|integer',
            'copy_paste' => 'required|integer'
        ]);

        $examId = $request->input('exam_id');
        $npm = $request->input('npm');
        $name = $request->input('name');
        $answers = $request->input('answers');
        $tabSwitches = $request->input('tab_switches');
        $copyPaste = $request->input('copy_paste');

        $user = DB::table('users')->where('npm', $npm)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak terdaftar.'], 404);
        }

        $existing = DB::table('lms_exam_attempts')
            ->where('exam_id', $examId)
            ->where('npm', $npm)
            ->first();

        if ($existing) {
            return response()->json(['success' => false, 'message' => 'Anda sudah menyelesaikan ujian ini sebelumnya.'], 400);
        }

        $questions = DB::table('lms_exam_questions')->where('exam_id', $examId)->get();
        $totalQuestions = $questions->count();
        $correctCount = 0;

        foreach ($questions as $q) {
            $userAnswer = isset($answers[$q->id]) ? $answers[$q->id] : null;
            if ($userAnswer === $q->correct_option) {
                $correctCount++;
            }
        }

        $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;

        DB::table('lms_exam_attempts')->insert([
            'exam_id' => $examId,
            'user_id' => $user->id,
            'npm' => $npm,
            'name' => $name,
            'score' => $score,
            'answers_json' => json_encode($answers),
            'tab_switches_count' => $tabSwitches,
            'copy_paste_count' => $copyPaste,
            'status' => 'COMPLETED',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        if ($tabSwitches >= 3 || $copyPaste >= 1) {
            $exam = DB::table('lms_exams')
                ->join('classes', 'lms_exams.class_id', '=', 'classes.id')
                ->select('lms_exams.title', 'classes.class_name')
                ->where('lms_exams.id', $examId)
                ->first();

            DB::table('fraud_alerts')->insert([
                'attendance_log_id' => null,
                'user_id' => $user->id,
                'npm' => $npm,
                'name' => $name,
                'class_name' => $exam ? $exam->class_name : 'Kelas Ujian',
                'anomaly_score' => 0.95,
                'fraud_type' => 'PROCTORING_SHIELD',
                'evidence' => json_encode([
                    'exam_title' => $exam ? $exam->title : 'Ujian Akademik',
                    'tab_switches_count' => $tabSwitches,
                    'copy_paste_count' => $copyPaste,
                    'score_achieved' => $score
                ]),
                'status' => 'PENDING',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        $this->recalculateStudentGrades($user->id, $npm, $name);

        return response()->json([
            'success' => true,
            'score' => $score,
            'tab_switches' => $tabSwitches,
            'copy_paste' => $copyPaste,
            'cheating_flagged' => ($tabSwitches >= 3 || $copyPaste >= 1),
            'message' => 'Ujian berhasil diselesaikan dan dianalisis oleh Proctoring Shield.'
        ]);
    }

    // 12. Ambil Riwayat Percobaan Ujian (Dosen/Admin)
    public function getExamAttempts(Request $request)
    {
        if (!$this->checkAcademicAuthority($request)) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak: Hanya dosen/admin.'], 403);
        }

        $attempts = DB::table('lms_exam_attempts')
            ->join('lms_exams', 'lms_exam_attempts.exam_id', '=', 'lms_exams.id')
            ->select('lms_exam_attempts.*', 'lms_exams.title as exam_title')
            ->orderBy('lms_exam_attempts.created_at', 'desc')
            ->get();

        return response()->json($attempts);
    }

    // 13. Ambil Rekapitulasi Nilai Akhir Akademik (LMS Academic Grades)
    public function getAcademicGrades(Request $request)
    {
        $npm = $request->header('X-User-NPM');
        $role = $request->header('X-User-Role');

        if (!$npm || !$role) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        if (in_array($role, ['dosen', 'admin'])) {
            $grades = DB::table('lms_academic_grades')
                ->join('classes', 'lms_academic_grades.class_id', '=', 'classes.id')
                ->select('lms_academic_grades.*', 'classes.class_name', 'classes.subject_code')
                ->orderBy('lms_academic_grades.final_score', 'desc')
                ->get();
        } else {
            $grades = DB::table('lms_academic_grades')
                ->join('classes', 'lms_academic_grades.class_id', '=', 'classes.id')
                ->select('lms_academic_grades.*', 'classes.class_name', 'classes.subject_code')
                ->where('lms_academic_grades.npm', $npm)
                ->get();
        }

        return response()->json($grades);
    }

    // Helper: Rekalkulasi Nilai Akhir Mahasiswa secara dinamis
    private function recalculateStudentGrades($userId, $npm, $name)
    {
        $classId = 1;

        $totalLogs = DB::table('attendance_logs')
            ->where('user_id', $userId)
            ->where('class_id', $classId)
            ->count();

        $presentLogs = DB::table('attendance_logs')
            ->where('user_id', $userId)
            ->where('class_id', $classId)
            ->where('status', 'PRESENT')
            ->count();

        $attendancePercentage = $totalLogs > 0 ? round(($presentLogs / $totalLogs) * 100, 1) : 100.0;
        $attendanceScore = $attendancePercentage;

        $assignmentAverage = DB::table('lms_assignment_submissions')
            ->where('user_id', $userId)
            ->where('status', 'GRADED')
            ->avg('score');

        $assignmentScore = $assignmentAverage !== null ? round($assignmentAverage, 1) : 80.0;

        $examAttempt = DB::table('lms_exam_attempts')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->first();

        $examScore = $examAttempt ? $examAttempt->score : 75.0;

        $finalScore = round(($attendanceScore * 0.20) + ($assignmentScore * 0.30) + ($examScore * 0.50), 1);

        $gradeLetter = 'E';
        if ($finalScore >= 85) $gradeLetter = 'A';
        elseif ($finalScore >= 80) $gradeLetter = 'A-';
        elseif ($finalScore >= 75) $gradeLetter = 'B+';
        elseif ($finalScore >= 70) $gradeLetter = 'B';
        elseif ($finalScore >= 65) $gradeLetter = 'C+';
        elseif ($finalScore >= 60) $gradeLetter = 'C';
        elseif ($finalScore >= 50) $gradeLetter = 'D';

        $existing = DB::table('lms_academic_grades')
            ->where('user_id', $userId)
            ->where('class_id', $classId)
            ->first();

        if ($existing) {
            DB::table('lms_academic_grades')
                ->where('id', $existing->id)
                ->update([
                    'attendance_percentage' => $attendancePercentage,
                    'attendance_score' => $attendanceScore,
                    'assignment_score' => $assignmentScore,
                    'exam_score' => $examScore,
                    'final_score' => $finalScore,
                    'grade_letter' => $gradeLetter,
                    'updated_at' => Carbon::now()
                ]);
        } else {
            DB::table('lms_academic_grades')->insert([
                'user_id' => $userId,
                'npm' => $npm,
                'name' => $name,
                'class_id' => $classId,
                'attendance_percentage' => $attendancePercentage,
                'attendance_score' => $attendanceScore,
                'assignment_score' => $assignmentScore,
                'exam_score' => $examScore,
                'final_score' => $finalScore,
                'grade_letter' => $gradeLetter,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
    }
}
