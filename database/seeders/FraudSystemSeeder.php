<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FraudSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Bersihkan tabel terlebih dahulu untuk menghindari duplikasi data demo
        DB::statement('PRAGMA foreign_keys = OFF;');
        DB::table('fraud_alerts')->truncate();
        DB::table('attendance_logs')->truncate();
        DB::table('classes')->truncate();
        DB::table('users')->truncate();
        DB::statement('PRAGMA foreign_keys = ON;');

        // 2. Seed Users
        $users = [
            [
                'name' => 'Echa',
                'email' => 'echa@univ.ac.id',
                'password' => Hash::make('echa123'),
                'npm' => '2021012000',
                'role' => 'mahasiswa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@univ.ac.id',
                'password' => Hash::make('password'),
                'npm' => '2021012001',
                'role' => 'mahasiswa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rian Hidayat',
                'email' => 'rian@univ.ac.id',
                'password' => Hash::make('password'),
                'npm' => '2021012002',
                'role' => 'mahasiswa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Siti Aminah',
                'email' => 'siti@univ.ac.id',
                'password' => Hash::make('password'),
                'npm' => '2021012003',
                'role' => 'mahasiswa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dedi Wijaya',
                'email' => 'dedi@univ.ac.id',
                'password' => Hash::make('password'),
                'npm' => '2021012004',
                'role' => 'mahasiswa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rina Lestari',
                'email' => 'rina@univ.ac.id',
                'password' => Hash::make('password'),
                'npm' => '2021012005',
                'role' => 'mahasiswa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dr. Ir. Hermawan',
                'email' => 'hermawan@univ.ac.id',
                'password' => Hash::make('password'),
                'npm' => '1985051201',
                'role' => 'dosen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Prof. Suprapto',
                'email' => 'suprapto@univ.ac.id',
                'password' => Hash::make('password'),
                'npm' => '1980122502',
                'role' => 'dosen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin IT Akademik',
                'email' => 'admin@univ.ac.id',
                'password' => Hash::make('password'),
                'npm' => 'admin01',
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        DB::table('users')->insert($users);


        // 3. Seed Classes
        $classes = [
            [
                'class_name' => 'Kecerdasan Buatan',
                'subject_code' => 'IF-301',
                'target_latitude' => -6.222300,
                'target_longitude' => 106.810000,
                'geofence_radius_meters' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'class_name' => 'Kalkulus II',
                'subject_code' => 'MA-202',
                'target_latitude' => -6.222300,
                'target_longitude' => 106.810000,
                'geofence_radius_meters' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'class_name' => 'Sistem Terdistribusi',
                'subject_code' => 'IF-405',
                'target_latitude' => -6.222300,
                'target_longitude' => 106.810000,
                'geofence_radius_meters' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        DB::table('classes')->insert($classes);

        // 4. Seed Attendance Logs & Fraud Alerts

        // Log Normal 1: Echa
        DB::table('attendance_logs')->insert([
            'user_id' => 1,
            'npm' => '2021012000',
            'name' => 'Echa',
            'class_id' => 1,
            'class_name' => 'Kecerdasan Buatan',
            'latitude' => -6.222310,
            'longitude' => 106.810020,
            'ip_address' => '192.168.0.100',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/125.0.0.0',
            'device_fingerprint' => 'fingerprint_echa_123',
            'distance_meters' => 2.5,
            'status' => 'PRESENT',
            'created_at' => now()->subHours(2),
            'updated_at' => now()->subHours(2),
        ]);

        // Log Normal 2: Rina Lestari
        DB::table('attendance_logs')->insert([
            'user_id' => 6,
            'npm' => '2021012005',
            'name' => 'Rina Lestari',
            'class_id' => 1,
            'class_name' => 'Kecerdasan Buatan',
            'latitude' => -6.222290,
            'longitude' => 106.809980,
            'ip_address' => '192.168.0.102',
            'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_4_1 like Mac OS X)',
            'device_fingerprint' => 'fingerprint_rina_abc',
            'distance_meters' => 2.3,
            'status' => 'PRESENT',
            'created_at' => now()->subHour(),
            'updated_at' => now()->subHour(),
        ]);

        // SEED FRAUD 1: Geolocation Mismatch (Budi Santoso dari Rumah)
        $budi_log_id = DB::table('attendance_logs')->insertGetId([
            'user_id' => 2,
            'npm' => '2021012001',
            'name' => 'Budi Santoso',
            'class_id' => 1,
            'class_name' => 'Kecerdasan Buatan',
            'latitude' => -6.312000,
            'longitude' => 106.832000,
            'ip_address' => '112.199.45.12',
            'user_agent' => 'Mozilla/5.0 (Android; Mobile; rv:124.0)',
            'device_fingerprint' => 'fingerprint_budi_xyz',
            'distance_meters' => 10250.4,
            'status' => 'FRAUD_SUSPECTED',
            'created_at' => now()->subMinutes(30),
            'updated_at' => now()->subMinutes(30),
        ]);

        DB::table('fraud_alerts')->insert([
            'attendance_log_id' => $budi_log_id,
            'user_id' => 2,
            'npm' => '2021012001',
            'name' => 'Budi Santoso',
            'class_name' => 'Kecerdasan Buatan',
            'anomaly_score' => 0.85,
            'fraud_type' => 'GEOLOCATION_MISMATCH',
            'evidence' => json_encode([
                'class_coords' => ['lat' => -6.222300, 'lon' => 106.810000],
                'user_coords' => ['lat' => -6.312000, 'lon' => 106.832000],
                'distance_meters' => 10250.4,
                'allowed_radius_meters' => 50
            ]),
            'status' => 'PENDING',
            'created_at' => now()->subMinutes(30),
            'updated_at' => now()->subMinutes(30),
        ]);

        // SEED FRAUD 2: Velocity Anomaly (Rian Hidayat - Absen Jakarta jam 8, absen Medan jam 8.05)
        // Log 1: Kalkulus di Jakarta
        DB::table('attendance_logs')->insert([
            'user_id' => 3,
            'npm' => '2021012002',
            'name' => 'Rian Hidayat',
            'class_id' => 2,
            'class_name' => 'Kalkulus II',
            'latitude' => -6.222300,
            'longitude' => 106.810000,
            'ip_address' => '182.253.12.34',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'device_fingerprint' => 'fingerprint_rian_345',
            'distance_meters' => 0.0,
            'status' => 'PRESENT',
            'created_at' => now()->subMinutes(45),
            'updated_at' => now()->subMinutes(45),
        ]);

        // Log 2: Kecerdasan Buatan di Medan
        $rian_log_medan_id = DB::table('attendance_logs')->insertGetId([
            'user_id' => 3,
            'npm' => '2021012002',
            'name' => 'Rian Hidayat',
            'class_id' => 1,
            'class_name' => 'Kecerdasan Buatan',
            'latitude' => 3.595200,
            'longitude' => 98.672200,
            'ip_address' => '36.85.120.44',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'device_fingerprint' => 'fingerprint_rian_joki_medan',
            'distance_meters' => 1400000.0,
            'status' => 'FRAUD_SUSPECTED',
            'created_at' => now()->subMinutes(40),
            'updated_at' => now()->subMinutes(40),
        ]);

        DB::table('fraud_alerts')->insert([
            'attendance_log_id' => $rian_log_medan_id,
            'user_id' => 3,
            'npm' => '2021012002',
            'name' => 'Rian Hidayat',
            'class_name' => 'Kecerdasan Buatan',
            'anomaly_score' => 0.99,
            'fraud_type' => 'VELOCITY_ANOMALY',
            'evidence' => json_encode([
                'previous_log' => [
                    'timestamp' => now()->subMinutes(45)->toIso8601String(),
                    'coords' => ['lat' => -6.222300, 'lon' => 106.810000],
                    'class_name' => 'Kalkulus II'
                ],
                'current_log' => [
                    'timestamp' => now()->subMinutes(40)->toIso8601String(),
                    'coords' => ['lat' => 3.595200, 'lon' => 98.672200],
                    'class_name' => 'Kecerdasan Buatan'
                ],
                'time_difference_minutes' => 5,
                'distance_kilometers' => 1400.0,
                'calculated_speed_kmh' => 16800.0
            ]),
            'status' => 'PENDING',
            'created_at' => now()->subMinutes(40),
            'updated_at' => now()->subMinutes(40),
        ]);

        // SEED FRAUD 3: Device Sharing (Siti Aminah dan Dedi Wijaya dengan device yang sama)
        $sharedFingerprint = 'joki_massal_fingerprint_hash_999';
        $sharedIP = '180.244.192.88';
        $sharedUA = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) Chrome/125.0.0.0';

        // Siti
        $siti_log_id = DB::table('attendance_logs')->insertGetId([
            'user_id' => 4,
            'npm' => '2021012003',
            'name' => 'Siti Aminah',
            'class_id' => 1,
            'class_name' => 'Kecerdasan Buatan',
            'latitude' => -6.222305,
            'longitude' => 106.810015,
            'ip_address' => $sharedIP,
            'user_agent' => $sharedUA,
            'device_fingerprint' => $sharedFingerprint,
            'distance_meters' => 0.6,
            'status' => 'FRAUD_SUSPECTED',
            'created_at' => now()->subMinutes(10),
            'updated_at' => now()->subMinutes(10),
        ]);

        DB::table('fraud_alerts')->insert([
            'attendance_log_id' => $siti_log_id,
            'user_id' => 4,
            'npm' => '2021012003',
            'name' => 'Siti Aminah',
            'class_name' => 'Kecerdasan Buatan',
            'anomaly_score' => 0.90,
            'fraud_type' => 'DEVICE_SHARING',
            'evidence' => json_encode([
                'shared_fingerprint' => $sharedFingerprint,
                'shared_by_npms' => ['2021012003', '2021012004'],
                'shared_by_names' => ['Siti Aminah', 'Dedi Wijaya'],
                'ip_address' => $sharedIP
            ]),
            'status' => 'PENDING',
            'created_at' => now()->subMinutes(10),
            'updated_at' => now()->subMinutes(10),
        ]);

        // Dedi
        $dedi_log_id = DB::table('attendance_logs')->insertGetId([
            'user_id' => 5,
            'npm' => '2021012004',
            'name' => 'Dedi Wijaya',
            'class_id' => 1,
            'class_name' => 'Kecerdasan Buatan',
            'latitude' => -6.222302,
            'longitude' => 106.810010,
            'ip_address' => $sharedIP,
            'user_agent' => $sharedUA,
            'device_fingerprint' => $sharedFingerprint,
            'distance_meters' => 0.2,
            'status' => 'FRAUD_SUSPECTED',
            'created_at' => now()->subMinutes(9),
            'updated_at' => now()->subMinutes(9),
        ]);

        DB::table('fraud_alerts')->insert([
            'attendance_log_id' => $dedi_log_id,
            'user_id' => 5,
            'npm' => '2021012004',
            'name' => 'Dedi Wijaya',
            'class_name' => 'Kecerdasan Buatan',
            'anomaly_score' => 0.90,
            'fraud_type' => 'DEVICE_SHARING',
            'evidence' => json_encode([
                'shared_fingerprint' => $sharedFingerprint,
                'shared_by_npms' => ['2021012003', '2021012004'],
                'shared_by_names' => ['Siti Aminah', 'Dedi Wijaya'],
                'ip_address' => $sharedIP
            ]),
            'status' => 'PENDING',
            'created_at' => now()->subMinutes(9),
            'updated_at' => now()->subMinutes(9),
        ]);

        // 5. Seed LMS Materials
        $materials = [
            [
                'class_id' => 1,
                'title' => 'Pengenalan Kecerdasan Buatan & Agen Cerdas',
                'description' => 'Materi pengantar mengenai konsep dasar AI, definisi agen cerdas, serta klasifikasi lingkungan agen.',
                'file_name' => 'Materi_01_Pengenalan_AI.pdf',
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'class_id' => 1,
                'title' => 'Algoritma Pencarian Buta (Blind Search)',
                'description' => 'Membahas konsep Breadth-First Search (BFS), Depth-First Search (DFS), dan Uniform Cost Search (UCS) beserta analisis kompleksitasnya.',
                'file_name' => 'Materi_02_Blind_Search.pdf',
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
            ],
            [
                'class_id' => 1,
                'title' => 'Heuristic Search & Algoritma A*',
                'description' => 'Implementasi pencarian terpimpin menggunakan fungsi heuristik, algoritma Greedy Best-First Search, dan algoritma optimal A*.',
                'file_name' => 'Materi_03_Heuristic_AStar.pdf',
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],
            [
                'class_id' => 2,
                'title' => 'Turunan Fungsi Multivariabel',
                'description' => 'Konsep turunan parsial, diferensial total, serta aplikasinya dalam penentuan nilai ekstrem lokal.',
                'file_name' => 'Kalkulus2_Turunan_Parsial.pdf',
                'created_at' => now()->subDays(9),
                'updated_at' => now()->subDays(9),
            ],
            [
                'class_id' => 3,
                'title' => 'Konsensus & Sinkronisasi Jam Terdistribusi',
                'description' => 'Materi mengenai algoritma Cristian, Berkeley, dan algoritma konsensus Paxos/Raft pada sistem terdistribusi.',
                'file_name' => 'Sisdis_04_Konsensus.pdf',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ]
        ];
        DB::table('lms_materials')->insert($materials);

        // 6. Seed LMS Assignments
        $assignment1_id = DB::table('lms_assignments')->insertGetId([
            'class_id' => 1,
            'title' => 'Tugas 1: Pembuatan Heuristik Maze Solver',
            'description' => 'Rancanglah fungsi heuristik yang adopsi jarak Manhattan untuk memecahkan labirin (maze) 2D. Tulis laporan analisis beserta kode Python.',
            'due_date' => now()->addDays(5),
            'max_score' => 100,
            'created_at' => now()->subDays(3),
            'updated_at' => now()->subDays(3),
        ]);

        $assignment2_id = DB::table('lms_assignments')->insertGetId([
            'class_id' => 1,
            'title' => 'Tugas 2: Implementasi Algoritma Minimax pada Tic-Tac-Toe',
            'description' => 'Buatlah game Tic-Tac-Toe sederhana dengan kecerdasan buatan berbasis algoritma Minimax dan Alpha-Beta Pruning agar AI tidak pernah kalah.',
            'due_date' => now()->addDays(12),
            'max_score' => 100,
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        // 7. Seed LMS Assignment Submissions
        $submissions = [
            [
                'assignment_id' => $assignment1_id,
                'user_id' => 1, // Echa
                'npm' => '2021012000',
                'name' => 'Echa',
                'submission_text' => 'Saya mengimplementasikan fungsi heuristik A* dengan Manhattan Distance. Kompleksitas ruang O(b^d) dan waktu O(b^d). Kode berjalan sukses memecahkan maze 50x50.',
                'score' => 95,
                'feedback' => 'Kerja bagus, analisis kompleksitas sangat detail dan implementasi kode rapi.',
                'status' => 'GRADED',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(1),
            ],
            [
                'assignment_id' => $assignment1_id,
                'user_id' => 2, // Budi Santoso
                'npm' => '2021012001',
                'name' => 'Budi Santoso',
                'submission_text' => 'Tugas 1 Heuristik Solver Budi Santoso. Menggunakan algoritma Greedy Best First Search. Hasil eksekusi terlampir.',
                'score' => 75,
                'feedback' => 'Implementasi sudah berjalan, namun algoritma Greedy Best-First tidak menjamin rute terpendek dibanding A*. Silakan pelajari perbedaannya.',
                'status' => 'GRADED',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(1),
            ],
            [
                'assignment_id' => $assignment1_id,
                'user_id' => 4, // Siti Aminah
                'npm' => '2021012003',
                'name' => 'Siti Aminah',
                'submission_text' => 'Pengumpulan Tugas 1 Kecerdasan Buatan - Siti Aminah. Menggunakan Manhattan Distance heuristik.',
                'score' => null,
                'feedback' => null,
                'status' => 'SUBMITTED',
                'created_at' => now()->subHours(5),
                'updated_at' => now()->subHours(5),
            ]
        ];
        DB::table('lms_assignment_submissions')->insert($submissions);

        // 8. Seed LMS Exams
        $exam_id = DB::table('lms_exams')->insertGetId([
            'class_id' => 1,
            'title' => 'Ujian Tengah Semester - Kecerdasan Buatan',
            'description' => 'Ujian tertutup berbasis sistem pengawasan proctoring otomatis. Dilarang meninggalkan tab ujian atau menyalin teks luar!',
            'duration_minutes' => 45,
            'created_at' => now()->subDays(5),
            'updated_at' => now()->subDays(5),
        ]);

        // 9. Seed LMS Exam Questions
        $questions = [
            [
                'exam_id' => $exam_id,
                'question_text' => 'Manakah dari algoritma berikut yang tergolong sebagai Informed Search (Pencarian Terpimpin)?',
                'option_a' => 'Breadth-First Search (BFS)',
                'option_b' => 'Depth-First Search (DFS)',
                'option_c' => 'Algoritma A*',
                'option_d' => 'Uniform Cost Search (UCS)',
                'correct_option' => 'C',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'exam_id' => $exam_id,
                'question_text' => 'Apa karakteristik utama dari fungsi heuristik yang "admissible" (layak)?',
                'option_a' => 'Selalu memperkirakan biaya yang lebih tinggi dari biaya sebenarnya (overestimate)',
                'option_b' => 'Tidak pernah memperkirakan biaya yang lebih tinggi dari biaya sebenarnya (underestimate)',
                'option_c' => 'Selalu bernilai nol untuk semua node',
                'option_d' => 'Menghitung biaya eksak tanpa estimasi',
                'correct_option' => 'B',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'exam_id' => $exam_id,
                'question_text' => 'Algoritma pencarian manakah yang mengevaluasi node berdasarkan f(n) = g(n) + h(n)?',
                'option_a' => 'Greedy Best-First Search',
                'option_b' => 'Algoritma A*',
                'option_c' => 'Depth-Limited Search',
                'option_d' => 'Hill Climbing',
                'correct_option' => 'B',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'exam_id' => $exam_id,
                'question_text' => 'Dalam algoritma Minimax untuk permainan dua pemain, apa tujuan dari Alpha-Beta Pruning?',
                'option_a' => 'Menambah kedalaman pencarian agar AI lebih cerdas',
                'option_b' => 'Memotong cabang pohon pencarian yang tidak mempengaruhi keputusan akhir untuk menghemat waktu',
                'option_c' => 'Mengubah fungsi evaluasi secara dinamis',
                'option_d' => 'Memastikan AI selalu memilih langkah acak',
                'correct_option' => 'B',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'exam_id' => $exam_id,
                'question_text' => 'Manakah contoh representasi lingkungan (environment) AI yang bersifat "Stochastic"?',
                'option_a' => 'Catur',
                'option_b' => 'Labirin tanpa rintangan bergerak',
                'option_c' => 'Permainan Ludo (dengan dadu)',
                'option_d' => 'Tic-Tac-Toe',
                'correct_option' => 'C',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        DB::table('lms_exam_questions')->insert($questions);

        // 10. Seed LMS Exam Attempts (Termasuk Log Keamanan Proctoring)
        $attempts = [
            [
                'exam_id' => $exam_id,
                'user_id' => 1, // Echa
                'npm' => '2021012000',
                'name' => 'Echa',
                'score' => 100,
                'answers_json' => json_encode(['1' => 'C', '2' => 'B', '3' => 'B', '4' => 'B', '5' => 'C']),
                'tab_switches_count' => 0,
                'copy_paste_count' => 0,
                'status' => 'COMPLETED',
                'created_at' => now()->subHours(4),
                'updated_at' => now()->subHours(4),
            ],
            [
                'exam_id' => $exam_id,
                'user_id' => 2, // Budi Santoso (Indikasi Cheating)
                'npm' => '2021012001',
                'name' => 'Budi Santoso',
                'score' => 60,
                'answers_json' => json_encode(['1' => 'C', '2' => 'A', '3' => 'B', '4' => 'B', '5' => 'A']),
                'tab_switches_count' => 5,
                'copy_paste_count' => 2,
                'status' => 'COMPLETED',
                'created_at' => now()->subHours(3),
                'updated_at' => now()->subHours(3),
            ],
            [
                'exam_id' => $exam_id,
                'user_id' => 4, // Siti Aminah
                'npm' => '2021012003',
                'name' => 'Siti Aminah',
                'score' => 80,
                'answers_json' => json_encode(['1' => 'C', '2' => 'B', '3' => 'B', '4' => 'A', '5' => 'C']),
                'tab_switches_count' => 1,
                'copy_paste_count' => 0,
                'status' => 'COMPLETED',
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ]
        ];
        DB::table('lms_exam_attempts')->insert($attempts);

        // 11. Seed LMS Academic Grades
        $grades = [
            [
                'user_id' => 1,
                'npm' => '2021012000',
                'name' => 'Echa',
                'class_id' => 1,
                'attendance_percentage' => 100.0,
                'attendance_score' => 100.0,
                'assignment_score' => 95.0,
                'exam_score' => 100.0,
                'final_score' => 98.0,
                'grade_letter' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'npm' => '2021012001',
                'name' => 'Budi Santoso',
                'class_id' => 1,
                'attendance_percentage' => 80.0,
                'attendance_score' => 80.0,
                'assignment_score' => 75.0,
                'exam_score' => 60.0,
                'final_score' => 68.5,
                'grade_letter' => 'C',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'npm' => '2021012003',
                'name' => 'Siti Aminah',
                'class_id' => 1,
                'attendance_percentage' => 80.0,
                'attendance_score' => 80.0,
                'assignment_score' => 85.0,
                'exam_score' => 80.0,
                'final_score' => 81.5,
                'grade_letter' => 'A-',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        DB::table('lms_academic_grades')->insert($grades);
    }
}
