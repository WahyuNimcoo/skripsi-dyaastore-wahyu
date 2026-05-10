# Panduan Implementasi Step-by-Step

> Acuan: Skripsi Bab 3.1.4 (Implementasi)
> Lingkungan: Laragon + WordPress 6.9.4 di `c:\laragon\www\dyaastore`
> URL Lokal: `https://dyaastore.test:8443/wp-admin`

## ⚠️ Sebelum Mulai
Pastikan Laragon **menyala** (Apache + MySQL). Login WordPress sebagai `dyaa_admin`.

> ✅ **Plugin & tema sudah otomatis terinstall** — kamu **TIDAK perlu** download/install manual lagi. Tinggal **Activate** saja di bawah.

---

## STEP 1 — Activate Plugin WooCommerce ✅ WAJIB

1. Login ke `https://dyaastore.test:8443/wp-admin`
2. Sidebar kiri → **Plugins** → **Installed Plugins**
3. Cari **WooCommerce** (sudah terdaftar) → klik **Activate**
4. Setup wizard akan muncul:
   - **Store details**: Negara: Indonesia, kota: (sesuaikan), zip: (sesuaikan)
   - **Industry**: Pilih `Other` atau `Subscriptions/Memberships`
   - **Product types**: Pilih `Downloads` (untuk produk digital)
   - **Business details**: 1-10 produk, sudah jualan online: No
   - **Theme**: Skip (kita pakai theme sendiri)

---

## STEP 2 — Konfigurasi WooCommerce

### 2.1 Setting Mata Uang ke Rupiah
1. **WooCommerce** → **Settings** → tab **General**
2. Currency: `Indonesian rupiah (Rp)`
3. Currency position: `Left with space` → `Rp 100.000`
4. Thousand separator: `.`
5. Decimal separator: `,`
6. Number of decimals: `0`
7. Klik **Save changes**

### 2.2 Setting Pajak (Tax)
1. Tab **Tax** (jika muncul) → `No, I don't charge sales tax`
2. Atau biarkan default

### 2.3 Setting Pembayaran
1. Tab **Payments**
2. Aktifkan:
   - ✅ **Direct bank transfer** (untuk Transfer Bank manual)
     - Klik "Manage" → tambahkan rekening BCA/Mandiri/dll
   - ✅ **Cash on delivery** → ❌ Matikan (tidak relevan untuk produk digital)

> 💡 **Catatan untuk skripsi**: E-Wallet & Virtual Account butuh plugin tambahan (mis. **Tripay**, **Duitku**, atau **Midtrans for WooCommerce**) yang akan dikonfigurasi di STEP 5.

### 2.5 Akun Pelanggan — Masuk & Daftar (WAJIB agar tombol "Daftar" berfungsi)

Tombol **Login** mengarah ke halaman **Akun** WooCommerce (`/my-account/`). Tombol **Daftar** mengarah ke **`/my-account/?action=register`** (form registrasi customer).

1. **Settings** → **General** → centang **Anyone can register** → **Save Changes**
2. **WooCommerce** → **Settings** → tab **Accounts & Privacy**
   - Aktifkan **Allow customers to create an account on the "My account" page**
   - (Opsional) Aktifkan juga **Allow customers to create an account during checkout**

Tanpa langkah ini, halaman Daftar bisa kosong atau tidak menampilkan form registrasi.

> **Admin WordPress** (`/wp-admin/`): tetap memakai **tampilan bawaan WordPress** (dashboard biru/abu-abu). Ini normal untuk skripsi — tidak di-custom seperti storefront; yang dibahas di Bab 4 biasanya **antarmuka pelanggan** + **WooCommerce Orders** di admin.

### 2.4 Setting Email

1. Tab **Emails**
2. From name: `Dyaa Store`
3. From address: email kamu
4. Header image: (opsional, bisa skip)

---

## STEP 3 — Activate Plugin Elementor ✅ WAJIB

1. **Plugins** → **Installed Plugins**
2. Cari **Elementor** → klik **Activate**
3. Setup wizard:
   - Buat akun Elementor: **Skip / Maybe Later**
   - Pilih: `I want to design pages from scratch`

> Tema **Hello Elementor** juga sudah terinstall, tidak perlu install ulang. Lanjut ke STEP 4.

---

## STEP 4 — Aktifkan Child Theme `dyaastore-child` ✅

Saya sudah membuat child theme di `wp-content/themes/dyaastore-child/`.

1. **Appearance** → **Themes**
2. Cari card **"Dyaa Store Child"** → klik **Activate**

> Child theme inilah yang akan kamu sebut di Bab 4 sebagai bukti implementasi custom code.

---

## STEP 5 — Install Plugin Tambahan (Opsional tapi Direkomendasikan)

| Plugin | Fungsi | Wajib? |
|--------|--------|--------|
| **WPS Hide Login** | Sembunyikan URL `/wp-admin` (security) | Opsional |
| **Wordfence Security** | Proteksi dari brute force | Opsional |
| **Yoast SEO** | SEO + meta description | Opsional |
| **Checkout Field Editor** | Edit field checkout (untuk Username Roblox) | ⚠️ Saya sudah handle via functions.php (lihat STEP 6), jadi **TIDAK perlu** plugin ini |
| **Tripay / Duitku Payment Gateway** | Untuk e-wallet & VA | **Wajib** kalau mau demo bayar VA/E-wallet di skripsi |

### Cara Install Payment Gateway Tripay
1. Daftar akun di [tripay.co.id](https://tripay.co.id) (mode sandbox tersedia)
2. Download plugin Tripay dari dashboard Tripay
3. **Plugins** → **Add New** → **Upload Plugin** → upload .zip Tripay
4. Aktivkan dan masukkan API Key sandbox

> 💡 Untuk skripsi, **mode sandbox/development sudah cukup** sebagai bukti integrasi.

---

## STEP 6 — Custom Field "Username Roblox" di Checkout

Saya sudah menulis kodenya di `wp-content/themes/dyaastore-child/functions.php`. Field ini akan otomatis muncul di checkout setelah child theme diaktifkan.

**Verifikasi:**
1. Buka shop, tambah produk ke cart
2. Klik Checkout
3. Pastikan ada field **"Username Roblox"** di form

---

## STEP 7 — Buat Produk Robux ✅ SUDAH OTOMATIS

> 💡 **Kamu TIDAK perlu input manual** — `dyaastore-seeder.php` (mu-plugin) **otomatis** membuat 6 kategori + 8 produk Robux **lengkap dengan gambar (featured image)** saat pertama kali admin login ke dashboard.

### Daftar Produk yang Otomatis Dibuat

| Paket | Harga | Sale | Kategori | Gambar |
|-------|-------|------|----------|--------|
| 100 Robux | Rp 18.000 | **Rp 15.000** | Paket Hemat | Stack koin emas |
| 400 Robux | Rp 65.000 | **Rp 55.000** | Paket Hemat | Stack koin emas |
| 800 Robux | Rp 125.000 | — | Voucher Robux | Pile koin emas |
| 1700 Robux | Rp 245.000 | **Rp 220.000** | Voucher Robux | Pile koin emas |
| 4500 Robux | Rp 625.000 | — | Gamepass | Treasure chest |
| 10.000 Robux | Rp 1.350.000 | — | Premium | Treasure chest |
| Bundle 2x800 | Rp 240.000 | **Rp 215.000** | Bundle | Gift box premium |
| Limited 2200 | Rp 320.000 | — | Limited | Treasure chest |

### Re-trigger Seeder (kalau butuh ulang)
- URL: `https://dyaastore.test:8443/wp-admin/?dyaa_seed=1`
- Hanya admin yang bisa akses. Kategori/produk yang sudah ada tidak akan dobel.

### Tambah Produk Manual (opsional)

1. Sidebar → **Products** → **Add New**
2. Isi: nama, deskripsi, harga, ✅ centang **Virtual**
3. **Product image**: upload gambar Robux (atau pakai 6 gambar di `wp-content/themes/dyaastore-child/assets/img/`)
4. Klik **Publish**

---

## STEP 8 — Desain Halaman dengan Elementor

### 8.1 Halaman Beranda — OTOMATIS (tanpa pengaturan Membaca)

**Mulai tema versi 1.2:** berkas **`front-page.php`** sudah ada di child theme. Artinya URL **`/`** akan menampilkan layout Dyaa Store (hero, navigasi atas, dll) **tanpa** harus membuat halaman statis di **Settings → Reading**.

> Tetap bisa pakai halaman statis + template **"Dyaa Store — Homepage"** jika kamu mau mengedit konten via Elementor di halaman itu.

### 8.1.B Halaman Beranda — PILIHAN TERCEPAT (Pakai Template Saya)

> 💡 Saya sudah buat **template homepage siap pakai** di `wp-content/themes/dyaastore-child/templates/template-homepage.php`. Tinggal pilih dari dropdown.

1. **Pages** → **Add New** → judul: `Beranda`
2. Di sidebar kanan, pada bagian **"Page Attributes"** → **Template**:
   - Pilih: **"Dyaa Store — Homepage"**
3. Klik **Publish** (jangan edit dengan Elementor — biarkan saja, template akan auto-render)
4. **Settings** → **Reading** → Homepage displays: `A static page` → pilih "Beranda"

Halaman kamu langsung punya:
- ✅ Hero section dengan tombol CTA
- ✅ Grid produk Robux populer (otomatis dari WooCommerce)
- ✅ Section "Cara Pesan" 3 langkah
- ✅ Stats (150+ Transaksi, 80+ Pengguna, 4.9/5 Rating)
- ✅ Testimoni 6 pelanggan
- ✅ Footer 4 kolom
- ✅ Floating WhatsApp button
- ✅ Dark mode toggle

### 8.1.B Halaman Beranda — PILIHAN MANUAL (Pakai Elementor)

Kalau mau bebas custom:
1. **Pages** → **Add New** → judul: `Beranda Custom`
2. Klik **Edit with Elementor**
3. Drag widgets:
   - Tambahkan section Hero, lalu di widget **Shortcode** masukkan: `[dyaa_hero]`
   - Tambahkan **Products** widget (WooCommerce) untuk grid produk
   - Tambahkan **Shortcode** widget: `[dyaa_stats]`
   - Tambahkan **Shortcode** widget: `[dyaa_testimonials]`
4. Save

### 8.2 Halaman Cara Pesan
1. **Pages** → **Add New** → judul: `Cara Pesan`
2. Edit with Elementor
3. Tulis 3 step:
   - 1. Pilih paket Robux
   - 2. Lakukan pembayaran
   - 3. Robux dikirim ke akun Roblox kamu

### 8.3 Menu Navigasi
1. **Appearance** → **Menus**
2. Buat menu baru: `Main Menu`
3. Tambahkan: Beranda, Shop, Cara Pesan, Akun Saya
4. Display location: ✅ Header

---

## STEP 9 — Atur Permalink

1. **Settings** → **Permalinks**
2. Pilih: `Post name` → URL jadi `/produk/nama-produk`
3. Save

---

## STEP 10 — Test End-to-End

Lakukan urutan ini sebagai customer:
1. Buka `https://dyaastore.test:8443` (incognito)
2. Klik produk → Add to Cart
3. Checkout → isi data + Username Roblox
4. Pilih Bank Transfer → Place Order
5. Cek email dummy / WP Admin → **WooCommerce** → **Orders** (order baru tampil)
6. Sebagai admin: ubah status ke "Completed"
7. Cek email konfirmasi

✅ Kalau semua berhasil, sistem sudah **siap untuk pengujian Black Box** sesuai `docs/03-pengujian-blackbox.md`.

---

## STEP 10b — Sesuaikan Nomor WhatsApp (PENTING)

Default-nya saya pakai nomor placeholder. Edit `wp-content/themes/dyaastore-child/functions.php`:

```php
define( 'DYAA_WHATSAPP_NUMBER', '6289515881150' ); // +62 895-1588-1150
define( 'DYAA_WHATSAPP_TEXT',   'Halo Dyaa Store, saya mau tanya tentang Robux' );
define( 'DYAA_BRAND_NAME',      'Dyaa Store' );
define( 'DYAA_BRAND_TAGLINE',   'Top Up Robux Termurah & Tercepat Se-Indonesia' );
```

## Shortcodes yang Tersedia

Saya sudah siapkan beberapa shortcode yang bisa dipakai di **Elementor** (widget Shortcode), **page editor**, atau **post**:

| Shortcode | Fungsi | Contoh |
|-----------|--------|--------|
| `[dyaa_hero]` | Hero section dengan tombol CTA | `[dyaa_hero title="Top Up <span class='accent'>Robux</span>" cta1_url="/shop"]` |
| `[dyaa_stats]` | Section stats (150+ Transaksi dll) | `[dyaa_stats]` |
| `[dyaa_testimonials]` | Section testimoni 6 review | `[dyaa_testimonials]` |
| `[products]` | Grid produk WooCommerce native | `[products limit="8" columns="4"]` |

## STEP 11 — Dokumentasi untuk Skripsi

Setelah implementasi selesai:
1. Screenshot setiap halaman → simpan di `docs/screenshots/`
2. Isi tabel pengujian Black Box di `docs/03-pengujian-blackbox.md`
3. Sebar kuesioner di `docs/04-kuesioner-likert.md` ke minimal 10 responden
4. Hitung hasil Likert
5. Tulis Bab IV skripsi dengan referensi ke file-file di folder `docs/` ini
