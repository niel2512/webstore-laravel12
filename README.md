# Webstore Laravel 12, Spatie & Livewire

Sebuah proyek demonstrasi Webstore (Toko Online) modern yang dibangun menggunakan TALL Stack (TailwindCss v4, Alpine.js, Laravel, Livewire)  dengan teknologi terbaru dari ekosistem Laravel, termasuk Livewire 3, Filament 3, dan Laravel Reverb untuk fungsionalitas real-time.

**(Catatan: Jangan lupa tambahkan screenshot aplikasi Anda di sini!)**
`[Gambar Screenshot Aplikasi Webstore Anda]`

## 🚀 Fitur Utama

* **Katalog Produk:** Menampilkan daftar produk dengan pencarian dan paginasi.
* **Halaman Detail Produk:** Informasi lengkap mengenai produk.
* **Keranjang Belanja (Shopping Cart):** Fungsionalitas tambah, update, dan hapus item dari keranjang belanja yang interaktif.
* **Proses Checkout:** Form checkout multi-langkah dengan validasi, pemilihan alamat, dan perhitungan ongkos kirim.
* **Panel Admin (Filament):** Panel admin yang kuat untuk mengelola pesanan, produk, dan data lainnya.
* **Notifikasi Real-time (Reverb):** Notifikasi toast akan muncul di panel admin setiap kali ada pesanan baru masuk, tanpa perlu me-refresh halaman.
* **Arsitektur Modern:** Menggunakan Data Transfer Objects (DTO) dengan `spatie/laravel-data` dan State Machine Pattern dengan `spatie/laravel-model-states` untuk mengelola status pesanan.

## ✨ Teknologi yang Digunakan

* **Backend:** [Laravel 11](https://laravel.com/)
* **Frontend:** [Livewire 3](https://livewire.laravel.com/), [Alpine.js](https://alpinejs.dev/), [Tailwind CSS](https://tailwindcss.com/)
* **Admin Panel:** [Filament 3](https://filamentphp.com/)
* **Real-time & WebSockets:** [Laravel Reverb](https://laravel.com/docs/11.x/reverb)
* **Arsitektur Data:** [Spatie Laravel-Data](https://spatie.be/docs/laravel-data/v4/introduction)
* **State Management:** [Spatie Laravel-Model-States](https://spatie.be/docs/laravel-model-states/v2/introduction)
* **Database:** MySQL

## 📦 Instalasi & Konfigurasi

Berikut adalah langkah-langkah untuk menjalankan proyek ini di lingkungan lokal Anda.

1.  **Clone Repositori**
    ```bash
    git clone [https://github.com/niel2512/webstore-laravel12.git](https://github.com/niel2512/webstore-laravel12.git)
    cd webstore-laravel12
    ```

2.  **Instal Dependensi Composer**
    ```bash
    composer install
    ```

3.  **Instal Dependensi NPM**
    ```bash
    npm install
    ```

4.  **Buat File `.env`**
    Salin file `.env.example` menjadi `.env`.
    ```bash
    cp .env.example .env
    ```

5.  **Generate Kunci Aplikasi**
    ```bash
    php artisan key:generate
    ```

6.  **Konfigurasi Database & Reverb**
    Buka file `.env` dan sesuaikan pengaturan berikut dengan konfigurasi lokal Anda:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=webstore
    DB_USERNAME=root
    DB_PASSWORD=

    REVERB_APP_ID=
    REVERB_APP_KEY=
    REVERB_APP_SECRET=
    ```
    Anda bisa mengisi nilai Reverb dengan menjalankan:
    ```bash
    php artisan reverb:install
    ```

7.  **Jalankan Migrasi Database**
    Perintah ini akan membuat semua tabel yang diperlukan di database Anda.
    ```bash
    php artisan migrate
    ```

8.  **Jalankan Seeder (Penting!)**
    Seeder ini akan mengisi data wilayah (provinsi, kota, dll.) dari API eksternal dan membuat beberapa data dummy.
    ```bash
    php artisan db:seed
    ```
    *(Catatan: Proses `RegionSeeder` mungkin memakan waktu beberapa menit karena mengambil data dari internet. Pastikan koneksi internet Anda stabil.)*

9.  **Buat Storage Link**
    ```bash
    php artisan storage:link
    ```

10. **Compile Aset Frontend**
    ```bash
    npm run dev
    ```

11. **Jalankan Server Reverb**
    Buka terminal baru dan biarkan proses ini berjalan.
    ```bash
    php artisan reverb:start
    ```

12. **Jalankan Queue Worker**
    Buka terminal baru lagi dan biarkan proses ini berjalan untuk menangani event.
    ```bash
    php artisan queue:work
    ```

13. **Jalankan Aplikasi**
    Kembali ke terminal pertama dan jalankan server pengembangan Laravel.
    ```bash
    php artisan serve
    ```
    Aplikasi Anda sekarang dapat diakses di `http://127.0.0.1:8000`.

## 🔑 Panel Admin

Untuk mengakses panel admin, Anda perlu membuat user terlebih dahulu.

1.  Jalankan perintah berikut untuk membuat user admin baru:
    ```bash
    php artisan make:filament-user
    ```
    Anda akan diminta untuk memasukkan nama, email, dan password.

2.  Akses panel admin di `/admin` dan login menggunakan kredensial yang baru saja Anda buat.

## 🤝 Kontribusi

Kontribusi dalam bentuk apapun sangat diterima. Silakan buat *fork* dari repositori ini, buat *branch* baru untuk fitur Anda, dan kirimkan *Pull Request*.

## 📄 Lisensi

Proyek ini berada di bawah Lisensi MIT. Lihat file [LICENSE](LICENSE) untuk detail lebih lanjut.
