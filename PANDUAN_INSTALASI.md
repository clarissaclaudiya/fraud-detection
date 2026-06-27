# PANDUAN INSTALASI: SECURE ATTENDANCE & LMS SHIELD

Panduan ini menjelaskan langkah-langkah untuk memasang dan menjalankan sistem Secure Attendance & LMS Shield di server lokal atau komputer pengembangan Anda.

---

## 1. Persyaratan Sistem
Sebelum memulai, pastikan sistem Anda telah terpasang komponen berikut:
- **PHP** >= 8.2 (dengan ekstensi php-sqlite3 aktif)
- **Composer** (Dependency Manager untuk PHP)
- **Node.js** & **NPM** (untuk mengelola build frontend/package)
- **SQLite**

---

## 2. Langkah-Langkah Instalasi

### Langkah 1: Ekstrak Project
Ekstrak file zip project ke direktori web server Anda (misalnya `/var/www/fraud-detection` atau folder pilihan Anda).

### Langkah 2: Konfigurasi Environment (`.env`)
Salin file konfigurasi `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Secara default, project ini menggunakan database SQLite. Pastikan konfigurasi database di file `.env` diatur seperti berikut:
```env
DB_CONNECTION=sqlite
# Kosongkan atau hapus baris DB_DATABASE, DB_FOREIGN_KEYS jika ingin menggunakan path default database/database.sqlite
```

### Langkah 3: Install Dependensi PHP
Jalankan Composer untuk menginstal semua library Laravel yang dibutuhkan:
```bash
composer install
```

### Langkah 4: Generate Application Key
Jalankan perintah berikut untuk menggenerasikan key keamanan aplikasi Laravel:
```bash
php artisan key:generate
```

### Langkah 5: Setup Database SQLite
Buat file database SQLite baru di dalam folder `database/` (jika belum ada):
```bash
touch database/database.sqlite
```

Jalankan migrasi tabel database:
```bash
php artisan migrate
```

Jalankan seeder untuk memasukkan data awal akademik (termasuk materi kuliah, tugas, soal ujian, akun mahasiswa Echa, dan log kecurangan sampel):
```bash
php artisan db:seed --class=FraudSystemSeeder
```

### Langkah 6: Bersihkan Cache (Opsional tetapi Direkomendasikan)
```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

### Langkah 7: Jalankan Server Pengembangan
Jalankan project menggunakan built-in web server Laravel:
```bash
php artisan serve --port=7978
```
Sistem kini dapat diakses melalui browser di alamat: `http://127.0.0.1:7978`

---

## 3. Menjalankan di Background menggunakan PM2 (Opsional)
Jika Anda ingin menjalankan aplikasi ini secara terus-menerus di server production/background menggunakan PM2:
```bash
pm2 start "php artisan serve --port=7978" --name "fraud-detection"
```

---

## 4. Kredensial Uji Coba Keamanan
*   **Akun Mahasiswa:**
    *   **NPM/Username:** `2021012000`
    *   **Password:** `echa123`
    *   *Detail:* Identitas terkunci sebagai **Echa** pada portal presensi.
*   **Akun Dosen:**
    *   **NIDN/Username:** `1985051201`
    *   **Password:** `password`
    *   *Detail:* Memiliki akses penuh ke panel monitoring deteksi fraud, penilaian tugas, dan menu admin.
