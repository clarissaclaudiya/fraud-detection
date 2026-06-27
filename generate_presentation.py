import os
import sys

# Pastikan fpdf2 terinstal
try:
    from fpdf import FPDF
except ImportError:
    print("Menginstal library fpdf2...")
    os.system(sys.executable + " -m pip install fpdf2")
    from fpdf import FPDF

class PresentationPDF(FPDF):
    def __init__(self):
        # Inisialisasi dengan format landscape A4 (297 x 210 mm)
        super().__init__(orientation="landscape", unit="mm", format="A4")
        self.set_margin(20)
        self.set_auto_page_break(auto=False)
        
    def header(self):
        # Header hanya untuk halaman isi (bukan cover/halaman 1)
        if self.page_no() > 1:
            self.set_font("Helvetica", "B", 8)
            self.set_text_color(70, 72, 212) # Deep Indigo
            self.cell(0, 10, "SECURE ATTENDANCE SHIELD  |  PORTAL AKADEMIK INTEGRASI LMS", new_x="LMARGIN", new_y="NEXT", align="R")
            self.set_draw_color(226, 232, 240) # Slate light border
            self.line(20, 25, 277, 25)
            self.ln(10)
            
    def footer(self):
        if self.page_no() > 1:
            self.set_y(-15)
            self.set_font("Helvetica", "I", 8)
            self.set_text_color(148, 163, 184) # Slate gray
            self.cell(0, 10, f"Halaman {self.page_no()}  |  Dikembangkan oleh Echa", new_x="RIGHT", new_y="TOP", align="C")

    def draw_bg_decoration(self):
        # Tambahkan aksen dekorasi garis/kotak di setiap slide
        self.set_fill_color(70, 72, 212) # Deep Indigo
        self.rect(0, 0, 8, 210, "F") # Aksen bar vertikal kiri
        
    def add_slide_title(self, title):
        self.set_font("Helvetica", "B", 22)
        self.set_text_color(30, 41, 59) # Dark Slate
        self.cell(0, 15, title, new_x="LMARGIN", new_y="NEXT")
        self.ln(5)

    def draw_bullet_point(self, title, desc, icon="[+]"):
        self.set_font("Helvetica", "B", 12)
        self.set_text_color(70, 72, 212) # Deep Indigo
        self.cell(15, 8, icon, new_x="RIGHT", new_y="TOP")
        self.cell(0, 8, title, new_x="LMARGIN", new_y="NEXT")
        
        self.set_font("Helvetica", "", 10.5)
        self.set_text_color(71, 85, 105) # Slate Gray
        self.set_x(35)
        self.multi_cell(0, 6, desc)
        self.ln(4)

# Create PDF Instance
pdf = PresentationPDF()

# ==================== SLIDE 1: COVER SLIDE ====================
pdf.add_page()
pdf.set_fill_color(30, 41, 59) # Dark Slate BG
pdf.rect(0, 0, 297, 210, "F")

# Aksen bar ungu di cover
pdf.set_fill_color(70, 72, 212)
pdf.rect(0, 0, 15, 210, "F")

# Title & Subtitle
pdf.set_y(65)
pdf.set_x(35)
pdf.set_font("Helvetica", "B", 32)
pdf.set_text_color(255, 255, 255)
pdf.cell(0, 15, "SECURE ATTENDANCE SHIELD", new_x="LMARGIN", new_y="NEXT")

pdf.set_x(35)
pdf.set_font("Helvetica", "B", 16)
pdf.set_text_color(129, 140, 248) # Indigo light
pdf.cell(0, 10, "Platform Keamanan Presensi & Integrasi LMS Terlengkap", new_x="LMARGIN", new_y="NEXT")

pdf.set_x(35)
pdf.set_font("Helvetica", "", 11)
pdf.set_text_color(203, 213, 225) # Light gray
pdf.cell(0, 10, "Analisis Forensik Geofencing, Velocity, Device Sharing, dan Proctoring Shield", new_x="LMARGIN", new_y="NEXT")

# Footer Cover
pdf.set_y(155)
pdf.set_x(35)
pdf.set_font("Helvetica", "B", 10)
pdf.set_text_color(255, 255, 255)
pdf.cell(0, 6, "Dikembangkan Oleh: Echa", new_x="LMARGIN", new_y="NEXT")
pdf.set_x(35)
pdf.set_font("Helvetica", "", 9)
pdf.set_text_color(148, 163, 184)
pdf.cell(0, 6, "Teknologi Stack: Laravel 11, SQLite, Tailwind CSS, PM2", new_x="LMARGIN", new_y="NEXT")


# ==================== SLIDE 2: LATAR BELAKANG & TUJUAN ====================
pdf.add_page()
pdf.draw_bg_decoration()
pdf.set_y(30)
pdf.set_x(20)
pdf.add_slide_title("Latar Belakang & Tujuan Proyek")

pdf.draw_bullet_point(
    "Tantangan Integritas Akademik",
    "Kecurangan dalam presensi (seperti joki absen) dan ujian daring (membuka tab browser lain, menyalin jawaban eksternal) menjadi masalah krusial di era pembelajaran digital modern.",
    "01"
)
pdf.draw_bullet_point(
    "Integrasi Sistem LMS & Security Shield",
    "Membangun portal terpadu yang menggabungkan fitur manajemen pembelajaran standar (Bahan Ajar & Tugas) dengan protokol pengawasan ketat berbasis kecerdasan buatan.",
    "02"
)
pdf.draw_bullet_point(
    "Pencegahan Preventif",
    "Menyediakan sistem peringatan dini (early-warning system) bagi dosen dan tindakan tegas bagi pelanggar guna menjaga keadilan akademik bagi seluruh mahasiswa.",
    "03"
)


# ==================== SLIDE 3: DETEKS FRAUD PRESENSI (VERIFIKATOR ENGINE) ====================
pdf.add_page()
pdf.draw_bg_decoration()
pdf.set_y(30)
pdf.set_x(20)
pdf.add_slide_title("Verifikator Presensi: Tiga Pilar Utama")

pdf.draw_bullet_point(
    "Analisis Geofencing (Formula Haversine)",
    "Menghitung jarak presisi antara koordinat GPS perangkat mahasiswa dengan titik tengah kelas. Mahasiswa wajib berada di dalam batas radius 50 meter agar kehadiran sah. Jika di luar batas, status diubah menjadi FRAUD_SUSPECTED.",
    "[GPS]"
)
pdf.draw_bullet_point(
    "Deteksi Kecepatan Tidak Rasional (Velocity Anomaly)",
    "Memantau perpindahan posisi fisik mahasiswa. Jika jarak antar sesi presensi dalam 24 jam terakhir membutuhkan kecepatan di atas 80 km/jam, sistem otomatis menandai anomali dan mencurigai adanya joki akun.",
    "[VEL]"
)
pdf.draw_bullet_point(
    "Deteksi Perangkat Bersama (Device Sharing)",
    "Menganalisis sidik jari browser (Browser Fingerprint) dan alamat IP. Jika satu tanda sidik jari perangkat digunakan oleh beberapa NPM berbeda dalam waktu berdekatan, sistem mengindikasikan joki massal.",
    "[DEV]"
)


# ==================== SLIDE 4: PROCTORING SHIELD (UJIAN ONLINE AMAN) ====================
pdf.add_page()
pdf.draw_bg_decoration()
pdf.set_y(30)
pdf.set_x(20)
pdf.add_slide_title("Ujian Terproktor & Proctoring Shield")

pdf.draw_bullet_point(
    "Sistem Pengawasan Browser Real-Time",
    "Saat mahasiswa memulai ujian, sistem mengaktifkan sensor blur pada jendela browser. Setiap kali mahasiswa mengalihkan fokus atau berpindah tab browser, pelanggaran langsung dicatat.",
    "[SHD]"
)
pdf.draw_bullet_point(
    "Pemblokiran Aksi Salin-Tempel (Copy-Paste)",
    "Mencegah mahasiswa menyalin teks pertanyaan ujian untuk dicari di luar, atau menempelkan jawaban dari dokumen eksternal. Aksi disaring di tingkat browser dan diblokir secara mutlak.",
    "[CPY]"
)
pdf.draw_bullet_point(
    "Pemicu Alarm Kecurangan Otomatis",
    "Jika pelanggaran melebihi batas toleransi (keluar tab >= 3 kali atau copy-paste >= 1 kali), sistem otomatis menerbitkan berkas anomali PROCTORING_SHIELD ke dasbor dosen untuk pemeriksaan forensik lebih lanjut.",
    "[ALR]"
)


# ==================== SLIDE 5: INTEGRASI MODUL LMS & EVALUASI NILAI ====================
pdf.add_page()
pdf.draw_bg_decoration()
pdf.set_y(30)
pdf.set_x(20)
pdf.add_slide_title("Portal LMS & Rekapitulasi Nilai Akhir")

pdf.draw_bullet_point(
    "Distribusi Bahan Ajar & Tugas Kuliah",
    "Memungkinkan dosen mengunggah materi resmi dan menerbitkan tugas. Mahasiswa dapat mengunduh materi dan mengumpulkan laporan jawaban berupa teks langsung pada portal.",
    "[LMS]"
)
pdf.draw_bullet_point(
    "Panel Penilaian Interaktif Dosen",
    "Dosen memiliki hak penuh untuk mengoreksi tugas mahasiswa, memberikan feedback tulisan, dan memasukkan nilai angka (skala 0 - 100) yang langsung tersinkronisasi.",
    "[GRD]"
)
pdf.draw_bullet_point(
    "Sinkronisasi Transkrip Nilai Otomatis",
    "Menghitung nilai akhir secara dinamis dengan formula pembobotan: (Presensi Sah * 20%) + (Rata-rata Tugas * 30%) + (Ujian Akhir * 50%). Nilai dikonversi menjadi Grade Huruf (A hingga E) secara real-time.",
    "[TRN]"
)


# ==================== SLIDE 6: KESIMPULAN & KEUNGGULAN PROYEK ====================
pdf.add_page()
pdf.draw_bg_decoration()
pdf.set_y(30)
pdf.set_x(20)
pdf.add_slide_title("Kesimpulan & Keunggulan Sistem")

pdf.draw_bullet_point(
    "Pengamanan Lapis Ganda (Double Protection)",
    "Backend controller memverifikasi header custom X-User-NPM and X-User-Role guna menghentikan penembakan API ilegal. Fungsi frontend melacak manipulasi visual dan memaksa pengusiran sesi (visual kick) jika terjadi pelanggaran hak akses.",
    "[SEC]"
)
pdf.draw_bullet_point(
    "Arsitektur Ringan & Skala Tinggi",
    "Menggunakan perpaduan Laravel, SQLite, dan Tailwind CSS via CDN. Menghasilkan respons cepat (di bawah 50ms) dengan konsumsi memori server minimal (PM2 monitoring membuktikan memori stabil di ~49 MB).",
    "[ARC]"
)
pdf.draw_bullet_point(
    "Transparansi & Akuntabilitas Akademik",
    "Menyediakan data forensik yang lengkap dan akurat bagi dosen untuk mengambil keputusan sanksi akademik secara adil dan transparan tanpa keraguan.",
    "[LAW]"
)

# Simpan PDF
output_path = "/var/www/fraud-detection/public/presentasi_project.pdf"
pdf.output(output_path)
print(f"PDF Berhasil dibuat di: {output_path}")
