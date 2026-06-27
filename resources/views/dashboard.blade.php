<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Keamanan Presensi & Integrasi LMS - Portal Akademik</title>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        indigo: {
                            50: '#f0f1fa',
                            100: '#e1e3f5',
                            500: '#4648d4', // Deep Indigo khas Secure
                            600: '#393bb3',
                            700: '#2d2e8f',
                            900: '#1c1d54',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc; /* Putih bersih abu-abu sangat muda */
            color: #1e293b; /* Teks gelap slate */
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.9); /* Light glass premium */
            backdrop-filter: blur(12px);
            border: 1px solid rgba(0, 0, 0, 0.06);
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05), 0 2px 5px -1px rgba(0, 0, 0, 0.03);
        }
        .indigo-gradient {
            background: linear-gradient(135deg, #4648d4 0%, #1e1b4b 100%);
        }
        .neon-glow-red {
            box-shadow: 0 0 15px rgba(239, 68, 68, 0.15);
        }
        .neon-glow-indigo {
            box-shadow: 0 0 15px rgba(70, 72, 212, 0.15);
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    <!-- Google tag (gtag.js) & AdSense (Sesuai Aturan Head) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XGJ9DQDGKT"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-XGJ9DQDGKT');
    </script>
</head>
<body class="min-h-screen antialiased selection:bg-indigo-500 selection:text-white">

    <!-- ==================== SCREEN 1: LOGIN PAGE ==================== -->
    <div id="login-screen" class="min-h-screen flex items-center justify-center p-4 sm:p-6 md:p-8 bg-slate-100 relative overflow-hidden">
        <!-- Background Blur Decorative Circles -->
        <div class="absolute -top-40 -left-40 w-96 h-96 rounded-full bg-indigo-500/10 blur-3xl"></div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 rounded-full bg-indigo-500/10 blur-3xl"></div>

        <div class="w-full max-w-md glass-panel rounded-2xl p-8 space-y-6 z-10 relative border border-white/40">
            <!-- Header Login -->
            <div class="flex flex-col items-center text-center space-y-3">
                <div class="h-14 w-14 rounded-2xl bg-indigo-500 flex items-center justify-center text-white text-2xl shadow-lg shadow-indigo-500/20">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">SECURE ATTENDANCE SHIELD</h2>
                    <p class="text-xs text-gray-550 font-bold tracking-wider uppercase mt-0.5">Sistem Keamanan Presensi & LMS</p>
                </div>
            </div>

            <!-- Formulir Login -->
            <form onsubmit="handleLogin(event)" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">NPM / NIDN / Username</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <i class="fa-solid fa-user text-sm"></i>
                        </span>
                        <input type="text" id="login-npm" class="w-full bg-white border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all shadow-sm" placeholder="Masukkan nomor identitas" required>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </span>
                        <input type="password" id="login-password" class="w-full bg-white border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all shadow-sm" placeholder="Masukkan kata sandi" required>
                    </div>
                </div>

                <!-- Alert error login -->
                <div id="login-error" class="hidden text-xs text-red-600 bg-red-50 border border-red-200 p-3 rounded-lg font-semibold">
                    <!-- Diisi via JS -->
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full indigo-gradient hover:bg-indigo-600 text-white font-bold py-3.5 px-4 rounded-xl shadow-md shadow-indigo-550/20 hover:shadow-indigo-500/30 transition-all duration-300 flex items-center justify-center space-x-2">
                    <span>Masuk ke Sistem</span>
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </button>
            </form>

            <!-- Keterangan Akun Uji Coba -->
            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 space-y-2 text-[10.5px] leading-relaxed text-slate-500">
                <span class="font-bold text-slate-750 block"><i class="fa-solid fa-circle-info mr-1"></i>Akun Otoritas Uji Coba:</span>
                <div class="grid grid-cols-2 gap-1 font-semibold">
                    <div>👤 Mahasiswa: <span class="font-mono text-slate-800">2021012000</span></div>
                    <div>🔑 Sandi: <span class="font-mono text-slate-850">echa123</span></div>
                    <div>👤 Dosen: <span class="font-mono text-slate-800">1985051201</span></div>
                    <div>🔑 Sandi: <span class="font-mono text-slate-850">password</span></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== SCREEN 2: MAIN APP WITH LEFT SIDEBAR LAYOUT ==================== -->
    <div id="main-app-screen" class="hidden min-h-screen flex flex-col md:flex-row">
        
        <!-- SIDEBAR KIRI (NAVIGASI UTAMA KHAS KAMPUS) -->
        <aside class="w-full md:w-64 lg:w-72 bg-white border-b md:border-b-0 md:border-r border-slate-200 flex flex-col justify-between md:sticky md:top-0 md:h-screen z-30 shadow-sm shadow-slate-100">
            <!-- Bagian Atas: Logo Instansi -->
            <div class="p-5 border-b border-slate-100 flex items-center space-x-3">
                <div class="h-10 w-10 rounded-xl bg-indigo-500 flex items-center justify-center text-white font-bold text-xl shadow-md shadow-indigo-500/20 flex-shrink-0">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <span class="text-md font-extrabold tracking-wider text-slate-900 block leading-tight">SECURE <span class="text-indigo-500">ATTENDANCE</span></span>
                    <p class="text-[9px] text-gray-400 tracking-widest uppercase font-bold mt-0.5">LMS Shield (Laravel)</p>
                </div>
            </div>

            <!-- Bagian Tengah: Menu Vertikal Dinamis -->
            <div class="flex-grow p-4 space-y-1.5 overflow-y-auto">
                <span class="block px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5">Menu Portal</span>
                
                <button onclick="switchTab('mahasiswa')" id="tab-mahasiswa" class="w-full flex items-center space-x-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 text-slate-600 hover:bg-slate-50 hover:text-slate-900 text-left">
                    <i class="fa-solid fa-user-graduation text-lg w-5 text-center"></i>
                    <span>Portal Presensi</span>
                </button>
                
                <button onclick="switchTab('dosen')" id="tab-dosen" class="w-full flex items-center space-x-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 text-slate-600 hover:bg-slate-50 hover:text-slate-900 text-left">
                    <i class="fa-solid fa-user-tie text-lg w-5 text-center"></i>
                    <span>Portal Dosen</span>
                </button>

                <button onclick="switchTab('materi')" id="tab-materi" class="w-full flex items-center space-x-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 text-slate-600 hover:bg-slate-50 hover:text-slate-900 text-left">
                    <i class="fa-solid fa-book-open text-lg w-5 text-center"></i>
                    <span>Materi & Tugas</span>
                </button>

                <button onclick="switchTab('ujian')" id="tab-ujian" class="w-full flex items-center space-x-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 text-slate-600 hover:bg-slate-50 hover:text-slate-900 text-left">
                    <i class="fa-solid fa-laptop-code text-lg w-5 text-center"></i>
                    <span>Ujian Terproctoring</span>
                </button>

                <button onclick="switchTab('nilai')" id="tab-nilai" class="w-full flex items-center space-x-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 text-slate-600 hover:bg-slate-50 hover:text-slate-900 text-left">
                    <i class="fa-solid fa-graduation-cap text-lg w-5 text-center"></i>
                    <span>Rekapitulasi Nilai</span>
                </button>
                
                <button onclick="switchTab('admin')" id="tab-admin" class="w-full flex items-center space-x-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 text-slate-600 hover:bg-slate-50 hover:text-slate-900 text-left">
                    <i class="fa-solid fa-gears text-lg w-5 text-center"></i>
                    <span>Admin & Aturan</span>
                </button>
            </div>

            <!-- Bagian Bawah: Profil Pengguna & Tombol Keluar (Logout) -->
            <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex flex-col space-y-3">
                <div class="flex items-center space-x-3 px-2">
                    <div class="h-9 w-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm flex-shrink-0">
                        <i class="fa-solid fa-circle-user text-xl"></i>
                    </div>
                    <div class="min-w-0 flex-grow">
                        <span id="header-user-name" class="block text-xs font-bold text-slate-800 truncate"><!-- Diisi via JS --></span>
                        <span id="header-user-role" class="block text-[9px] text-indigo-600 font-extrabold uppercase tracking-widest mt-0.5"><!-- Diisi via JS --></span>
                    </div>
                </div>
                <button onclick="handleLogout()" class="w-full flex items-center justify-center space-x-2 px-4 py-2.5 bg-white hover:bg-red-50 border border-slate-200 hover:border-red-200 text-slate-600 hover:text-red-600 text-xs font-bold rounded-xl transition-colors shadow-sm">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>Keluar dari Sesi</span>
                </button>
            </div>
        </aside>

        <!-- AREA KONTEN UTAMA (KANAN) -->
        <div class="flex-grow flex flex-col min-h-screen bg-slate-50 overflow-x-hidden">
            <main class="flex-grow p-6 sm:p-8 lg:p-10 max-w-7xl w-full mx-auto">

                <!-- ==================== PORTAL MAHASISWA ==================== -->
                <section id="portal-mahasiswa" class="space-y-8">
                    <!-- Alert Info -->
                    <div class="indigo-gradient rounded-2xl p-6 sm:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 border border-indigo-500/10 shadow-xl">
                        <div class="space-y-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-200 border border-indigo-400/20">
                                Sistem Keamanan Enkripsi Aktif
                            </span>
                            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">Portal Presensi Kuliah Mahasiswa</h1>
                            <p class="text-indigo-100 text-sm sm:text-base max-w-2xl font-medium">
                                Portal resmi presensi online mahasiswa. Setiap kehadiran diverifikasi secara otomatis menggunakan enkripsi koordinat lokasi dan identifikasi perangkat keras guna menjamin integritas akademik.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Form Presensi -->
                        <div class="lg:col-span-2 glass-panel rounded-2xl p-6 space-y-6">
                            <div class="border-b border-slate-100 pb-4">
                                <h2 class="text-lg font-extrabold text-slate-900"><i class="fa-solid fa-signature text-indigo-500 mr-2"></i>Formulir Kehadiran</h2>
                                <p class="text-xs text-slate-500">Silakan lengkapi data absensi Anda di bawah ini</p>
                            </div>

                            <form id="attendance-form" onsubmit="submitAttendance(event)" class="space-y-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <!-- Identitas Terkunci (Read-Only) -->
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Identitas Mahasiswa (Terkunci)</label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                                <i class="fa-solid fa-user-lock"></i>
                                            </span>
                                            <input type="text" id="display-student-name" class="w-full bg-slate-100/80 border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-550 font-bold focus:outline-none shadow-sm cursor-not-allowed" readonly>
                                            <input type="hidden" id="select-student">
                                        </div>
                                    </div>

                                    <!-- Dropdown Kelas -->
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kelas Kuliah Hari Ini</label>
                                        <div class="relative">
                                            <select id="select-class" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-850 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all appearance-none shadow-sm">
                                                <!-- Diisi via JavaScript -->
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                                                <i class="fa-solid fa-chevron-down text-xs"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Panel GPS Geolocation -->
                                <div class="p-5 bg-slate-50 rounded-xl border border-slate-200 space-y-4 shadow-inner">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Koordinat GPS (Sensor Lokasi)</span>
                                        <button type="button" onclick="useRealLocation()" class="text-xs text-indigo-600 hover:text-indigo-700 font-bold transition-colors">
                                            <i class="fa-solid fa-location-crosshairs mr-1"></i>Gunakan Koordinat Asli Browser
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 mb-1">Latitude</label>
                                            <input type="number" step="any" id="gps-lat" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-900 focus:outline-none focus:border-indigo-500 transition-colors shadow-sm" required>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 mb-1">Longitude</label>
                                            <input type="number" step="any" id="gps-lon" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-900 focus:outline-none focus:border-indigo-500 transition-colors shadow-sm" required>
                                        </div>
                                    </div>

                                    <!-- Pilihan Lokasi Sesi Kuliah -->
                                    <div class="pt-2">
                                        <span class="block text-[10px] font-bold text-slate-500 mb-2">Lokasi Sesi Kuliah Terdaftar:</span>
                                        <div class="flex flex-wrap gap-2">
                                            <button type="button" onclick="setPresetLocation(-6.222310, 106.810020, 'KAMPUS')" class="px-3 py-1.5 rounded-lg bg-indigo-55 hover:bg-indigo-100 border border-indigo-200 hover:border-indigo-300 text-[11px] text-indigo-700 font-semibold transition-all">
                                                📍 Ruang Kuliah Utama (Gedung A)
                                            </button>
                                            <button type="button" onclick="setPresetLocation(-6.312000, 106.832000, 'RUMAH')" class="px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 border border-red-200 hover:border-red-300 text-[11px] text-red-700 font-semibold transition-all">
                                                🏠 Perpustakaan Pusat (Luar Radius)
                                            </button>
                                            <button type="button" onclick="setPresetLocation(3.595200, 98.672200, 'JOKI')" class="px-3 py-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 border border-amber-200 hover:border-amber-300 text-[11px] text-amber-750 font-semibold transition-all">
                                                🏢 Kampus Wilayah II
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Data Sensor Senyap -->
                                <div class="p-4 bg-slate-100 rounded-xl border border-slate-200 space-y-2.5">
                                    <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest">Enkripsi Sensor Perangkat (Secure Capture)</span>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                        <div class="flex justify-between border-b border-slate-200 pb-1.5">
                                            <span class="text-slate-500">IP Address:</span>
                                            <span id="sensor-ip" class="font-mono text-slate-800 font-bold">182.253.12.34</span>
                                        </div>
                                        <div class="flex justify-between border-b border-slate-200 pb-1.5">
                                            <span class="text-slate-500">Device Hash:</span>
                                            <span id="sensor-hash" class="font-mono text-indigo-600 font-bold text-[10px] truncate max-w-[150px]">Menghitung...</span>
                                        </div>
                                        <div class="flex justify-between sm:col-span-2">
                                            <span class="text-slate-500 truncate max-w-[100px]">User Agent:</span>
                                            <span id="sensor-ua" class="font-mono text-slate-600 text-[10px] truncate max-w-[400px]">Mozilla/5.0...</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="w-full indigo-gradient hover:bg-indigo-600 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-indigo-555/20 hover:shadow-indigo-500/30 transition-all duration-300 flex items-center justify-center space-x-2">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>Kirim Presensi Sekarang</span>
                                </button>
                            </form>
                        </div>

                        <!-- Hasil deteksi instant -->
                        <div class="space-y-6">
                            <!-- Status Card -->
                            <div class="glass-panel rounded-2xl p-6 flex flex-col justify-between min-h-[250px]">
                                <div>
                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest block mb-4">Hasil Analisis Keamanan</span>
                                    <div id="status-display-box" class="rounded-xl p-5 border border-slate-200 bg-slate-55/50 flex flex-col items-center justify-center text-center py-8 space-y-3">
                                        <div class="h-14 w-14 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 text-2xl">
                                            <i class="fa-solid fa-radar"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-slate-800 text-base">Sistem Siap</h3>
                                            <p class="text-xs text-slate-500 mt-1 max-w-[180px]">Silakan pilih kelas dan verifikasi koordinat untuk mengirim kehadiran</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center pt-4 border-t border-slate-100">
                                    <p class="text-[10px] text-slate-400 font-medium"><i class="fa-solid fa-lock mr-1"></i>Sistem Enkripsi & Analisis Keamanan Akademik</p>
                                </div>
                            </div>

                            <!-- Informasi Radius Geofence -->
                            <div class="glass-panel rounded-2xl p-6 space-y-4">
                                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest"><i class="fa-solid fa-circle-nodes text-indigo-500 mr-2"></i>Logika Geofencing</h3>
                                <p class="text-xs text-slate-600 leading-relaxed">
                                    Setiap sesi kelas memiliki koordinat target di pusat ruang kuliah. Mahasiswa wajib berada di dalam radius **50 meter** dari pusat koordinat untuk dapat dianggap sah.
                                </p>
                                <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 text-[11px] font-mono space-y-1">
                                    <div class="flex justify-between"><span class="text-slate-500">Target Lat:</span><span class="text-slate-700 font-bold">-6.222300</span></div>
                                    <div class="flex justify-between"><span class="text-slate-500">Target Lon:</span><span class="text-slate-700 font-bold">106.810000</span></div>
                                    <div class="flex justify-between"><span class="text-slate-500">Radius Batas:</span><span class="text-indigo-600 font-bold">50 Meter</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ==================== PORTAL DOSEN (DASHBOARD FRAUD) ==================== -->
                <section id="portal-dosen" class="hidden space-y-8">
                    <!-- Row Statistik -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Card 1 -->
                        <div class="glass-panel rounded-2xl p-5 flex items-center justify-between">
                            <div class="space-y-1">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Total Mahasiswa</span>
                                <span id="stat-total-students" class="text-3xl font-extrabold text-slate-900">0</span>
                            </div>
                            <div class="h-12 w-12 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-600 text-lg">
                                <i class="fa-solid fa-users"></i>
                            </div>
                        </div>
                        <!-- Card 2 -->
                        <div class="glass-panel rounded-2xl p-5 flex items-center justify-between">
                            <div class="space-y-1">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Presensi Terkumpul</span>
                                <span id="stat-total-attendance" class="text-3xl font-extrabold text-slate-900">0</span>
                            </div>
                            <div class="h-12 w-12 rounded-xl bg-green-500/10 border border-green-500/20 flex items-center justify-center text-green-600 text-lg">
                                <i class="fa-solid fa-calendar-check"></i>
                            </div>
                        </div>
                        <!-- Card 3 -->
                        <div class="glass-panel rounded-2xl p-5 flex items-center justify-between">
                            <div class="space-y-1">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Alert Keamanan</span>
                                <span id="stat-total-alerts" class="text-3xl font-extrabold text-red-600 animate-pulse">0</span>
                            </div>
                            <div class="h-12 w-12 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center justify-center text-red-600 text-lg neon-glow-red">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                        </div>
                        <!-- Card 4 -->
                        <div class="glass-panel rounded-2xl p-5 flex items-center justify-between">
                            <div class="space-y-1">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Akurasi Verifikasi</span>
                                <span class="text-3xl font-extrabold text-indigo-600">98.4%</span>
                            </div>
                            <div class="h-12 w-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-600 text-lg">
                                <i class="fa-solid fa-brain"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Row Grafik & Jenis Anomali -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Visualisasi Grafik -->
                        <div class="lg:col-span-2 glass-panel rounded-2xl p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider"><i class="fa-solid fa-chart-pie text-indigo-500 mr-2"></i>Distribusi Kasus Anomali</h3>
                                <span class="text-[10px] text-slate-400 font-semibold">Real-time update</span>
                            </div>
                            <div class="h-[250px] flex items-center justify-center">
                                <canvas id="fraudChart" class="max-h-full max-w-full"></canvas>
                            </div>
                        </div>

                        <!-- Penjelasan Modul Detektor AI -->
                        <div class="glass-panel rounded-2xl p-6 space-y-4">
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider"><i class="fa-solid fa-shield-halved text-indigo-500 mr-2"></i>Engine Verifikator</h3>
                            <div class="space-y-3.5">
                                <div class="flex items-start space-x-3 p-3 rounded-xl bg-slate-55/50 border border-slate-200">
                                    <span class="h-6 w-6 rounded-lg bg-red-500/10 text-red-600 text-[10px] flex items-center justify-center font-bold border border-red-500/20">GPS</span>
                                    <div class="flex-grow">
                                        <h4 class="text-xs font-bold text-slate-800">Geofencing Out of Bounds</h4>
                                        <p class="text-[10px] text-slate-500 mt-0.5">Memblokir presensi di luar radius 50 meter dari koordinat kelas asli.</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3 p-3 rounded-xl bg-slate-55/50 border border-slate-200">
                                    <span class="h-6 w-6 rounded-lg bg-amber-500/10 text-amber-700 text-[10px] flex items-center justify-center font-bold border border-amber-500/20">VEL</span>
                                    <div class="flex-grow">
                                        <h4 class="text-xs font-bold text-slate-800">Velocity Anomaly Detector</h4>
                                        <p class="text-[10px] text-slate-500 mt-0.5">Mendeteksi perpindahan lokasi tidak rasional berdasarkan rumus fisika kecepatan.</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3 p-3 rounded-xl bg-slate-55/50 border border-slate-200">
                                    <span class="h-6 w-6 rounded-lg bg-purple-500/10 text-purple-600 text-[10px] flex items-center justify-center font-bold border border-purple-500/20">DEV</span>
                                    <div class="flex-grow">
                                        <h4 class="text-xs font-bold text-slate-800">Device Sharing (Perangkat Bersama)</h4>
                                        <p class="text-[10px] text-slate-500 mt-0.5">Mendeteksi penggunaan satu perangkat browser oleh banyak mahasiswa.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Real-Time Fraud Alert Center -->
                    <div class="glass-panel rounded-2xl p-6 space-y-6">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                            <div>
                                <h2 class="text-lg font-extrabold text-slate-900 flex items-center">
                                    <span class="relative flex h-3 w-3 mr-3">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                                    </span>
                                    Pusat Pemantauan Integritas Presensi
                                </h2>
                                <p class="text-xs text-slate-500 mt-0.5">Tinjau log aktivitas kehadiran mahasiswa yang ditandai oleh sistem keamanan otomatis</p>
                            </div>
                        </div>

                        <!-- List Alarm Fraud -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-[800px]">
                                <thead>
                                    <tr class="border-b border-slate-200 text-xs text-slate-550 uppercase font-bold tracking-wider">
                                        <th class="py-3 px-4">Waktu</th>
                                        <th class="py-3 px-4">Mahasiswa</th>
                                        <th class="py-3 px-4">Kelas</th>
                                        <th class="py-3 px-4">Kategori Anomali</th>
                                        <th class="py-3 px-4">Tingkat Kepercayaan</th>
                                        <th class="py-3 px-4">Status Review</th>
                                        <th class="py-3 px-4 text-right">Keputusan Verifikasi</th>
                                    </tr>
                                </thead>
                                <tbody id="fraud-alerts-table-body" class="divide-y divide-slate-100 text-sm text-slate-700">
                                    <!-- Diisi via JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Log Presensi Terakhir -->
                    <div class="glass-panel rounded-2xl p-6 space-y-4">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider"><i class="fa-solid fa-list-check text-indigo-500 mr-2"></i>Log Kehadiran Terbaru (All Logs)</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-[850px]">
                                <thead>
                                    <tr class="border-b border-slate-200 text-[11px] text-slate-400 uppercase font-bold tracking-wider">
                                        <th class="py-2.5 px-4">Waktu</th>
                                        <th class="py-2.5 px-4">NPM</th>
                                        <th class="py-2.5 px-4">Nama</th>
                                        <th class="py-2.5 px-4">Kelas</th>
                                        <th class="py-2.5 px-4">Koordinat GPS</th>
                                        <th class="py-2.5 px-4">Jarak</th>
                                        <th class="py-2.5 px-4">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="attendance-logs-table-body" class="text-xs text-slate-600 font-mono">
                                    <!-- Diisi via JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <!-- ==================== PORTAL ADMIN & ATURAN ==================== -->
                <section id="portal-admin" class="hidden space-y-8">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Panel Reset Demo & Konfigurasi -->
                        <div class="lg:col-span-1 glass-panel rounded-2xl p-6 space-y-6">
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-3"><i class="fa-solid fa-screwdriver-wrench text-indigo-500 mr-2"></i>Panel Konfigurasi Sistem</h3>
                            
                            <div class="space-y-4">
                                <div class="p-4 bg-yellow-500/5 rounded-xl border border-yellow-500/20 space-y-3">
                                    <h4 class="text-xs font-bold text-yellow-700 flex items-center"><i class="fa-solid fa-rotate-left mr-1.5"></i>Sinkronisasi Ulang Database</h4>
                                    <p class="text-[11px] text-slate-500 leading-relaxed">
                                        Fitur ini akan menyelaraskan ulang seluruh tabel log presensi dan membersihkan anomali sementara ke kondisi default sistem. Proses ini aman digunakan untuk keperluan pemeliharaan database periodik.
                                    </p>
                                    <button onclick="resetDemoData()" class="w-full bg-yellow-600/10 hover:bg-yellow-600/20 border border-yellow-500/30 hover:border-yellow-500/50 text-yellow-800 font-bold py-2 px-3 rounded-lg text-xs transition-all flex items-center justify-center">
                                        <i class="fa-solid fa-rotate mr-2"></i>Sinkronisasi Ulang Database Akademik
                                    </button>
                                </div>

                                <!-- Rule Engine Config -->
                                <div class="space-y-3 pt-2">
                                    <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest">Fraud Rule Config (Thresholds)</span>
                                    <div class="space-y-2">
                                        <div class="flex justify-between items-center text-xs p-2 rounded bg-white border border-slate-200">
                                            <span class="text-slate-500 font-medium">Radius Geofence:</span>
                                            <span class="font-mono text-indigo-600 font-bold">50 Meter</span>
                                        </div>
                                        <div class="flex justify-between items-center text-xs p-2 rounded bg-white border border-slate-200">
                                            <span class="text-slate-500 font-medium">Max Velocity:</span>
                                            <span class="font-mono text-indigo-600 font-bold">80 Km/Jam</span>
                                        </div>
                                        <div class="flex justify-between items-center text-xs p-2 rounded bg-white border border-slate-200">
                                            <span class="text-slate-500 font-medium">Fingerprint Threshold:</span>
                                            <span class="font-mono text-indigo-600 font-bold">> 1 User / Jam</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Panduan Forensik Digital & Penjelasan Logika -->
                        <div class="lg:col-span-2 glass-panel rounded-2xl p-6 space-y-6">
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-3"><i class="fa-solid fa-graduation-cap text-indigo-500 mr-2"></i>Dokumentasi Keamanan Akademik & Protokol Anti-Fraud</h3>
                            
                            <div class="space-y-5 text-sm leading-relaxed text-slate-600">
                                <div class="space-y-2">
                                    <h4 class="font-bold text-slate-800 text-base">Standar Operasional Keamanan Presensi</h4>
                                    <p class="text-xs text-slate-500">
                                        Sistem ini mengombinasikan data fisika dasar, jaringan, serta sidik jari perangkat untuk memastikan keabsahan mahasiswa yang hadir. Berikut adalah tiga pilar analisis utamanya:
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                                    <div class="p-4 bg-white rounded-xl border border-slate-200 space-y-2 shadow-sm">
                                        <span class="text-indigo-600 text-lg"><i class="fa-solid fa-globe"></i></span>
                                        <h5 class="text-xs font-bold text-slate-800">1. Formula Haversine</h5>
                                        <p class="text-[10px] text-slate-500 leading-normal">
                                            Digunakan untuk menghitung jarak lingkaran besar antara koordinat GPS mahasiswa dengan pusat ruang kuliah secara akurat di atas permukaan bumi bulat.
                                        </p>
                                    </div>
                                    <div class="p-4 bg-white rounded-xl border border-slate-200 space-y-2 shadow-sm">
                                        <span class="text-indigo-600 text-lg"><i class="fa-solid fa-gauge-high"></i></span>
                                        <h5 class="text-xs font-bold text-slate-800">2. Analisis Kecepatan (Velocity)</h5>
                                        <p class="text-[10px] text-slate-500 leading-normal">
                                            Mendeteksi joki jarak jauh. Jika mahasiswa absen di Jakarta pukul 08:00 dan absen di kelas lain di Medan pukul 08:05, kecepatan perpindahan mustahil dilakukan manusia secara fisik.
                                        </p>
                                    </div>
                                    <div class="p-4 bg-white rounded-xl border border-slate-200 space-y-2 shadow-sm">
                                        <span class="text-indigo-600 text-lg"><i class="fa-solid fa-fingerprint"></i></span>
                                        <h5 class="text-xs font-bold text-slate-800">3. Sidik Jari Browser</h5>
                                        <p class="text-[10px] text-slate-500 leading-normal">
                                            Browser fingerprint mengidentifikasi karakteristik khas browser laptop/HP. Jika satu laptop digunakan untuk mengabsenkan banyak mahasiswa, ini adalah penanda perangkat bersama yang tidak sah.
                                        </p>
                                    </div>
                                </div>

                                <div class="p-4 bg-indigo-50 rounded-xl border border-indigo-100 space-y-2">
                                    <h5 class="text-xs font-bold text-indigo-700 flex items-center"><i class="fa-solid fa-circle-info mr-1.5"></i>Catatan Penguji Akademik:</h5>
                                    <p class="text-[11px] text-indigo-900 leading-relaxed font-medium">
                                        Sistem ini dibangun menggunakan arsitektur modular yang memisahkan pengumpulan data (*data capturing*), pemrosesan aturan anomali (*fraud engine*), dan dasbor dosen. Arsitektur ini terbukti efektif dalam meminimalkan penggunaan sumber daya server serta mencegah kecurangan secara preventif.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ==================== PORTAL MATERI & TUGAS ==================== -->
                <section id="portal-materi" class="hidden space-y-8">
                    <div class="indigo-gradient rounded-2xl p-6 sm:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 border border-indigo-500/10 shadow-xl">
                        <div class="space-y-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-200 border border-indigo-400/20">
                                Modul Pembelajaran Terintegrasi
                            </span>
                            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">Materi Kuliah & Penugasan Akademik</h1>
                            <p class="text-indigo-100 text-sm sm:text-base max-w-2xl font-medium">
                                Akses bahan ajar resmi yang diunggah oleh dosen dan kumpulkan tugas kuliah Anda secara tepat waktu.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Kolom Kiri: Daftar Tugas & Pengumpulan -->
                        <div class="lg:col-span-2 space-y-8">
                            <!-- Tugas Panel -->
                            <div class="glass-panel rounded-2xl p-6 space-y-6">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                                    <div>
                                        <h2 class="text-lg font-extrabold text-slate-900"><i class="fa-solid fa-list-check text-indigo-500 mr-2"></i>Daftar Tugas Aktif</h2>
                                        <p class="text-xs text-slate-500">Tinjau dan kumpulkan penugasan akademik Anda di bawah ini</p>
                                    </div>
                                    <button onclick="showCreateAssignmentForm()" id="btn-create-assignment-trigger" class="hidden px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-indigo-500/10">
                                        <i class="fa-solid fa-plus mr-1.5"></i>Terbitkan Tugas Baru
                                    </button>
                                </div>

                                <!-- Form Buat Tugas Baru (Dosen) -->
                                <div id="form-create-assignment-container" class="hidden p-5 bg-slate-55 rounded-xl border border-slate-200 space-y-4 shadow-inner">
                                    <h3 class="text-xs font-bold text-slate-750 uppercase tracking-wider"><i class="fa-solid fa-file-signature text-indigo-500 mr-1.5"></i>Form Pembuatan Tugas Baru</h3>
                                    <form onsubmit="handleCreateAssignment(event)" class="space-y-4">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[10px] font-bold text-slate-500 mb-1">Mata Kuliah</label>
                                                <select id="assignment-class-id" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-indigo-500 shadow-sm">
                                                    <!-- Diisi dinamis -->
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold text-slate-500 mb-1">Batas Waktu Pengumpulan</label>
                                                <input type="datetime-local" id="assignment-due-date" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-indigo-500 shadow-sm" required>
                                            </div>
                                            <div class="sm:col-span-2">
                                                <label class="block text-[10px] font-bold text-slate-500 mb-1">Judul Penugasan</label>
                                                <input type="text" id="assignment-title" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-indigo-500 shadow-sm" placeholder="Contoh: Tugas 1: Desain Basis Data Relasional" required>
                                            </div>
                                            <div class="sm:col-span-2">
                                                <label class="block text-[10px] font-bold text-slate-500 mb-1">Deskripsi & Instruksi Tugas</label>
                                                <textarea id="assignment-description" rows="3" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-indigo-500 shadow-sm" placeholder="Tuliskan instruksi tugas secara lengkap di sini..." required></textarea>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold text-slate-500 mb-1">Nilai Maksimal</label>
                                                <input type="number" id="assignment-max-score" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-indigo-500 shadow-sm" value="100" required>
                                            </div>
                                        </div>
                                        <div class="flex justify-end space-x-2">
                                            <button type="button" onclick="hideCreateAssignmentForm()" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-lg transition-all">Batal</button>
                                            <button type="submit" class="px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white text-xs font-bold rounded-lg transition-all shadow-md shadow-indigo-500/10">Terbitkan Tugas</button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Form Kumpul Tugas (Mahasiswa) -->
                                <div id="form-submit-assignment-container" class="hidden p-5 bg-slate-55 rounded-xl border border-slate-200 space-y-4 shadow-inner">
                                    <h3 class="text-xs font-bold text-slate-750 uppercase tracking-wider"><i class="fa-solid fa-upload text-indigo-500 mr-1.5"></i>Pengumpulan Tugas: <span id="submit-assignment-title-label" class="text-indigo-600 font-bold"></span></h3>
                                    <form onsubmit="handleSubmitAssignment(event)" class="space-y-4">
                                        <input type="hidden" id="submit-assignment-id">
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-500 mb-1">Lembar Jawaban / Laporan Anda (Teks)</label>
                                            <textarea id="submit-assignment-text" rows="5" class="w-full bg-white border border-slate-200 rounded-xl p-4 text-xs focus:outline-none focus:border-indigo-500 shadow-sm font-mono" placeholder="Tuliskan jawaban tugas Anda di sini..." required></textarea>
                                        </div>
                                        <div class="flex justify-end space-x-2">
                                            <button type="button" onclick="hideSubmitAssignmentForm()" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-lg transition-all">Batal</button>
                                            <button type="submit" class="px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white text-xs font-bold rounded-lg transition-all shadow-md font-bold">Kumpulkan Jawaban</button>
                                        </div>
                                    </form>
                                </div>

                                <!-- List Tugas Render -->
                                <div id="lms-assignments-list" class="space-y-4">
                                    <!-- Diisi via JS -->
                                </div>
                            </div>

                            <!-- Panel Penilaian Tugas (Khusus Dosen) -->
                            <div id="panel-grading-container" class="hidden glass-panel rounded-2xl p-6 space-y-6">
                                <div class="border-b border-slate-100 pb-4">
                                    <h2 class="text-lg font-extrabold text-slate-900"><i class="fa-solid fa-graduation-cap text-indigo-500 mr-2"></i>Penilaian Tugas Mahasiswa</h2>
                                    <p class="text-xs text-slate-505">Tinjau hasil lembar jawaban mahasiswa dan berikan skor akademik</p>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse min-w-[700px]">
                                        <thead>
                                            <tr class="border-b border-slate-200 text-xs text-slate-550 uppercase font-bold tracking-wider">
                                                <th class="py-3 px-4">Tugas</th>
                                                <th class="py-3 px-4">Mahasiswa</th>
                                                <th class="py-3 px-4">Waktu Kumpul</th>
                                                <th class="py-3 px-4">Nilai / Status</th>
                                                <th class="py-3 px-4 text-right">Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody id="lms-submissions-table-body" class="divide-y divide-slate-100 text-sm text-slate-700">
                                            <!-- Diisi via JS -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan: Bahan Ajar / Materi -->
                        <div class="space-y-8">
                            <div class="glass-panel rounded-2xl p-6 space-y-6">
                                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider"><i class="fa-solid fa-book text-indigo-500 mr-2"></i>Bahan Ajar Kuliah</h3>
                                        <p class="text-[10px] text-slate-500 mt-0.5">Materi resmi diunggah oleh dosen</p>
                                    </div>
                                    <button onclick="showUploadMaterialForm()" id="btn-upload-material-trigger" class="hidden px-2.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 text-indigo-700 text-[10px] font-bold rounded-lg transition-all">
                                        <i class="fa-solid fa-cloud-arrow-up mr-1"></i>Unggah
                                    </button>
                                </div>

                                <!-- Form Upload Materi (Dosen) -->
                                <div id="form-upload-material-container" class="hidden p-4 bg-slate-55 rounded-xl border border-slate-200 space-y-3 shadow-inner">
                                    <h4 class="text-[11px] font-bold text-slate-750 uppercase"><i class="fa-solid fa-file-pdf text-red-600 mr-1"></i>Unggah Materi Baru</h4>
                                    <form onsubmit="handleUploadMaterial(event)" class="space-y-3">
                                        <div>
                                            <label class="block text-[9px] font-bold text-slate-500 mb-1">Mata Kuliah</label>
                                            <select id="material-class-id" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:border-indigo-500 shadow-sm">
                                                <!-- Diisi dinamis -->
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-[9px] font-bold text-slate-500 mb-1">Judul Materi</label>
                                            <input type="text" id="material-title" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:border-indigo-500 shadow-sm" placeholder="Contoh: Pertemuan 4: Konsep Jaringan" required>
                                        </div>
                                        <div>
                                            <label class="block text-[9px] font-bold text-slate-500 mb-1">Deskripsi Singkat</label>
                                            <textarea id="material-description" rows="2" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:border-indigo-500 shadow-sm" placeholder="Deskripsi materi..." required></textarea>
                                        </div>
                                        <div>
                                            <label class="block text-[9px] font-bold text-slate-500 mb-1">Nama File Dokumen</label>
                                            <input type="text" id="material-file-name" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:border-indigo-500 shadow-sm" placeholder="Contoh: Slide_Pertemuan_04.pdf" required>
                                        </div>
                                        <div class="flex justify-end space-x-2">
                                            <button type="button" onclick="hideUploadMaterialForm()" class="px-2.5 py-1.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-lg">Batal</button>
                                            <button type="submit" class="px-3 py-1.5 bg-indigo-500 text-white text-[10px] font-bold rounded-lg shadow-sm">Simpan</button>
                                        </div>
                                    </form>
                                </div>

                                <!-- List Materi Render -->
                                <div id="lms-materials-list" class="space-y-3">
                                    <!-- Diisi via JS -->
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ==================== PORTAL UJIAN ONLINE ==================== -->
                <section id="portal-ujian" class="hidden space-y-8">
                    <div class="indigo-gradient rounded-2xl p-6 sm:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 border border-indigo-500/10 shadow-xl">
                        <div class="space-y-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-200 border border-indigo-400/20">
                                Proctoring Shield Active
                            </span>
                            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">Sistem Ujian Online Terproteksi</h1>
                            <p class="text-indigo-100 text-sm sm:text-base max-w-2xl font-medium">
                                Melaksanakan ujian secara adil. Sistem memantau integritas pengerjaan melalui algoritma pendeteksi fokus browser (*Proctoring Shield*).
                            </p>
                        </div>
                    </div>

                    <!-- SCREEN UJIAN AKTIF (Sedang Mengerjakan Ujian) -->
                    <div id="lms-active-exam-container" class="hidden glass-panel rounded-2xl p-6 md:p-8 space-y-6 max-w-4xl mx-auto border-2 border-red-200 shadow-xl relative overflow-hidden">
                        <div class="absolute top-0 inset-x-0 h-1.5 bg-red-650 animate-pulse"></div>

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                            <div class="space-y-1">
                                <span class="inline-flex items-center text-[10px] font-extrabold text-red-655 bg-red-50 px-2.5 py-1 rounded border border-red-100 tracking-wider uppercase animate-pulse">
                                    <span class="h-2.5 w-2.5 rounded-full bg-red-600 mr-1.5 inline-block"></span>Sistem Pengawasan Aktif
                                </span>
                                <h2 id="active-exam-title" class="text-lg font-extrabold text-slate-900">Ujian Tengah Semester</h2>
                            </div>
                            <div class="px-4 py-2 bg-slate-900 text-white font-mono rounded-xl text-center shadow-sm">
                                <span class="text-[9px] text-slate-400 font-bold block uppercase tracking-wider">Sisa Waktu</span>
                                <span id="active-exam-timer" class="text-base font-bold">00:00</span>
                            </div>
                        </div>

                        <!-- Panel Warning Sensor -->
                        <div class="p-4 bg-red-50 rounded-xl border border-red-200 flex items-start space-x-3 text-xs text-red-808">
                            <span class="text-red-600 text-base mt-0.5"><i class="fa-solid fa-circle-exclamation"></i></span>
                            <div class="space-y-1 font-medium">
                                <span class="font-extrabold block uppercase tracking-wide">Peringatan Keras Pengawasan:</span>
                                <p class="leading-relaxed">
                                    Dilarang keras meninggalkan jendela ujian, membuka tab baru, atau melakukan aksi copy-paste. Tindakan Anda dipantau secara real-time. Keluar tab >= 3 kali atau copy-paste >= 1 kali akan langsung memicu alarm pelanggaran akademik dan dikirim ke dasbor dosen!
                                </p>
                                <div class="flex space-x-4 pt-1 font-bold text-[10px] text-red-750">
                                    <div>🚫 Keluar Tab: <span id="active-proctoring-switches" class="font-mono bg-white px-1.5 py-0.5 rounded border border-red-200 text-red-600 font-bold">0</span> Kali</div>
                                    <div>🚫 Copy-Paste: <span id="active-proctoring-copypaste" class="font-mono bg-white px-1.5 py-0.5 rounded border border-red-200 text-red-600 font-bold">0</span> Kali</div>
                                </div>
                            </div>
                        </div>

                        <!-- Lembar Soal -->
                        <form id="active-exam-form" onsubmit="handleActiveExamSubmit(event)" class="space-y-8 pt-4">
                            <input type="hidden" id="active-exam-id">
                            
                            <!-- Container Soal Diisi via JS -->
                            <div id="active-exam-questions-container" class="space-y-6 divide-y divide-slate-100">
                                <!-- Diisi dinamis -->
                            </div>

                            <button type="submit" class="w-full bg-red-600 hover:bg-red-500 text-white font-bold py-4 rounded-xl shadow-lg shadow-red-650/20 hover:shadow-red-650/30 transition-all flex items-center justify-center space-x-2">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>Kumpulkan Jawaban Ujian</span>
                            </button>
                        </form>
                    </div>

                    <!-- SCREEN MONITORING & DAFTAR UJIAN (Default View) -->
                    <div id="lms-default-exam-container" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Kolom Kiri: Sesi Ujian Aktif -->
                        <div class="lg:col-span-2 space-y-8">
                            <div class="glass-panel rounded-2xl p-6 space-y-6">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                                    <div>
                                        <h2 class="text-lg font-extrabold text-slate-900"><i class="fa-solid fa-file-invoice text-indigo-500 mr-2"></i>Daftar Sesi Ujian</h2>
                                        <p class="text-xs text-slate-505">Pilih sesi ujian Anda atau pantau hasil pengerjaan</p>
                                    </div>
                                    <button onclick="showCreateExamForm()" id="btn-create-exam-trigger" class="hidden px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white text-xs font-bold rounded-xl transition-all shadow-md">
                                        <i class="fa-solid fa-plus mr-1.5"></i>Buat Sesi Ujian Baru
                                    </button>
                                </div>

                                <!-- Form Pembuatan Ujian (Dosen) -->
                                <div id="form-create-exam-container" class="hidden p-5 bg-slate-55 rounded-xl border border-slate-200 space-y-4 shadow-inner">
                                    <h3 class="text-xs font-bold text-slate-750 uppercase tracking-wider"><i class="fa-solid fa-file-circle-plus text-indigo-500 mr-1.5"></i>Form Pembuatan Ujian Baru</h3>
                                    <form onsubmit="handleCreateExam(event)" class="space-y-4">
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                            <div>
                                                <label class="block text-[10px] font-bold text-slate-500 mb-1">Mata Kuliah</label>
                                                <select id="exam-class-id" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-indigo-500 shadow-sm">
                                                    <!-- Diisi dinamis -->
                                                </select>
                                            </div>
                                            <div class="sm:col-span-2">
                                                <label class="block text-[10px] font-bold text-slate-500 mb-1">Judul Ujian</label>
                                                <input type="text" id="exam-title" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-indigo-500 shadow-sm" placeholder="Contoh: Ujian Tengah Semester (UTS) Kecerdasan Buatan" required>
                                            </div>
                                            <div class="sm:col-span-2">
                                                <label class="block text-[10px] font-bold text-slate-500 mb-1">Deskripsi Ujian</label>
                                                <input type="text" id="exam-description" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-indigo-500 shadow-sm" placeholder="Contoh: Sifat ujian tertutup. Dilarang kerja sama." required>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold text-slate-500 mb-1">Durasi Pengerjaan (Menit)</label>
                                                <input type="number" id="exam-duration" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-indigo-500 shadow-sm" value="30" min="5" required>
                                            </div>
                                        </div>

                                        <!-- Question Builder -->
                                        <div class="p-4 bg-white rounded-lg border border-slate-200 space-y-4">
                                            <span class="block text-[10px] font-bold text-indigo-600 uppercase tracking-widest border-b pb-1.5">Pembuat Soal Ujian (Simulasi PG)</span>
                                            <div class="space-y-4">
                                                <div class="space-y-2">
                                                    <label class="block text-[10px] font-bold text-slate-600">Soal Pilihan Ganda 1:</label>
                                                    <input type="text" id="exam-q1-text" class="w-full bg-slate-50 border border-slate-200 rounded px-2.5 py-1.5 text-xs focus:outline-none" value="Manakah dari algoritma berikut yang tergolong sebagai Informed Search (Pencarian Terpimpin)?" required>
                                                    <div class="grid grid-cols-2 gap-2 text-xs">
                                                        <input type="text" id="exam-q1-a" class="border rounded px-2 py-1" value="Breadth-First Search (BFS)" required>
                                                        <input type="text" id="exam-q1-b" class="border rounded px-2 py-1" value="Depth-First Search (DFS)" required>
                                                        <input type="text" id="exam-q1-c" class="border rounded px-2 py-1" value="Algoritma A*" required>
                                                        <input type="text" id="exam-q1-d" class="border rounded px-2 py-1" value="Uniform Cost Search (UCS)" required>
                                                    </div>
                                                    <div class="flex items-center space-x-2 text-xs">
                                                        <span class="font-bold text-slate-500">Kunci Jawaban:</span>
                                                        <select id="exam-q1-correct" class="border rounded px-2 py-1 font-bold text-indigo-650">
                                                            <option value="A">A</option><option value="B">B</option><option value="C" selected>C</option><option value="D">D</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="space-y-2 pt-2 border-t border-slate-100">
                                                    <label class="block text-[10px] font-bold text-slate-600">Soal Pilihan Ganda 2:</label>
                                                    <input type="text" id="exam-q2-text" class="w-full bg-slate-50 border border-slate-200 rounded px-2.5 py-1.5 text-xs focus:outline-none" value="Apa karakteristik utama dari fungsi heuristik yang admissible (layak)?" required>
                                                    <div class="grid grid-cols-2 gap-2 text-xs">
                                                        <input type="text" id="exam-q2-a" class="border rounded px-2 py-1" value="Selalu overestimate biaya sebenarnya" required>
                                                        <input type="text" id="exam-q2-b" class="border rounded px-2 py-1" value="Tidak pernah overestimate biaya sebenarnya (underestimate)" required>
                                                        <input type="text" id="exam-q2-c" class="border rounded px-2 py-1" value="Selalu bernilai nol" required>
                                                        <input type="text" id="exam-q2-d" class="border rounded px-2 py-1" value="Menghitung biaya eksak" required>
                                                    </div>
                                                    <div class="flex items-center space-x-2 text-xs">
                                                        <span class="font-bold text-slate-500">Kunci Jawaban:</span>
                                                        <select id="exam-q2-correct" class="border rounded px-2 py-1 font-bold text-indigo-655">
                                                            <option value="A">A</option><option value="B" selected>B</option><option value="C">C</option><option value="D">D</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex justify-end space-x-2">
                                            <button type="button" onclick="hideCreateExamForm()" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-lg transition-all">Batal</button>
                                            <button type="submit" class="px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white text-xs font-bold rounded-lg transition-all shadow-md font-bold">Terbitkan Ujian</button>
                                        </div>
                                    </form>
                                </div>

                                <!-- List Ujian Render -->
                                <div id="lms-exams-list" class="space-y-4">
                                    <!-- Diisi via JS -->
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan: Log Hasil Pengawasan (Lecturer View) -->
                        <div class="space-y-8">
                            <div class="glass-panel rounded-2xl p-6 space-y-4">
                                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest"><i class="fa-solid fa-shield-halved text-indigo-500 mr-2"></i>Laporan Proctoring</h3>
                                <p class="text-xs text-slate-500 leading-relaxed">
                                    Dosen dapat memantau aktivitas mencurigakan mahasiswa saat ujian, seperti membuka tab browser lain atau menyalin teks eksternal.
                                </p>
                                <div id="lms-proctoring-panel-list" class="space-y-3.5">
                                    <!-- Diisi via JS -->
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ==================== PORTAL REKAPITULASI NILAI ==================== -->
                <section id="portal-nilai" class="hidden space-y-8">
                    <div class="indigo-gradient rounded-2xl p-6 sm:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 border border-indigo-500/10 shadow-xl">
                        <div class="space-y-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-200 border border-indigo-400/20">
                                Transparansi Nilai Akademik
                            </span>
                            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">Transkrip & Evaluasi Nilai Akhir</h1>
                            <p class="text-indigo-100 text-sm sm:text-base max-w-2xl font-medium">
                                Rekapitulasi nilai akademik mahasiswa terhitung secara dinamis dari persentase kehadiran sah, rata-rata tugas, dan nilai ujian.
                            </p>
                        </div>
                    </div>

                    <div class="glass-panel rounded-2xl p-6 md:p-8 space-y-6">
                        <div class="border-b border-slate-100 pb-4">
                            <h2 class="text-lg font-extrabold text-slate-900"><i class="fa-solid fa-graduation-cap text-indigo-500 mr-2"></i>Daftar Penilaian Akademik</h2>
                            <p class="text-xs text-slate-500">Evaluasi capaian belajar mahasiswa berdasarkan bobot nilai (20% Presensi, 30% Tugas, 50% Ujian)</p>
                        </div>

                        <!-- Tabel Nilai Master (Dosen & Mahasiswa) -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-[800px]">
                                <thead>
                                    <tr class="border-b border-slate-200 text-xs text-slate-550 uppercase font-bold tracking-wider">
                                        <th class="py-3 px-4">Mahasiswa</th>
                                        <th class="py-3 px-4">Kode / Sesi Kuliah</th>
                                        <th class="py-3 px-4">Persentase Kehadiran</th>
                                        <th class="py-3 px-4">Skor Presensi (20%)</th>
                                        <th class="py-3 px-4">Skor Tugas (30%)</th>
                                        <th class="py-3 px-4">Skor Ujian (50%)</th>
                                        <th class="py-3 px-4 font-extrabold text-indigo-600">Skor Akhir</th>
                                        <th class="py-3 px-4 text-center font-extrabold text-indigo-700">Grade</th>
                                    </tr>
                                </thead>
                                <tbody id="lms-grades-table-body" class="divide-y divide-slate-100 text-sm text-slate-700">
                                    <!-- Diisi via JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

            </main>

            <!-- Footer -->
            <footer class="border-t border-slate-200 bg-white py-6 text-center text-xs text-slate-500">
                <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <p>&copy; 2026 Portal Akademik. Dikembangkan oleh Echa.</p>
                    <div class="flex space-x-4">
                        <span class="text-slate-450 font-semibold">Sistem Informasi Akademik Aman</span>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Modal Detail Forensik Fraud (CRITICAL UNTUK MEMAMERKAN BUKTI) -->
        <div id="forensic-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white w-full max-w-lg rounded-2xl border border-slate-200 p-6 space-y-6 shadow-2xl relative">
                <button onclick="closeForensicModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition-colors text-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>

                <div class="flex items-center space-x-3 border-b border-slate-150 pb-3">
                    <div class="h-10 w-10 rounded-lg bg-red-100 border border-red-200 flex items-center justify-center text-red-600 text-lg shadow-sm">
                        <i class="fa-solid fa-magnifying-glass-location"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base">Forensik Detektor Anomali</h3>
                        <p class="text-xs text-slate-500">Analisis bukti anomali kecurangan yang dikumpulkan sistem</p>
                    </div>
                </div>

                <!-- Detail Bukti -->
                <div id="modal-evidence-content" class="space-y-4 text-xs text-slate-700">
                    <!-- Diisi via JavaScript -->
                </div>

                <!-- Tombol Resolusi di Modal -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-slate-100">
                    <button id="modal-btn-reject" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-200 text-xs text-slate-600 font-bold transition-all">
                        Sahkan Kehadiran (Valid)
                    </button>
                    <button id="modal-btn-approve" class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-xs text-white font-bold transition-all shadow-lg shadow-red-600/20">
                        Batalkan Kehadiran (Tidak Valid)
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- ==================== FRONTEND JAVASCRIPT LOGIC ==================== -->
    <script>
        // Global variables
        const API_BASE = '';
        let currentTab = 'mahasiswa';
        let fraudChartInstance = null;
        let globalDashboardData = null;
        let currentUser = null;

        // LMS Proctoring Variables
        let activeExamTimerId = null;
        let activeExamTabSwitches = 0;
        let activeExamCopyPasteCount = 0;

        // Inisialisasi halaman & Pemeriksaan Autentikasi
        window.addEventListener('DOMContentLoaded', () => {
            checkAuth();
            generateDeviceFingerprint();
            loadClasses();
            
            // Auto update setiap 10 detik untuk dashboard dosen jika tab aktif
            setInterval(() => {
                if (currentUser && (currentUser.role === 'dosen' || currentUser.role === 'admin') && currentTab === 'dosen') {
                    fetchDashboardData();
                }
            }, 10000);
        });

        // 1. Logika Pemeriksaan Autentikasi
        function checkAuth() {
            const userJson = localStorage.getItem('secure_attendance_user');
            const loginScreen = document.getElementById('login-screen');
            const mainAppScreen = document.getElementById('main-app-screen');

            if (!userJson) {
                // Sembunyikan aplikasi utama, tampilkan layar login
                loginScreen.classList.remove('hidden');
                mainAppScreen.classList.add('hidden');
                currentUser = null;
            } else {
                // Tampilkan aplikasi utama, sembunyikan login
                loginScreen.classList.add('hidden');
                mainAppScreen.classList.remove('hidden');
                currentUser = JSON.parse(userJson);

                // Isi profil pengguna di sidebar
                document.getElementById('header-user-name').innerText = currentUser.name;
                document.getElementById('header-user-role').innerText = currentUser.role;

                // Terapkan Hak Akses (Role-Based Access Control)
                applyRoleAccess();
            }
        }

        // 2. Logika Penerapan Hak Akses (Role-Based Tabs Visibility)
        function applyRoleAccess() {
            const tabMahasiswa = document.getElementById('tab-mahasiswa');
            const tabDosen = document.getElementById('tab-dosen');
            const tabAdmin = document.getElementById('tab-admin');

            const btnCreateAssignment = document.getElementById('btn-create-assignment-trigger');
            const btnUploadMaterial = document.getElementById('btn-upload-material-trigger');
            const btnCreateExam = document.getElementById('btn-create-exam-trigger');
            const panelGrading = document.getElementById('panel-grading-container');
            const proctoringPanel = document.getElementById('lms-proctoring-panel-list');

            if (currentUser.role === 'mahasiswa') {
                // Mahasiswa hanya bisa melihat tab mahasiswa di sidebar
                if (tabMahasiswa) tabMahasiswa.classList.remove('hidden');
                if (tabDosen) tabDosen.classList.add('hidden');
                if (tabAdmin) tabAdmin.classList.add('hidden');

                if (btnCreateAssignment) btnCreateAssignment.classList.add('hidden');
                if (btnUploadMaterial) btnUploadMaterial.classList.add('hidden');
                if (btnCreateExam) btnCreateExam.classList.add('hidden');
                if (panelGrading) panelGrading.classList.add('hidden');
                if (proctoringPanel && proctoringPanel.parentElement) proctoringPanel.parentElement.classList.add('hidden');
                
                // Kunci form presensi dengan identitas mahasiswa yang login
                document.getElementById('display-student-name').value = `${currentUser.npm} - ${currentUser.name}`;
                document.getElementById('select-student').value = currentUser.npm;

                switchTab('mahasiswa');
            } else if (currentUser.role === 'dosen' || currentUser.role === 'admin') {
                // Dosen/Admin melihat tab monitoring dan admin, sembunyikan portal presensi
                if (tabMahasiswa) tabMahasiswa.classList.add('hidden');
                if (tabDosen) tabDosen.classList.remove('hidden');
                if (tabAdmin) tabAdmin.classList.remove('hidden');

                if (btnCreateAssignment) btnCreateAssignment.classList.remove('hidden');
                if (btnUploadMaterial) btnUploadMaterial.classList.remove('hidden');
                if (btnCreateExam) btnCreateExam.classList.remove('hidden');
                if (panelGrading) panelGrading.classList.remove('hidden');
                if (proctoringPanel && proctoringPanel.parentElement) proctoringPanel.parentElement.classList.remove('hidden');

                switchTab('dosen');
                fetchDashboardData();
            }
        }

        // 3. Eksekusi Proses Login
        async function handleLogin(e) {
            e.preventDefault();
            const npm = document.getElementById('login-npm').value;
            const password = document.getElementById('login-password').value;
            const errorDiv = document.getElementById('login-error');

            errorDiv.classList.add('hidden');

            try {
                const res = await fetch(`${API_BASE}/api/login`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ npm, password })
                });
                const result = await res.json();

                if (res.ok && result.success) {
                    // Simpan sesi ke localStorage
                    localStorage.setItem('secure_attendance_user', JSON.stringify(result.user));
                    
                    // Bersihkan form
                    document.getElementById('login-npm').value = '';
                    document.getElementById('login-password').value = '';
                    
                    // Jalankan auth checker
                    checkAuth();
                } else {
                    errorDiv.innerText = result.message || 'Gagal masuk ke dalam sistem';
                    errorDiv.classList.remove('hidden');
                }
            } catch (err) {
                console.error(err);
                errorDiv.innerText = 'Koneksi gagal. Pastikan server Laravel aktif';
                errorDiv.classList.remove('hidden');
            }
        }

        // 4. Eksekusi Logout (Keluar Sesi)
        function handleLogout() {
            localStorage.removeItem('secure_attendance_user');
            checkAuth();
        }

        // Tab Switcher (Navigasi Vertikal Sidebar)
        function switchTab(tabName) {
            // Proteksi visual: jika mahasiswa mencoba masuk ke portal dosen atau admin
            if (currentUser && currentUser.role === 'mahasiswa' && ['dosen', 'admin'].includes(tabName)) {
                alert("Deteksi Pelanggaran Otoritas: Anda tidak memiliki hak akses ke portal ini!");
                handleLogout();
                return;
            }

            currentTab = tabName;
            
            const portalMahasiswa = document.getElementById('portal-mahasiswa');
            const portalDosen = document.getElementById('portal-dosen');
            const portalAdmin = document.getElementById('portal-admin');
            const portalMateri = document.getElementById('portal-materi');
            const portalUjian = document.getElementById('portal-ujian');
            const portalNilai = document.getElementById('portal-nilai');

            if (portalMahasiswa) portalMahasiswa.classList.add('hidden');
            if (portalDosen) portalDosen.classList.add('hidden');
            if (portalAdmin) portalAdmin.classList.add('hidden');
            if (portalMateri) portalMateri.classList.add('hidden');
            if (portalUjian) portalUjian.classList.add('hidden');
            if (portalNilai) portalNilai.classList.add('hidden');
            
            const activeSection = document.getElementById(`portal-${tabName}`);
            if (activeSection) activeSection.classList.remove('hidden');

            // Reset dan set kelas aktif secara parsial agar mempertahankan kelas 'hidden'
            const tabs = ['mahasiswa', 'dosen', 'admin', 'materi', 'ujian', 'nilai'];
            tabs.forEach(t => {
                const btn = document.getElementById(`tab-${t}`);
                if (btn) {
                    if (t === tabName) {
                        btn.classList.add('font-bold', 'text-white', 'bg-indigo-500', 'shadow-md', 'shadow-indigo-500/10');
                        btn.classList.remove('font-semibold', 'text-slate-600', 'hover:bg-slate-50', 'hover:text-slate-900');
                    } else {
                        btn.classList.remove('font-bold', 'text-white', 'bg-indigo-500', 'shadow-md', 'shadow-indigo-500/10');
                        btn.classList.add('font-semibold', 'text-slate-600', 'hover:bg-slate-50', 'hover:text-slate-900');
                    }
                }
            });

            if ((tabName === 'dosen' || tabName === 'admin') && currentUser) {
                fetchDashboardData();
            }

            // Fetch data spesifik modul LMS saat berpindah tab
            if (currentUser) {
                if (tabName === 'materi') {
                    fetchMaterials();
                    fetchAssignments();
                    if (currentUser.role !== 'mahasiswa') {
                        fetchSubmissions();
                    }
                } else if (tabName === 'ujian') {
                    fetchExams();
                    if (currentUser.role !== 'mahasiswa') {
                        fetchExamAttempts();
                    }
                } else if (tabName === 'nilai') {
                    fetchGrades();
                }
            }
        }

        // Generate Sidik Jari Browser (Fingerprint) secara real-time
        async function generateDeviceFingerprint() {
            const sensorHashElem = document.getElementById('sensor-hash');
            const sensorUaElem = document.getElementById('sensor-ua');
            const userAgent = navigator.userAgent;
            sensorUaElem.innerText = userAgent;

            try {
                const components = [
                    userAgent,
                    navigator.language,
                    screen.colorDepth,
                    `${screen.width}x${screen.height}`,
                    new Date().getTimezoneOffset(),
                    navigator.platform
                ];
                
                const msgBuffer = new TextEncoder().encode(components.join('||'));
                const hashBuffer = await crypto.subtle.digest('SHA-256', msgBuffer);
                const hashArray = Array.from(new Uint8Array(hashBuffer));
                const hashHex = hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
                
                sensorHashElem.innerText = hashHex;
                sensorHashElem.setAttribute('data-hash', hashHex);
            } catch (err) {
                sensorHashElem.innerText = 'fingerprint_error_fallback_2026';
                sensorHashElem.setAttribute('data-hash', 'fingerprint_error_fallback_2026');
            }
        }

        // Ambil lokasi asli browser
        function useRealLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    document.getElementById('gps-lat').value = position.coords.latitude.toFixed(6);
                    document.getElementById('gps-lon').value = position.coords.longitude.toFixed(6);
                }, (error) => {
                    alert("Gagal mengambil lokasi: " + error.message);
                });
            } else {
                alert("Geolocation tidak didukung browser.");
            }
        }

        // Set lokasi simulator cepat
        function setPresetLocation(lat, lon, scenario) {
            document.getElementById('gps-lat').value = lat.toFixed(6);
            document.getElementById('gps-lon').value = lon.toFixed(6);

            // Simulasi IP dan Fingerprint agar skenario kecurangan terlihat riil
            const sensorHashElem = document.getElementById('sensor-hash');
            const sensorIpElem = document.getElementById('sensor-ip');
            
            if (scenario === 'KAMPUS') {
                sensorIpElem.innerText = '182.253.12.34';
                generateDeviceFingerprint(); // Kembalikan ke fingerprint asli
            } else if (scenario === 'RUMAH') {
                sensorIpElem.innerText = '112.199.45.12';
                generateDeviceFingerprint();
            } else if (scenario === 'JOKI') {
                sensorIpElem.innerText = '36.85.120.44';
                sensorHashElem.innerText = 'joki_medan_device_hash_xyz888';
                sensorHashElem.setAttribute('data-hash', 'joki_medan_device_hash_xyz888');
            }
        }

        // Ambil data dropdown kelas
        async function loadClasses() {
            try {
                const res = await fetch(`${API_BASE}/api/classes`);
                const classes = await res.json();
                const select = document.getElementById('select-class');
                select.innerHTML = classes.map(c => `<option value="${c.id}">${c.subject_code} - ${c.class_name}</option>`).join('');
            } catch (err) {
                console.error("Gagal load kelas:", err);
            }
        }

        // Ambil data dashboard utama dari Backend Laravel
        async function fetchDashboardData() {
            try {
                const res = await fetch(`${API_BASE}/api/dashboard-data`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-User-NPM': currentUser ? currentUser.npm : '',
                        'X-User-Role': currentUser ? currentUser.role : ''
                    }
                });
                const data = await res.json();
                globalDashboardData = data;

                // Update metrik
                document.getElementById('stat-total-students').innerText = data.stats.total_students;
                document.getElementById('stat-total-attendance').innerText = data.stats.total_attendance;
                document.getElementById('stat-total-alerts').innerText = data.stats.total_alerts;

                // Render Tables
                renderAlertsTable(data.active_alerts);
                renderRecentLogsTable(data.recent_logs);

                // Render Chart
                renderChart(data.stats.fraud_by_type);

            } catch (err) {
                console.error("Gagal mengambil data dashboard:", err);
            }
        }

        // Kirim Presensi dari Portal Mahasiswa
        async function submitAttendance(e) {
            e.preventDefault();
            
            const npm = document.getElementById('select-student').value;
            const class_id = parseInt(document.getElementById('select-class').value);
            const latitude = parseFloat(document.getElementById('gps-lat').value);
            const longitude = parseFloat(document.getElementById('gps-lon').value);
            
            const ip_address = document.getElementById('sensor-ip').innerText;
            const user_agent = document.getElementById('sensor-ua').innerText;
            const device_fingerprint = document.getElementById('sensor-hash').getAttribute('data-hash');

            const payload = { npm, class_id, latitude, longitude, ip_address, user_agent, device_fingerprint };

            const statusBox = document.getElementById('status-display-box');
            
            // Set loading state
            statusBox.innerHTML = `
                <div class="h-14 w-14 rounded-full bg-indigo-500/10 flex items-center justify-center text-indigo-600 text-2xl animate-spin border-2 border-dashed border-indigo-500">
                </div>
                <div class="space-y-1">
                    <h3 class="font-bold text-slate-800 text-base">Mengenkripsi & Menganalisis...</h3>
                    <p class="text-xs text-slate-500">Mengamankan koneksi dan mengecek sensor keamanan</p>
                </div>
            `;

            try {
                const res = await fetch(`${API_BASE}/api/attend`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const result = await res.json();

                // Delay sedikit agar animasi loading terlihat dramatis dan keren
                setTimeout(() => {
                    if (result.fraud_flagged) {
                        // Tampilan terdeteksi anomali
                        statusBox.innerHTML = `
                            <div class="h-14 w-14 rounded-full bg-red-100 border border-red-200 flex items-center justify-center text-red-600 text-2xl neon-glow-red animate-pulse">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                            <div class="space-y-1.5">
                                <h3 class="font-extrabold text-red-600 text-base">Peringatan Keamanan</h3>
                                <p class="text-xs text-red-800 font-bold bg-red-50 border border-red-200 px-2.5 py-1.5 rounded-lg shadow-sm">${result.message}</p>
                                <p class="text-[10px] text-slate-500 mt-1 font-semibold">Jenis Deteksi: <span class="text-red-600 font-mono font-bold">${result.details.fraud_type}</span></p>
                            </div>
                        `;
                    } else {
                        // Tampilan sukses
                        statusBox.innerHTML = `
                            <div class="h-14 w-14 rounded-full bg-green-100 border border-green-200 flex items-center justify-center text-green-600 text-2xl shadow-sm">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <div class="space-y-1">
                                <h3 class="font-extrabold text-green-600 text-base">Presensi Sukses</h3>
                                <p class="text-xs text-slate-600 font-medium">${result.message}</p>
                                <p class="text-[10px] text-slate-400 font-mono font-semibold">Jarak ke Kelas: ${result.details.distance} meter</p>
                            </div>
                        `;
                    }
                    
                    // Segarkan dashboard dosen secara background
                    fetchDashboardData();
                }, 1000);

            } catch (err) {
                console.error(err);
                statusBox.innerHTML = `
                    <div class="h-14 w-14 rounded-full bg-red-100 flex items-center justify-center text-red-600 text-2xl">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </div>
                    <div class="space-y-1">
                        <h3 class="font-bold text-slate-800 text-base">Koneksi Gagal</h3>
                        <p class="text-xs text-slate-500">Pastikan server backend Laravel berjalan di port 7978</p>
                    </div>
                `;
            }
        }

        // Render Grafik Distribusi Fraud (Light Mode)
        function renderChart(fraudData) {
            const ctx = document.getElementById('fraudChart').getContext('2d');
            
            // Destory chart sebelumnya agar tidak tumpang tindih
            if (fraudChartInstance) {
                fraudChartInstance.destroy();
            }

            const dataValues = [
                fraudData.GEOLOCATION_MISMATCH || 0,
                fraudData.VELOCITY_ANOMALY || 0,
                fraudData.DEVICE_SHARING || 0
            ];

            // Cek jika tidak ada fraud sama sekali, buat visualisasi kosong yang indah
            const totalData = dataValues.reduce((a, b) => a + b, 0);
            const hasData = totalData > 0;

            fraudChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Geofencing Mismatch', 'Velocity Anomaly', 'Perangkat Bersama'],
                    datasets: [{
                        label: 'Jumlah Kasus',
                        data: hasData ? dataValues : [0, 0, 0],
                        backgroundColor: [
                            'rgba(239, 68, 68, 0.8)',  // Red for Geofence
                            'rgba(245, 158, 11, 0.8)', // Amber for Velocity
                            'rgba(168, 85, 247, 0.8)'  // Purple for Device Sharing
                        ],
                        borderColor: [
                            '#ef4444',
                            '#d97706',
                            '#8b5cf6'
                        ],
                        borderWidth: 1.5,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleColor: '#fff',
                            bodyColor: '#e2e8f0',
                            borderColor: '#475569',
                            borderWidth: 1,
                            padding: 10
                        }
                    },
                    scales: {
                        y: {
                            grid: { color: '#f1f5f9' },
                            ticks: { color: '#64748b', stepSize: 1, beginAtZero: true, font: { weight: 'bold' } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#64748b', font: { weight: 'bold' } }
                        }
                    }
                }
            });
        }

        // Render Tabel Alarm Fraud (Portal Dosen)
        function renderAlertsTable(alerts) {
            const tbody = document.getElementById('fraud-alerts-table-body');
            if (alerts.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400 font-semibold">
                            <i class="fa-solid fa-circle-check text-green-500 text-xl block mb-2"></i>
                            Tidak ada alarm kecurangan aktif. Semua presensi aman.
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = alerts.map(a => {
                // Formatting format tipe fraud agar human readable
                let typeBadge = '';
                if (a.fraud_type === 'GEOLOCATION_MISMATCH') {
                    typeBadge = `<span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 border border-red-200 text-red-700 shadow-sm"><i class="fa-solid fa-location-crosshairs mr-1"></i>Geofencing</span>`;
                } else if (a.fraud_type === 'VELOCITY_ANOMALY') {
                    typeBadge = `<span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 border border-amber-200 text-amber-800 shadow-sm"><i class="fa-solid fa-gauge-high mr-1"></i>Velocity</span>`;
                } else if (a.fraud_type === 'DEVICE_SHARING') {
                    typeBadge = `<span class="px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 border border-purple-200 text-purple-700 shadow-sm"><i class="fa-solid fa-users mr-1"></i>Perangkat Bersama</span>`;
                } else if (a.fraud_type === 'PROCTORING_SHIELD') {
                    typeBadge = `<span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 border border-red-250 text-red-750 shadow-sm"><i class="fa-solid fa-laptop-code mr-1"></i>Ujian Proctoring</span>`;
                }

                // Score Badge color
                let scoreColor = 'text-amber-600 font-extrabold';
                if (a.anomaly_score >= 0.90) scoreColor = 'text-red-600 font-extrabold';

                // Status review
                let statusBadge = '';
                if (a.status === 'PENDING') {
                    statusBadge = `<span class="inline-flex items-center text-xs text-yellow-600 font-bold"><span class="h-2 w-2 rounded-full bg-yellow-550 mr-1.5 animate-pulse"></span>Pending</span>`;
                } else if (a.status === 'APPROVED') {
                    statusBadge = `<span class="inline-flex items-center text-xs text-red-600 font-bold bg-red-50 px-2 py-1 rounded border border-red-100"><i class="fa-solid fa-circle-xmark mr-1.5"></i>Kehadiran Dibatalkan</span>`;
                } else if (a.status === 'REJECTED') {
                    statusBadge = `<span class="inline-flex items-center text-xs text-green-600 font-bold bg-green-50 px-2 py-1 rounded border border-green-100"><i class="fa-solid fa-circle-check mr-1.5"></i>Sahkan (Valid)</span>`;
                }

                // Format waktu singkat
                const date = new Date(a.created_at || a.timestamp);
                const timeStr = date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

                // Aksi Buttons
                const actionButtons = a.status === 'PENDING' ? `
                    <div class="flex justify-end space-x-2">
                        <button onclick="openForensic('${a.id}')" class="px-3 py-1.5 bg-indigo-500 hover:bg-indigo-600 text-white text-xs font-bold rounded-lg transition-all shadow-md shadow-indigo-500/10">
                            <i class="fa-solid fa-magnifying-glass-location mr-1"></i>Investigasi
                        </button>
                    </div>
                ` : `
                    <div class="text-right text-xs text-slate-400 font-medium italic">Selesai ditinjau</div>
                `;

                return `
                    <tr class="hover:bg-slate-50/80 transition-colors border-b border-slate-100">
                        <td class="py-4.5 px-4 font-bold text-slate-500">${timeStr} WIB</td>
                        <td class="py-4.5 px-4">
                            <div class="font-extrabold text-slate-800">${a.name}</div>
                            <div class="text-xs text-slate-400 font-mono font-semibold">${a.npm}</div>
                        </td>
                        <td class="py-4.5 px-4 font-bold text-slate-700">${a.class_name}</td>
                        <td class="py-4.5 px-4">${typeBadge}</td>
                        <td class="py-4.5 px-4 font-mono text-base ${scoreColor}">${(a.anomaly_score * 100).toFixed(0)}%</td>
                        <td class="py-4.5 px-4">${statusBadge}</td>
                        <td class="py-4.5 px-4">${actionButtons}</td>
                    </tr>
                `;
            }).join('');
        }

        // Render Tabel Log Kehadiran Mentah (Portal Dosen)
        function renderRecentLogsTable(logs) {
            const tbody = document.getElementById('attendance-logs-table-body');
            if (logs.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" class="py-4 text-center text-slate-400">Belum ada aktivitas presensi terlog</td></tr>`;
                return;
            }

            tbody.innerHTML = logs.map(l => {
                const date = new Date(l.created_at || l.timestamp);
                const timeStr = date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

                let statusBadge = '';
                if (l.status === 'PRESENT') {
                    statusBadge = `<span class="text-green-600 font-extrabold bg-green-50 px-2 py-0.5 rounded border border-green-100 shadow-sm">PRESENT</span>`;
                } else if (l.status === 'FRAUD_SUSPECTED') {
                    statusBadge = `<span class="text-red-600 font-extrabold bg-red-50 px-2 py-0.5 rounded border border-red-100 shadow-sm animate-pulse">FRAUD_ALERT</span>`;
                } else if (l.status === 'ABSENT') {
                    statusBadge = `<span class="text-slate-400 font-extrabold bg-slate-100 px-2 py-0.5 rounded border border-slate-200">VOID / ABSENT</span>`;
                }

                // Jarak ke kelas (jika ada)
                const distanceStr = l.distance_meters > 1000 
                    ? `${(l.distance_meters / 1000).toFixed(2)} km` 
                    : `${l.distance_meters.toFixed(1)} m`;

                return `
                    <tr class="border-b border-slate-100 hover:bg-slate-50/50">
                        <td class="py-2.5 px-4 text-slate-400 font-semibold">${timeStr}</td>
                        <td class="py-2.5 px-4 text-slate-500 font-medium">${l.npm}</td>
                        <td class="py-2.5 px-4 text-slate-800 font-bold truncate max-w-[120px]">${l.name}</td>
                        <td class="py-2.5 px-4 text-slate-500 font-semibold">${l.class_name}</td>
                        <td class="py-2.5 px-4 text-slate-400 text-[10px] font-bold">${l.latitude.toFixed(4)}, ${l.longitude.toFixed(4)}</td>
                        <td class="py-2.5 px-4 text-indigo-600 font-extrabold">${distanceStr}</td>
                        <td class="py-2.5 px-4">${statusBadge}</td>
                    </tr>
                `;
            }).join('');
        }

        // Buka Panel Forensik & Tampilkan Detail Bukti (CRITICAL UNTUK DEMO)
        function openForensic(alertId) {
            const alert = globalDashboardData.active_alerts.find(a => a.id == alertId);
            if (!alert) return;

            const modal = document.getElementById('forensic-modal');
            const content = document.getElementById('modal-evidence-content');
            const evidence = JSON.parse(alert.evidence);

            let evidenceHtml = '';

            // Tampilkan bukti spesifik sesuai jenis kecurangan
            if (alert.fraud_type === 'GEOLOCATION_MISMATCH') {
                evidenceHtml = `
                    <div class="p-4 rounded-xl bg-red-50 border border-red-100 space-y-3 shadow-sm">
                        <h4 class="font-extrabold text-red-700 text-sm"><i class="fa-solid fa-map-location-dot mr-1.5"></i>Pelanggaran Koordinat Geofencing</h4>
                        <p class="text-slate-600 text-[11px] leading-relaxed font-medium">
                            Mahasiswa melakukan absensi jauh di luar radius resmi yang ditetapkan dosen untuk ruangan kelas.
                        </p>
                        <div class="grid grid-cols-2 gap-2 text-[11px] font-mono border-t border-red-100 pt-2 font-semibold text-slate-700">
                            <div>
                                <span class="block text-slate-400 font-bold">Koordinat Kelas:</span>
                                <span class="text-slate-800">${evidence.class_coords.lat.toFixed(6)}, ${evidence.class_coords.lon.toFixed(6)}</span>
                            </div>
                            <div>
                                <span class="block text-slate-400 font-bold">Koordinat Mahasiswa:</span>
                                <span class="text-red-600">${evidence.user_coords.lat.toFixed(6)}, ${evidence.user_coords.lon.toFixed(6)}</span>
                            </div>
                            <div class="col-span-2 mt-1 flex justify-between items-center bg-white p-2 rounded border border-slate-150 shadow-inner">
                                <span class="text-slate-550">Jarak Terdeteksi:</span>
                                <span class="text-red-600 font-extrabold text-sm">${(evidence.distance_meters).toLocaleString('id-ID')} Meter</span>
                            </div>
                            <div class="col-span-2 flex justify-between items-center bg-white p-2 rounded border border-slate-150 shadow-inner">
                                <span class="text-slate-550">Radius Diizinkan:</span>
                                <span class="text-green-600 font-extrabold">${evidence.allowed_radius_meters} Meter</span>
                            </div>
                        </div>
                    </div>
                `;
            } else if (alert.fraud_type === 'VELOCITY_ANOMALY') {
                evidenceHtml = `
                    <div class="p-4 rounded-xl bg-amber-50 border border-amber-100 space-y-3 shadow-sm">
                        <h4 class="font-extrabold text-amber-800 text-sm"><i class="fa-solid fa-gauge-high mr-1.5"></i>Anomali Kecepatan Perpindahan (Velocity)</h4>
                        <p class="text-slate-600 text-[11px] leading-relaxed font-medium">
                            Mahasiswa terdeteksi melakukan perpindahan lokasi fisik dengan kecepatan di luar batas logika manusia dalam rentang waktu singkat. Kemungkinan besar akun sedang di-joki.
                        </p>
                        
                        <div class="space-y-2 text-[11px] border-t border-amber-100 pt-2 font-mono font-semibold text-slate-700">
                            <div class="p-2 bg-white rounded border border-slate-150 shadow-sm">
                                <span class="block text-slate-400 uppercase text-[9px] font-extrabold">1. Log Pertama (Normal):</span>
                                <div class="flex justify-between mt-0.5">
                                    <span class="text-slate-700 font-bold">${evidence.previous_log.class_name}</span>
                                    <span class="text-slate-800">${new Date(evidence.previous_log.timestamp).toLocaleTimeString('id-ID')} WIB</span>
                                </div>
                                <span class="text-slate-400 text-[10px] block">${evidence.previous_log.coords.lat.toFixed(4)}, ${evidence.previous_log.coords.lon.toFixed(4)}</span>
                            </div>
                            
                            <div class="p-2 bg-red-50 rounded border border-red-100">
                                <span class="block text-red-500 uppercase text-[9px] font-extrabold">2. Log Kedua (Mencurigakan):</span>
                                <div class="flex justify-between mt-0.5">
                                    <span class="text-red-700 font-bold">${evidence.current_log.class_name}</span>
                                    <span class="text-red-600">${new Date(evidence.current_log.timestamp).toLocaleTimeString('id-ID')} WIB</span>
                                </div>
                                <span class="text-red-500 text-[10px] block">${evidence.current_log.coords.lat.toFixed(4)}, ${evidence.current_log.coords.lon.toFixed(4)}</span>
                            </div>

                            <div class="grid grid-cols-2 gap-2 pt-1 text-[11px]">
                                <div class="bg-white p-2 rounded border border-slate-150 shadow-sm">
                                    <span class="text-slate-400 block">Jarak Kota:</span>
                                    <span class="text-slate-800 font-extrabold">${evidence.distance_kilometers} Km</span>
                                </div>
                                <div class="bg-white p-2 rounded border border-slate-150 shadow-sm">
                                    <span class="text-slate-400 block">Selisih Waktu:</span>
                                    <span class="text-slate-800 font-extrabold">${evidence.time_difference_minutes} Menit</span>
                                </div>
                                <div class="col-span-2 bg-red-100 p-2 rounded border border-red-250 flex justify-between items-center shadow-inner">
                                    <span class="text-red-700 font-bold">Kecepatan Dihitung:</span>
                                    <span class="text-red-600 font-extrabold text-sm">${(evidence.calculated_speed_kmh).toLocaleString('id-ID')} Km/Jam</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            } else if (alert.fraud_type === 'DEVICE_SHARING') {
                evidenceHtml = `
                    <div class="p-4 rounded-xl bg-purple-50 border border-purple-100 space-y-3 shadow-sm">
                        <h4 class="font-extrabold text-purple-700 text-sm"><i class="fa-solid fa-users mr-1.5"></i>Deteksi Berbagi Perangkat (Perangkat Bersama)</h4>
                        <p class="text-slate-600 text-[11px] leading-relaxed font-medium">
                            Sistem mendeteksi bahwa beberapa akun mahasiswa berbeda melakukan absensi menggunakan browser / sidik jari perangkat laptop yang identik dalam rentang waktu berdekatan.
                        </p>
                        
                        <div class="space-y-2 text-[11px] border-t border-purple-100 pt-2 font-mono font-semibold text-slate-700">
                            <div class="p-2 bg-white rounded border border-slate-150 shadow-sm">
                                <span class="text-slate-400 block">Karakteristik Device Hash (Fingerprint):</span>
                                <span class="text-indigo-600 font-extrabold block truncate text-[10px]">${evidence.shared_fingerprint}</span>
                            </div>
                            <div class="p-2 bg-white rounded border border-slate-150 shadow-sm">
                                <span class="text-slate-400 block">IP Address Joki:</span>
                                <span class="text-slate-800 font-extrabold block">${evidence.ip_address}</span>
                            </div>
                            <div class="p-3 bg-purple-50 rounded border border-purple-100 space-y-1">
                                <span class="text-purple-700 font-bold block uppercase text-[9px]">Daftar Mahasiswa Menggunakan Device Ini:</span>
                                <ul class="list-disc list-inside text-slate-600 space-y-0.5 text-[10.5px]">
                                    ${evidence.shared_by_names.map((name, idx) => `<li><span class="text-slate-800 font-bold">${name}</span> (${evidence.shared_by_npms[idx]})</li>`).join('')}
                                </ul>
                            </div>
                        </div>
                    </div>
                `;
            } else if (alert.fraud_type === 'PROCTORING_SHIELD') {
                evidenceHtml = `
                    <div class="p-4 rounded-xl bg-red-50 border border-red-100 space-y-3 shadow-sm">
                        <h4 class="font-extrabold text-red-700 text-sm"><i class="fa-solid fa-laptop-code mr-1.5"></i>Pelanggaran Pengawasan Ujian (Proctoring Shield)</h4>
                        <p class="text-slate-600 text-[11px] leading-relaxed font-medium">
                            Sistem pengawasan mendeteksi tindakan mencurigakan saat mahasiswa mengerjakan ujian online. Fokus browser dialihkan berkali-kali atau ada penyalinan teks luar.
                        </p>
                        <div class="grid grid-cols-2 gap-2 text-[11px] font-mono border-t border-red-100 pt-2 font-semibold text-slate-700">
                            <div class="col-span-2 bg-white p-2 rounded border border-slate-150 shadow-sm flex justify-between items-center">
                                <span class="text-slate-550">Sesi Ujian:</span>
                                <span class="text-slate-800 font-bold">${evidence.exam_title}</span>
                            </div>
                            <div class="bg-white p-2 rounded border border-slate-150 shadow-sm">
                                <span class="text-slate-400 block">Keluar Tab Browser:</span>
                                <span class="text-red-600 font-extrabold text-sm">${evidence.tab_switches_count} Kali</span>
                            </div>
                            <div class="bg-white p-2 rounded border border-slate-150 shadow-sm">
                                <span class="text-slate-400 block">Copy-Paste Teks:</span>
                                <span class="text-red-600 font-extrabold text-sm">${evidence.copy_paste_count} Kali</span>
                            </div>
                            <div class="col-span-2 bg-slate-900 text-white p-2.5 rounded flex justify-between items-center shadow-inner">
                                <span class="font-bold">Skor Diperoleh:</span>
                                <span class="font-mono text-base font-bold">${evidence.score_achieved} / 100</span>
                            </div>
                        </div>
                    </div>
                `;
            }

            content.innerHTML = `
                <div class="space-y-3">
                    <div class="flex justify-between items-center bg-slate-55 p-2.5 rounded-lg border border-slate-200">
                        <span class="text-slate-500 font-bold">Mahasiswa:</span>
                        <span class="text-slate-800 font-extrabold">${alert.name} (${alert.npm})</span>
                    </div>
                    <div class="flex justify-between items-center bg-slate-55 p-2.5 rounded-lg border border-slate-200">
                        <span class="text-slate-500 font-bold">Kelas Sesi:</span>
                        <span class="text-slate-800 font-extrabold">${alert.class_name}</span>
                    </div>
                </div>
                ${evidenceHtml}
            `;

            // Setup tombol aksi di modal
            document.getElementById('modal-btn-reject').onclick = () => resolveAlert(alert.id, 'REJECTED');
            document.getElementById('modal-btn-approve').onclick = () => resolveAlert(alert.id, 'APPROVED');

            modal.classList.remove('hidden');
        }

        // Action Resolusi dari Dosen (Terima/Batalkan Presensi)
        function closeForensicModal() {
            document.getElementById('forensic-modal').classList.add('hidden');
        }

        // Action Resolusi dari Dosen (Terima/Batalkan Presensi)
        async function resolveAlert(alertId, decision) {
            try {
                const res = await fetch(`${API_BASE}/api/alerts/resolve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-User-NPM': currentUser ? currentUser.npm : '',
                        'X-User-Role': currentUser ? currentUser.role : ''
                    },
                    body: JSON.stringify({ alert_id: alertId, decision })
                });
                const result = await res.json();
                
                if (result.success) {
                    closeForensicModal();
                    fetchDashboardData(); // Refresh data dashboard
                } else {
                    alert("Gagal meresolusi alert: " + result.message);
                }
            } catch (err) {
                console.error(err);
                alert("Koneksi gagal.");
            }
        }

        // Reset data demo ke kondisi default
        async function resetDemoData() {
            try {
                const res = await fetch(`${API_BASE}/api/reset-demo`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-User-NPM': currentUser ? currentUser.npm : '',
                        'X-User-Role': currentUser ? currentUser.role : ''
                    }
                });
                const result = await res.json();
                if (result.success) {
                    alert("Database akademik berhasil diselaraskan ke kondisi default.");
                    fetchDashboardData();
                }
            } catch (err) {
                console.error(err);
                alert("Gagal reset data.");
            }
        }

        // =========================================================================
        // JAVASCRIPT LOGIC UNTUK MODUL LMS TAMBAHAN (MATERI, TUGAS, UJIAN, NILAI)
        // =========================================================================

        // --- 1. MODUL BAHAN AJAR / MATERI ---
        async function fetchMaterials() {
            try {
                const res = await fetch(`${API_BASE}/api/materials`);
                const materials = await res.json();
                const container = document.getElementById('lms-materials-list');
                
                if (materials.length === 0) {
                    container.innerHTML = `<p class="text-xs text-slate-400 text-center py-4">Belum ada bahan ajar diunggah.</p>`;
                    return;
                }

                container.innerHTML = materials.map(m => `
                    <div class="p-3.5 bg-slate-55 rounded-xl border border-slate-200 space-y-2 hover:border-indigo-300 hover:shadow-sm transition-all duration-300">
                        <div class="flex items-start justify-between gap-2">
                            <div class="h-8 w-8 rounded-lg bg-red-50 border border-red-100 flex items-center justify-center text-red-600 text-md flex-shrink-0">
                                <i class="fa-solid fa-file-pdf"></i>
                            </div>
                            <div class="min-w-0 flex-grow">
                                <h4 class="text-xs font-bold text-slate-800 truncate">${m.title}</h4>
                                <span class="text-[9px] text-indigo-650 font-extrabold uppercase tracking-wide block mt-0.5">${m.subject_code} - ${m.class_name}</span>
                            </div>
                        </div>
                        <p class="text-[10.5px] text-slate-500 leading-relaxed font-medium">${m.description || ''}</p>
                        <div class="flex items-center justify-between pt-1.5 border-t border-slate-100 text-[9.5px] font-bold text-slate-400 font-mono">
                            <span>${m.file_name}</span>
                            <a href="#" onclick="alert('Mengunduh berkas: ${m.file_name}'); return false;" class="text-indigo-600 hover:text-indigo-700 flex items-center"><i class="fa-solid fa-circle-down mr-1 text-xs"></i>Download</a>
                        </div>
                    </div>
                `).join('');

                // Isi dropdown kelas di form materi & tugas
                const classSelects = ['assignment-class-id', 'material-class-id', 'exam-class-id'];
                const classesRes = await fetch(`${API_BASE}/api/classes`);
                const classes = await classesRes.json();
                
                classSelects.forEach(sid => {
                    const sel = document.getElementById(sid);
                    if (sel) {
                        sel.innerHTML = classes.map(c => `<option value="${c.id}">${c.subject_code} - ${c.class_name}</option>`).join('');
                    }
                });

            } catch (err) {
                console.error("Gagal mengambil bahan ajar:", err);
            }
        }

        function showUploadMaterialForm() {
            document.getElementById('form-upload-material-container').classList.remove('hidden');
        }

        function hideUploadMaterialForm() {
            document.getElementById('form-upload-material-container').classList.add('hidden');
        }

        async function handleUploadMaterial(e) {
            e.preventDefault();
            const class_id = parseInt(document.getElementById('material-class-id').value);
            const title = document.getElementById('material-title').value;
            const description = document.getElementById('material-description').value;
            const file_name = document.getElementById('material-file-name').value;

            try {
                const res = await fetch(`${API_BASE}/api/materials`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-User-NPM': currentUser ? currentUser.npm : '',
                        'X-User-Role': currentUser ? currentUser.role : ''
                    },
                    body: JSON.stringify({ class_id, title, description, file_name })
                });
                const result = await res.json();

                if (result.success) {
                    hideUploadMaterialForm();
                    document.getElementById('material-title').value = '';
                    document.getElementById('material-description').value = '';
                    document.getElementById('material-file-name').value = '';
                    fetchMaterials();
                } else {
                    alert("Gagal mengunggah materi: " + result.message);
                }
            } catch (err) {
                console.error(err);
            }
        }

        // --- 2. MODUL TUGAS & PENGUMPULAN ---
        async function fetchAssignments() {
            try {
                const res = await fetch(`${API_BASE}/api/assignments`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-User-NPM': currentUser ? currentUser.npm : '',
                        'X-User-Role': currentUser ? currentUser.role : ''
                    }
                });
                const assignments = await res.json();
                const container = document.getElementById('lms-assignments-list');
                
                if (assignments.length === 0) {
                    container.innerHTML = `<p class="text-xs text-slate-400 text-center py-4">Belum ada tugas aktif saat ini.</p>`;
                    return;
                }

                container.innerHTML = assignments.map(a => {
                    let statusLabel = '';
                    let actionBtn = '';

                    // Jika siswa, tampilkan status pengumpulan tersendiri
                    if (currentUser.role === 'mahasiswa') {
                        if (a.submission) {
                            if (a.submission.status === 'GRADED') {
                                statusLabel = `<span class="px-2.5 py-1 rounded-lg text-[10.5px] font-bold bg-green-50 border border-green-200 text-green-700 shadow-sm"><i class="fa-solid fa-circle-check mr-1 text-xs"></i>Dinilai: ${a.submission.score}/${a.max_score}</span>`;
                                actionBtn = `<div class="p-3 bg-slate-50 border border-slate-200 rounded-xl space-y-1 text-xs mt-3"><span class="font-bold text-slate-500 block">Feedback Dosen:</span><p class="text-slate-700 font-semibold italic font-medium">"${a.submission.feedback || 'Sangat baik.'}"</p></div>`;
                            } else {
                                statusLabel = `<span class="px-2.5 py-1 rounded-lg text-[10.5px] font-bold bg-yellow-50 border border-yellow-200 text-yellow-700 shadow-sm"><i class="fa-solid fa-clock mr-1 text-xs"></i>Sudah Dikumpulkan (Menunggu Penilaian)</span>`;
                                actionBtn = `<button onclick="showSubmitAssignmentForm('${a.id}', '${a.title}', '${a.submission.submission_text}')" class="mt-3 px-3.5 py-1.5 bg-white hover:bg-slate-50 border border-slate-200 text-slate-600 text-xs font-bold rounded-xl transition-all shadow-sm"><i class="fa-solid fa-pen mr-1"></i>Edit Jawaban</button>`;
                            }
                        } else {
                            statusLabel = `<span class="px-2.5 py-1 rounded-lg text-[10.5px] font-bold bg-red-50 border border-red-200 text-red-700 shadow-sm"><i class="fa-solid fa-circle-xmark mr-1 text-xs"></i>Belum Mengumpulkan</span>`;
                            actionBtn = `<button onclick="showSubmitAssignmentForm('${a.id}', '${a.title}')" class="mt-3 px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white text-xs font-bold rounded-xl transition-all shadow-md"><i class="fa-solid fa-upload mr-1.5"></i>Kumpulkan Tugas</button>`;
                        }
                    } else {
                        // Dosen melihat detail tugas umum
                        statusLabel = `<span class="px-2.5 py-1 rounded-lg text-[10.5px] font-bold bg-indigo-50 border border-indigo-150 text-indigo-750 font-mono shadow-sm"><i class="fa-solid fa-calendar mr-1"></i>Max Score: ${a.max_score}</span>`;
                    }

                    const dueDateStr = new Date(a.due_date).toLocaleString('id-ID', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' });

                    return `
                        <div class="p-5 bg-white rounded-2xl border border-slate-200 space-y-3 hover:shadow-sm hover:border-slate-300 transition-all duration-300">
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 border-b border-slate-100 pb-3">
                                <div>
                                    <h3 class="text-sm font-extrabold text-slate-900">${a.title}</h3>
                                    <span class="text-[9px] text-indigo-650 font-extrabold uppercase tracking-wide block mt-0.5">${a.subject_code} - ${a.class_name}</span>
                                </div>
                                <div class="flex-shrink-0 flex items-center">${statusLabel}</div>
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed font-semibold">${a.description}</p>
                            <div class="flex items-center text-[10.5px] text-slate-455 font-bold font-mono">
                                <i class="fa-solid fa-clock-rotate-left mr-1.5 text-xs text-slate-400"></i>
                                <span>Batas Waktu: ${dueDateStr} WIB</span>
                            </div>
                            ${actionBtn}
                        </div>
                    `;
                }).join('');

            } catch (err) {
                console.error("Gagal mengambil tugas:", err);
            }
        }

        function showCreateAssignmentForm() {
            document.getElementById('form-create-assignment-container').classList.remove('hidden');
        }

        function hideCreateAssignmentForm() {
            document.getElementById('form-create-assignment-container').classList.add('hidden');
        }

        async function handleCreateAssignment(e) {
            e.preventDefault();
            const class_id = parseInt(document.getElementById('assignment-class-id').value);
            const title = document.getElementById('assignment-title').value;
            const description = document.getElementById('assignment-description').value;
            const due_date = document.getElementById('assignment-due-date').value;
            const max_score = parseInt(document.getElementById('assignment-max-score').value);

            try {
                const res = await fetch(`${API_BASE}/api/assignments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-User-NPM': currentUser ? currentUser.npm : '',
                        'X-User-Role': currentUser ? currentUser.role : ''
                    },
                    body: JSON.stringify({ class_id, title, description, due_date, max_score })
                });
                const result = await res.json();

                if (result.success) {
                    hideCreateAssignmentForm();
                    document.getElementById('assignment-title').value = '';
                    document.getElementById('assignment-description').value = '';
                    document.getElementById('assignment-due-date').value = '';
                    fetchAssignments();
                } else {
                    alert("Gagal menerbitkan tugas: " + result.message);
                }
            } catch (err) {
                console.error(err);
            }
        }

        function showSubmitAssignmentForm(assignmentId, title, existingText = '') {
            document.getElementById('form-submit-assignment-container').classList.remove('hidden');
            document.getElementById('submit-assignment-id').value = assignmentId;
            document.getElementById('submit-assignment-title-label').innerText = title;
            document.getElementById('submit-assignment-text').value = existingText;
            document.getElementById('form-submit-assignment-container').scrollIntoView({ behavior: 'smooth' });
        }

        function hideSubmitAssignmentForm() {
            document.getElementById('form-submit-assignment-container').classList.add('hidden');
        }

        async function handleSubmitAssignment(e) {
            e.preventDefault();
            const assignment_id = parseInt(document.getElementById('submit-assignment-id').value);
            const submission_text = document.getElementById('submit-assignment-text').value;
            const npm = currentUser.npm;
            const name = currentUser.name;

            try {
                const res = await fetch(`${API_BASE}/api/assignments/submit`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-User-NPM': currentUser ? currentUser.npm : '',
                        'X-User-Role': currentUser ? currentUser.role : ''
                    },
                    body: JSON.stringify({ assignment_id, submission_text, npm, name })
                });
                const result = await res.json();

                if (result.success) {
                    hideSubmitAssignmentForm();
                    fetchAssignments();
                    alert("Jawaban tugas Anda berhasil dikirim.");
                } else {
                    alert("Gagal mengumpulkan tugas: " + result.message);
                }
            } catch (err) {
                console.error(err);
            }
        }

        // --- 3. MODUL GRADING (NILAI TUGAS OLEH DOSEN) ---
        async function fetchSubmissions() {
            try {
                const res = await fetch(`${API_BASE}/api/assignments/submissions`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-User-NPM': currentUser ? currentUser.npm : '',
                        'X-User-Role': currentUser ? currentUser.role : ''
                    }
                });
                const submissions = await res.json();
                const tbody = document.getElementById('lms-submissions-table-body');
                
                if (submissions.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="5" class="py-6 text-center text-slate-400 font-semibold">Belum ada mahasiswa mengumpulkan tugas.</td></tr>`;
                    return;
                }

                tbody.innerHTML = submissions.map(s => {
                    let scoreBadge = '';
                    let actionBtn = '';

                    if (s.status === 'GRADED') {
                        scoreBadge = `<span class="px-2.5 py-1 rounded-full text-xs font-bold bg-green-55/15 border border-green-200 text-green-800 font-mono shadow-sm">${s.score} / 100</span>`;
                        actionBtn = `<button onclick="showGradeSubmissionPrompt('${s.id}', '${s.name}', \`${s.submission_text.replace(/`/g, '\\`').replace(/'/g, "\\'")}\`, ${s.score}, \`${(s.feedback || '').replace(/`/g, '\\`').replace(/'/g, "\\'")}\`)" class="px-2.5 py-1.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-600 text-xs font-bold rounded-lg transition-colors"><i class="fa-solid fa-pencil mr-1"></i>Koreksi</button>`;
                    } else {
                        scoreBadge = `<span class="px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-55/15 border border-yellow-200 text-yellow-850 shadow-sm animate-pulse">Menunggu Nilai</span>`;
                        actionBtn = `<button onclick="showGradeSubmissionPrompt('${s.id}', '${s.name}', \`${s.submission_text.replace(/`/g, '\\`').replace(/'/g, "\\'")}\`)" class="px-3 py-1.5 bg-indigo-500 hover:bg-indigo-600 text-white text-xs font-bold rounded-lg transition-colors shadow-md shadow-indigo-500/10"><i class="fa-solid fa-graduation-cap mr-1"></i>Beri Nilai</button>`;
                    }

                    const dateStr = new Date(s.created_at || s.timestamp).toLocaleString('id-ID', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' });

                    return `
                        <tr class="hover:bg-slate-50/50 transition-colors border-b border-slate-100">
                            <td class="py-3 px-4 font-bold text-slate-755">${s.assignment_title}</td>
                            <td class="py-3 px-4">
                                <div class="font-extrabold text-slate-800">${s.name}</div>
                                <div class="text-xs text-slate-400 font-mono font-semibold">${s.npm}</div>
                            </td>
                            <td class="py-3 px-4 font-semibold text-slate-500">${dateStr} WIB</td>
                            <td class="py-3 px-4">${scoreBadge}</td>
                            <td class="py-3 px-4 text-right">${actionBtn}</td>
                        </tr>
                    `;
                }).join('');

            } catch (err) {
                console.error("Gagal mengambil pengumpulan tugas:", err);
            }
        }

        function showGradeSubmissionPrompt(submissionId, studentName, text, currentScore = '', currentFeedback = '') {
            const modal = document.getElementById('forensic-modal');
            const content = document.getElementById('modal-evidence-content');
            
            content.innerHTML = `
                <div class="space-y-4">
                    <div class="p-3 bg-indigo-50 border border-indigo-100 rounded-xl space-y-1.5">
                        <span class="text-[10px] font-extrabold text-indigo-700 uppercase tracking-widest block">Lembar Jawaban Mahasiswa:</span>
                        <p class="text-slate-800 font-mono text-[11px] whitespace-pre-wrap leading-relaxed max-h-[150px] overflow-y-auto p-2 bg-white rounded border border-slate-150">${text}</p>
                    </div>
                    <div class="space-y-2 border-t border-slate-100 pt-3 text-xs">
                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1">Skor Nilai (0 - 100):</label>
                            <input type="number" id="grade-score-input" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-indigo-500 shadow-sm font-bold" min="0" max="100" value="${currentScore}" placeholder="Masukkan nilai angka">
                        </div>
                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1">Catatan Koreksi / Feedback Dosen:</label>
                            <textarea id="grade-feedback-input" rows="3" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-indigo-500 shadow-sm" placeholder="Tuliskan catatan akademik...">${currentFeedback}</textarea>
                        </div>
                    </div>
                </div>
            `;

            modal.querySelector('h3').innerText = `Penilaian Lembar Tugas: ${studentName}`;
            modal.querySelector('p').innerText = "Koreksi lembar jawaban mahasiswa dan berikan skor nilai.";
            
            const btnApprove = document.getElementById('modal-btn-approve');
            const btnReject = document.getElementById('modal-btn-reject');
            
            btnApprove.className = "px-4 py-2 rounded-xl bg-indigo-500 hover:bg-indigo-600 text-xs text-white font-bold transition-all shadow-lg shadow-indigo-600/20";
            btnApprove.innerText = "Kirim Nilai Akademik";
            btnApprove.onclick = async () => {
                const score = parseInt(document.getElementById('grade-score-input').value);
                const feedback = document.getElementById('grade-feedback-input').value;
                if (isNaN(score) || score < 0 || score > 100) {
                    alert("Masukkan skor nilai antara 0 - 100");
                    return;
                }
                
                try {
                    const res = await fetch(`${API_BASE}/api/assignments/grade`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-User-NPM': currentUser ? currentUser.npm : '',
                            'X-User-Role': currentUser ? currentUser.role : ''
                        },
                        body: JSON.stringify({ submission_id: submissionId, score, feedback })
                    });
                    const result = await res.json();
                    if (result.success) {
                        closeForensicModal();
                        fetchSubmissions();
                        setTimeout(() => {
                            modal.querySelector('h3').innerText = "Forensik Detektor Anomali";
                            modal.querySelector('p').innerText = "Analisis bukti anomali kecurangan yang dikumpulkan sistem";
                            btnApprove.className = "px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-xs text-white font-bold transition-all shadow-lg shadow-red-600/20";
                            btnApprove.innerText = "Batalkan Kehadiran (Tidak Valid)";
                        }, 500);
                    } else {
                        alert("Gagal menilai: " + result.message);
                    }
                } catch (err) {
                    console.error(err);
                }
            };

            btnReject.innerText = "Batal";
            btnReject.onclick = () => {
                closeForensicModal();
                setTimeout(() => {
                    modal.querySelector('h3').innerText = "Forensik Detektor Anomali";
                    modal.querySelector('p').innerText = "Analisis bukti anomali kecurangan yang dikumpulkan sistem";
                    btnApprove.className = "px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-xs text-white font-bold transition-all shadow-lg shadow-red-600/20";
                    btnApprove.innerText = "Batalkan Kehadiran (Tidak Valid)";
                    btnReject.className = "px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-200 text-xs text-slate-600 font-bold transition-all";
                    btnReject.innerText = "Sahkan Kehadiran (Valid)";
                }, 500);
            };

            modal.classList.remove('hidden');
        }

        // --- 4. MODUL UJIAN ONLINE & PROCTORING SHIELD ---
        async function fetchExams() {
            try {
                const res = await fetch(`${API_BASE}/api/exams`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-User-NPM': currentUser ? currentUser.npm : '',
                        'X-User-Role': currentUser ? currentUser.role : ''
                    }
                });
                const exams = await res.json();
                const container = document.getElementById('lms-exams-list');
                
                if (exams.length === 0) {
                    container.innerHTML = `<p class="text-xs text-slate-400 text-center py-4">Belum ada sesi ujian terbit saat ini.</p>`;
                    return;
                }

                container.innerHTML = exams.map(e => {
                    let statusBadge = '';
                    let actionBtn = '';

                    if (currentUser.role === 'mahasiswa') {
                        if (e.attempt) {
                            statusBadge = `<span class="px-2.5 py-1 rounded-lg text-[10.5px] font-bold bg-green-50 border border-green-200 text-green-700 shadow-sm"><i class="fa-solid fa-circle-check mr-1 text-xs"></i>Selesai Mengerjakan</span>`;
                            actionBtn = `
                                <div class="mt-4 grid grid-cols-2 gap-2 text-xs border-t border-slate-100 pt-3">
                                    <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-200"><span class="text-slate-450 block font-bold text-[9px] uppercase font-mono">Skor Ujian:</span><span class="font-extrabold text-slate-800 text-sm font-mono">${e.attempt.score} / 100</span></div>
                                    <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-200"><span class="text-slate-450 block font-bold text-[9px] uppercase font-mono">Pelanggaran Fokus:</span><span class="font-extrabold text-red-600 text-sm font-mono">${e.attempt.tab_switches_count} Keluar Tab</span></div>
                                </div>
                            `;
                        } else {
                            statusBadge = `<span class="px-2.5 py-1 rounded-lg text-[10.5px] font-bold bg-red-50 border border-red-200 text-red-700 shadow-sm"><i class="fa-solid fa-lock-open mr-1 text-xs"></i>Ujian Tersedia</span>`;
                            actionBtn = `<button onclick="startExam('${e.id}', ${e.duration_minutes}, '${e.title}')" class="mt-4 w-full py-2.5 bg-indigo-500 hover:bg-indigo-600 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-indigo-500/10 flex items-center justify-center space-x-1.5"><i class="fa-solid fa-circle-play"></i><span>Mulai Ujian Sekarang</span></button>`;
                        }
                    } else {
                        statusBadge = `<span class="px-2.5 py-1 rounded-lg text-[10.5px] font-bold bg-indigo-55/40 border border-indigo-200 text-indigo-755 font-mono shadow-sm"><i class="fa-solid fa-hourglass-half mr-1"></i>Durasi: ${e.duration_minutes} Menit</span>`;
                    }

                    return `
                        <div class="p-5 bg-white rounded-2xl border border-slate-200 space-y-3 hover:shadow-sm hover:border-slate-300 transition-all duration-300">
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 border-b border-slate-100 pb-3">
                                <div>
                                    <h3 class="text-sm font-extrabold text-slate-900">${e.title}</h3>
                                    <span class="text-[9px] text-indigo-650 font-extrabold uppercase tracking-wide block mt-0.5">${e.subject_code} - ${e.class_name}</span>
                                </div>
                                <div class="flex-shrink-0 flex items-center">${statusBadge}</div>
                            </div>
                            <p class="text-xs text-slate-505 leading-relaxed font-semibold">${e.description || 'Tidak ada deskripsi.'}</p>
                            ${actionBtn}
                        </div>
                    `;
                }).join('');

            } catch (err) {
                console.error("Gagal mengambil ujian:", err);
            }
        }

        function showCreateExamForm() {
            document.getElementById('form-create-exam-container').classList.remove('hidden');
        }

        function hideCreateExamForm() {
            document.getElementById('form-create-exam-container').classList.add('hidden');
        }

        async function handleCreateExam(e) {
            e.preventDefault();
            const class_id = parseInt(document.getElementById('exam-class-id').value);
            const title = document.getElementById('exam-title').value;
            const description = document.getElementById('exam-description').value;
            const duration_minutes = parseInt(document.getElementById('exam-duration').value);

            const questions = [
                {
                    question_text: document.getElementById('exam-q1-text').value,
                    option_a: document.getElementById('exam-q1-a').value,
                    option_b: document.getElementById('exam-q1-b').value,
                    option_c: document.getElementById('exam-q1-c').value,
                    option_d: document.getElementById('exam-q1-d').value,
                    correct_option: document.getElementById('exam-q1-correct').value
                },
                {
                    question_text: document.getElementById('exam-q2-text').value,
                    option_a: document.getElementById('exam-q2-a').value,
                    option_b: document.getElementById('exam-q2-b').value,
                    option_c: document.getElementById('exam-q2-c').value,
                    option_d: document.getElementById('exam-q2-d').value,
                    correct_option: document.getElementById('exam-q2-correct').value
                }
            ];

            try {
                const res = await fetch(`${API_BASE}/api/exams`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-User-NPM': currentUser ? currentUser.npm : '',
                        'X-User-Role': currentUser ? currentUser.role : ''
                    },
                    body: JSON.stringify({ class_id, title, description, duration_minutes, questions })
                });
                const result = await res.json();

                if (result.success) {
                    hideCreateExamForm();
                    document.getElementById('exam-title').value = '';
                    document.getElementById('exam-description').value = '';
                    fetchExams();
                } else {
                    alert("Gagal menerbitkan sesi ujian: " + result.message);
                }
            } catch (err) {
                console.error(err);
            }
        }

        async function startExam(examId, durationMinutes, title) {
            if (!confirm("Apakah Anda yakin ingin memulai ujian sekarang? Sensor pengawas Proctoring Shield akan mendeteksi seluruh gerakan jendela browser Anda secara ketat!")) {
                return;
            }

            try {
                const res = await fetch(`${API_BASE}/api/exams/${examId}/questions`);
                const questions = await res.json();
                
                const qContainer = document.getElementById('active-exam-questions-container');
                document.getElementById('active-exam-id').value = examId;
                document.getElementById('active-exam-title').innerText = title;
                
                if (questions.length === 0) {
                    qContainer.innerHTML = `<p class="text-xs text-slate-455 py-4">Belum ada soal ujian terunggah pada sistem.</p>`;
                    return;
                }

                qContainer.innerHTML = questions.map((q, idx) => `
                    <div class="space-y-3 py-4 ${idx > 0 ? 'border-t border-slate-100' : ''}">
                        <span class="block text-xs font-extrabold text-slate-800 leading-relaxed font-mono">Soal ${idx + 1}: ${q.question_text}</span>
                        <div class="grid grid-cols-1 gap-2 text-xs font-medium text-slate-650">
                            <label class="flex items-center space-x-2.5 p-2 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer transition-colors">
                                <input type="radio" name="question_${q.id}" value="A" class="text-indigo-600 focus:ring-indigo-500" required>
                                <span>A. ${q.option_a}</span>
                            </label>
                            <label class="flex items-center space-x-2.5 p-2 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer transition-colors">
                                <input type="radio" name="question_${q.id}" value="B" class="text-indigo-600 focus:ring-indigo-500">
                                <span>B. ${q.option_b}</span>
                            </label>
                            <label class="flex items-center space-x-2.5 p-2 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer transition-colors">
                                <input type="radio" name="question_${q.id}" value="C" class="text-indigo-600 focus:ring-indigo-500">
                                <span>C. ${q.option_c}</span>
                            </label>
                            <label class="flex items-center space-x-2.5 p-2 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer transition-colors">
                                <input type="radio" name="question_${q.id}" value="D" class="text-indigo-600 focus:ring-indigo-500">
                                <span>D. ${q.option_d}</span>
                            </label>
                        </div>
                    </div>
                `).join('');

                document.getElementById('lms-default-exam-container').classList.add('hidden');
                document.getElementById('lms-active-exam-container').classList.remove('hidden');

                document.querySelector('aside').classList.add('pointer-events-none', 'opacity-30');

                activeExamTabSwitches = 0;
                activeExamCopyPasteCount = 0;
                document.getElementById('active-proctoring-switches').innerText = '0';
                document.getElementById('active-proctoring-copypaste').innerText = '0';

                window.activeExamBlurHandler = () => {
                    activeExamTabSwitches++;
                    document.getElementById('active-proctoring-switches').innerText = activeExamTabSwitches;
                    showProctoringToast("Fokus Layar Dialihkan! Tindakan pelanggaran dicatat oleh sistem pengawas.");
                };

                window.activeExamCopyPasteHandler = (e) => {
                    e.preventDefault();
                    activeExamCopyPasteCount++;
                    document.getElementById('active-proctoring-copypaste').innerText = activeExamCopyPasteCount;
                    showProctoringToast("Tindakan Copy/Paste Diblokir! Pelanggaran dicatat.");
                };

                window.addEventListener('blur', window.activeExamBlurHandler);
                document.addEventListener('copy', window.activeExamCopyPasteHandler);
                document.addEventListener('paste', window.activeExamCopyPasteHandler);

                let timeRemaining = durationMinutes * 60;
                const timerSpan = document.getElementById('active-exam-timer');
                
                activeExamTimerId = setInterval(() => {
                    timeRemaining--;
                    const mins = Math.floor(timeRemaining / 60).toString().padStart(2, '0');
                    const secs = (timeRemaining % 60).toString().padStart(2, '0');
                    timerSpan.innerText = `${mins}:${secs}`;

                    if (timeRemaining <= 0) {
                        clearInterval(activeExamTimerId);
                        alert("Batas waktu ujian habis! Lembar jawaban akan dikumpulkan secara otomatis oleh sistem.");
                        document.getElementById('active-exam-form').requestSubmit();
                    }
                }, 1000);

            } catch (err) {
                console.error("Gagal memulai ujian:", err);
            }
        }

        async function handleActiveExamSubmit(e) {
            e.preventDefault();
            
            if (activeExamTimerId) {
                clearInterval(activeExamTimerId);
            }

            window.removeEventListener('blur', window.activeExamBlurHandler);
            document.removeEventListener('copy', window.activeExamCopyPasteHandler);
            document.removeEventListener('paste', window.activeExamCopyPasteHandler);

            document.querySelector('aside').classList.remove('pointer-events-none', 'opacity-30');

            const exam_id = parseInt(document.getElementById('active-exam-id').value);
            const npm = currentUser.npm;
            const name = currentUser.name;

            const form = document.getElementById('active-exam-form');
            const answers = {};
            const radioInputs = form.querySelectorAll('input[type="radio"]:checked');
            
            radioInputs.forEach(input => {
                const qId = input.name.replace('question_', '');
                answers[qId] = input.value;
            });

            const payload = {
                exam_id,
                npm,
                name,
                answers,
                tab_switches: activeExamTabSwitches,
                copy_paste: activeExamCopyPasteCount
            };

            try {
                const res = await fetch(`${API_BASE}/api/exams/submit`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-User-NPM': currentUser ? currentUser.npm : '',
                        'X-User-Role': currentUser ? currentUser.role : ''
                    },
                    body: JSON.stringify(payload)
                });
                const result = await res.json();

                document.getElementById('lms-active-exam-container').classList.add('hidden');
                document.getElementById('lms-default-exam-container').classList.remove('hidden');

                if (result.success) {
                    let alertMsg = `Ujian Selesai!\n\nSkor Nilai Anda: ${result.score} / 100`;
                    if (result.cheating_flagged) {
                        alertMsg += `\n⚠️ Sistem mencatat adanya ${result.tab_switches} pelanggaran fokus tab browser. Alarm kecurangan dikirim ke Dosen.`;
                    } else {
                        alertMsg += `\n✅ Ujian diselesaikan secara jujur (bebas fraud).`;
                    }
                    alert(alertMsg);
                    fetchExams();
                } else {
                    alert("Gagal mengumpulkan ujian: " + result.message);
                }

            } catch (err) {
                console.error(err);
                alert("Koneksi gagal.");
            }
        }

        function showProctoringToast(message) {
            const toast = document.createElement('div');
            toast.className = 'fixed top-5 right-5 z-50 px-5 py-3.5 bg-red-600 text-white font-bold text-xs rounded-xl shadow-2xl flex items-center space-x-2 border border-red-500 animate-bounce';
            toast.innerHTML = `<i class="fa-solid fa-triangle-exclamation text-base"></i><span>${message}</span>`;
            document.body.appendChild(toast);
            setTimeout(() => { toast.remove(); }, 3000);
        }

        async function fetchExamAttempts() {
            try {
                const res = await fetch(`${API_BASE}/api/exams/attempts`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-User-NPM': currentUser ? currentUser.npm : '',
                        'X-User-Role': currentUser ? currentUser.role : ''
                    }
                });
                const attempts = await res.json();
                const container = document.getElementById('lms-proctoring-panel-list');
                
                if (attempts.length === 0) {
                    container.innerHTML = `<p class="text-xs text-slate-400 text-center py-4">Belum ada data pengerjaan ujian.</p>`;
                    return;
                }

                container.innerHTML = attempts.map(a => {
                    let cheatingWarn = '';
                    let cardBg = 'bg-slate-55/50 border-slate-200';
                    
                    if (a.tab_switches_count >= 3 || a.copy_paste_count >= 1) {
                        cheatingWarn = `
                            <div class="mt-2.5 p-2 bg-red-50 rounded border border-red-200 flex items-center space-x-1.5 text-[10px] font-bold text-red-700 animate-pulse">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <span>Kecurigaan Tinggi: Pelanggaran Fokus Terdeteksi!</span>
                            </div>
                        `;
                        cardBg = 'bg-red-500/5 border-red-300';
                    } else {
                        cheatingWarn = `
                            <div class="mt-2.5 p-2 bg-green-50 rounded border border-green-150 flex items-center space-x-1.5 text-[10px] font-bold text-green-700">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>Aktivitas Aman: Pengerjaan Jujur.</span>
                            </div>
                        `;
                        cardBg = 'bg-green-55/5 border-green-200';
                    }

                    return `
                        <div class="p-4 rounded-2xl border ${cardBg} space-y-2 transition-all duration-300 hover:shadow-sm">
                            <div class="flex items-start justify-between gap-2 border-b border-slate-200 pb-2">
                                <div>
                                    <h4 class="text-xs font-bold text-slate-800">${a.name}</h4>
                                    <span class="text-[9.5px] text-slate-400 font-bold font-mono">${a.npm}</span>
                                </div>
                                <span class="text-xs font-extrabold font-mono text-indigo-650 bg-white px-2 py-1 rounded-lg border">${a.score} / 100</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-[9.5px] font-bold font-mono text-slate-500 pt-1">
                                <div>🚫 Keluar Tab: <span class="text-slate-800 font-extrabold">${a.tab_switches_count} Kali</span></div>
                                <div>📋 Copy-Paste: <span class="text-slate-800 font-extrabold">${a.copy_paste_count} Kali</span></div>
                            </div>
                            ${cheatingWarn}
                        </div>
                    `;
                }).join('');

            } catch (err) {
                console.error("Gagal mengambil proctoring logs:", err);
            }
        }

        async function fetchGrades() {
            try {
                const res = await fetch(`${API_BASE}/api/grades`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-User-NPM': currentUser ? currentUser.npm : '',
                        'X-User-Role': currentUser ? currentUser.role : ''
                    }
                });
                const grades = await res.json();
                const tbody = document.getElementById('lms-grades-table-body');
                
                if (grades.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="8" class="py-6 text-center text-slate-450 font-bold">Data nilai akademik belum terisi.</td></tr>`;
                    return;
                }

                tbody.innerHTML = grades.map(g => {
                    let gradeColor = 'text-slate-800';
                    if (g.grade_letter.startsWith('A')) gradeColor = 'text-green-600 font-extrabold bg-green-50 border border-green-150 px-2 py-1 rounded-lg';
                    else if (g.grade_letter.startsWith('B')) gradeColor = 'text-blue-600 font-extrabold bg-blue-50 border border-blue-150 px-2 py-1 rounded-lg';
                    else if (g.grade_letter.startsWith('C')) gradeColor = 'text-yellow-600 font-extrabold bg-yellow-50 border border-yellow-150 px-2 py-1 rounded-lg';
                    else if (g.grade_letter.startsWith('D') || g.grade_letter === 'E') gradeColor = 'text-red-600 font-extrabold bg-red-50 border border-red-150 px-2 py-1 rounded-lg';

                    return `
                        <tr class="hover:bg-slate-50 transition-colors border-b border-slate-100">
                            <td class="py-4.5 px-4">
                                <div class="font-extrabold text-slate-800">${g.name}</div>
                                <div class="text-xs text-slate-400 font-mono font-semibold">${g.npm}</div>
                            </td>
                            <td class="py-4.5 px-4 font-bold text-slate-700">${g.subject_code} - ${g.class_name}</td>
                            <td class="py-4.5 px-4 font-bold text-indigo-600">${g.attendance_percentage}%</td>
                            <td class="py-4.5 px-4 font-mono font-semibold text-slate-500">${g.attendance_score}</td>
                            <td class="py-4.5 px-4 font-mono font-semibold text-slate-500">${g.assignment_score}</td>
                            <td class="py-4.5 px-4 font-mono font-semibold text-slate-500">${g.exam_score}</td>
                            <td class="py-4.5 px-4 font-mono font-extrabold text-indigo-650 text-base">${g.final_score}</td>
                            <td class="py-4.5 px-4 text-center font-mono text-base"><span class="${gradeColor}">${g.grade_letter}</span></td>
                        </tr>
                    `;
                }).join('');

            } catch (err) {
                console.error("Gagal mengambil transkrip nilai:", err);
            }
        }
    </script>
</body>
</html>
