# Perancangan Sistem (UML) — Dyaa Store E-Commerce

> Acuan: Skripsi Bab 2.4.2 (UML) & Bab 3.1.3 (Perancangan)
> Diagram menggunakan **Mermaid** (dapat dirender langsung di GitHub/VS Code)

## 1. Use Case Diagram

```mermaid
flowchart LR
    Customer((Customer))
    Admin((Admin))

    subgraph Sistem["Website E-Commerce Dyaa Store"]
        UC1[Lihat Katalog Robux]
        UC2[Daftar / Login Akun]
        UC3[Tambah ke Keranjang]
        UC4[Checkout Pesanan]
        UC5[Pilih Metode Pembayaran]
        UC6[Lihat Status Pesanan]
        UC7[Kelola Produk Robux]
        UC8[Kelola Pesanan]
        UC9[Konfirmasi Pengiriman Robux]
        UC10[Lihat Laporan Penjualan]
    end

    Customer --> UC1
    Customer --> UC2
    Customer --> UC3
    Customer --> UC4
    Customer --> UC5
    Customer --> UC6
    Admin --> UC7
    Admin --> UC8
    Admin --> UC9
    Admin --> UC10
```

### Skenario Use Case Utama

#### UC-04: Checkout Pesanan
- **Aktor**: Customer
- **Pre-condition**: Customer sudah login dan memiliki item di keranjang
- **Main Flow**:
  1. Customer klik tombol "Checkout"
  2. Sistem menampilkan form data pengiriman (termasuk Username Roblox)
  3. Customer mengisi data dan memilih metode pembayaran
  4. Sistem menghitung total dan menampilkan ringkasan
  5. Customer konfirmasi pesanan
  6. Sistem mencatat order dengan status "Pending Payment"
- **Post-condition**: Order tercatat dan Customer diarahkan ke instruksi pembayaran

---

## 2. Activity Diagram — Alur Pembelian Robux

```mermaid
flowchart TD
    Start([Mulai]) --> A[Customer membuka website]
    A --> B[Pilih paket Robux]
    B --> C[Klik 'Add to Cart']
    C --> D[Buka halaman keranjang]
    D --> E{Login?}
    E -- Belum --> F[Login / Daftar]
    E -- Sudah --> G[Klik Checkout]
    F --> G
    G --> H[Isi data: nama, email, Username Roblox]
    H --> I[Pilih metode pembayaran]
    I --> J{Pembayaran berhasil?}
    J -- Ya --> K[Order status: Processing]
    J -- Tidak --> L[Order status: Failed]
    K --> M[Admin menerima notifikasi]
    M --> N[Admin kirim Robux manual]
    N --> O[Admin ubah status: Completed]
    O --> P[Customer terima notifikasi via email]
    P --> End([Selesai])
    L --> End
```

---

## 3. Class Diagram (Konseptual berbasis WooCommerce)

```mermaid
classDiagram
    class User {
        +int id
        +string username
        +string email
        +string password
        +string role
        +register()
        +login()
    }

    class Customer {
        +string roblox_username
        +string phone
        +placeOrder()
        +viewOrderHistory()
    }

    class Admin {
        +manageProducts()
        +processOrders()
        +viewReports()
    }

    class Product {
        +int product_id
        +string name
        +int robux_amount
        +decimal price
        +string description
        +bool is_virtual
        +bool is_downloadable
    }

    class Cart {
        +int cart_id
        +int user_id
        +addItem()
        +removeItem()
        +calculateTotal()
    }

    class Order {
        +int order_id
        +int customer_id
        +datetime order_date
        +decimal total
        +string status
        +string roblox_username
        +createOrder()
        +updateStatus()
    }

    class Payment {
        +int payment_id
        +int order_id
        +string method
        +decimal amount
        +string status
        +processPayment()
    }

    User <|-- Customer
    User <|-- Admin
    Customer "1" --> "*" Order : places
    Order "1" --> "*" Product : contains
    Order "1" --> "1" Payment : has
    Customer "1" --> "1" Cart : owns
    Cart "1" --> "*" Product : contains
```

---

## 4. Sequence Diagram — Proses Checkout

```mermaid
sequenceDiagram
    actor C as Customer
    participant W as Website (WordPress)
    participant WC as WooCommerce
    participant DB as Database
    participant PG as Payment Gateway
    actor A as Admin

    C->>W: Klik Checkout
    W->>WC: Validasi cart
    WC->>C: Tampilkan form checkout + field Username Roblox
    C->>W: Submit data + pilih metode bayar
    W->>WC: Buat order (status: Pending Payment)
    WC->>DB: Simpan data order
    WC->>PG: Request pembayaran
    PG-->>C: Tampilkan instruksi bayar
    C->>PG: Lakukan pembayaran
    PG-->>WC: Notifikasi pembayaran sukses
    WC->>DB: Update order status: Processing
    WC->>A: Email notifikasi pesanan baru
    WC-->>C: Email konfirmasi pesanan
    A->>WC: Kirim Robux manual
    A->>WC: Ubah status: Completed
    WC-->>C: Email pesanan selesai
```

---

## 5. Struktur Navigasi Website

```
Beranda (/)
├── Shop / Katalog Robux (/shop)
│   └── Detail Produk (/product/{slug})
├── Keranjang (/cart)
├── Checkout (/checkout)
├── Akun Saya (/my-account)
│   ├── Riwayat Pesanan
│   └── Edit Profil
├── Cara Pesan / FAQ (/cara-pesan)
└── Kontak (/kontak)

Admin Dashboard (/wp-admin)
├── Dashboard
├── WooCommerce → Pesanan
├── Produk
└── Laporan
```

---

## 6. Wireframe Tekstual

### 6.1 Halaman Beranda
```
┌─────────────────────────────────────────────┐
│  [Logo Dyaa Store]    Menu: Home Shop FAQ   │
├─────────────────────────────────────────────┤
│                                             │
│   HERO: "Top Up Robux Termurah & Tercepat"  │
│         [Tombol: Belanja Sekarang]          │
│                                             │
├─────────────────────────────────────────────┤
│   PAKET POPULER                             │
│   ┌──────┐  ┌──────┐  ┌──────┐  ┌──────┐    │
│   │ 80 R │  │ 400R │  │ 800R │  │ 1700 │    │
│   │ Rp14k│  │ Rp65k│  │ Rp130│  │ Rp275│    │
│   └──────┘  └──────┘  └──────┘  └──────┘    │
├─────────────────────────────────────────────┤
│   CARA PESAN (3 step icon)                  │
├─────────────────────────────────────────────┤
│   FOOTER: Kontak | FAQ | Sosial Media       │
└─────────────────────────────────────────────┘
```

### 6.2 Halaman Checkout
```
┌─────────────────────────────────────────────┐
│  Detail Pemesan          | Ringkasan Pesanan│
│  ─────────────────       | ───────────────  │
│  Nama Lengkap: [____]    | 400 Robux  x1    │
│  Email: [____]           | Subtotal: 65.000 │
│  No. HP: [____]          | Total:    65.000 │
│  Username Roblox: [____] |                  │
│                          | Metode Bayar:    │
│  ─────────────────       | ( ) E-Wallet    │
│                          | ( ) VA          │
│                          | ( ) Transfer     │
│                          |                  │
│                          | [Bayar Sekarang] │
└─────────────────────────────────────────────┘
```
