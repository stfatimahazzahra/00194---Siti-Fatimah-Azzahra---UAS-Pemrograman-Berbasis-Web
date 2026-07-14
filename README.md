# RallyPlay - Sistem Booking Lapangan Badminton

Aplikasi booking lapangan badminton berbasis PHP native (PDO) + MySQL + Bootstrap 5.

## Fitur

**Publik (tanpa login)**
- Lihat daftar & detail lapangan (foto, fasilitas, harga)
- Cek jadwal yang sudah terisi per tanggal
- Booking sebagai tamu (guest) dengan nama & no HP

**User (setelah login/daftar)**
- Booking lapangan (tersimpan ke akun)
- Lihat riwayat booking & status pembayaran
- Batalkan booking yang masih *pending*

**Admin**
- Dashboard statistik (total lapangan, booking, pendapatan, dsb)
- CRUD Lapangan (nama, lokasi, harga, deskripsi, foto, fasilitas, status)
- Kelola Booking (ubah status: pending/confirmed/completed/cancelled, ubah status pembayaran, hapus)
- CRUD Fasilitas (nama + icon Font Awesome)

## Instalasi

1. **Salin folder ini** ke folder web server lokal kamu:
   - XAMPP: `htdocs/booking-badminton`
   - Laragon: `www/booking-badminton`
   - MAMP: `htdocs/booking-badminton`

2. **Buat database & import struktur.**
   - Buka phpMyAdmin, buat database baru bernama `booking_badminton`
   - Klik tab **Import**, pilih file `database/booking_badminton.sql`, klik **Go/Kirim**

3. **Atur koneksi database** di `config/database.php`:
   ```php
   private static $host = 'localhost';       // atau 'localhost:8889' untuk MAMP
   private static $db_name = 'booking_badminton';
   private static $username = 'root';
   private static $password = '';            // sesuaikan dengan MySQL lokal kamu
   ```
   - XAMPP / Laragon default: username `root`, password **kosong**
   - MAMP default: host `localhost:8889`, username `root`, password `root`

4. **Pastikan folder upload bisa ditulis** (permission write) untuk:
   `assets/uploads/courts/`

5. **Akses aplikasi:**
   - Situs utama: `http://localhost/booking-badminton/index.php`
   - Login admin: `http://localhost/booking-badminton/login.php`
     - Email: `admin@rallyplay.com`
     - Password: sesuai yang diset saat dump dibuat (hash bcrypt di database).
       Jika tidak tahu password aslinya, reset lewat query berikut di phpMyAdmin
       (contoh set password baru jadi `admin123`):
       ```sql
       UPDATE users SET password = '$2y$10$qa9Ku8wDnk5XM4bIaV1eeuP2G2w8Z1uTmWGslCnAZn9UsjDzhsIgK'
       WHERE email = 'admin@rallyplay.com';
       -- hash di atas = admin123
       ```

## Struktur Folder

```
booking-badminton/
├── admin/                  # Panel admin (dashboard, CRUD lapangan/booking/fasilitas)
│   └── includes/           # Header & footer khusus admin (sidebar)
├── assets/
│   ├── css/style.css
│   └── uploads/courts/     # Foto lapangan hasil upload admin
├── config/
│   ├── database.php        # Koneksi PDO
│   └── session.php         # Helper session & auth guard
├── includes/
│   ├── header.php / footer.php
│   └── functions.php       # Helper umum (flash message, format rupiah, dll)
├── database/
│   └── booking_badminton.sql
├── index.php, courts.php, court-detail.php   # Halaman publik
├── login.php, register.php, logout.php       # Autentikasi
├── dashboard.php, cancel-booking.php         # Area user
├── booking-process.php, booking-success.php  # Proses booking
└── get-slots.php                              # Endpoint AJAX cek jadwal
```

## Catatan Keamanan (untuk pengembangan lebih lanjut)

- Semua query sudah pakai *prepared statement* (PDO) untuk mencegah SQL Injection.
- Password disimpan dengan `password_hash()` (bcrypt).
- Untuk produksi, tambahkan: validasi CSRF token, rate-limiting login, dan validasi upload file yang lebih ketat (cek MIME type asli, bukan hanya ekstensi).
