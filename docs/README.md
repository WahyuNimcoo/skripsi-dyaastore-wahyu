# Dokumentasi Skripsi — Dyaa Store

Folder `docs/` berisi 7 dokumen yang menjadi **bukti pengembangan** Tugas Akhir:

> **"Rancang Bangun Website E-Commerce Dyaa Store Berbasis WordPress Menggunakan WooCommerce dengan Metode Waterfall untuk Penjualan Robux"**
>
> Wahyu Akbar Pratama Siregar — NIM 0110122029 — Sistem Informasi STT-NF 2026

---

## Pemetaan Dokumen ↔ BAB Skripsi

| File | Bagian Skripsi | Cakupan |
|---|---|---|
| [`01-analisis-kebutuhan.md`](01-analisis-kebutuhan.md) | **BAB III §3.1.2** | Profil sistem, **36 KF**, 7 KNF, batasan, kebutuhan pengguna, *traceability matrix* |
| [`02-perancangan-uml.md`](02-perancangan-uml.md) | **BAB III §3.1.3** | Use case, activity, class, sequence, ERD, struktur navigasi, wireframe |
| [`05-panduan-implementasi.md`](05-panduan-implementasi.md) | **BAB IV §4.1.1** | Implementasi lingkungan + langkah instalasi 4 step |
| [`06-implementasi-antarmuka.md`](06-implementasi-antarmuka.md) | **BAB IV §4.1.2** | Implementasi antarmuka per halaman (15 kode AT) |
| [`07-implementasi-fitur.md`](07-implementasi-fitur.md) | **BAB IV §4.1.3** | Implementasi fitur level kode (14 kode FT) + listing program |
| [`03-pengujian-blackbox.md`](03-pengujian-blackbox.md) | **BAB IV §4.2.1** | 100 *test case* Black Box |
| [`04-kuesioner-likert.md`](04-kuesioner-likert.md) | **BAB IV §4.2.2** | Kuesioner 24 pernyataan dalam 7 aspek + rumus pengolahan |

---

## Sistem Penomoran (Traceability)

Untuk memudahkan rujukan silang antar bab, dipakai 3 jenis kode:

| Prefix | Arti | Contoh | Sumber |
|---|---|---|---|
| **KF-XX** | Kebutuhan Fungsional | KF-23 (Custom Field Roblox) | `01-analisis-kebutuhan.md` |
| **AT-XX** | Antarmuka (UI) | AT-05 (Halaman Checkout) | `06-implementasi-antarmuka.md` |
| **FT-XX** | Fitur (Code-Level) | FT-01 (Custom Field Roblox) | `07-implementasi-fitur.md` |
| **TC-XNN** | Test Case Black Box | TC-F04 (validasi username kosong) | `03-pengujian-blackbox.md` |

### Contoh alur rujukan

```
KF-23  (Field Username Roblox di checkout)
   │
   ├─→ AT-05  (Halaman Checkout — bagaimana ditampilkan)
   ├─→ FT-01  (Listing program: hooks woocommerce_*, validasi)
   └─→ TC-F03..TC-F09  (test case untuk verifikasi)
```

Setiap KF di `01-analisis-kebutuhan.md` **dijamin** punya minimal 1 AT, 1 FT, dan 1 TC yang merujuk balik.

---

## Cara Menggunakan untuk BAB IV Skripsi

Saat menulis BAB IV, kamu bisa menyalin / paraphrase dari file-file ini sesuai pemetaan di atas:

### §4.1 Implementasi Sistem
1. **§4.1.1 Lingkungan Implementasi** → ringkasan dari `05-panduan-implementasi.md` §1, §2.
2. **§4.1.2 Implementasi Antarmuka** → konten utama dari `06-implementasi-antarmuka.md`.
   - Setiap subbab memuat 1 kode AT + screenshot yang sudah disiapkan di `docs/screenshots/`.
3. **§4.1.3 Implementasi Fitur (Listing Program)** → konten utama dari `07-implementasi-fitur.md`.
   - Setiap subbab memuat 1 kode FT + cuplikan kode yang sudah ada di file ini.

### §4.2 Pengujian Sistem
1. **§4.2.1 Pengujian Black Box** → tabel test case dari `03-pengujian-blackbox.md` (100 TC dalam 9 kategori + sub-kategori F2 QRIS).
2. **§4.2.2 Pengujian Skala Likert** → kuesioner & analisis dari `04-kuesioner-likert.md` (24 pernyataan dalam 7 aspek).

### §4.3 Hasil & Pembahasan (jika ada)
- Rangkum *Pass rate* Black Box + persentase Likert per aspek.
- Bandingkan dengan kriteria kelulusan di `03-pengujian-blackbox.md` §Ringkasan dan kategori interpretasi di `04-kuesioner-likert.md` §5.4.

---

## Struktur Folder Pendukung

```
docs/
├── README.md                          ← (file ini) indeks & mapping
├── 01-analisis-kebutuhan.md           ← BAB III §3.1.2
├── 02-perancangan-uml.md              ← BAB III §3.1.3
├── 03-pengujian-blackbox.md           ← BAB IV §4.2.1
├── 04-kuesioner-likert.md             ← BAB IV §4.2.2
├── 05-panduan-implementasi.md         ← BAB IV §4.1.1
├── 06-implementasi-antarmuka.md       ← BAB IV §4.1.2
├── 07-implementasi-fitur.md           ← BAB IV §4.1.3
└── screenshots/                       ← bukti visual (siapkan saat menulis BAB IV)
    ├── 01-beranda-desktop-dark.png
    ├── 01-beranda-desktop-light.png
    ├── 01-beranda-mobile.png
    ├── 02-shop-grid.png
    ├── 03-detail-produk.png
    ├── 04-cart-isi.png
    ├── 05-checkout-form-isi.png
    ├── 05-checkout-validasi-error.png
    ├── 06-thankyou.png
    ├── 07-auth-login-dark.png
    ├── 07-auth-register-dark.png
    ├── 09a-tentang.png    … 09e-dukungan.png
    ├── 10-sidebar-desktop.png
    ├── 11-topbar-loggedout.png
    ├── 12-bottomnav-mobile.png
    ├── 13-footer.png
    ├── 14b-wa-sticker.png
    ├── 14c-live-toast.png
    └── 15a-admin-orders-list.png … 15c-dashboard-widget.png
```

> Penamaan screenshot mengikuti **kode AT** di `06-implementasi-antarmuka.md`. Total ada ~24 file yang perlu disiapkan untuk BAB IV §4.1.2.

---

## Checklist Menjelang Sidang

| Item | Status |
|---|---|
| Semua KF (36) terimplementasi & punya rujukan | ✅ (lihat traceability) |
| Semua halaman antarmuka punya screenshot | ⏳ ambil saat finalisasi |
| Black Box: 100 TC sudah dieksekusi & diisi Pass/Fail | ⏳ saat pengujian |
| Likert: minimal 20 responden mengisi (24 pernyataan) | ⏳ saat sebar kuesioner |
| Ringkasan eksekutif untuk slide presentasi | ⏳ |
| Repository GitHub (kode + docs) sudah final & dapat di-clone | ✅ ([dyaastore](https://github.com/WahyuNimcoo/dyaastore)) |

---

## Tools Tambahan

- **Diagram**: dokumen menggunakan format **Mermaid** yang dapat di-render langsung di GitHub atau VS Code (pasang ekstensi `Markdown Preview Mermaid Support`).
- **Konversi ke Word**: untuk menyusun naskah BAB IV, gunakan [Pandoc](https://pandoc.org/):
  ```bash
  pandoc docs/06-implementasi-antarmuka.md -o bab4-1-2.docx --reference-doc=template-skripsi.docx
  ```
- **Screenshot**: gunakan ekstensi browser seperti **GoFullPage** untuk capture full halaman, simpan di `docs/screenshots/` dengan nama yang persis seperti tertulis di file `06-implementasi-antarmuka.md`.

---

## Kontak

Pertanyaan teknis terkait sistem ini bisa dilihat di `wp-content/themes/dyaastore-child/readme.txt` atau lewat repository GitHub di atas.
