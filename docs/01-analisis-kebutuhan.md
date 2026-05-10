# Analisis Kebutuhan Sistem — Website E-Commerce Dyaa Store

> Acuan: Skripsi Wahyu Akbar Pratama Siregar (NIM 0110122029) — STT-NF, 2026
> Tahapan Waterfall: **3.1.2 Analisis Kebutuhan**

## 1. Profil Sistem
- **Nama Sistem**: Website E-Commerce Dyaa Store
- **Tujuan**: Penjualan produk digital Robux untuk platform Roblox
- **Aktor**: Customer (pembeli) & Admin (pemilik toko)
- **Platform**: WordPress 6.9.4 + WooCommerce + Elementor (sesuai Bab 2.3 skripsi)

## 2. Kebutuhan Fungsional (KF)

| Kode | Aktor | Kebutuhan Fungsional |
|------|-------|---------------------|
| KF-01 | Customer | Sistem dapat menampilkan halaman utama dengan daftar paket Robux |
| KF-02 | Customer | Sistem dapat menampilkan detail produk Robux (jumlah, harga, deskripsi) |
| KF-03 | Customer | Sistem dapat melakukan pendaftaran akun pengguna baru |
| KF-04 | Customer | Sistem dapat melakukan login dan logout |
| KF-05 | Customer | Sistem dapat menambahkan produk ke keranjang belanja |
| KF-06 | Customer | Sistem dapat menampilkan halaman keranjang dan total pembayaran |
| KF-07 | Customer | Sistem dapat memproses checkout dan menerima username Roblox pembeli |
| KF-08 | Customer | Sistem dapat menyediakan pilihan metode pembayaran (e-wallet, virtual account, transfer bank) |
| KF-09 | Customer | Sistem dapat menampilkan status pesanan (Pending → Processing → Completed) |
| KF-10 | Customer | Sistem dapat mengirim notifikasi konfirmasi pesanan via email |
| KF-11 | Admin | Sistem dapat melakukan login admin dengan kredensial khusus |
| KF-12 | Admin | Sistem dapat menambah, mengubah, dan menghapus produk Robux |
| KF-13 | Admin | Sistem dapat menampilkan daftar pesanan masuk |
| KF-14 | Admin | Sistem dapat mengubah status pesanan setelah Robux dikirim |
| KF-15 | Admin | Sistem dapat melihat laporan transaksi penjualan |

## 3. Kebutuhan Non-Fungsional (KNF)

| Kode | Aspek | Deskripsi |
|------|-------|-----------|
| KNF-01 | Keamanan | Sistem menggunakan autentikasi password dan HTTPS pada lingkungan produksi |
| KNF-02 | Usability | Antarmuka mudah digunakan oleh pengguna awam (target: skor Skala Likert ≥ 4) |
| KNF-03 | Performance | Halaman utama dimuat ≤ 3 detik pada koneksi 4G standar |
| KNF-04 | Compatibility | Tampilan responsif di perangkat desktop, tablet, dan mobile |
| KNF-05 | Maintainability | Sistem dibangun pada CMS WordPress sehingga mudah dipelihara tanpa pemrograman langsung |
| KNF-06 | Reliability | Data transaksi tersimpan di database MySQL dengan integritas data terjaga |

## 4. Batasan Sistem (mengikat — lihat Bab 1.4 skripsi)

1. ❌ **Tidak** ada integrasi API resmi Roblox (Robux dikirim manual oleh Admin)
2. ❌ **Tidak** ada pengembangan backend custom di luar ekosistem WordPress
3. ✅ Pembayaran terbatas pada e-wallet, virtual account, dan transfer bank
4. ✅ Fitur dibatasi pada inti e-commerce (katalog, cart, checkout, konfirmasi, dashboard admin)
5. ❌ **Tidak** mencakup integrasi marketplace pihak ketiga, sistem rekomendasi otomatis, atau analitik mendalam

## 5. Kebutuhan Pengguna (User Requirements)

### 5.1 Customer
- Ingin melihat paket Robux dengan harga jelas
- Ingin proses pembelian cepat (tidak perlu chat WhatsApp)
- Ingin metode pembayaran yang familiar
- Ingin tahu status pesanannya tanpa harus bertanya

### 5.2 Admin (Pemilik Dyaa Store)
- Ingin pesanan tercatat otomatis tanpa input manual
- Ingin notifikasi setiap ada pesanan baru
- Ingin laporan penjualan terstruktur
- Ingin rekap stok dan transaksi tersedia kapanpun
