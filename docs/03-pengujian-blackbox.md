# BAB IV 4.2.1 — Pengujian Black Box

> **Acuan Skripsi**: BAB II §2.4.3 (Black Box Testing), BAB III §3.2.4 (Metode Pengujian), BAB IV §4.2.1 (Hasil Pengujian Sistem)
> **Tujuan**: memverifikasi setiap KF (`docs/01-analisis-kebutuhan.md`) berdasarkan **input → output yang diharapkan**, tanpa melihat struktur internal.
> **Lingkungan**: Laragon Full + WordPress 6.9.x + child theme `dyaastore-child` aktif. Dokumentasi setup ada di `docs/05-panduan-implementasi.md`.

---

## 1. Strategi Pengujian

| Aspek | Metode |
|---|---|
| Teknik | Equivalence Partitioning + Boundary Value Analysis |
| Scope | Hanya fitur yang **benar-benar diimplementasi** dalam Tugas Akhir (lihat `docs/07-implementasi-fitur.md`) |
| Aktor uji | Pengunjung, Customer (akun test), Admin (`dyaa_admin`) |
| Browser uji | Chrome 130 (utama) + Firefox 130 (verifikasi cross-browser) |
| Viewport uji | Desktop ≥1024px, Tablet ≤1024px (drawer sidebar), Mobile ≤768px (bottom nav) |
| Format hasil | Pass / Fail per Test Case (TC) → ringkasan persentase per kategori |

### Kolom Tabel
| Kolom | Penjelasan |
|---|---|
| **TC-XXNN** | ID unik test case (misal TC-A01) |
| **KF Tracking** | KF yang diverifikasi (lihat `docs/01-analisis-kebutuhan.md`) |
| **Antarmuka** | Kode AT yang menjadi tempat pengujian (lihat `docs/06-implementasi-antarmuka.md`) |
| **Skenario** | Apa yang dilakukan oleh penguji |
| **Input** | Nilai input / aksi spesifik |
| **Expected Output** | Hasil yang diharapkan sesuai spesifikasi |
| **Hasil Aktual** | Diisi saat eksekusi (deskriptif) |
| **Status** | Pass / Fail |

---

## A. Pengujian Akses & Navigasi (KF-01 s/d KF-05)

| TC | KF | AT | Skenario | Input | Expected Output | Hasil Aktual | Status |
|---|---|---|---|---|---|---|---|
| TC-A01 | KF-01 | AT-01 | Buka halaman Beranda | Akses URL `/` | Tampil hero, flash sale, kategori, paket terlaris, cara pesan, stats, testimoni, FAQ, footer | _isi_ | _ |
| TC-A02 | KF-02 | AT-10 | Sidebar tampil di desktop | Buka homepage di viewport 1920×1080 | Sidebar muncul fixed di kiri dengan 3 grup (Menu, Navigasi, Pengguna) + WhatsApp CTA | _isi_ | _ |
| TC-A03 | KF-02 | AT-10 | Sidebar mobile (drawer) | Resize viewport ke 375×667, klik tombol hamburger | Sidebar slide dari kiri + overlay gelap muncul; klik overlay → sidebar tertutup | _isi_ | _ |
| TC-A04 | KF-03 | AT-11 | Topbar lengkap saat logged-out | Buka homepage tanpa login | Search input + Theme toggle + Cart icon + Tombol Masuk (orange) + Tombol Daftar (ghost) | _isi_ | _ |
| TC-A05 | KF-03 | AT-11 | Topbar saat logged-in | Login lalu buka homepage | Search + Toggle + Cart icon + Tombol "Akun Saya" (orange, hanya 1 CTA) | _isi_ | _ |
| TC-A06 | KF-04 | AT-12 | Bottom nav muncul di mobile | Viewport ≤ 767px | 4 ikon: Beranda, Shop, Keranjang (badge), Akun — fixed di bottom | _isi_ | _ |
| TC-A07 | KF-04 | AT-12 | Bottom nav tidak muncul di desktop | Viewport ≥ 1024px | Bottom nav `display: none` | _isi_ | _ |
| TC-A08 | KF-05 | AT-14a | Klik theme toggle topbar | Klik pill switch di topbar | Body dapat class `dyaa-light`; toggle thumb pindah ke kiri; toggle sidebar ikut sync | _isi_ | _ |
| TC-A09 | KF-05 | AT-14a | Klik theme toggle sidebar | Klik pill switch di sidebar (mode terang) | Body kehilangan `dyaa-light`; thumb pindah ke kanan; toggle topbar ikut sync; label sidebar berubah ke "Mode Terang" | _isi_ | _ |
| TC-A10 | KF-05 | AT-14a | Persistensi tema setelah refresh | Aktifkan mode terang lalu hard refresh | Halaman langsung tampil mode terang **tanpa flash gelap** | _isi_ | _ |
| TC-A11 | KNF-07 | AT-10 | Tombol hamburger tidak pink (Hello Elementor) | Viewport ≤1024px, buka halaman produk, hover + Tab-focus ke `#dyaa-sidebar-toggle` | Latar tombol tetap netral (abu/kartu tema); **bukan** solid pink `#c36` dari `hello-elementor/assets/css/reset.css` | _isi_ | _ |
| TC-A12 | KF-02 | AT-10 | Sidebar drawer benar-benar off-canvas | Viewport ≤1024px | Sidebar tidak mengambil lebar konten; `transform` menyembunyikan panel; konten utama full width dari kiri (padding body `0`) | _isi_ | _ |
| TC-A13 | KNF-05 | AT-11 | Cache stylesheet child theme ter-invalidate | Simpan perubahan `style.css` lalu reload sekali (tanpa clear cache manual) | Query string `style.css?ver=` berisi angka Unix (filemtime), bukan hanya nomor versi statis | _isi_ | _ |

---

## B. Pengujian Halaman Statis (KF-06 s/d KF-10)

| TC | KF | AT | Skenario | Input | Expected Output | Hasil Aktual | Status |
|---|---|---|---|---|---|---|---|
| TC-B01 | KF-06 | AT-09 | Buka halaman Tentang | Klik menu "Tentang" di sidebar | URL `/tentang/`, muncul section header + Visi/Misi/alasan memilih | _isi_ | _ |
| TC-B02 | KF-07 | AT-09 | Buka halaman FAQ | Klik menu "FAQ" di sidebar | URL `/faq/`, muncul 8 pertanyaan accordion `<details>` | _isi_ | _ |
| TC-B03 | KF-07 | AT-09 | Expand item FAQ | Klik salah satu pertanyaan | Pertanyaan terbuka, jawaban tampil; klik lagi → tertutup | _isi_ | _ |
| TC-B04 | KF-08 | AT-09 | Buka Syarat & Ketentuan | Klik menu Syarat & Ketentuan | URL `/syarat-ketentuan/`, muncul 6 section legal | _isi_ | _ |
| TC-B05 | KF-09 | AT-09 | Buka Kebijakan Privasi | Klik menu Kebijakan Privasi | URL `/kebijakan-privasi/`, muncul 5 section privasi | _isi_ | _ |
| TC-B06 | KF-09 | — | Privacy page terdaftar di WP | Settings → Privacy | Halaman "Kebijakan Privasi" sudah ter-set sebagai Privacy Policy Page | _isi_ | _ |
| TC-B07 | KF-10 | AT-09 | Buka Dukungan Pelanggan | Klik menu Dukungan | URL `/dukungan/`, muncul jam ops + cara kontak + link FAQ + link cek pesanan | _isi_ | _ |
| TC-B08 | — | — | Trigger ulang halaman seeder | Sebagai admin akses `/wp-admin/?dyaa_pages=1` | Notice "Manual re-seed pages: 0 halaman baru dibuat (yang sudah ada di-skip)." | _isi_ | _ |

---

## C. Pengujian Katalog & Produk (KF-11 s/d KF-15)

| TC | KF | AT | Skenario | Input | Expected Output | Hasil Aktual | Status |
|---|---|---|---|---|---|---|---|
| TC-C01 | KF-11 | AT-02 | Buka halaman Shop | Klik menu Shop / Semua Paket | URL `/shop/`, grid 8 produk Robux (4 kolom desktop) | _isi_ | _ |
| TC-C02 | KF-12 | AT-03 | Buka detail produk | Klik produk "100 Robux" | URL `/product/100-robux/`, tampil image + title + harga + Add to Cart | _isi_ | _ |
| TC-C03 | KF-12 | AT-03 | Detail produk dengan harga sale | Klik produk yang ada sale (mis. 1700 Robux) | Harga lama coret, harga sale aktif, badge SALE muncul | _isi_ | _ |
| TC-C04 | KF-13 | AT-01 | Lihat 6 kartu kategori di beranda | Scroll ke section Kategori Produk | 6 kartu: Paket Hemat (PROMO), Voucher (TERLARIS), Gamepass, Premium (BARU), Bundle, Limited (HOT) | _isi_ | _ |
| TC-C05 | KF-13 | AT-02 | Filter kategori | Klik kartu kategori "Paket Hemat" | URL `/product-category/paket-hemat/`, hanya tampil produk kategori tsb | _isi_ | _ |
| TC-C06 | KF-14 | AT-01 | Section Flash Sale tampil | Buka beranda | Header ⚡ "Flash Sale Robux" + countdown timer + 3 produk on-sale | _isi_ | _ |
| TC-C07 | KF-14 | AT-01 | Countdown timer berjalan | Tunggu 5 detik di beranda | Detik di countdown turun real-time (visible pada `[data-cd="seconds"]`) | _isi_ | _ |
| TC-C08 | KF-15 | AT-11 | Search produk valid | Topbar → ketik "800" → Enter | URL `/?s=800&post_type=product`, tampil "800 Robux" + variannya | _isi_ | _ |
| TC-C09 | KF-15 | AT-11 | Search produk tidak ada | Ketik "PSN Card" | Tampil pesan "No products were found matching your selection." | _isi_ | _ |

---

## D. Pengujian Autentikasi (KF-16 s/d KF-19)

| TC | KF | AT | Skenario | Input | Expected Output | Hasil Aktual | Status |
|---|---|---|---|---|---|---|---|
| TC-D01 | KF-16 | AT-07 | Buka halaman My Account (logged-out) | Klik tombol "Masuk" di topbar | URL `/my-account/`, layout split-screen (hero kiri + card kanan), tab "Masuk" aktif | _isi_ | _ |
| TC-D02 | KF-16 | AT-07 | Buka via tombol Daftar | Klik tombol "Daftar" di topbar | URL `/my-account/?action=register`, tab "Daftar" aktif | _isi_ | _ |
| TC-D03 | KF-16 | AT-07 | Switch tab tanpa reload | Klik tab "Daftar" di card auth | Pane register aktif, URL berubah ke `?action=register` (tanpa reload halaman) | _isi_ | _ |
| TC-D04 | KF-17 | AT-07 | Registrasi customer baru | Email valid baru + password 8+ char → Submit | Akun terbuat, login otomatis, redirect ke `/my-account/` (Akun Saya) | _isi_ | _ |
| TC-D05 | KF-17 | AT-07 | Registrasi dengan email duplikat | Email yang sudah terdaftar | Notice merah: "An account is already registered with your email address." | _isi_ | _ |
| TC-D06 | KF-18 | AT-07 | Login customer dengan kredensial valid | Email + password yang benar | Redirect ke `/my-account/` (Akun Saya) tampil | _isi_ | _ |
| TC-D07 | KF-18 | AT-07 | Login dengan password salah | Email valid + password salah | Notice: "ERROR: The password you entered for the email address ... is incorrect." | _isi_ | _ |
| TC-D08 | KF-18 | AT-08 | Logout customer | Klik link Logout | Sesi berakhir, redirect ke `/my-account/` (form login lagi) | _isi_ | _ |
| TC-D09 | KF-19 | AT-08 | Tampilan Akun Saya | Login lalu buka `/my-account/` | Menu Woo: Dashboard, Orders, Downloads, Addresses, Account details, Logout | _isi_ | _ |

---

## E. Pengujian Keranjang (KF-20 s/d KF-21)

| TC | KF | AT | Skenario | Input | Expected Output | Hasil Aktual | Status |
|---|---|---|---|---|---|---|---|
| TC-E01 | KF-20 | AT-03 | Tambah ke keranjang | Klik "Add to Cart" pada produk | Notice sukses muncul; counter `.dyaa-cart-badge` di topbar bertambah +1 setelah refresh | _isi_ | _ |
| TC-E02 | KF-20 | AT-04 | Buka keranjang | Klik ikon cart di topbar / bottom nav | URL `/cart/`, tampil tabel item dengan thumbnail + harga + quantity + subtotal | _isi_ | _ |
| TC-E03 | KF-21 | AT-04 | Ubah quantity | Ubah qty 1 → 3 lalu klik Update Cart | Subtotal otomatis berubah (harga × 3) | _isi_ | _ |
| TC-E04 | KF-21 | AT-04 | Hapus item | Klik ikon × pada item | Item hilang dari tabel; total dihitung ulang | _isi_ | _ |
| TC-E05 | KF-21 | AT-04 | Keranjang kosong | Hapus semua item | Pesan "Your cart is currently empty." + tombol "Return to shop" | _isi_ | _ |
| TC-E06 | KF-20 | AT-12 | Counter cart di bottom nav (mobile) | Tambah produk + buka di viewport mobile | Badge angka muncul di icon Keranjang | _isi_ | _ |

---

## F. Pengujian Checkout & Field Roblox (KF-22 s/d KF-25)

> Section paling penting untuk skripsi karena menguji **fitur custom utama** (FT-01).

| TC | KF | AT | Skenario | Input | Expected Output | Hasil Aktual | Status |
|---|---|---|---|---|---|---|---|
| TC-F01 | KF-22 | AT-05 | Buka halaman Checkout | Cart isi → klik Proceed to Checkout | URL `/checkout/`, layout 2 kolom (form kiri, summary kanan) | _isi_ | _ |
| TC-F02 | KF-22 | AT-05 | Section Roblox tampil | Buka checkout | Section "🎮 Data Akun Roblox" muncul setelah catatan order | _isi_ | _ |
| TC-F03 | KF-23 | AT-05 | Checkout sukses dengan data lengkap | Billing valid + Username `roblox_player99` + metode bayar | Redirect ke `/checkout/order-received/...`, halaman thank-you tampil | _isi_ | _ |
| TC-F04 | KF-23 | AT-05 | Validasi: username kosong | Field Username Roblox dikosongkan + submit | Notice merah: "Username Roblox wajib diisi untuk pengiriman Robux." | _isi_ | _ |
| TC-F05 | KF-23 | AT-05 | Validasi: username terlalu pendek | Username `ab` (2 char) | Notice: "Username Roblox harus 3-20 karakter." | _isi_ | _ |
| TC-F06 | KF-23 | AT-05 | Validasi: username terlalu panjang | Username 21 char | Notice: "Username Roblox harus 3-20 karakter." | _isi_ | _ |
| TC-F07 | KF-23 | AT-05 | Validasi: karakter ilegal | Username `roblox-player!` | Notice: "Username Roblox hanya boleh huruf, angka, dan underscore." | _isi_ | _ |
| TC-F08 | KF-23 | AT-05 | Boundary value: tepat 3 karakter | Username `abc` | Submit sukses, order tercatat | _isi_ | _ |
| TC-F09 | KF-23 | AT-05 | Boundary value: tepat 20 karakter | Username `abcdefghij1234567890` | Submit sukses, order tercatat | _isi_ | _ |
| TC-F10 | KF-24 | AT-05 | Pilih Direct Bank Transfer | Pilih Direct bank transfer + submit | Order status: On hold; halaman thank-you tampil instruksi rekening | _isi_ | _ |
| TC-F11 | KF-25 | AT-06 | Username Roblox tampil di Thank You | Lanjut dari TC-F03 | Halaman thank-you menampilkan baris "Username Roblox: roblox_player99" | _isi_ | _ |

---

## F2. Pengujian Pembayaran QRIS (KF-24 — FT-13)

> Sub-section dedikasi untuk gateway custom **WC_Dyaa_QRIS_Gateway** (FT-13).
> Pre-condition: child theme aktif, opsi `dyaastore_qris_default_enabled = 1`
> (auto), halaman Cart & Checkout sudah classic shortcode.

| TC | KF | AT | Skenario | Input | Expected Output | Hasil Aktual | Status |
|---|---|---|---|---|---|---|---|
| TC-F2.1 | KF-24 | AT-15 | QRIS gateway terdaftar di admin | Login `dyaa_admin` → WooCommerce → Settings → Payments | Baris "QRIS — Scan & Bayar" muncul dengan toggle berwarna hijau **Active** | _isi_ | _ |
| TC-F2.2 | KF-24 | AT-15 | Settings page QRIS lengkap | Klik "Manage" pada baris QRIS | 8 field tampil: enabled, title, description, instructions, merchant_name, merchant_nmid, qr_image_url, wa_number, order_status | _isi_ | _ |
| TC-F2.3 | KF-24 | AT-05 | QRIS muncul di payment methods checkout | Cart isi → buka /checkout/ (logout) | Radio "QRIS (Scan & Bayar)" muncul sebagai opsi pertama dengan thumbnail QR 44px di sebelah label | _isi_ | _ |
| TC-F2.4 | KF-24 | AT-05 | QRIS terpilih default | Buka /checkout/ → scroll ke Payment | Radio QRIS sudah `checked` secara default; deskripsi langsung tampil | _isi_ | _ |
| TC-F2.5 | KF-24 | AT-05 | Description tampil saat radio diklik | Klik radio QRIS | Deskripsi: "Bayar dengan scan QRIS pakai aplikasi e-wallet (DANA, OVO, GoPay, ShopeePay)..." | _isi_ | _ |
| TC-F2.6 | KF-24 | AT-06b | Place order QRIS → status on-hold | Billing valid + QRIS + klik Place order | Order status di admin: **On hold**; stok produk berkurang | _isi_ | _ |
| TC-F2.7 | KF-24 | AT-06b | Thank You menampilkan brand-line | Lanjut dari TC-F2.6 | Brand-line "QRIS · dya store · NMID ID1026477730984" tampil di kepala panel | _isi_ | _ |
| TC-F2.8 | KF-24 | AT-06b | Thank You menampilkan gambar QR | Lanjut dari TC-F2.6 | Gambar QR resmi muncul di kolom kiri (white bg, rounded, max-width 320px) | _isi_ | _ |
| TC-F2.9 | KF-24 | AT-06b | Sub-judul menyertakan #order & total | Lanjut dari TC-F2.6 | Teks "Pesanan #{N} menunggu pembayaran sebesar Rp{X}" tampil persis | _isi_ | _ |
| TC-F2.10 | KF-24 | AT-06b | Instruksi 4 langkah pembayaran tampil | Lanjut dari TC-F2.6 | Paragraf "1. Buka aplikasi... 2. Scan... 3. Konfirmasi... 4. Kirim bukti..." tampil utuh | _isi_ | _ |
| TC-F2.11 | KF-24 | AT-06b | Box nominal akurat | Lanjut dari TC-F2.6 | Card menampilkan "Nominal: Rp{total}" dan "Nomor Pesanan: #{N}" dengan font monospace | _isi_ | _ |
| TC-F2.12 | KF-24 | AT-06b | Tombol WhatsApp deeplink valid | Hover tombol → copy link | URL: `https://wa.me/{WA_NUMBER}?text=...` (encoded); query `text` berisi #order, total, Roblox username | _isi_ | _ |
| TC-F2.13 | KF-24 | AT-06b | Link "Unduh gambar QR" berfungsi | Klik link di bawah QR | Browser men-download file `qris-dyaa-store-{N}.png` | _isi_ | _ |
| TC-F2.14 | KF-24 | AT-06b | Panel QRIS di mode terang | Lanjut TC-F2.6, klik theme toggle | Background panel berubah krem/putih, teks tetap kontras, tombol WA tetap hijau | _isi_ | _ |
| TC-F2.15 | KF-24 | AT-06b | Panel QRIS responsif <768px | Buka thank-you di viewport 375×667 | Layout 1 kolom: QR di atas, instruksi di bawah; padding panel 20px | _isi_ | _ |
| TC-F2.16 | KF-24 | AT-06c | Email konfirmasi QRIS terkirim | Lanjut TC-F2.6 → buka email customer | Email berisi blok HTML krem dengan: judul, gambar QR (max-width 280px), tombol WA hijau, instruksi 4 langkah | _isi_ | _ |
| TC-F2.17 | KF-24 | AT-15 | Admin dapat ubah Merchant Name | Settings → QRIS → ubah merchant_name → Save | Brand-line di thank-you page berubah sesuai input baru | _isi_ | _ |
| TC-F2.18 | KF-24 | AT-15 | Admin dapat menonaktifkan QRIS | Uncheck enabled → Save | Reload /checkout/ → QRIS hilang dari payment methods | _isi_ | _ |
| TC-F2.19 | KF-24 | — | Idempotensi auto-enable | Setelah admin nonaktifkan, reload site | QRIS **tetap nonaktif** (flag `dyaastore_qris_default_enabled=1` mencegah overwrite) | _isi_ | _ |
| TC-F2.20 | KF-24 | AT-05 | Halaman Cart dipaksa classic | Buka /cart/ | Form keranjang klasik tampil (bukan Block Cart); tombol "Proceed to checkout" terlihat | _isi_ | _ |

---

## G. Pengujian Manajemen Pesanan oleh Admin (KF-26 s/d KF-31)

| TC | KF | AT | Skenario | Input | Expected Output | Hasil Aktual | Status |
|---|---|---|---|---|---|---|---|
| TC-G01 | KF-26 | — | Login admin valid | `dyaa_admin` + password valid | Masuk ke `/wp-admin/`, dashboard tampil | _isi_ | _ |
| TC-G02 | KF-26 | — | Login admin password salah | password salah | Pesan: "Error: The password you entered for the username ... is incorrect." | _isi_ | _ |
| TC-G03 | KF-27 | — | Tambah produk Robux baru | Add new product: nama "5000 Robux", harga 800.000, kategori Premium | Produk tersimpan, otomatis ter-set sebagai Virtual product | _isi_ | _ |
| TC-G04 | KF-27 | — | Edit harga produk | Ubah harga 18.000 → 16.500 | Harga baru tersimpan; tampil di shop | _isi_ | _ |
| TC-G05 | KF-27 | — | Hapus produk | Klik Trash pada produk | Produk hilang dari shop | _isi_ | _ |
| TC-G06 | KF-27 | — | Trigger ulang seeder produk | Akses `/wp-admin/?dyaa_seed=1` | Notice "Re-seed selesai (yang sudah ada di-skip)" | _isi_ | _ |
| TC-G07 | KF-28 | AT-15a | Lihat kolom Username Roblox di order list | WooCommerce → Orders | Kolom **Username Roblox** muncul setelah kolom Status | _isi_ | _ |
| TC-G08 | KF-28 | AT-15a | Order tanpa username (mis. order legacy) | Buka order yang dibuat sebelum field aktif | Kolom Username Roblox menampilkan tanda `—` | _isi_ | _ |
| TC-G09 | KF-29 | AT-15b | Detail order: Username Roblox tampil | Klik order yang dibuat di TC-F03 | Di sidebar (di bawah Billing Address) muncul "Username Roblox: roblox_player99" | _isi_ | _ |
| TC-G10 | KF-30 | — | Ubah status order | Order Pending → ubah ke Processing | Status berubah, tabel order list ter-update | _isi_ | _ |
| TC-G11 | KF-30 | — | Ubah status order ke Completed | Status → Completed | Status berubah ke Completed; trigger email otomatis ke customer | _isi_ | _ |
| TC-G12 | KF-31 | AT-15c | Dashboard widget tampil | Buka `/wp-admin/index.php` | Widget "🎮 Dyaa Store — Ringkasan" muncul dengan jumlah produk + order | _isi_ | _ |

---

## H. Pengujian Notifikasi Email (KF-32 s/d KF-34)

> **Catatan**: di lingkungan Laragon lokal, email tidak terkirim secara default. Untuk pengujian gunakan **WP Mail SMTP** + Mailtrap (sandbox) atau cek lewat file log `wp-content/debug.log` setelah aktifkan `WP_DEBUG_LOG`.

| TC | KF | AT | Skenario | Input | Expected Output | Hasil Aktual | Status |
|---|---|---|---|---|---|---|---|
| TC-H01 | KF-32 | — | Email konfirmasi ke customer | Order baru dibuat (TC-F03) | Email "Your order is confirmed" diterima customer dengan **Username Roblox** tercantum | _isi_ | _ |
| TC-H02 | KF-33 | — | Email pemberitahuan ke admin | Order baru dibuat | Email "New order: #XXXX" diterima admin dengan Username Roblox | _isi_ | _ |
| TC-H03 | KF-34 | — | Email order completed | Admin ubah status ke Completed (TC-G11) | Email "Your order is now complete" diterima customer | _isi_ | _ |

---

## I. Pengujian Antarmuka & Fitur Khas (KF-35, KF-36, KNF-04, KNF-07)

| TC | KF/KNF | AT | Skenario | Input | Expected Output | Hasil Aktual | Status |
|---|---|---|---|---|---|---|---|
| TC-I01 | KF-35 | AT-14b | WhatsApp sticker tampil | Buka homepage (desktop) | Sticker oranye + balon "BUTUH BANTUAN — KLIK DISINI!!" muncul kanan-bawah | _isi_ | _ |
| TC-I02 | KF-35 | AT-14b | Klik WhatsApp sticker | Klik sticker | Tab baru terbuka ke `https://wa.me/6289515881150?text=...` dengan teks pre-filled | _isi_ | _ |
| TC-I03 | KF-36 | AT-14c | Live toast tampil | Buka homepage, tunggu 4 detik | Toast tampil di kiri-bawah dengan nama + produk + waktu | _isi_ | _ |
| TC-I04 | KF-36 | AT-14c | Live toast rotasi | Tunggu 8 detik tambahan | Konten toast berganti ke transaksi berikutnya | _isi_ | _ |
| TC-I05 | KF-36 | AT-14c | Tutup live toast | Klik tombol × | Toast hilang dan tidak muncul lagi sampai refresh | _isi_ | _ |
| TC-I06 | KNF-04 | AT-01 | Responsive desktop | Viewport 1920×1080 | Layout 3 kolom (sidebar + main + topbar full); tidak ada horizontal scroll | _isi_ | _ |
| TC-I07 | KNF-04 | AT-01 | Responsive tablet | Viewport 1024×768 atau lebih sempit hingga 769px | Sidebar tersembunyi (drawer); tombol hamburger tampil; topbar beri ruang kiri untuk hamburger; tidak ada horizontal scroll | _isi_ | _ |
| TC-I08 | KNF-04 | AT-01 | Responsive mobile | Viewport 375×667 | Bottom nav muncul; sidebar tersembunyi (hamburger); konten 1 kolom; tidak ada horizontal scroll | _isi_ | _ |
| TC-I09 | KNF-07 | AT-14a | Pre-paint anti-flash | Set mode terang → close tab → buka kembali | Halaman langsung tampil mode terang, **tanpa flash gelap** sepersekian detik | _isi_ | _ |

---

## Ringkasan Hasil Pengujian (Diisi Setelah Eksekusi)

| Kategori | Total TC | Pass | Fail | % Berhasil |
|---|---|---|---|---|
| A. Akses & Navigasi | 13 | _ | _ | _ |
| B. Halaman Statis | 8 | _ | _ | _ |
| C. Katalog & Produk | 9 | _ | _ | _ |
| D. Autentikasi | 9 | _ | _ | _ |
| E. Keranjang | 6 | _ | _ | _ |
| F. Checkout & Field Roblox | 11 | _ | _ | _ |
| F2. Pembayaran QRIS (FT-13) | 20 | _ | _ | _ |
| G. Manajemen Pesanan Admin | 12 | _ | _ | _ |
| H. Notifikasi Email | 3 | _ | _ | _ |
| I. Antarmuka & Fitur Khas | 9 | _ | _ | _ |
| **TOTAL** | **100** | _ | _ | _ |

### Rumus Persentase
```
% Berhasil = (Jumlah TC Pass / Total TC) × 100%
```

### Kriteria Kelulusan Sistem (sesuai BAB III §3.2.4)
| % Berhasil | Kategori | Tindakan |
|---|---|---|
| ≥ 95% | Sangat Layak | Sistem siap production |
| 85% – 94% | Layak | Sistem dapat dipakai dengan catatan minor |
| 70% – 84% | Cukup Layak | Perlu perbaikan pada TC yang gagal |
| < 70% | Tidak Layak | Sistem belum dapat di-rilis, perbaikan menyeluruh |

### Kesimpulan
> _Diisi setelah eksekusi_, contoh format:
>
> "Berdasarkan hasil pengujian Black Box dengan total 100 *test case* yang dieksekusi pada lingkungan Laragon + WordPress 6.9 + WooCommerce 10.7 + child theme `dyaastore-child`, diperoleh tingkat keberhasilan sebesar **___ %** (Pass: ___, Fail: ___). Sub-kategori F2 (20 TC) khusus memverifikasi gateway pembayaran QRIS (FT-13) yang dibangun sebagai *custom WooCommerce payment gateway* dengan model verifikasi manual via WhatsApp — sesuai batasan BAB I §1.4 yang tidak melibatkan integrasi API payment gateway pihak ketiga. Tiga TC tambahan (TC-A11–TC-A13) memverifikasi kompatibilitas dengan tema parent Hello Elementor (`reset.css`), perilaku sidebar off-canvas, dan invalidasi cache stylesheet lewat `filemtime()` (FT-14). Dengan demikian sistem Dyaa Store dinyatakan **___** sesuai kriteria kelulusan BAB III §3.2.4. Test case yang gagal serta upaya perbaikannya dijelaskan pada Lampiran B."

---

## Lampiran A — Catatan Pelaksanaan Pengujian

| Aspek | Catatan |
|---|---|
| Tester | Wahyu Akbar Pratama Siregar (NIM 0110122029) |
| Tanggal pelaksanaan | _isi_ |
| Versi sistem yang diuji | child theme `dyaastore-child` v _isi_ |
| Database snapshot | _isi (export `.sql` jika perlu)_ |
| Bukti screenshot | Disimpan di `docs/screenshots/` (lihat naming di `06-implementasi-antarmuka.md`) |

## Lampiran B — Test Case yang Gagal (jika ada)

| TC | Skenario | Penyebab | Tindakan Perbaikan | Status Setelah Fix |
|---|---|---|---|---|
| _TC-XX0N_ | _isi singkat_ | _isi root cause_ | _isi langkah perbaikan_ | _Pass setelah retest_ |
