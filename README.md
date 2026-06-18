# Telkom Property Management System

Sebuah sistem informasi manajemen properti berbasis web yang dibangun menggunakan **Laravel**. Aplikasi ini dirancang untuk mempermudah pengelolaan properti, penyewaan, galeri, serta pengaturan antarmuka publik secara dinamis dengan dukungan *multi-role dashboard*.

## 🌟 Fitur Utama

- **Manajemen Properti**: Tambah, edit, hapus, dan kelola daftar properti secara detail.
- **Manajemen Penyewaan**: Pemantauan status sewa, pencatatan transaksi, dan pengelolaan data penyewa.
- **Galeri Properti**: Kelola foto dan aset visual untuk setiap properti.
- **Pengaturan Sistem Dinamis**: Ubah logo, nama brand, dan informasi kontak publik langsung dari dashboard tanpa perlu mengubah kode.
- **Multi-Role Access Control**: Terdapat pembagian hak akses untuk **Admin**, **Manager**, dan tampilan **Public** (landing page).

## 💻 Tech Stack

- **Framework:** Laravel (PHP)
- **Frontend:** Blade Templating
- **Database:** MySQL

## 🚀 Instalasi & Setup Lokal

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di mesin lokal Anda:

1. **Clone repository ini**
   ```bash
   git clone https://github.com/salmaputriarisa-glitch/propery-telkom.git
   cd propery-telkom
   ```

2. **Install dependency PHP (Composer)**
   ```bash
   composer install
   ```

3. **Install dependency Frontend (NPM)**
   ```bash
   npm install
   npm run build
   ```

4. **Konfigurasi Environment**
   Salin file `.env.example` menjadi `.env` dan sesuaikan kredensial database Anda.
   ```bash
   copy .env.example .env
   ```

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Jalankan Migrasi Database (dan Seeder jika ada)**
   ```bash
   php artisan migrate
   ```

7. **Link Storage (wajib untuk fitur upload gambar & galeri)**
   ```bash
   php artisan storage:link
   ```

8. **Jalankan Development Server**
   ```bash
   php artisan serve
   ```
   Aplikasi sekarang dapat diakses melalui `http://localhost:8000`.
