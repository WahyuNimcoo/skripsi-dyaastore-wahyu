# Analisis Kebutuhan Sistem — Website E-Commerce Dyaa Store

> **Acuan Skripsi**: BAB III §3.1.2 (Analisis Kebutuhan)
> **Penulis**: Wahyu Akbar Pratama Siregar (NIM 0110122029) — Sistem Informasi STT-NF 2026
> **Tujuan dokumen ini**: menjadi *baseline* kebutuhan sistem yang ditarik balik (*traceability*) di **BAB IV** sebagai dasar implementasi & pengujian.

---

## 1. Profil Sistem


| Aspek         | Spesifikasi                                                         |
| ------------- | ------------------------------------------------------------------- |
| Nama Sistem   | Website E-Commerce **Dyaa Store**                                   |
| Domain Bisnis | Penjualan virtual currency **Robux** untuk platform Roblox          |
| Aktor Sistem  | **Pengunjung**, **Customer** (terdaftar), **Admin** (pemilik toko)  |
| Platform      | WordPress 6.9.x + WooCommerce + Elementor (parent: Hello Elementor) |
| Pengembangan  | Metode **Waterfall** dengan pendekatan R&D                          |
| Pengujian     | **Black Box Testing** + **Skala Likert 5**                          |
| Repositori    | `c:\laragon\www\dyaastore` (development)                            |


---

## 2. Kebutuhan Fungsional (KF)

Tabel berikut adalah daftar KF beserta status implementasi dan **referensi file** yang menjadi bukti realisasi pada BAB IV.

### 2.1 Akses & Navigasi


| Kode  | Aktor      | Kebutuhan                                                                                                | Status | Referensi Implementasi                                                                     |
| ----- | ---------- | -------------------------------------------------------------------------------------------------------- | ------ | ------------------------------------------------------------------------------------------ |
| KF-01 | Pengunjung | Sistem menampilkan halaman Beranda dengan hero, paket Robux populer, kategori, FAQ, dan testimoni        | ✅      | `templates/part-home-main.php`, `front-page.php`                                           |
| KF-02 | Pengunjung | Sistem menyediakan **sidebar navigasi** 3 grup (Menu / Navigasi / Pengguna)                              | ✅      | `functions.php → dyaastore_render_sidebar()`                                               |
| KF-03 | Pengunjung | Sistem menyediakan **bilah navigasi atas** (search produk, cart, theme toggle, Login, Daftar)            | ✅      | `functions.php → dyaastore_render_top_navigation()`                                        |
| KF-04 | Pengunjung | Sistem menyediakan **bottom navigation 4 ikon** untuk perangkat mobile                                   | ✅      | `functions.php → dyaastore_render_bottom_nav()`                                            |
| KF-05 | Pengunjung | Sistem menyediakan **toggle Mode Terang / Mode Gelap** (pill switch, tersinkron antara topbar & sidebar) | ✅      | `style.css` (kelas `.dyaa-theme-toggle`), `assets/js/dyaastore.js` (fungsi `syncToggle()`) |


### 2.2 Halaman Statis (Konten Pendukung)


| Kode  | Aktor      | Kebutuhan                                                                      | Status | Referensi Implementasi                                     |
| ----- | ---------- | ------------------------------------------------------------------------------ | ------ | ---------------------------------------------------------- |
| KF-06 | Pengunjung | Halaman **Tentang Kami** (visi, misi, alasan memilih)                          | ✅      | `wp-content/mu-plugins/dyaastore-pages.php` slug `tentang` |
| KF-07 | Pengunjung | Halaman **FAQ** (8 pertanyaan umum)                                            | ✅      | `dyaastore-pages.php` slug `faq`                           |
| KF-08 | Pengunjung | Halaman **Syarat & Ketentuan**                                                 | ✅      | `dyaastore-pages.php` slug `syarat-ketentuan`              |
| KF-09 | Pengunjung | Halaman **Kebijakan Privasi** (terdaftar otomatis sebagai *WP Privacy Policy*) | ✅      | `dyaastore-pages.php` slug `kebijakan-privasi`             |
| KF-10 | Pengunjung | Halaman **Dukungan Pelanggan** dengan jam operasional & kontak                 | ✅      | `dyaastore-pages.php` slug `dukungan`                      |


### 2.3 Katalog Produk


| Kode  | Aktor      | Kebutuhan                                                                                          | Status | Referensi Implementasi                                                       |
| ----- | ---------- | -------------------------------------------------------------------------------------------------- | ------ | ---------------------------------------------------------------------------- |
| KF-11 | Pengunjung | Sistem menampilkan halaman **Shop** (grid paket Robux)                                             | ✅      | WooCommerce native (`/shop/`)                                                |
| KF-12 | Pengunjung | Sistem menampilkan **detail produk** beserta harga, deskripsi, dan tombol Add to Cart              | ✅      | WooCommerce native (`/product/{slug}/`)                                      |
| KF-13 | Pengunjung | Sistem menyediakan **6 kategori Robux** (Paket Hemat, Voucher, Gamepass, Premium, Bundle, Limited) | ✅      | Shortcode `[dyaa_categories]` + auto-create di `dyaastore-seeder.php`        |
| KF-14 | Pengunjung | Sistem menampilkan **Flash Sale** dengan countdown timer                                           | ✅      | Shortcode `[dyaa_flashsale]` + countdown JS di `dyaastore.js`                |
| KF-15 | Pengunjung | Sistem menyediakan **pencarian produk** di topbar                                                  | ✅      | Form search Woo (`post_type=product`) di `dyaastore_render_top_navigation()` |


### 2.4 Autentikasi & Akun


| Kode  | Aktor      | Kebutuhan                                                                                                          | Status | Referensi Implementasi                                  |
| ----- | ---------- | ------------------------------------------------------------------------------------------------------------------ | ------ | ------------------------------------------------------- |
| KF-16 | Pengunjung | Sistem menyediakan **halaman Daftar / Login** dengan tampilan *split-screen* + tab (mengganti default WooCommerce) | ✅      | `woocommerce/myaccount/form-login.php` (override)       |
| KF-17 | Customer   | Sistem dapat melakukan **registrasi** akun baru (paksa enable lewat filter)                                        | ✅      | `functions.php → dyaastore_force_enable_registration()` |
| KF-18 | Customer   | Sistem dapat melakukan **login** dan **logout**                                                                    | ✅      | WooCommerce native + redirect ke `?action=register`     |
| KF-19 | Customer   | Sistem menyediakan **dashboard akun** (riwayat pesanan, edit profil)                                               | ✅      | WooCommerce native `/my-account/`                       |


### 2.5 Keranjang & Checkout


| Kode  | Aktor    | Kebutuhan                                                                                                                                                | Status                  | Referensi Implementasi                                                               |
| ----- | -------- | -------------------------------------------------------------------------------------------------------------------------------------------------------- | ----------------------- | ------------------------------------------------------------------------------------ |
| KF-20 | Customer | Sistem dapat **menambah produk ke keranjang** dengan badge counter di topbar                                                                             | ✅                       | WooCommerce native + custom badge `.dyaa-cart-badge`                                 |
| KF-21 | Customer | Sistem dapat **mengubah jumlah** atau **menghapus** item di keranjang                                                                                    | ✅                       | WooCommerce native                                                                   |
| KF-22 | Customer | Halaman **Checkout** menampilkan ringkasan pesanan + form data pembeli                                                                                   | ✅                       | WooCommerce native                                                                   |
| KF-23 | Customer | Form Checkout berisi field tambahan **Username Roblox** (wajib, validasi 3–20 karakter alfanumerik+underscore)                                           | ✅                       | `functions.php → dyaastore_add_roblox_field()` + `dyaastore_validate_roblox_field()` |
| KF-24 | Customer | Sistem menyediakan minimal satu metode pembayaran aktif (**Direct Bank Transfer**); metode lain (e-wallet, QRIS, VA) opsional via plugin payment gateway | ✅ default / ⚠️ opsional | WooCommerce → Settings → Payments                                                    |
| KF-25 | Customer | Sistem menampilkan halaman **Thank You** dengan Username Roblox tercantum                                                                                | ✅                       | `functions.php → dyaastore_display_roblox_in_thankyou()`                             |


### 2.6 Manajemen oleh Admin


| Kode  | Aktor | Kebutuhan                                                                                              | Status | Referensi Implementasi                                                         |
| ----- | ----- | ------------------------------------------------------------------------------------------------------ | ------ | ------------------------------------------------------------------------------ |
| KF-26 | Admin | Sistem menyediakan **dashboard admin** WordPress (default) untuk login admin                           | ✅      | WordPress native `/wp-admin/`                                                  |
| KF-27 | Admin | Admin dapat **menambah/ubah/hapus** produk Robux (otomatis Virtual product)                            | ✅      | WooCommerce native + `functions.php → dyaastore_default_product_virtual()`     |
| KF-28 | Admin | Admin dapat melihat **daftar pesanan** beserta kolom **Username Roblox** (tanpa harus klik tiap order) | ✅      | `mu-plugins/dyaastore-helpers.php → dyaastore_orders_column()` (legacy + HPOS) |
| KF-29 | Admin | Admin dapat melihat **detail pesanan** termasuk Username Roblox di sidebar order                       | ✅      | `functions.php → dyaastore_display_roblox_in_admin()`                          |
| KF-30 | Admin | Admin dapat **mengubah status pesanan** (Pending → Processing → Completed)                             | ✅      | WooCommerce native                                                             |
| KF-31 | Admin | Sistem menampilkan **dashboard widget ringkasan** (jumlah produk & order) di halaman utama wp-admin    | ✅      | `mu-plugins/dyaastore-helpers.php → dyaastore_dashboard_widget()`              |


### 2.7 Notifikasi & Komunikasi


| Kode  | Aktor      | Kebutuhan                                                                                | Status | Referensi Implementasi                                           |
| ----- | ---------- | ---------------------------------------------------------------------------------------- | ------ | ---------------------------------------------------------------- |
| KF-32 | Customer   | Sistem mengirim **email konfirmasi** pesanan baru ke customer (termasuk Username Roblox) | ✅      | WooCommerce native email + `dyaastore_display_roblox_in_email()` |
| KF-33 | Admin      | Sistem mengirim **email pemberitahuan** pesanan baru ke admin                            | ✅      | WooCommerce native email                                         |
| KF-34 | Customer   | Sistem mengirim **email pemberitahuan** ketika status pesanan berubah ke *Completed*     | ✅      | WooCommerce native email                                         |
| KF-35 | Pengunjung | Sistem menyediakan **floating WhatsApp button** dengan pesan pre-filled                  | ✅      | `functions.php → dyaastore_render_whatsapp_button()`             |
| KF-36 | Pengunjung | Sistem menampilkan **live transaction toast** (social proof) di pojok kiri-bawah         | ✅      | `functions.php → dyaastore_render_live_toast()` + JS rotator     |


**Total KF**: 36 (semua ✅ kecuali KF-24 sebagian opsional). Daftar ini menjadi pondasi tabel pengujian Black Box di `docs/03-pengujian-blackbox.md`.

---

## 3. Kebutuhan Non-Fungsional (KNF)


| Kode   | Aspek                    | Kebutuhan                                                                                               | Tolok Ukur                                                                            | Verifikasi                                               |
| ------ | ------------------------ | ------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------- | -------------------------------------------------------- |
| KNF-01 | **Keamanan**             | Validasi input pada form custom; tidak meminta password Roblox                                          | Field Username Roblox → regex `^[A-Za-z0-9_]+$`, 3–20 karakter                        | Lihat `dyaastore_validate_roblox_field()`                |
| KNF-02 | **Usability**            | Antarmuka mudah digunakan oleh pengguna awam (target Skala Likert ≥ 4 / Sangat Baik)                    | Persentase ≥ 80% pada kuesioner                                                       | `docs/04-kuesioner-likert.md`                            |
| KNF-03 | **Performance**          | Halaman Beranda dimuat ≤ 3 detik pada koneksi 4G                                                        | Lighthouse Performance score ≥ 70                                                     | Test manual Chrome DevTools                              |
| KNF-04 | **Compatibility**        | Tampilan responsif: **Desktop** (≥ 1024px), **Tablet** (768px–1023px), **Mobile** (≤ 767px)             | Tidak ada horizontal scroll di breakpoint manapun                                     | Inspect DevTools + screenshot 3 viewport                 |
| KNF-05 | **Maintainability**      | Sistem dibangun di atas WordPress + child theme sehingga kode kustom terpisah dari core                 | Semua override ada di `wp-content/themes/dyaastore-child/` & `wp-content/mu-plugins/` | Struktur folder repo                                     |
| KNF-06 | **Reliability**          | Data transaksi tersimpan di database MySQL; auto-create halaman & produk dilindungi *flag* (idempotent) | `DYAA_PAGES_FLAG`, `DYAA_SEEDER_FLAG`                                                 | `mu-plugins/dyaastore-pages.php`, `dyaastore-seeder.php` |
| KNF-07 | **Aesthetic & Branding** | Sistem mendukung **Mode Gelap (default)** & **Mode Terang** dengan pre-paint script anti-flash          | `localStorage('dyaa-theme')` + `wp_head` priority 1                                   | `functions.php → dyaastore_pre_paint_theme()`            |


---

## 4. Batasan Sistem (sesuai BAB I §1.4 Skripsi)

1. ❌ **Tanpa integrasi API resmi Roblox** — pengiriman Robux dilakukan **manual** oleh admin (gamepass / group payout).
2. ❌ **Tanpa pengembangan backend custom** di luar ekosistem WordPress (semua logic ditulis sebagai child theme & mu-plugins).
3. ✅ Pembayaran terbatas pada **Direct Bank Transfer** (bawaan WooCommerce). E-wallet/QRIS/VA tersedia jika admin menambah plugin gateway pihak ketiga (mis. Tripay, Duitku, Midtrans) — di luar cakupan inti skripsi.
4. ✅ Fitur dibatasi pada inti e-commerce + halaman pendukung (FAQ, Tentang, Syarat, Privasi, Dukungan).
5. ❌ **Tanpa** sistem rekomendasi otomatis, integrasi marketplace pihak ketiga, atau analitik lanjutan.
6. ✅ Lingkungan target adalah **server lokal Laragon** (Apache + MySQL + PHP) dengan opsi deploy ke hosting cPanel.

---

## 5. Kebutuhan Pengguna (User Requirements)

### 5.1 Pengunjung (sebelum login)

- Bisa langsung melihat paket Robux & harga tanpa harus daftar.
- Bisa membaca FAQ untuk meyakinkan diri sebelum transaksi.
- Bisa menghubungi admin via WhatsApp untuk pertanyaan cepat.
- Mendapat *social proof* lewat testimoni & live transaction toast.

### 5.2 Customer (terdaftar)

- Pendaftaran cepat lewat form *split-screen* dengan tab Login/Daftar.
- Proses pembelian transparan: harga sudah termasuk pajak, tidak ada *hidden fee*.
- Robux diterima ≤ 10 menit pada jam kerja (08.00–22.00 WIB).
- Bisa cek riwayat pesanan kapan pun di **My Account**.
- Mendapat email konfirmasi otomatis di setiap perubahan status pesanan.

### 5.3 Admin (Pemilik Dyaa Store)

- Pesanan tercatat otomatis di WooCommerce → Orders.
- Username Roblox terlihat **langsung di kolom listing pesanan** (tanpa buka detail).
- Notifikasi email setiap pesanan baru.
- Dashboard widget ringkasan jumlah produk & order pada halaman utama wp-admin.

---

## 6. Traceability Matrix (Ringkasan untuk BAB IV)

Setiap KF di atas akan dipetakan kembali pada **3 lapis**:

```
KF-XX (Analisis Kebutuhan)
   │
   ├── Implementasi Antarmuka  ─── docs/06-implementasi-antarmuka.md
   ├── Implementasi Fitur/Code ─── docs/07-implementasi-fitur.md
   └── Pengujian Black Box     ─── docs/03-pengujian-blackbox.md
```

Diagram ini memastikan **tidak ada KF yang tidak diimplementasi** dan **tidak ada test case yang tidak punya rujukan KF**.