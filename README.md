# DOKUMENTASI SISTEM: SECURE ATTENDANCE & LMS SHIELD

Platform terintegrasi yang menggabungkan sistem manajemen pembelajaran (**LMS**) dengan protokol pengawasan keamanan ketat (**Security Proctoring**) berbasis **Laravel** dan **SQLite**. Sistem ini dirancang khusus untuk mencegah kecurangan akademik baik dalam presensi perkuliahan maupun pelaksanaan ujian online secara real-time.

---

## 1. Kredensial Akun Uji Coba Keamanan
Untuk keperluan presentasi dan pengujian sistem, telah disediakan dua akun dengan peran berbeda yang memiliki hak akses terisolasi (*Role-Based Access Control*):

*   **Akun Mahasiswa (Portal Presensi, Ujian, & Nilai)**
    *   **NPM / Username:** `2021012000`
    *   **Kata Sandi:** `echa123`
    *   *Catatan:* Identitas terkunci otomatis sebagai **Echa** pada form presensi untuk mencegah pemalsuan nama.
*   **Akun Dosen (Portal Monitoring, Penilaian, & Admin)**
    *   **NIDN / Username:** `1985051201`
    *   **Kata Sandi:** `password`
    *   *Catatan:* Memiliki otoritas penuh untuk meninjau alarm kecurangan, memberikan nilai tugas, dan mengelola konfigurasi sistem.

---

## 2. Arsitektur Pengamanan Lapis Ganda (Double Protection)
Sistem ini mengimplementasikan pengamanan tingkat tinggi untuk mencegah bypass akses visual (Inspect Element) maupun penembakan API secara langsung:

1.  **Proteksi API Backend (Controller-Level):**
    Setiap request AJAX sensitif (`/api/dashboard-data`, `/api/alerts/resolve`, `/api/reset-demo`, `/api/assignments/grade`, dll) wajib menyertakan custom headers keamanan:
    *   `X-User-NPM`: NPM/NIDN pengguna aktif.
    *   `X-User-Role`: Peran akademis pengguna (`mahasiswa`, `dosen`, `admin`).
    
    Metode `checkAcademicAuthority()` pada `FraudDetectionController` memverifikasi header tersebut langsung ke database. Jika role mahasiswa mencoba mengakses endpoint dosen, backend otomatis mengembalikan respons **403 Forbidden**.
2.  **Proteksi Visual Frontend (Blade-Level):**
    Fungsi `switchTab(tabName)` secara ketat memeriksa peran pengguna sebelum menampilkan konten.
3.  **Pengusiran Sesi Paksa (Visual Kick):**
    Jika terdeteksi manipulasi visual (misalnya mahasiswa memodifikasi kelas CSS tombol navigasi untuk masuk ke Portal Dosen), engine JavaScript akan mendeteksi pelanggaran tersebut secara instan, memicu pesan peringatan keamanan, dan memanggil fungsi `handleLogout()` secara paksa untuk menghancurkan sesi secara instan.

---

## 3. Menu, Fitur, dan Modul Utama Portal

Sistem dikemas dalam bentuk *Single Page Application* (SPA) dengan navigasi sidebar vertikal di sebelah kiri khas sistem akademik resmi universitas. Berikut penjelasan detail setiap menu:

### A. Portal Presensi (Menu Mahasiswa)
Menu utama bagi mahasiswa untuk melakukan absensi kehadiran kelas secara mandiri dengan verifikasi otomatis:
*   **Formulir Kehadiran Terkunci:** Nama dan NPM mahasiswa terisi secara otomatis berdasarkan sesi login aktif dan bersifat *read-only* (tidak dapat diubah).
*   **Geofencing Verification (Formula Haversine):** Mengukur jarak geometris antara koordinat GPS perangkat mahasiswa dengan koordinat pusat ruang kuliah yang ditentukan dosen. Mahasiswa wajib berada di dalam **radius 50 meter**.
*   **Velocity Anomaly Detector (Sensor Kecepatan):** Mencegah joki jarak jauh. Jika mahasiswa melakukan presensi di kelas A, lalu melakukan presensi di kelas B dalam waktu berdekatan dengan kecepatan perpindahan fisik di atas **80 km/jam**, sistem otomatis menandainya sebagai fraud.
*   **Device Sharing Detector (Sidik Jari Perangkat):** Engine JavaScript menangkap karakteristik browser (*Browser Fingerprint* seperti User Agent, resolusi layar, bahasa, timezone) menjadi hash unik. Jika satu sidik jari perangkat digunakan oleh lebih dari satu mahasiswa dalam waktu bersamaan, sistem akan mendeteksi indikasi joki massal.
*   **Status Kehadiran:** Jika lolos seluruh sensor, status tercatat **PRESENT**. Jika melanggar salah satu sensor, status tercatat **FRAUD_SUSPECTED** dan memicu alarm ke dasbor dosen.

### B. Portal Dosen (Menu Dosen - Dasbor Monitoring)
Pusat kendali dan pengawasan bagi dosen untuk memantau integritas akademik kelas secara real-time:
*   **Statistik Ringkas:** Menampilkan jumlah total mahasiswa, presensi terkumpul, jumlah alarm kecurangan aktif, dan tingkat akurasi verifikasi.
*   **Grafik Distribusi Kasus (Chart.js):** Visualisasi grafis batang yang menunjukkan perbandingan jumlah kasus kecurangan berdasarkan kategori (*Geofencing*, *Velocity*, *Device Sharing*).
*   **Pusat Pemantauan Integritas (Alert Table):** Tabel khusus yang menampung daftar mahasiswa yang ditandai melakukan kecurangan. Dilengkapi informasi waktu, kategori anomali, dan skor kepercayaan indikasi kecurangan (dalam persen).
*   **Investigasi Forensik & Resolusi Keputusan:** Dosen dapat mengeklik tombol "Investigasi" untuk membuka **Modal Forensik**. Modal ini menyajikan bukti digital yang dikumpulkan sistem:
    *   *Kasus Geofencing:* Menampilkan koordinat kelas vs koordinat mahasiswa beserta selisih jarak dalam meter.
    *   *Kasus Velocity:* Menampilkan riwayat waktu, koordinat, nama kelas pertama vs kedua, serta kecepatan perpindahan yang dihitung dalam km/jam.
    *   *Kasus Device Sharing:* Menampilkan hash sidik jari perangkat yang sama beserta daftar nama dan NPM mahasiswa yang menggunakannya secara bersamaan.
    *   *Kasus Proctoring:* Menampilkan detail jumlah pelanggaran keluar tab browser dan penyalinan teks selama ujian berlangsung.
    *   *Resolusi:* Dosen dapat mengambil keputusan final: **Sahkan Kehadiran (Valid)** yang akan mengubah status menjadi `PRESENT` atau **Batalkan Kehadiran (Tidak Valid)** yang akan mengubah status menjadi `ABSENT`.
*   **Tabel Log Kehadiran Mentah (All Logs):** Menampilkan riwayat seluruh presensi mahasiswa yang masuk (baik sah maupun fraud) dengan format waktu presisi hingga detik.

### C. Materi & Tugas (Menu Mahasiswa & Dosen)
Modul LMS terintegrasi untuk distribusi bahan ajar dan pengumpulan tugas kuliah:
*   **Bahan Ajar Kuliah:**
    *   *Dosen:* Dapat mengunggah materi baru dengan mengisi judul, deskripsi, dan nama file dokumen.
    *   *Mahasiswa:* Dapat melihat daftar materi kuliah dan mengunduh berkas bahan ajar secara langsung.
*   **Daftar Tugas Aktif:**
    *   *Dosen:* Dapat menerbitkan tugas baru lengkap dengan batas waktu pengerjaan (*due date*) dan skor maksimal.
    *   *Mahasiswa:* Dapat melihat daftar tugas aktif, batas waktu, status pengumpulan, skor yang diperoleh, dan catatan umpan balik (*feedback*) dari dosen.
*   **Pengumpulan Tugas (Mahasiswa):** Mahasiswa mengumpulkan tugas berupa penulisan laporan atau jawaban teks pada kolom editor yang disediakan.
*   **Panel Penilaian Tugas (Dosen):** Dosen dapat melihat tabel mahasiswa yang telah mengumpulkan tugas. Dosen dapat mengeklik tombol "Beri Nilai" untuk memeriksa lembar jawaban mahasiswa, memberikan skor angka (0-100), dan menuliskan feedback akademik. Nilai ini akan langsung memperbarui transkrip nilai akhir mahasiswa.

### D. Ujian Terproktor (Menu Mahasiswa & Dosen - Proctoring Shield)
Modul ujian online pilihan ganda terproteksi dengan sensor browser ketat untuk menjamin kejujuran pengerjaan:
*   **Pelaksanaan Ujian Mahasiswa:** Mahasiswa memilih sesi ujian aktif, membaca instruksi tata tertib, dan mulai mengerjakan soal pilihan ganda dengan batasan waktu mundur (countdown timer).
*   **Sensor Pengawas Proctoring Shield:**
    *   *Deteksi Keluar Tab Browser:* Menggunakan event listener `window.blur`. Jika mahasiswa berpindah ke tab browser lain, membuka aplikasi lain, atau meminimalkan browser, sistem akan langsung memicu *toast* peringatan merah dan mencatat pelanggaran.
    *   *Pemblokiran Copy-Paste:* Memblokir aksi salin-tempel teks (`copy` dan `paste`) di area ujian menggunakan `e.preventDefault()`. Tindakan ini diblokir secara mutlak dan dicatat sebagai pelanggaran proctoring.
*   **Pemberitahuan Kecurangan:** Jika mahasiswa menyelesaikan ujian dengan jumlah keluar tab >= 3 kali atau copy-paste >= 1 kali, status ujian ditandai memiliki indikasi kecurangan tinggi. Sistem secara otomatis mengirimkan alarm `PROCTORING_SHIELD` beserta skor ujian ke dasbor dosen.
*   **Laporan Proctoring (Dosen):** Dosen dapat memantau log pengerjaan ujian seluruh mahasiswa, termasuk nilai yang diperoleh dan jumlah pelanggaran proctoring yang dilakukan masing-masing mahasiswa secara detail.

### E. Rekapitulasi Nilai (Menu Mahasiswa & Dosen)
Modul transparansi nilai akhir akademis yang terhitung secara dinamis:
*   **Formula Penilaian Akhir:** Nilai akhir dihitung secara otomatis berdasarkan bobot persentase:
    $$\text{Skor Akhir} = (\text{Persentase Kehadiran Sah} \times 20\%) + (\text{Rata-rata Nilai Tugas} \times 30\%) + (\text{Nilai Ujian Akhir} \times 50\%)$$
    *   *Catatan:* Hanya kehadiran dengan status `PRESENT` yang dihitung. Status `FRAUD_SUSPECTED` atau `ABSENT` tidak dihitung sebagai kehadiran.
*   **Konversi Grade Huruf:** Skor akhir dikonversi secara otomatis menjadi Grade Akademik:
    *   Skor $\ge 85$: **A**
    *   Skor $\ge 80$: **A-**
    *   Skor $\ge 75$: **B+**
    *   Skor $\ge 70$: **B**
    *   Skor $\ge 65$: **C+**
    *   Skor $\ge 60$: **C**
    *   Skor $\ge 50$: **D**
    *   Skor $< 50$: **E**
*   **Tampilan Tabel:**
    *   *Dosen:* Dapat melihat transkrip nilai akhir seluruh mahasiswa di kelas untuk keperluan input nilai akhir portal kampus.
    *   *Mahasiswa:* Hanya dapat melihat transkrip nilai miliknya sendiri guna menjamin kerahasiaan nilai antar-mahasiswa.

### F. Admin & Aturan (Menu Dosen/Admin)
Halaman konfigurasi dan pemeliharaan sistem:
*   **Penyelarasan Ulang Database (Reset Demo Data):** Fitur bagi dosen/admin untuk membersihkan database dan menyetel ulang seluruh data log presensi, tugas, dan ujian ke kondisi default sistem (seeding ulang secara otomatis melalui API backend). Sangat berguna untuk demonstrasi berulang.
*   **Konfigurasi Batas Aturan (Thresholds):** Menampilkan parameter aktif kecerdasan buatan, seperti radius geofence (50 meter), batas kecepatan maksimal (80 km/jam), dan ambang batas berbagi perangkat (> 1 pengguna per jam).

---

## 4. Spesifikasi Stack Teknologi & Kinerja
*   **Framework Utama:** Laravel 11/12 (PHP 8.3)
*   **Database Engine:** SQLite (Direktori `database/database.sqlite`), memberikan kinerja pencarian data yang sangat cepat dan portabilitas tinggi.
*   **Desain Antarmuka:** Tailwind CSS via CDN untuk fleksibilitas styling, dikombinasikan dengan palet warna premium **Deep Indigo** (`#4648d4`) yang melambangkan keamanan dan profesionalisme sistem.
*   **Pengelola Proses:** PM2 (Process Manager 2). Sistem berjalan online secara background di bawah layanan PM2 dengan nama proses `fraud-detection` (ID: 35) pada port lokal `7978`. Kinerja memori terbukti sangat efisien dan stabil di kisaran **~49 MB** dengan penggunaan CPU **0%** pada kondisi siaga (*idle*).

---

## 5. Repositori GitHub & Riwayat Kontrol
*   **Repositori Publik:** `git@github.com:clarissaclaudiya/fraud-detection.git`
*   **Branch Utama:** `main`
*   **Konfigurasi Akun Pengunggah (Sekali Pakai):**
    *   Username: `clarissaclaudiya`
    *   Email: `411232036@mahasiswa.undira.ac.id`
*   **Kunci SSH Server Terdaftar:** `~/.ssh/id_rsa.pub` (Akun GitHub: `clarissaclaudiya`)
*   **Log Unggahan Pertama:** 2026-06-27 oleh Baymax.
