# Dyaa Store — Website E-Commerce Robux

Repository implementasi **Tugas Akhir** Program Studi Sistem Informasi STT-NF 2026:

> **"Rancang Bangun Website E-Commerce Dyaa Store Berbasis WordPress Menggunakan WooCommerce dengan Metode Waterfall untuk Penjualan Robux"**
>
> Penulis: **Wahyu Akbar Pratama Siregar** — NIM 0110122029
> Pembimbing: Jemiro Kasih, S.T., M.MSI.

---

## Tentang Proyek

Sistem e-commerce berbasis WordPress + WooCommerce untuk Toko Dyaa Store yang menjual mata uang virtual **Robux** untuk platform game Roblox. Sistem dirancang menggunakan metodologi **Waterfall** dalam pendekatan **Research and Development (R&D)** dan dievaluasi dengan **Black Box Testing** + **Skala Likert**.

## Tech Stack

| Komponen | Tools |
|----------|-------|
| Web Server | Laragon (Apache + PHP) |
| Database | MySQL / MariaDB |
| CMS | WordPress 6.9.4 |
| Plugin E-Commerce | WooCommerce |
| Page Builder | Elementor (free) |
| Tema Parent | Hello Elementor |
| Tema Child | `dyaastore-child` (custom — di repo ini) |

## Struktur Proyek

```
c:\laragon\www\dyaastore\
├── docs/                              ← Dokumentasi Skripsi (BAB III & IV)
│   ├── README.md                      ← Indeks + mapping ke BAB IV
│   ├── 01-analisis-kebutuhan.md       ← BAB III §3.1.2 (36 KF + 7 KNF)
│   ├── 02-perancangan-uml.md          ← BAB III §3.1.3 (UML lengkap)
│   ├── 03-pengujian-blackbox.md       ← BAB IV §4.2.1 (77 test case)
│   ├── 04-kuesioner-likert.md         ← BAB IV §4.2.2 (22 pernyataan)
│   ├── 05-panduan-implementasi.md     ← BAB IV §4.1.1 (lingkungan + setup)
│   ├── 06-implementasi-antarmuka.md   ← BAB IV §4.1.2 (15 kode AT per halaman)
│   └── 07-implementasi-fitur.md       ← BAB IV §4.1.3 (12 kode FT level kode)
├── wp-content/
│   ├── themes/dyaastore-child/        ← Child theme custom
│   │   ├── style.css                  ← Design system: hero, cards, stats, testimoni, auth split-screen, theme toggle
│   │   ├── functions.php              ← Hooks: sidebar, topbar, bottom nav, footer, custom field Roblox, shortcodes
│   │   ├── front-page.php             ← Render homepage di URL "/"
│   │   ├── inc/icons.php              ← Library SVG icons (Lucide-style)
│   │   ├── readme.txt
│   │   ├── assets/
│   │   │   ├── img/                   ← Logo, hero, 4 gambar paket Robux (.png)
│   │   │   └── js/dyaastore.js        ← Dark mode toggle, counter, countdown, live toast, auth tabs
│   │   ├── templates/
│   │   │   ├── template-homepage.php  ← Page template "Dyaa Store — Homepage"
│   │   │   └── part-home-main.php     ← Markup inti beranda (reusable)
│   │   └── woocommerce/myaccount/
│   │       └── form-login.php         ← Override auth split-screen + tab Login/Daftar
│   ├── mu-plugins/
│   │   ├── dyaastore-helpers.php      ← Kolom Username Roblox di order list (legacy + HPOS) + dashboard widget
│   │   ├── dyaastore-pages.php        ← Auto-create 5 halaman statis
│   │   └── dyaastore-seeder.php       ← Auto-create 6 kategori + 8 produk Robux + featured image
│   └── plugins/                       ← WooCommerce, Elementor (di-install via wp-admin)
└── (file inti WordPress, di-exclude .gitignore)
```

## Mapping Implementasi → Skripsi

| Bab Skripsi | Lokasi di Repo |
|---|---|
| BAB III §3.1.2 Analisis Kebutuhan | [`docs/01-analisis-kebutuhan.md`](docs/01-analisis-kebutuhan.md) |
| BAB III §3.1.3 Perancangan (UML) | [`docs/02-perancangan-uml.md`](docs/02-perancangan-uml.md) |
| **BAB IV §4.1.1** Implementasi Lingkungan | [`docs/05-panduan-implementasi.md`](docs/05-panduan-implementasi.md) |
| **BAB IV §4.1.2** Implementasi Antarmuka | [`docs/06-implementasi-antarmuka.md`](docs/06-implementasi-antarmuka.md) |
| **BAB IV §4.1.3** Implementasi Fitur (Listing Program) | [`docs/07-implementasi-fitur.md`](docs/07-implementasi-fitur.md) |
| **BAB IV §4.2.1** Pengujian Black Box | [`docs/03-pengujian-blackbox.md`](docs/03-pengujian-blackbox.md) |
| **BAB IV §4.2.2** Pengujian Skala Likert | [`docs/04-kuesioner-likert.md`](docs/04-kuesioner-likert.md) |

> Lihat [`docs/README.md`](docs/README.md) untuk indeks lengkap dan sistem **traceability** kode (KF / AT / FT / TC).

## Cara Menjalankan (Quick Start)

1. **Pastikan Laragon menyala** (Apache + MySQL aktif)
2. Akses `https://dyaastore.test:8443/wp-admin`
3. Login sebagai `dyaa_admin`
4. Ikuti panduan di [`docs/05-panduan-implementasi.md`](docs/05-panduan-implementasi.md):
   - Step 1: Install WooCommerce
   - Step 2: Konfigurasi WooCommerce (Rupiah)
   - Step 3: Install Elementor + Hello Elementor
   - Step 4: Aktifkan child theme **"Dyaa Store Child"**
   - Step 5–10: Setup produk, halaman, dan testing

## Fitur Utama

### Untuk Customer (UI v2 — Dark-first ala referensi)
- ✅ **Sidebar kiri permanen** (240px) dengan logo Dyaa Store, grup MENU (Beranda · Semua Paket · Leaderboard · Cek Pesanan · Mode Terang/Gelap), grup NAVIGASI (Daftar Layanan · FAQ · Dukungan · Tentang · S&K · Privasi), grup PENGGUNA (Akun Saya / Masuk · Daftar / Keluar), dan kartu CTA Join WhatsApp Group di bawah — persis seperti referensi
- ✅ **Top bar sticky** dengan search bar besar di tengah, theme toggle, ikon keranjang (badge real-time), tombol **Login (orange gradient)** dan **Daftar (outline)**
- ✅ **Dark theme default** — palet navy gelap + accent emas/orange ala referensi; toggle ke light mode tersedia (preferensi tersimpan)
- ✅ **Hero section** "TOP UP ROBUX" dengan teks emas gradient besar, ilustrasi karakter Roblox + treasure chest, dan badge "TOP UP CEPAT & TERPERCAYA"
- ✅ **Section heading dengan vertical orange bar** accent (mirip referensi)
- ✅ **Flash Sale** dengan ikon mascot, countdown pill orange (HH:MM:SS), dan 3 produk on-sale dengan badge SALE!
- ✅ **Kategori Robux** — 6 kartu gradient warna-warni (Paket Hemat / Voucher / Gamepass / Premium / Bundle / Limited) dengan badge HOT/NEW/PROMO di pojok
- ✅ **Product cards gradient** dengan harga emas + tombol Add to cart orange gradient
- ✅ **Floating live transaction toast** bottom-left (auto-rotate fake-buy notif untuk social proof)
- ✅ **WhatsApp sticker bottom-right** dengan balon "BUTUH BANTUAN KLIK DISINI!!" (mascot 25D366)
- ✅ Mobile: sidebar slide-in dengan tombol hamburger, dan bottom navigation 4 ikon
- ✅ Input **Username Roblox** saat checkout (custom field dengan validasi)
- ✅ Pilihan pembayaran: Transfer Bank, E-Wallet, Virtual Account
- ✅ **Footer 4 kolom dark** (Brand · Peta Situs · Akses Cepat · Ketentuan) dengan vertical orange bar di setiap heading
- ✅ Stats animasi + 6 testimoni pelanggan

### Untuk Admin
- ✅ Dashboard ringkasan custom (produk aktif & pesanan pending)
- ✅ Kelola produk Robux (tambah/edit/hapus)
- ✅ Kelola pesanan (lihat Username Roblox di kolom listing)
- ✅ Email notifikasi otomatis
- ✅ Update status pesanan (Pending → Processing → Completed)
- ✅ **Auto-seeder** demo: 6 kategori + 8 produk Robux otomatis dibuat saat pertama kali admin login, lengkap dengan **featured image** otomatis (golden coins, treasure chest, gift box) untuk setiap produk

## Shortcodes Custom (siap pakai di Elementor)

| Shortcode | Output |
|-----------|--------|
| `[dyaa_hero]` | Hero section dengan tombol CTA |
| `[dyaa_flashsale]` | Flash sale section + countdown 6 jam + produk on-sale |
| `[dyaa_categories]` | 6 card kategori Robux dengan badge HOT/NEW/PROMO |
| `[dyaa_stats]` | Stats animasi (150+ Transaksi dll) |
| `[dyaa_testimonials]` | Grid 6 testimoni pelanggan |
| `[products]` | Grid produk (native WooCommerce) |

## Batasan Sistem (Sesuai Skripsi Bab 1.4)

- ❌ Tidak ada integrasi API resmi Roblox (pengiriman Robux dilakukan manual oleh Admin)
- ❌ Tidak ada backend custom di luar ekosistem WordPress
- ❌ Tidak ada sistem rekomendasi/analitik mendalam
- ✅ Pembayaran terbatas pada e-wallet, VA, transfer bank
- ✅ Fokus pada fitur inti e-commerce

## Lingkungan Pengembangan

- **Hardware**: Intel Core i5, RTX 3050, 16GB DDR4, SSD 512GB
- **OS**: Windows 10/11 64-bit

## Referensi & Inspirasi UI

- Situs sejenis: [dyaastore.fusionifydigital.store](https://dyaastore.fusionifydigital.store/) — referensi tampilan, **bukan** untuk dicontek persis (banyak fitur di luar batasan skripsi)

## Lisensi

Proyek ini dibuat untuk keperluan akademik. Hak Cipta © 2026 Wahyu Akbar Pratama Siregar.
WordPress dan WooCommerce adalah open-source di bawah GPLv2.
