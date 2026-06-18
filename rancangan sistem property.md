DOKUMEN ANALISIS KEBUTUHAN SISTEM
Perancangan dan Pembangunan Sistem Informasi Manajemen Data Properti Berbasis Web pada Telkom Property
________________________________________
1. Pendahuluan
1.1 Latar Belakang
Telkom Property sebagai unit pengelola aset properti membutuhkan sistem informasi yang mampu mengelola data properti secara terstruktur, terintegrasi, dan real-time. Dalam pelaksanaannya, pengelolaan data seperti informasi properti, harga sewa, status ketersediaan, serta laporan penyewaan masih berpotensi dilakukan secara manual atau belum terpusat dalam satu sistem terintegrasi.
Hal tersebut dapat menyebabkan:
• Ketidakkonsistenan data properti
• Keterlambatan pembaruan status sewa
• Kesulitan monitoring laporan pendapatan
• Risiko kesalahan pencatatan data
Oleh karena itu, diperlukan sistem informasi manajemen data properti berbasis web yang mampu mengelola data secara terpusat serta menyediakan informasi properti kepada publik secara transparan dan akurat.

1.2 Tujuan Pengembangan Sistem
Tujuan dari pengembangan sistem ini adalah:
1. Membangun sistem terintegrasi untuk pengelolaan data properti Telkom Property.
2. Memudahkan admin dalam mengelola properti dan penyewaan.
3. Menyediakan fitur monitoring laporan bagi manajemen.
4. Menyediakan informasi properti yang dapat diakses publik tanpa login.
5. Meningkatkan efisiensi dan akurasi pengelolaan data properti.

1.3 Ruang Lingkup Sistem
Ruang lingkup sistem meliputi:
• Pengelolaan data properti oleh Admin.
• Monitoring laporan oleh Manager.
• Penyajian informasi properti kepada User/Penyewa.
• Informasi properti mencakup:
	o Nama properti
	o Alamat
	o Luas
	o Harga sewa (Rp/meter/bulan)
	o Status (Tersedia/Disewa)
	o Deskripsi
	o Galeri foto
	o Link Google Maps
• Sistem menyediakan kontak pengelola (WhatsApp dan Telepon).
• Sistem berbasis web menggunakan framework Laravel.
• Metode pengembangan menggunakan model Waterfall.

2. Permasalahan Sistem Berjalan
Permasalahan yang ditemukan pada sistem berjalan antara lain:
1. Data properti belum dikelola dalam sistem terpusat.
2. Status properti tidak selalu terupdate secara real-time.
3. Tidak tersedia dashboard monitoring bagi manajemen.
4. Informasi properti untuk publik belum terstruktur dengan baik.
5. Laporan pendapatan belum tersaji dalam bentuk grafik yang informatif.
Permasalahan tersebut menjadi dasar pengembangan sistem baru yang lebih terintegrasi.

3. Deskripsi Umum Sistem
3.1 Gambaran Umum
Sistem Informasi Manajemen Data Properti Berbasis Web pada Telkom Property merupakan sistem yang memiliki tiga jenis pengguna, yaitu:
1. Admin
2. Manager
3. User/Penyewa
Sistem ini memungkinkan admin mengelola data properti dan penyewaan, manager melakukan monitoring laporan, dan user melihat informasi properti secara publik tanpa login.

3.2 Karakteristik Pengguna
1. Admin
Memiliki hak akses penuh terhadap sistem.
Menu yang tersedia:
• Dashboard
• Data Properti
• Galeri Properti
• Penyewaan
• Laporan
• Pengaturan

2. Manager
Memiliki hak akses terbatas untuk monitoring.
Menu yang tersedia:
• Dashboard
• Laporan
Manager tidak dapat menambah, mengubah, atau menghapus data.

3. User / Penyewa
Tidak perlu login.
Menu yang tersedia:
• Beranda
• Daftar Properti
• Detail Properti
• Lokasi (Google Maps)
• Kontak Pengelola
User hanya dapat melihat informasi.

3.3 Lingkungan Operasional
• Sistem berjalan pada browser web modern.
• Backend menggunakan framework Laravel.
• Database menggunakan sistem relasional (MySQL).
• Sistem bersifat responsive (desktop & mobile).

4. Stakeholder Sistem
Stakeholder	Peran	Kepentingan
Admin	Pengelola sistem	Mengelola data properti & penyewaan
Manager	Monitoring	Mengawasi laporan dan pendapatan
User/Penyewa	Pengguna publik	Mendapatkan informasi properti
Manajemen	Pengambil keputusan	Evaluasi aset dan pendapatan

5. Analisis Kebutuhan Sistem

5.1 Kebutuhan Fungsional
A. Kebutuhan Fungsional Admin
1. Admin dapat login ke sistem.
2. Admin dapat melihat dashboard ringkasan properti.
3. Admin dapat menambah data properti.
4. Admin dapat mengedit data properti.
5. Admin dapat menghapus data properti.
6. Admin dapat mengelola harga sewa properti.
7. Admin dapat mengubah status properti (Tersedia/Disewa).
8. Admin dapat mengelola galeri foto (multi upload).
9. Admin dapat mengelola link Google Maps.
10. Admin dapat mengelola data penyewaan.
11. Admin dapat melihat laporan pendapatan bulanan.
12. Admin dapat melihat laporan pendapatan tahunan.
13. Admin dapat mengelola profil dan kontak pengelola.

B. Kebutuhan Fungsional Manager
1. Manager dapat login ke sistem.
2. Manager dapat melihat dashboard monitoring.
3. Manager dapat melihat laporan grafik bulanan.
4. Manager dapat melihat laporan grafik tahunan.
5. Manager dapat melihat distribusi status properti.
Manager tidak dapat melakukan perubahan data.

C. Kebutuhan Fungsional User / Penyewa
1. User dapat melihat daftar properti.
2. User dapat melihat detail properti.
3. User dapat melihat harga dan status properti.
4. User dapat melihat galeri foto.
5. User dapat melihat lokasi melalui Google Maps.
6. User dapat menghubungi pengelola melalui WhatsApp dan Telepon.
User tidak diwajibkan login dan hanya memiliki akses melihat data.

5.2 Kebutuhan Non-Fungsional
1. Keamanan
	o Autentikasi untuk Admin dan Manager.
	o Role-based access control.
2. Kinerja
	o Sistem mampu menampilkan data dengan respon cepat.
3. Usability
	o Antarmuka enterprise clean dan mudah digunakan.
4. Reliabilitas
	o Data tersimpan aman dalam database.
5. Kompatibilitas
	o Dapat diakses melalui browser modern dan perangkat mobile.

5.3 Prioritas Kebutuhan
Kebutuhan	Prioritas
Login Admin & Manager	High
CRUD Properti	High
Laporan Grafik	High
Galeri Multi Upload	Medium
Deskripsi Properti	Low

6. Kebutuhan Data
6.1 Data Properti
• ID Properti
• Nama Properti
• Alamat
• Luas
• Harga Sewa
• Status Properti
• Deskripsi
• Link Google Maps
6.2 Data Penyewaan
• ID Penyewaan
• Nama Penyewa
• Properti
• Tanggal Mulai
• Tanggal Selesai
• Status Penyewaan
6.3 Data Pengguna
• ID
• Nama
• Email
• Password
• Role (Admin/Manager)

7. Batasan Sistem
1. Sistem tidak mencakup pembayaran online.
2. Sistem tidak mencakup kontrak digital.
3. Sistem tidak menyediakan fitur chat internal.
4. Sistem hanya menyediakan kontak eksternal (WhatsApp & Telepon).

8. Kriteria Penerimaan Sistem
Sistem dinyatakan berhasil apabila:
1. Admin dapat mengelola data properti dan penyewaan dengan baik.
2. Manager dapat melihat laporan monitoring secara akurat.
3. User dapat melihat informasi properti secara lengkap.
4. Status properti tampil sesuai kondisi real-time.

9. Kesimpulan
Dokumen analisis kebutuhan ini menjadi dasar dalam perancangan dan pembangunan Sistem Informasi Manajemen Data Properti Berbasis Web pada Telkom Property menggunakan metode Waterfall.
Sistem diharapkan mampu meningkatkan efektivitas pengelolaan aset properti serta menyediakan informasi yang transparan dan akurat kepada publik.

