# BAB IV 4.1.1 — Implementasi Lingkungan & Panduan Instalasi

> **Acuan Skripsi**: BAB IV §4.1 (Implementasi Sistem)
> **Lingkungan**: Laragon Full + WordPress 6.9.x di `c:\laragon\www\dyaastore`
> **URL Lokal**: `https://dyaastore.test:8443/`
> **Tujuan dokumen ini**: panduan teknis yang **dapat direplikasi** oleh penguji/dosen — sesuai dengan apa yang sudah ditulis pada BAB IV §4.1.1 skripsi.

---

## 1. Spesifikasi Lingkungan (Hardware & Software)


| Komponen           | Versi / Spesifikasi                                   |
| ------------------ | ----------------------------------------------------- |
| Sistem Operasi     | Windows 10 / 11 64-bit                                |
| Web Server         | Apache 2.4.x (bawaan Laragon Full)                    |
| Database           | MariaDB 10.x / MySQL 8.x                              |
| Bahasa             | PHP 8.1+                                              |
| CMS                | WordPress 6.9.x                                       |
| Plugin Inti        | WooCommerce ≥ 9.x, Elementor ≥ 3.x, Akismet (default) |
| Tema Parent        | Hello Elementor                                       |
| Tema Anak          | `dyaastore-child` (custom — dibuat dalam skripsi)     |
| Browser Pengujian  | Chrome 130+, Firefox 130+, Safari 17+, Edge 130+      |
| Resolusi Pengujian | Desktop 1920×1080, Tablet 768×1024, Mobile 375×667    |


> Kebutuhan minimum hardware: prosesor dual-core, RAM 4 GB, storage 2 GB free. Selama pengembangan dipakai laptop dengan Intel i5 / Ryzen 5, RAM 8 GB.

---

## 2. Struktur Folder Custom

Hanya folder berikut yang dimodifikasi/dibuat dalam skripsi (tidak menyentuh core WordPress):

```
c:\laragon\www\dyaastore\
├── docs/                                    ← Dokumentasi skripsi (BAB III & IV)
│   ├── 01-analisis-kebutuhan.md
│   ├── 02-perancangan-uml.md
│   ├── 03-pengujian-blackbox.md
│   ├── 04-kuesioner-likert.md
│   ├── 05-panduan-implementasi.md           ← (file ini)
│   ├── 06-implementasi-antarmuka.md
│   └── 07-implementasi-fitur.md
│
├── wp-content/themes/dyaastore-child/       ← Child theme custom
│   ├── style.css                            ← Design system Dyaa Store
│   ├── functions.php                        ← Hooks, shortcode, custom field Roblox
│   ├── front-page.php                       ← Render homepage di URL "/"
│   ├── inc/icons.php                        ← Library SVG icons
│   ├── assets/
│   │   ├── img/                             ← 6 gambar (hero, logo, robux x4)
│   │   └── js/dyaastore.js                  ← JS interaktif (toggle, countdown, toast)
│   ├── templates/
│   │   ├── template-homepage.php            ← Page template "Dyaa Store — Homepage"
│   │   └── part-home-main.php               ← Partial markup beranda
│   └── woocommerce/myaccount/form-login.php ← Override auth split-screen
│
└── wp-content/mu-plugins/                   ← Must-Use plugins
    ├── dyaastore-helpers.php                ← Kolom Username Roblox di order list + dashboard widget
    ├── dyaastore-pages.php                  ← Auto-create 5 halaman statis
    └── dyaastore-seeder.php                 ← Auto-create 6 kategori + 8 produk Robux demo
```

---

## 3. Langkah Instalasi (4 Step)

> **Catatan penting**: berkat 3 *mu-plugins* yang sudah ditulis, sebagian besar isi (5 halaman statis + 6 kategori produk + 8 produk Robux dengan gambar) dibuat **otomatis** saat admin pertama kali masuk ke wp-admin. Kamu **tidak perlu** input manual.

### STEP 1 — Aktifkan Plugin WooCommerce

1. Login ke `https://dyaastore.test:8443/wp-admin` sebagai admin.
2. Sidebar kiri → **Plugins → Installed Plugins**.
3. Cari **WooCommerce** → klik **Activate**.
4. Setup Wizard WooCommerce muncul:


| Field                | Isian                               |
| -------------------- | ----------------------------------- |
| Country / Region     | Indonesia                           |
| Currency             | IDR — Indonesian Rupiah (Rp)        |
| Industry             | Other                               |
| Product types        | Downloads / Virtual products        |
| Sudah jualan online? | No                                  |
| Theme step           | **Skip** (kita pakai theme sendiri) |


1. Setelah wizard selesai, masuk ke **WooCommerce → Settings → General**, set:


| Setting            | Nilai                          |
| ------------------ | ------------------------------ |
| Currency position  | Left with space (`Rp 125.000`) |
| Thousand separator | `.`                            |
| Decimal separator  | `,`                            |
| Number of decimals | `0`                            |


### STEP 2 — Aktifkan Plugin Elementor

1. **Plugins → Installed Plugins** → cari **Elementor** → klik **Activate**.
2. Skip akun Elementor saat diminta (klik **Maybe Later**).
3. Tema parent **Hello Elementor** akan otomatis terdeteksi (sudah ter-install di repo).

### STEP 3 — Aktifkan Child Theme `dyaastore-child`

1. **Appearance → Themes**.
2. Cari kartu **"Dyaa Store Child"** → klik **Activate**.

> Saat child theme aktif, `functions.php` akan langsung memuat: sidebar custom, topbar, bottom nav, footer global, custom field Roblox di checkout, semua shortcode, dan pre-paint script anti-flash light mode.

### STEP 4 — Refresh wp-admin Sekali untuk Trigger Auto-Seeders

1. Klik **Dashboard** di sidebar wp-admin (atau refresh tab admin).
2. Notice hijau akan muncul:
  - "Dyaa Store — N halaman statis baru telah dibuat (Tentang, FAQ, Syarat & Ketentuan, Kebijakan Privasi, Dukungan)."
  - "Dyaa Store — Kategori & 8 produk Robux demo berhasil dibuat."
3. Selesai. Buka tab incognito dan kunjungi `https://dyaastore.test:8443/` — beranda Dyaa Store sudah tampil lengkap.

---

## 4. Verifikasi Pasca-Aktivasi

Cek 5 indikator berikut untuk memastikan instalasi berhasil:


| #   | Verifikasi                           | Cara Cek                                        | Hasil yang Diharapkan                                                                                                  |
| --- | ------------------------------------ | ----------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- |
| 1   | **5 halaman statis ter-buat**        | wp-admin → **Pages**                            | Muncul: Tentang Dyaa Store, FAQ, Syarat & Ketentuan, Kebijakan Privasi, Dukungan Pelanggan                             |
| 2   | **6 kategori produk ter-buat**       | wp-admin → **Products → Categories**            | Muncul: Paket Hemat, Voucher Robux, Gamepass, Premium, Bundle, Limited                                                 |
| 3   | **8 produk Robux ter-buat**          | wp-admin → **Products**                         | Muncul 8 paket: 100, 400, 800, 1700, 4500, 10000 Robux + Bundle 2×800 + Limited 2200; tiap produk punya featured image |
| 4   | **Beranda dengan layout Dyaa Store** | Buka `https://dyaastore.test:8443/` (incognito) | Tampil: hero, flash sale countdown, 6 kategori, paket terlaris, cara pesan, stats, testimoni, FAQ, footer 4 kolom      |
| 5   | **Custom field Roblox di Checkout**  | Tambah produk → buka `/checkout/`               | Muncul section **🎮 Data Akun Roblox** dengan field "Username Roblox" required                                         |


---

## 5. Konfigurasi Tambahan (Opsional)

### 5.1 Atur Permalink ke `/post-name/`

1. **Settings → Permalinks** → pilih **Post name**.
2. **Save Changes**.

> Permalink struktur ini disarankan karena URL produk jadi `/product/100-robux/` (lebih SEO-friendly daripada `?p=123`).

### 5.2 Sesuaikan Konstanta Brand & WhatsApp

Edit `wp-content/themes/dyaastore-child/functions.php` (baris 25–28):

```php
define( 'DYAA_WHATSAPP_NUMBER', '6289515881150' ); // ganti dengan no. WA toko
define( 'DYAA_WHATSAPP_TEXT',   'Halo Dyaa Store, saya mau tanya tentang Robux' );
define( 'DYAA_BRAND_NAME',      'Dyaa Store' );
define( 'DYAA_BRAND_TAGLINE',   'Top Up Robux Termurah & Tercepat Se-Indonesia' );
```

Setelah disimpan, semua tombol WhatsApp (sticker mengambang, sidebar CTA, footer) otomatis pakai nomor baru.

### 5.3 Sesuaikan QRIS Payment Gateway (Bundled)

Child theme sudah membawa **custom payment gateway** `WC_Dyaa_QRIS_Gateway`
(file `wp-content/themes/dyaastore-child/inc/class-wc-dyaa-qris-gateway.php`)
yang **otomatis aktif** saat tema di-aktifkan pertama kali. Tidak perlu
install plugin tambahan.

**Alur kerja**:

1. Customer pilih "QRIS (Scan & Bayar)" di checkout → order status
   otomatis **On hold**.
2. Halaman *Thank You* menampilkan **gambar QR resmi Dyaa Store** + 4
   langkah pembayaran + tombol hijau **Kirim Bukti via WhatsApp**
   (pesan pre-filled dengan nomor order, total, dan Username Roblox).
3. Customer scan QR pakai e-wallet/m-banking apapun (DANA, OVO, GoPay,
   ShopeePay, BCA mobile, dll) → bayar → kirim screenshot ke WA admin.
4. Admin verifikasi → ubah status order ke **Processing/Completed**
   secara manual (sama seperti alur Direct Bank Transfer).

**Mengganti gambar QR (jika nama merchant berubah)**:

1. Cetak ulang QR di aplikasi merchant QRIS kamu (mis. Quick QR BCA,
   Mandiri Merchant, dll).
2. Replace file: `wp-content/themes/dyaastore-child/assets/img/dyaa-qris.png`
3. Atau ubah lewat **WooCommerce → Settings → Payments → QRIS — Scan &
   Bayar → Manage → URL gambar QRIS** (boleh isi URL eksternal).

**Mengubah merchant info dan nomor WhatsApp konfirmasi**:

Buka **WooCommerce → Settings → Payments → QRIS — Scan & Bayar → Manage**:


| Field             | Default                                 | Catatan                                        |
| ----------------- | --------------------------------------- | ---------------------------------------------- |
| Judul di Checkout | "QRIS (Scan & Bayar)"                   | Label radio button di payment methods          |
| Description       | Penjelasan singkat e-wallet & m-banking | Tampil di bawah label saat dipilih             |
| Instructions      | 4 langkah pembayaran                    | Render di thank-you page + email customer      |
| Merchant Name     | `dya store`                             | Ditampilkan di brand-line atas panel QR        |
| NMID              | `ID1026477730984`                       | National Merchant ID, dari QR fisik            |
| Nomor WhatsApp    | `${DYAA_WHATSAPP_NUMBER}`               | Format internasional tanpa `+` (mis 62895xxxx) |
| Order Status      | **On hold** (rekomendasi)               | Stok dikunci sampai admin verifikasi           |

> Gateway dibuat **idempoten**: opsi `dyaastore_qris_default_enabled`
> menjamin admin yang sengaja menonaktifkan QRIS tidak akan ter-overwrite
> ke `enabled=yes` setiap reload.

### 5.4 (Opsional) Plugin Payment Gateway Tambahan

Selain QRIS bundled di atas, admin dapat menambah plugin gateway pihak
ketiga jika ingin payment otomatis tanpa verifikasi manual:


| Plugin                   | Sumber                          | Catatan                        |
| ------------------------ | ------------------------------- | ------------------------------ |
| Tripay Payment Gateway   | tripay.co.id (sandbox tersedia) | Mendukung VA, e-wallet, QRIS   |
| Duitku Payment Gateway   | duitku.com                      | Mendukung VA, e-wallet, retail |
| Midtrans for WooCommerce | midtrans.com                    | Sandbox + production           |


> Plugin pihak ketiga ini **di luar inti skripsi** dan **opsional** sesuai
> batasan BAB I §1.4. QRIS bundled sudah cukup untuk seluruh skenario
> pembayaran yang diuji di BAB IV §4.2.

### 5.5 Re-trigger Seeders Manual (jika dibutuhkan)

Jika kategori / produk / halaman statis terhapus dan ingin di-buat ulang:


| Aksi                      | URL Trigger               | Hasil                                      |
| ------------------------- | ------------------------- | ------------------------------------------ |
| Re-seed kategori + produk | `/wp-admin/?dyaa_seed=1`  | Yang sudah ada di-skip; yang hilang dibuat |
| Re-seed halaman statis    | `/wp-admin/?dyaa_pages=1` | Yang sudah ada di-skip; yang hilang dibuat |


> Hanya admin (`manage_options`) yang bisa men-trigger.

---

## 6. Konfigurasi Akun Pelanggan (sudah otomatis)


| Pengaturan                                                    | Nilai                    | Sumber                                                                                  |
| ------------------------------------------------------------- | ------------------------ | --------------------------------------------------------------------------------------- |
| Allow customers to create an account on the "My account" page | ✅ Aktif                  | Dipaksa lewat `pre_option_woocommerce_enable_myaccount_registration` di `functions.php` |
| Allow registration on My Account                              | ✅ Aktif                  | Sda                                                                                     |
| Username/email register                                       | Email saja (default Woo) | WooCommerce → Settings → Accounts & Privacy                                             |


> Tombol **Daftar** di topbar mengarah ke `/my-account/?action=register` — JavaScript di `dyaastore.js` otomatis switch tab ke "Daftar" pada halaman split-screen.

---

## 7. Snapshot Akhir Lingkungan

Setelah Step 1–4 selesai, struktur sistem live di lokal seharusnya:


| URL                            | Konten                                                         |
| ------------------------------ | -------------------------------------------------------------- |
| `/`                            | Beranda Dyaa Store full layout                                 |
| `/shop/`                       | Grid 8 produk Robux                                            |
| `/product/100-robux/`          | Detail produk + tombol Add to Cart                             |
| `/cart/`                       | Halaman keranjang                                              |
| `/checkout/`                   | Form checkout + section "Data Akun Roblox"                     |
| `/my-account/`                 | Login (split-screen, tab "Masuk")                              |
| `/my-account/?action=register` | Login (split-screen, tab "Daftar" aktif)                       |
| `/tentang/`                    | Halaman Tentang Kami                                           |
| `/faq/`                        | Halaman FAQ                                                    |
| `/syarat-ketentuan/`           | Halaman Syarat & Ketentuan                                     |
| `/kebijakan-privasi/`          | Halaman Kebijakan Privasi                                      |
| `/dukungan/`                   | Halaman Dukungan Pelanggan                                     |
| `/wp-admin/`                   | Dashboard admin (default WP) + widget "Dyaa Store — Ringkasan" |


---

## 8. Troubleshooting Cepat


| Masalah                                        | Solusi                                                                                               |
| ---------------------------------------------- | ---------------------------------------------------------------------------------------------------- |
| Beranda kosong / hanya judul "Sample Page"     | Pastikan child theme `dyaastore-child` aktif di Appearance → Themes                                  |
| Layout patah / styling hilang                  | Hard refresh browser (`Ctrl+Shift+F5`); pastikan child theme version di `style.css` baru ter-update  |
| Halaman statis hilang                          | Buka `/wp-admin/?dyaa_pages=1` (sebagai admin) untuk re-trigger                                      |
| Produk demo hilang                             | Buka `/wp-admin/?dyaa_seed=1` (sebagai admin) untuk re-trigger                                       |
| Field Username Roblox tidak muncul di checkout | Pastikan child theme aktif, plugin WooCommerce aktif, lalu refresh halaman checkout                  |
| Warning "Email could not be sent"              | Wajar di Laragon. Untuk demo nyata, install plugin **WP Mail SMTP** + akun Mailtrap/SendGrid sandbox |
| Mode Terang/Gelap tidak berubah saat klik      | Cek console browser; pastikan `dyaastore.js` ter-load (Network tab)                                  |


---

## 9. Rangkuman untuk BAB IV §4.1.1

Pada BAB IV skripsi, bagian §4.1.1 dapat menulis ringkas:

> Implementasi sistem dilakukan pada lingkungan lokal Laragon Full (Apache + MariaDB + PHP 8.1) dengan WordPress 6.9 sebagai CMS. Plugin **WooCommerce** dan **Elementor** diaktifkan sebagai pendukung fungsi e-commerce dan pembuat halaman. Seluruh kode kustom yang dikembangkan dalam Tugas Akhir ini ditempatkan di dalam *child theme* `dyaastore-child` dan tiga *must-use plugin* (`dyaastore-helpers.php`, `dyaastore-pages.php`, `dyaastore-seeder.php`) sehingga **tidak menyentuh core WordPress** maupun plugin pihak ketiga. Pendekatan ini dipilih agar sistem mudah dipelihara dan tahan terhadap pembaruan WordPress (sesuai KNF-05 Maintainability).

> Empat langkah aktivasi (Step 1–4 pada bagian sebelumnya) menghasilkan sistem siap pakai dengan **6 kategori produk**, **8 produk Robux demo**, dan **5 halaman statis** (Tentang, FAQ, Syarat & Ketentuan, Kebijakan Privasi, Dukungan Pelanggan) yang seluruhnya dibuat secara **otomatis** oleh *seeder* sehingga eksperimen dapat direplikasi sepenuhnya oleh penguji.

Detail implementasi antarmuka per halaman dilanjutkan di [`06-implementasi-antarmuka.md`](06-implementasi-antarmuka.md) dan implementasi fitur level kode di [`07-implementasi-fitur.md`](07-implementasi-fitur.md).