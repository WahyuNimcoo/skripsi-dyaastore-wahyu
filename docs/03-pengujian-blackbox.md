# Skenario Pengujian Black Box — Dyaa Store

> Acuan: Skripsi Bab 2.4.3 (Black Box Testing) & Bab 3.2.4 (Metode Pengujian)
> Diisi setelah implementasi selesai. Setiap baris akan jadi bukti pengujian di **BAB IV** skripsi.

## Format Pengujian

| ID | Skenario | Input | Expected Output | Actual Output | Status |
|----|----------|-------|-----------------|---------------|--------|

---

## A. Pengujian Modul Autentikasi

| ID | Skenario | Input | Expected Output | Actual Output | Status |
|----|----------|-------|-----------------|---------------|--------|
| TC-A01 | Login admin dengan kredensial valid | username: `dyaa_admin`, password: valid | Masuk ke dashboard WP | _diisi_ | _Pass/Fail_ |
| TC-A02 | Login admin dengan password salah | username: valid, password: salah | Pesan error "Password salah" | _diisi_ | _Pass/Fail_ |
| TC-A03 | Registrasi customer baru | data lengkap dan valid | Akun terbuat, login otomatis | _diisi_ | _Pass/Fail_ |
| TC-A04 | Registrasi dengan email duplikat | email yang sudah terdaftar | Pesan error "Email sudah digunakan" | _diisi_ | _Pass/Fail_ |
| TC-A05 | Logout customer | klik tombol logout | Sesi berakhir, redirect ke beranda | _diisi_ | _Pass/Fail_ |

## B. Pengujian Manajemen Produk (Admin)

| ID | Skenario | Input | Expected Output | Actual Output | Status |
|----|----------|-------|-----------------|---------------|--------|
| TC-B01 | Tambah produk Robux baru | nama, harga, gambar, deskripsi | Produk muncul di halaman shop | _diisi_ | _Pass/Fail_ |
| TC-B02 | Edit harga produk | ubah harga produk eksisting | Harga baru tersimpan dan tampil | _diisi_ | _Pass/Fail_ |
| TC-B03 | Hapus produk | klik delete pada produk | Produk hilang dari katalog | _diisi_ | _Pass/Fail_ |
| TC-B04 | Tambah produk dengan harga kosong | harga = blank | Form gagal submit, ada validasi | _diisi_ | _Pass/Fail_ |
| TC-B05 | Set produk sebagai Virtual & Downloadable | centang opsi virtual | Produk tidak butuh shipping | _diisi_ | _Pass/Fail_ |

## C. Pengujian Katalog & Pencarian (Customer)

| ID | Skenario | Input | Expected Output | Actual Output | Status |
|----|----------|-------|-----------------|---------------|--------|
| TC-C01 | Buka halaman shop | klik menu Shop | Tampil grid produk Robux | _diisi_ | _Pass/Fail_ |
| TC-C02 | Klik detail produk | klik salah satu paket | Halaman detail tampil | _diisi_ | _Pass/Fail_ |
| TC-C03 | Cari produk via search | keyword: "800" | Produk 800 Robux tampil | _diisi_ | _Pass/Fail_ |
| TC-C04 | Filter berdasarkan kategori | pilih kategori tertentu | Hanya produk di kategori itu tampil | _diisi_ | _Pass/Fail_ |

## D. Pengujian Keranjang Belanja

| ID | Skenario | Input | Expected Output | Actual Output | Status |
|----|----------|-------|-----------------|---------------|--------|
| TC-D01 | Tambah ke keranjang | klik "Add to Cart" | Notifikasi sukses, counter bertambah | _diisi_ | _Pass/Fail_ |
| TC-D02 | Ubah quantity di cart | ubah qty 1 → 3 | Subtotal berubah otomatis | _diisi_ | _Pass/Fail_ |
| TC-D03 | Hapus item dari cart | klik X pada item | Item hilang, total update | _diisi_ | _Pass/Fail_ |
| TC-D04 | Keranjang kosong | hapus semua item | Pesan "Cart is empty" tampil | _diisi_ | _Pass/Fail_ |

## E. Pengujian Checkout & Pembayaran

| ID | Skenario | Input | Expected Output | Actual Output | Status |
|----|----------|-------|-----------------|---------------|--------|
| TC-E01 | Checkout dengan field lengkap | data + Username Roblox | Order tercatat status Pending | _diisi_ | _Pass/Fail_ |
| TC-E02 | Checkout tanpa Username Roblox | username kosong | Validasi error: "Wajib diisi" | _diisi_ | _Pass/Fail_ |
| TC-E03 | Pilih pembayaran Transfer Bank | metode: Bank Transfer | Tampil instruksi rekening | _diisi_ | _Pass/Fail_ |
| TC-E04 | Pilih pembayaran E-Wallet | metode: e-wallet | Redirect ke gateway | _diisi_ | _Pass/Fail_ |
| TC-E05 | Pilih pembayaran Virtual Account | metode: VA | Tampil nomor VA | _diisi_ | _Pass/Fail_ |

## F. Pengujian Manajemen Pesanan (Admin)

| ID | Skenario | Input | Expected Output | Actual Output | Status |
|----|----------|-------|-----------------|---------------|--------|
| TC-F01 | Lihat daftar pesanan | buka WooCommerce → Orders | Daftar order tampil | _diisi_ | _Pass/Fail_ |
| TC-F02 | Lihat detail pesanan | klik order | Detail termasuk Username Roblox tampil | _diisi_ | _Pass/Fail_ |
| TC-F03 | Ubah status order ke Completed | klik update status | Status berubah, customer ter-notif | _diisi_ | _Pass/Fail_ |
| TC-F04 | Filter pesanan by status | pilih "Processing" | Hanya order processing tampil | _diisi_ | _Pass/Fail_ |

## G. Pengujian Notifikasi

| ID | Skenario | Input | Expected Output | Actual Output | Status |
|----|----------|-------|-----------------|---------------|--------|
| TC-G01 | Email konfirmasi order baru ke customer | order baru dibuat | Email diterima | _diisi_ | _Pass/Fail_ |
| TC-G02 | Email notifikasi ke admin | order baru dibuat | Admin terima email | _diisi_ | _Pass/Fail_ |
| TC-G03 | Email order completed | status: completed | Customer terima email selesai | _diisi_ | _Pass/Fail_ |

---

## Ringkasan Hasil Pengujian (Diisi Setelah Eksekusi)

| Kategori | Total TC | Pass | Fail | % Berhasil |
|----------|----------|------|------|------------|
| A. Autentikasi | 5 | _ | _ | _ |
| B. Manajemen Produk | 5 | _ | _ | _ |
| C. Katalog | 4 | _ | _ | _ |
| D. Keranjang | 4 | _ | _ | _ |
| E. Checkout | 5 | _ | _ | _ |
| F. Manajemen Pesanan | 4 | _ | _ | _ |
| G. Notifikasi | 3 | _ | _ | _ |
| **TOTAL** | **30** | _ | _ | _ |

**Kesimpulan**: _diisi setelah pengujian (mis. "Sistem dinyatakan layak digunakan dengan tingkat keberhasilan X%")_
