<div align="center">

# 🏢 Telkom Property Management System

**Sistem Informasi Manajemen Properti Berbasis Web**

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Blade](https://img.shields.io/badge/Blade-Templating-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/docs/blade)
[![Vite](https://img.shields.io/badge/Vite-Build_Tool-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vitejs.dev)

Aplikasi web full-stack yang dirancang untuk mempermudah pengelolaan aset properti **Telkom Property Area Sumbar Jambi**. Dibangun menggunakan **Laravel 13**, aplikasi ini mencakup manajemen properti, penyewaan, galeri, laporan pendapatan, serta pengaturan antarmuka publik secara dinamis dengan dukungan **Multi-Role Dashboard** (Admin, Manager, dan User/Penyewa).

---

### 🌐 Live Demo

🔗 **[https://telkom-property-management-system-production.up.railway.app](https://telkom-property-management-system-production.up.railway.app)**

</div>

---

## 🔑 Akun Demo

Gunakan akun berikut untuk mengakses sistem melalui halaman [**Login**](https://telkom-property-management-system-production.up.railway.app/login):

| Role | Email | Password | Akses |
| :---: | :--- | :---: | :--- |
| 🛡️ **Admin** | `admin@telkomproperty.com` | `password` | Full access — kelola properti, penyewaan, galeri, laporan, & pengaturan sistem |
| 📊 **Manager** | `manager@telkomproperty.com` | `password` | Read-only — lihat dashboard, laporan pendapatan, & data properti |

> **Catatan:** Akun dengan role **User/Penyewa** dapat dibuat melalui halaman registrasi pada website.

---

## 📸 Screenshots

### 🔐 Halaman Login
Halaman login dengan desain modern berbasis glassmorphism dan dark theme.

![Halaman Login](docs/halaman%20login%20admin%20dan%20meneger.png)

---

### 📊 Dashboard Admin
Dashboard utama admin yang menampilkan ringkasan jumlah properti, status ketersediaan, dan daftar penyewaan aktif.

![Dashboard Admin](docs/dashboard%20admin.png)

---

### 🏠 Data Properti
Halaman manajemen data properti lengkap dengan fitur CRUD (Create, Read, Update, Delete).

![Data Properti Admin](docs/data%20property%20admin.png)

---

### 🖼️ Galeri Properti
Kelola foto dan aset visual untuk setiap properti secara terorganisir.

![Galeri Properti](docs/galeri%20property%20admin.png)

---

### 📋 Manajemen Penyewaan
Pantau data penyewa, masa sewa, status kontrak, dan kelola transaksi penyewaan.

![Penyewaan Admin](docs/peyewaan%20admin.png)

---

### 📈 Laporan Pendapatan & Statistik
Visualisasi data pendapatan bulanan, estimasi tahunan, grafik bar chart, dan distribusi status properti menggunakan donut chart.

![Laporan Admin](docs/laporan%20admin.png)

---

### ⚙️ Pengaturan Sistem
Ubah nama perusahaan, logo, alamat, dan informasi kontak langsung dari dashboard tanpa mengubah kode.

![Pengaturan Admin](docs/peganturan%20admin%20.png)

---

### 📊 Dashboard Manager
Dashboard khusus manager dengan akses read-only untuk memonitor performa dan data properti.

![Dashboard Manager](docs/dashoboard%20meneger.png)

---

### 🌍 Halaman Publik — Detail Properti
Halaman publik yang menampilkan informasi lengkap properti, kalkulator harga sewa, galeri foto, deskripsi, dan tombol kontak WhatsApp/Telepon.

![Detail Properti Publik](docs/Halaman%20detail%20%20Publik%20.png)

---

## 🌟 Fitur Utama

### 🛡️ Panel Admin
- **Dashboard Interaktif** — Ringkasan real-time jumlah properti, status ketersediaan, dan penyewaan aktif
- **Manajemen Properti** — CRUD lengkap untuk data properti (nama, alamat, luas, harga sewa, status)
- **Manajemen Penyewaan** — Pencatatan transaksi sewa, pemantauan masa kontrak, dan perubahan status
- **Galeri Properti** — Upload dan kelola foto multiple untuk setiap properti
- **Laporan & Statistik** — Grafik pendapatan bulanan (Bar Chart), distribusi properti (Donut Chart), dan estimasi pendapatan tahunan
- **Pengaturan Dinamis** — Ubah logo, nama brand, alamat, nomor telepon, dan email perusahaan dari dashboard
- **Manajemen Pembayaran** — Integrasi pencatatan pembayaran untuk setiap transaksi penyewaan

### 📊 Panel Manager
- **Dashboard Overview** — Monitoring performa bisnis properti
- **Laporan Pendapatan** — Akses baca untuk analisis data keuangan
- **Data Properti** — Lihat seluruh data properti (read-only)

### 👤 Panel User / Penyewa
- **Dashboard Pribadi** — Ringkasan informasi penyewaan pengguna
- **Riwayat Penyewaan** — Lihat seluruh riwayat dan detail transaksi sewa
- **Pemesanan Properti** — Ajukan pemesanan sewa properti secara online

### 🌍 Halaman Publik
- **Landing Page** — Beranda dengan properti unggulan dan statistik ketersediaan
- **Daftar Properti** — Pencarian, filter status, dan sorting (terbaru, harga, luas)
- **Detail Properti** — Kalkulator harga sewa otomatis, galeri foto, deskripsi lengkap, lokasi, dan tombol kontak WhatsApp/Telepon
- **Registrasi Akun** — Pendaftaran akun penyewa baru

### 💳 Integrasi Pembayaran
- **Midtrans** — Payment gateway untuk transaksi pembayaran online
- **Duitku** — Alternatif payment gateway dengan callback otomatis

---

## 💻 Tech Stack

| Layer | Teknologi |
| :--- | :--- |
| **Framework** | Laravel 13.x (PHP 8.3) |
| **Frontend** | Blade Templating + Vite |
| **Database** | MySQL 8.0 |
| **Styling** | Custom CSS + Bootstrap Components |
| **Charts** | Chart.js (Bar Chart & Donut Chart) |
| **Payment Gateway** | Midtrans & Duitku |
| **Hosting** | Railway.app |

---

## 🏗️ Arsitektur Sistem

```
┌─────────────────────────────────────────────────────┐
│                   PUBLIC PAGES                       │
│  Beranda  ·  Daftar Properti  ·  Detail Properti    │
└─────────────────────┬───────────────────────────────┘
                      │
              ┌───────┴───────┐
              │  AUTH SYSTEM  │
              │  Login/Register│
              └───────┬───────┘
                      │
        ┌─────────────┼─────────────┐
        │             │             │
   ┌────┴────┐  ┌─────┴─────┐  ┌───┴────┐
   │  ADMIN  │  │  MANAGER  │  │  USER  │
   │  Panel  │  │   Panel   │  │ Panel  │
   └────┬────┘  └─────┬─────┘  └───┬────┘
        │             │             │
   ┌────┴─────────────┴─────────────┴────┐
   │          LARAVEL BACKEND            │
   │  Controllers · Models · Services    │
   └────────────────┬────────────────────┘
                    │
   ┌────────────────┴────────────────────┐
   │          MySQL DATABASE             │
   │  Properties · Rentals · Payments    │
   │  Users · Galleries · Settings       │
   └─────────────────────────────────────┘
```

---

## 🚀 Instalasi & Setup Lokal

### Prasyarat
- PHP >= 8.3
- Composer
- Node.js & NPM
- MySQL 8.0
- Git

### Langkah Instalasi

**1. Clone Repository**
```bash
git clone https://github.com/cimaaorieensa-hue/Telkom-Property-Management-System.git
cd Telkom-Property-Management-System
```

**2. Install Dependencies**
```bash
composer install
npm install
```

**3. Konfigurasi Environment**
```bash
copy .env.example .env
```
Sesuaikan kredensial database pada file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=property
DB_USERNAME=root
DB_PASSWORD=
```

**4. Generate Application Key**
```bash
php artisan key:generate
```

**5. Jalankan Migrasi & Seeder**
```bash
php artisan migrate
php artisan db:seed
```

**6. Link Storage**
```bash
php artisan storage:link
```

**7. Build Assets & Jalankan Server**
```bash
npm run build
php artisan serve
```

Aplikasi dapat diakses melalui: **`http://localhost:8000`**

---

## 📁 Struktur Direktori

```
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Controller panel admin
│   │   ├── Manager/        # Controller panel manager
│   │   ├── User/           # Controller panel user/penyewa
│   │   ├── Auth/           # Controller autentikasi
│   │   └── PublicController.php
│   ├── Models/             # Eloquent models
│   ├── Services/           # Business logic (Midtrans, Duitku)
│   └── Middleware/         # Role-based access control
├── database/
│   ├── migrations/         # Schema database
│   └── seeders/            # Data awal (admin, manager, properti)
├── resources/views/
│   ├── admin/              # Views panel admin
│   ├── manager/            # Views panel manager
│   ├── user/               # Views panel user
│   ├── public/             # Views halaman publik
│   ├── auth/               # Views login & register
│   └── layouts/            # Layout templates & sidebar
├── routes/
│   └── web.php             # Definisi semua route
├── docs/                   # Screenshot dokumentasi
└── public/                 # Assets publik
```

---

## 👥 Tim Pengembang

Dikembangkan sebagai bagian dari program **Kerja Praktik** di **Telkom Property Area Sumbar Jambi**.

---

## 📄 Lisensi

Proyek ini dikembangkan untuk keperluan internal dan akademik. Hak cipta © 2026 Telkom Property Area Sumbar Jambi.
