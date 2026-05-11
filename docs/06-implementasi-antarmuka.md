# BAB IV 4.1.2 — Implementasi Antarmuka

> **Acuan Skripsi**: BAB IV §4.1.2 (Implementasi Antarmuka)
> **Cakupan**: deskripsi setiap halaman & komponen UI yang sudah diimplementasi pada Dyaa Store, lengkap dengan **Kode KF** yang dirujuk dan tempat penyimpanan screenshot bukti.
> **Catatan**: simpan setiap screenshot di folder `docs/screenshots/` dengan nama persis seperti tertera (misal `01-beranda-desktop-dark.png`) sehingga mudah disisipkan ke dokumen Word skripsi.

---

## Konvensi Penomoran Antarmuka

| Kode | Halaman / Komponen |
|---|---|
| AT-01 | Beranda |
| AT-02 | Halaman Shop / Katalog Produk |
| AT-03 | Halaman Detail Produk |
| AT-04 | Halaman Keranjang |
| AT-05 | Halaman Checkout (dengan custom field Roblox) |
| AT-06 | Halaman Thank You / Order Received (+ Panel QRIS) |
| AT-07 | Halaman My Account (Split-Screen Login + Daftar) |
| AT-08 | Halaman Akun Saya (setelah login) |
| AT-09 | 5 Halaman Statis (Tentang, FAQ, Syarat, Privasi, Dukungan) |
| AT-10 | Komponen Sidebar Navigasi |
| AT-11 | Komponen Bilah Navigasi Atas (Topbar) |
| AT-12 | Komponen Bottom Navigation (mobile) |
| AT-13 | Komponen Footer Global |
| AT-14 | Komponen Floating: Theme Toggle, WhatsApp Sticker, Live Toast |
| AT-15 | Antarmuka Admin (Order List + Dashboard Widget) |

---

## AT-01 — Halaman Beranda

| Atribut | Detail |
|---|---|
| **URL** | `/` |
| **Sumber template** | `wp-content/themes/dyaastore-child/front-page.php` → `templates/part-home-main.php` |
| **KF terkait** | KF-01, KF-13, KF-14, KF-15 |
| **Screenshot** | `docs/screenshots/01-beranda-desktop-dark.png`, `01-beranda-desktop-light.png`, `01-beranda-mobile.png` |

### Komponen yang Tampil (urutan dari atas)

1. **Sidebar kiri** (sticky desktop / drawer mobile) — lihat AT-10.
2. **Topbar** dengan search produk, theme toggle, cart icon, Login & Daftar — lihat AT-11.
3. **Hero Section** — shortcode `[dyaa_hero]`. Berisi:
   - Badge eyebrow ("TOP UP CEPAT & TERPERCAYA")
   - Heading: *"TOP UP **ROBUX**"* dengan kata "ROBUX" beraksen orange
   - Sub-heading deskriptif
   - 2 CTA: **"Belanja Sekarang →"** (orange solid) dan **"Hubungi Kami"** (outline)
   - Ilustrasi Robux di sisi kanan (asset `dyaa-hero.png`)
4. **Flash Sale** — shortcode `[dyaa_flashsale]`. Berisi:
   - Header: ⚡ "Flash Sale Robux" + countdown live (HH:MM:SS, default 6 jam)
   - 3 produk yang sedang sale (atau random fallback)
5. **Kategori Produk** — shortcode `[dyaa_categories]`. 6 kartu: Paket Hemat (badge PROMO), Voucher Robux (TERLARIS), Gamepass, Premium (BARU), Bundle, Limited (HOT).
6. **Paket Robux Terlaris** — `[products limit="8" columns="4" orderby="popularity"]`.
7. **Cara Pesan Robux di Dyaa Store** — 3 step card (Pilih Paket → Isi Username & Bayar → Robux Masuk).
8. **Stats** — shortcode `[dyaa_stats]`: 4 angka (Pesanan 8.420+, Pelanggan 1.250+, Rating 4.8/5, Pengiriman ±7 mnt) dengan animasi *count-up*.
9. **Testimoni** — 6 ulasan dari pelanggan dengan avatar, badge "verified", rating bintang.
10. **FAQ Singkat** — 5 pertanyaan (accordion `<details>`).
11. **Footer 4 kolom** — lihat AT-13.
12. **Floating UI** — WhatsApp sticker, Live transaction toast, Theme toggle (sudah masuk topbar) — lihat AT-14.

### Catatan Visual
- **Mode default**: Gelap (dark navy `#0a1330` background, accent oranye `#f59e0b`).
- Pre-paint script (`dyaastore_pre_paint_theme()`) menjamin **tidak ada flash light mode** saat refresh dalam mode terang.

---

## AT-02 — Halaman Shop / Katalog Produk

| Atribut | Detail |
|---|---|
| **URL** | `/shop/` |
| **Sumber** | WooCommerce native + override CSS Dyaa Store |
| **KF terkait** | KF-11, KF-13, KF-15 |
| **Screenshot** | `docs/screenshots/02-shop-grid.png`, `02-shop-filter-kategori.png` |

### Yang Tampil
- Grid produk responsif: 4 kolom desktop, 2 kolom tablet, 1–2 kolom mobile.
- Setiap kartu produk: featured image, nama paket, harga (sale dengan strike-through bila diskon), badge **SALE**, tombol **Add to Cart**.
- Sidebar Woo (jika diaktifkan): filter kategori, filter harga.
- URL kategori: `/shop/?product_cat=paket-hemat` (juga bisa lewat `[dyaa_categories]` di beranda).

---

## AT-03 — Halaman Detail Produk

| Atribut | Detail |
|---|---|
| **URL** | `/product/{slug}/` (contoh `/product/100-robux/`) |
| **Sumber** | WooCommerce native |
| **KF terkait** | KF-12 |
| **Screenshot** | `docs/screenshots/03-detail-produk.png` |

### Yang Tampil
- Featured image besar di sisi kiri.
- Judul produk + harga (sale strike-through bila ada).
- Short description (`short_description` dari seeder).
- Quantity selector + tombol **Add to Cart** orange.
- Long description (`description` dari seeder) di bawah.
- Breadcrumb di atas (Home → Shop → kategori → produk).

---

## AT-04 — Halaman Keranjang

| Atribut | Detail |
|---|---|
| **URL** | `/cart/` |
| **Sumber** | WooCommerce native |
| **KF terkait** | KF-20, KF-21 |
| **Screenshot** | `docs/screenshots/04-cart-isi.png`, `04-cart-kosong.png` |

### Yang Tampil
- Tabel item: thumbnail, nama, harga, quantity (input number), subtotal, ikon × untuk hapus.
- Card "Cart totals": Subtotal, Total, tombol **Proceed to Checkout**.
- Bila kosong: pesan "Your cart is currently empty." + tombol **Return to shop**.
- Counter di topbar (`.dyaa-cart-badge`) update setelah refresh.

---

## AT-05 — Halaman Checkout (Custom Field Roblox)

| Atribut | Detail |
|---|---|
| **URL** | `/checkout/` |
| **Sumber** | WooCommerce native + injeksi custom field di `functions.php` |
| **KF terkait** | KF-22, KF-23, KF-24 |
| **Screenshot** | `docs/screenshots/05-checkout-form-isi.png`, `05-checkout-validasi-error.png` |

### Yang Tampil

Layout 2 kolom (desktop): kiri = form billing, kanan = order summary.

**Section custom yang DIINJEKSI di antara catatan order dan tombol bayar:**

```
┌─────────────────────────────────────────┐
│  🎮 Data Akun Roblox                    │
│  ─────────────────────────────────────  │
│  Username Roblox *                      │
│  [ roblox_player123                  ]  │
│                                         │
│  ⚠ Pastikan username Roblox kamu benar.│
│    Robux akan dikirim ke akun ini,      │
│    kesalahan input bukan tanggung       │
│    jawab kami.                          │
└─────────────────────────────────────────┘
```

### Validasi yang Berjalan
| Pelanggaran | Notice yang Tampil |
|---|---|
| Field kosong | "Username Roblox wajib diisi untuk pengiriman Robux." |
| Panjang < 3 atau > 20 karakter | "Username Roblox harus 3-20 karakter." |
| Mengandung karakter selain a-z A-Z 0-9 _ | "Username Roblox hanya boleh huruf, angka, dan underscore." |

Notice ditampilkan di atas form (warna merah, sesuai default WooCommerce).

### Payment Methods (KF-24)

Section "Order" menampilkan daftar radio metode pembayaran. Setelah QRIS
gateway terdaftar, urutan default:

1. ◉ **QRIS (Scan & Bayar)** ← dipilih default · thumbnail QR kecil 44px di sebelah label · saat dipilih, deskripsi "Bayar dengan scan QRIS pakai aplikasi e-wallet..." muncul.
2. ○ Direct Bank Transfer (cadangan, dikonfigurasi admin).

Tombol "Place order" memicu `WC_Dyaa_QRIS_Gateway::process_payment()` →
order status `on-hold` → redirect ke `/checkout/order-received/{id}/`
(AT-06).

---

## AT-06 — Halaman Thank You

| Atribut          | Detail                                                                                                                                  |
| ---------------- | --------------------------------------------------------------------------------------------------------------------------------------- |
| **URL**          | `/checkout/order-received/{order-id}/?key=...`                                                                                          |
| **Sumber**       | WooCommerce native + injeksi custom                                                                                                     |
| **KF terkait**   | KF-24, KF-25, KF-32                                                                                                                     |
| **Screenshot**   | `docs/screenshots/06-thankyou-qris-dark.png`, `06-thankyou-qris-light.png`                                                              |

### AT-06a — Header Konfirmasi Order

- Header "Thank you. Your order has been received."
- Tabel ringkasan: Order number, Date, Total, Payment method.
- **Username Roblox tercantum** sebelum tabel detail order (custom dari `dyaastore_display_roblox_in_thankyou()`).
- Tabel order detail (produk, qty, subtotal).

### AT-06b — Panel QRIS (jika metode pembayaran = QRIS)

Render via `WC_Dyaa_QRIS_Gateway::thankyou_page()` ketika
`woocommerce_thankyou_dyaa_qris` action fired. Konten:


| Elemen               | Komposisi & Behavior                                                                                            |
| -------------------- | --------------------------------------------------------------------------------------------------------------- |
| Brand-line pill      | Badge "QRIS" oranye + nama merchant "dya store" + NMID "ID1026477730984"                                        |
| Judul                | "Scan QR untuk Membayar" (H2)                                                                                   |
| Sub-judul            | "Pesanan #{order_number} menunggu pembayaran sebesar Rp{total}"                                                 |
| Kolom kiri (QR)      | Gambar QRIS resmi (`dyaa-qris.png`, white bg, rounded 18px, max-width 320px) + link "Unduh gambar QR"           |
| Kolom kanan (instr.) | H3 "Langkah Pembayaran" + 4 langkah (buka e-wallet → scan → bayar → kirim bukti WA)                             |
| Box nominal          | Card glass dengan 2 baris: "Nominal" (oranye, font monospace) & "Nomor Pesanan" (#{order_number})               |
| CTA WhatsApp         | Tombol pill hijau (gradient `#25d366→#128c7e`) "Kirim Bukti Pembayaran via WhatsApp →" → deeplink `wa.me/`      |
| Note refund/SLA      | Paragraf border-kiri oranye: rata-rata 5-10 menit pada jam kerja 08.00–22.00 WIB                                |

**Tema gelap (default)**: gradient oranye→biru gelap (`rgba(245,158,11,.12)` → `rgba(11,23,64,.55)`).
**Tema terang** (kelas `body.dyaa-light`): gradient krem (`#fff7ed → #ffffff`),
border oranye lembut, shadow oranye samar.

**Responsif**: grid 2 kolom (QR | instruksi) di ≥768px, single column di <768px (QR di atas, instruksi di bawah). Padding panel mengecil dari 28px → 20px di mobile.

### AT-06c — Email Konfirmasi Customer

Hook `woocommerce_email_before_order_table` (priority 10) menempel blok
HTML berwarna krem dengan: judul, total, instruksi, gambar QR (max-width 280px), dan tombol hijau WhatsApp. Versi plain-text di-render lewat
cabang `$plain_text === true`.

---

## AT-07 — Halaman My Account (Split-Screen Login + Daftar)

| Atribut | Detail |
|---|---|
| **URL** | `/my-account/` (tab Login default) atau `/my-account/?action=register` (tab Daftar default) |
| **Sumber** | Override `wp-content/themes/dyaastore-child/woocommerce/myaccount/form-login.php` |
| **KF terkait** | KF-16, KF-17, KF-18 |
| **Screenshot** | `docs/screenshots/07-auth-login-dark.png`, `07-auth-register-dark.png`, `07-auth-mobile.png` |

### Layout

```
┌─────────────────────────────────────────────────────────────┐
│ AUTH SHELL — body class: .dyaa-auth-screen                  │
│ ┌──────────────────────────┬──────────────────────────────┐ │
│ │  HERO (kiri)             │  CARD (kanan)                │ │
│ │                          │                              │ │
│ │  Eyebrow: TOP UP ROBUX   │  Tabs:                       │ │
│ │  Heading: Beli Robux     │  [● Masuk] [  Daftar  ]      │ │
│ │  dengan tenang…          │                              │ │
│ │                          │  Tab "Masuk":                │ │
│ │  Sub: deskripsi kepercayaan│ Email/Username  [icon][__] │ │
│ │                          │  Password       [icon][__]   │ │
│ │  3 Feature card:         │  ☐ Remember me · Lupa Pass?  │ │
│ │  - 🛡 Aman & TOS Roblox  │  [   MASUK   ] (orange)      │ │
│ │  - ⚡ Pengiriman cepat   │  Belum punya akun? Daftar     │ │
│ │  - 💰 Harga transparan   │                              │ │
│ │                          │                              │ │
│ └──────────────────────────┴──────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

### Behavior
- **Tab switching tanpa reload**: handler JS di `dyaastore.js` (`setTab()`) toggle class `is-active` antar pane.
- URL ikut update via `window.history.replaceState()` → memungkinkan share link `?action=register`.
- Header default WordPress (judul "My account") **disembunyikan** lewat CSS spesifik `body.dyaa-auth-screen .page-header { display:none !important; }`.
- Tombol submit memakai gradient orange (override default Woo) lewat selector dengan specificity tinggi.

---

## AT-08 — Halaman Akun Saya (setelah login)

| Atribut | Detail |
|---|---|
| **URL** | `/my-account/` (saat user logged-in) |
| **Sumber** | WooCommerce native |
| **KF terkait** | KF-19 |
| **Screenshot** | `docs/screenshots/08-myaccount-dashboard.png`, `08-myaccount-orders.png` |

### Yang Tampil
- Sidebar Woo: Dashboard, Orders, Downloads, Addresses, Account details, Logout.
- Area utama:
  - Dashboard: salam + ringkasan akun.
  - Orders: tabel riwayat pesanan dengan kolom Order, Date, Status, Total, Actions.
  - Account details: form edit profil + ubah password.

---

## AT-09 — 5 Halaman Statis

| Slug | URL | KF | Screenshot |
|---|---|---|---|
| `tentang` | `/tentang/` | KF-06 | `docs/screenshots/09a-tentang.png` |
| `faq` | `/faq/` | KF-07 | `docs/screenshots/09b-faq.png` |
| `syarat-ketentuan` | `/syarat-ketentuan/` | KF-08 | `docs/screenshots/09c-syarat.png` |
| `kebijakan-privasi` | `/kebijakan-privasi/` | KF-09 | `docs/screenshots/09d-privasi.png` |
| `dukungan` | `/dukungan/` | KF-10 | `docs/screenshots/09e-dukungan.png` |

### Sumber Konten
Semua dibuat otomatis oleh `wp-content/mu-plugins/dyaastore-pages.php` saat admin pertama kali masuk. Konten memakai class CSS `.dyaa-page-static` sehingga otomatis selaras dengan design system Dyaa Store (tipografi, spacing, dark/light mode).

### Komponen di Setiap Halaman
- Section header: eyebrow + judul + sub-heading.
- Block konten: heading H3 + paragraf + list.
- FAQ memakai `<details>` (accordion native HTML, tanpa JS).

---

## AT-10 — Komponen Sidebar Navigasi

| Atribut | Detail |
|---|---|
| **Sumber** | `functions.php → dyaastore_render_sidebar()` (hook `wp_body_open` priority 4) |
| **KF terkait** | KF-02, KF-05 |
| **Screenshot** | `docs/screenshots/10-sidebar-desktop.png`, `10-sidebar-mobile-drawer.png` |

### Struktur (3 grup + WhatsApp CTA)

```
[Logo Dyaa Store]
─────────────────
Menu
├── 🏠 Beranda                    (active jika is_front_page)
├── 🎮 Semua Paket
├── 🏆 Paket Terlaris
├── 🧾 Cek Pesanan
└── ☀ Mode Terang  [○────●]      ← pill switch sun/moon

Navigasi
├── 📋 Daftar Layanan             (active jika is_shop / is_product_category / is_product)
├── ❓ FAQ                         (active jika is_page('faq'))
├── 💬 Dukungan Pelanggan         (active jika is_page('dukungan'))
├── ℹ Tentang                    (active jika is_page('tentang'))
├── 📄 Syarat & Ketentuan         (active jika is_page('syarat-ketentuan'))
└── 🛡 Kebijakan Privasi          (active jika is_page('kebijakan-privasi'))

Pengguna [logged-out]
├── 🔑 Masuk
└── 👤+ Daftar

Pengguna [logged-in]
├── 👤 Akun Saya
└── 🚪 Keluar

─────────────────
[💚 Grup WhatsApp Member]
       Gabung Grup
```

### Behavior
- **Desktop**: sticky di sisi kiri, lebar 280px.
- **Mobile (≤ 1024px)**: tersembunyi, dibuka via tombol hamburger di kiri-atas. Overlay gelap muncul di belakang.
- Active link diberi class `.is-active` (background lebih terang + border kiri orange).
- Theme toggle (di grup Menu) tersinkron dengan toggle di Topbar.

---

## AT-11 — Komponen Bilah Navigasi Atas (Topbar)

| Atribut | Detail |
|---|---|
| **Sumber** | `functions.php → dyaastore_render_top_navigation()` (hook `wp_body_open` priority 5) |
| **KF terkait** | KF-03, KF-05, KF-15, KF-20 |
| **Screenshot** | `docs/screenshots/11-topbar-loggedout.png`, `11-topbar-loggedin.png` |

### Struktur
```
┌─────────────────────────────────────────────────────────────┐
│ [🔍 Cari paket Robux, voucher, bundle…………………………………]      │
│                          [☀/🌙] [🛒 3] [🔑 Masuk] [Daftar]  │
└─────────────────────────────────────────────────────────────┘
```

| Komponen | Fungsi |
|---|---|
| Search input | Form GET ke `/?s={query}&post_type=product` (Woo product search) |
| Theme toggle | Pill switch sun/moon, sinkron dengan sidebar |
| Cart icon | Badge angka (`WC()->cart->get_cart_contents_count()`) — tampil hanya jika > 0 |
| **Masuk** | CTA orange solid → `/my-account/` |
| **Daftar** | CTA outline ghost → `/my-account/?action=register` |
| Saat logged-in | Hanya tampil **Akun Saya** (orange solid) |

### Kompatibilitas tema parent (Hello Elementor)

Tema parent memuat `reset.css` dengan warna aksen `#c36` pada `<button>` default. Child theme menetralkan efek pink tersebut untuk `body.dyaa-site` (lihat bagian paling atas `style.css` dan §5.5 di `05-panduan-implementasi.md`) agar hamburger, theme toggle, dan kontrol lain mengikuti palet Dyaa Store.

---

## AT-12 — Bottom Navigation (Mobile Only)

| Atribut | Detail |
|---|---|
| **Sumber** | `functions.php → dyaastore_render_bottom_nav()` (hook `wp_footer` priority 2) |
| **KF terkait** | KF-04, KF-20 |
| **Screenshot** | `docs/screenshots/12-bottomnav-mobile.png` |

### Struktur (4 ikon, fixed bottom)
```
┌────────────────────────────────────┐
│ [🏠 Beranda] [🎮 Shop] [🛒 Keranjang⁽³⁾] [👤 Akun] │
└────────────────────────────────────┘
```

- Tampil pada viewport **≤768px** lewat CSS media query (`display: flex` pada `.dyaa-bottomnav`).
- Item aktif diberi indikator (warna orange + label tebal).
- Cart icon punya badge counter.

---

## AT-13 — Footer Global 4 Kolom

| Atribut | Detail |
|---|---|
| **Sumber** | `functions.php → dyaastore_render_global_footer()` (hook `wp_footer` priority 1) |
| **Screenshot** | `docs/screenshots/13-footer.png` |

### 4 Kolom

| Kolom 1 — Brand | Kolom 2 — Peta Situs | Kolom 3 — Bantuan | Kolom 4 — Legal |
|---|---|---|---|
| Logo + tagline | Beranda | Cara Pesan Robux | Tentang Kami |
| Deskripsi singkat | Semua Paket Robux | Cek Status Pesanan | Kebijakan Privasi |
| Trust badges (🛡 aman, ✓ garansi) | Keranjang | Dukungan Pelanggan | Syarat & Ketentuan |
| | Akun Saya | Pertanyaan Umum (FAQ) | |

### Strip Bawah
```
© {tahun} Dyaa Store. Hak cipta dilindungi.
Studi kasus Tugas Akhir — Wahyu Akbar Pratama Siregar — Sistem Informasi STT-NF
```

---

## AT-14 — Komponen Floating

### AT-14a — Theme Toggle (Pill Switch)

| Atribut | Detail |
|---|---|
| **Lokasi** | Topbar + Sidebar (tersinkron) |
| **Sumber markup** | `dyaa_render_top_navigation()` & `dyaa_render_sidebar()` |
| **Sumber CSS** | `style.css` selektor `.dyaa-theme-toggle*` |
| **Sumber JS** | `assets/js/dyaastore.js` fungsi `syncToggle()` |

**Behavior**:
- Klik → toggle class `dyaa-light` di `<body>`, simpan ke `localStorage('dyaa-theme')`.
- Mode **gelap (default)**: thumb orange di kanan, ikon bulan aktif.
- Mode **terang**: thumb orange di kiri, ikon matahari aktif.
- Pre-paint script di `wp_head` priority 1 mencegah flash mode terang saat refresh.

### AT-14b — WhatsApp Sticker

| Atribut | Detail |
|---|---|
| **Lokasi** | Pojok kanan-bawah, fixed |
| **Sumber** | `dyaastore_render_whatsapp_button()` (hook `wp_footer`) |
| **KF terkait** | KF-35 |
| **Screenshot** | `docs/screenshots/14b-wa-sticker.png` |

Berisi balon teks **"BUTUH BANTUAN — KLIK DISINI!!"** di samping ikon WhatsApp animasi. Klik membuka `https://wa.me/{nomor}?text={pesan-prefilled}` di tab baru.

### AT-14c — Live Transaction Toast

| Atribut | Detail |
|---|---|
| **Lokasi** | Pojok kiri-bawah, fixed |
| **Sumber** | `dyaastore_render_live_toast()` (hook `wp_footer`) + JS rotator di `dyaastore.js` |
| **KF terkait** | KF-36 |
| **Screenshot** | `docs/screenshots/14c-live-toast.png` |

Menampilkan transaksi simulatif berputar (sebagai social proof):
```
┌──────────────────────────────────┐
│ [AP]  Andi Pratama  ✓           [×]│
│       baru saja membeli           │
│       1.700 Robux                 │
│       2 menit yang lalu           │
└──────────────────────────────────┘
```
Ada tombol close `×` di pojok kanan-atas. Setelah ditutup, toast tidak muncul kembali sampai refresh.

---

## AT-15 — Antarmuka Admin (Custom)

| Atribut | Detail |
|---|---|
| **URL** | `/wp-admin/edit.php?post_type=shop_order` (legacy) atau `/wp-admin/admin.php?page=wc-orders` (HPOS) |
| **Sumber** | `mu-plugins/dyaastore-helpers.php` |
| **KF terkait** | KF-28, KF-29, KF-31 |

### AT-15a — Kolom Username Roblox di Listing Pesanan

```
┌────────┬─────────────┬────────────┬────────┬─────────────────┬───────┐
│ Order  │ Date        │ Status     │ Total  │ Username Roblox │ ...   │
├────────┼─────────────┼────────────┼────────┼─────────────────┼───────┤
│ #1024  │ 2 jam lalu  │ Processing │ 65.000 │ roblox_player99 │       │
│ #1023  │ 5 jam lalu  │ Completed  │ 245.000│ andipratama_id  │       │
│ #1022  │ kemarin     │ Pending    │ 18.000 │ —               │       │
└────────┴─────────────┴────────────┴────────┴─────────────────┴───────┘
```
- Kolom diinjeksi setelah kolom **Status** lewat filter `manage_edit-shop_order_columns` (legacy) dan `manage_woocommerce_page_wc-orders_columns` (HPOS).
- Username ditampilkan dengan warna hijau & bold; tanda `—` jika kosong.

**Screenshot**: `docs/screenshots/15a-admin-orders-list.png`

### AT-15b — Detail Pesanan: Username Roblox di Sidebar

Di halaman edit order (`/wp-admin/post.php?post=ID&action=edit` atau halaman detail HPOS), di bawah Billing Address muncul:

```
Username Roblox: roblox_player99    (warna biru, bold)
```

**Screenshot**: `docs/screenshots/15b-admin-order-detail.png`

### AT-15c — Dashboard Widget Ringkasan

Di `/wp-admin/index.php` (halaman utama wp-admin) tampil widget **🎮 Dyaa Store — Ringkasan**:

```
┌──────────────────────────────────────────┐
│ 🎮 Dyaa Store — Ringkasan                │
│ ──────────────────────────────────────── │
│ Total produk Robux  :  8                 │
│ Total pesanan       :  N                 │
│ Order Pending       :  N                 │
│ Order Completed     :  N                 │
└──────────────────────────────────────────┘
```

**Screenshot**: `docs/screenshots/15c-dashboard-widget.png`

---

## Rekap Mapping Antarmuka ↔ KF

| Kode AT | Halaman / Komponen | KF yang Diuji di BAB IV |
|---|---|---|
| AT-01 | Beranda | KF-01, KF-13, KF-14 |
| AT-02 | Shop | KF-11, KF-13, KF-15 |
| AT-03 | Detail Produk | KF-12 |
| AT-04 | Cart | KF-20, KF-21 |
| AT-05 | Checkout | KF-22, KF-23 |
| AT-06 | Thank You + Panel QRIS | KF-24, KF-25, KF-32 |
| AT-07 | Auth split-screen | KF-16, KF-17, KF-18 |
| AT-08 | My Account | KF-19 |
| AT-09 | 5 Halaman statis | KF-06–KF-10 |
| AT-10 | Sidebar | KF-02 |
| AT-11 | Topbar | KF-03, KF-15 |
| AT-12 | Bottom nav | KF-04 |
| AT-13 | Footer | (umum) |
| AT-14a | Theme toggle | KF-05 |
| AT-14b | WA sticker | KF-35 |
| AT-14c | Live toast | KF-36 |
| AT-15 | Admin custom | KF-28, KF-29, KF-31 |

> Setiap kode AT di tabel ini akan **dirujuk balik** pada `docs/03-pengujian-blackbox.md` dan `docs/07-implementasi-fitur.md` sehingga BAB IV punya satu sumber kebenaran.
