# BAB IV 4.1.3 — Implementasi Fitur (Code-Level)

> **Acuan Skripsi**: BAB IV §4.1.3 (Implementasi Fitur / Listing Program)
> **Cakupan**: dokumentasi level kode untuk setiap fitur custom Dyaa Store.
> **Untuk skripsi**: setiap snippet di bawah dapat di-copy ke "Lampiran Listing Program" atau dijadikan kotak kode di BAB IV.

---

## Konvensi Pengkodean (sudah diterapkan)

| Aspek | Konvensi | Alasan |
|---|---|---|
| Function PHP | prefix `dyaastore_` (snake_case) | Hindari kolisi dengan plugin/theme lain |
| Class CSS | prefix `.dyaa-` (kebab-case) | Hindari kolisi dengan WooCommerce/Elementor |
| Constant | prefix `DYAA_` (UPPER_SNAKE) | Konvensi PHP standar |
| Shortcode | prefix `[dyaa_]` | Mudah dikenali di Elementor |
| Order meta | prefix `_` (underscore-leading) | Konvensi WP utk hidden meta |
| Translation domain | `dyaastore-child` | Sesuai slug child theme |
| Inline doc | PHPDoc + WordPress Coding Standards | Mempermudah review oleh dosen |

---

## Daftar Fitur yang Dibahas

| Kode FT | Fitur | Lokasi File | KF Tracking |
|---|---|---|---|
| FT-01 | Custom Field "Username Roblox" di Checkout | `functions.php` | KF-23 |
| FT-02 | Theme Toggle Pill Switch (Dark / Light Mode) | `functions.php`, `style.css`, `dyaastore.js` | KF-05 |
| FT-03 | Auth Split-Screen + Tab Login/Daftar | `woocommerce/myaccount/form-login.php`, `dyaastore.js` | KF-16, KF-17 |
| FT-04 | Flash Sale + Countdown Timer | `functions.php` (shortcode), `dyaastore.js` | KF-14 |
| FT-05 | Live Transaction Toast (Social Proof) | `functions.php`, `dyaastore.js` | KF-36 |
| FT-06 | Auto-Create 5 Halaman Statis | `mu-plugins/dyaastore-pages.php` | KF-06–KF-10 |
| FT-07 | Auto-Create 6 Kategori + 8 Produk Demo | `mu-plugins/dyaastore-seeder.php` | KF-13, KF-27 |
| FT-08 | Kolom Username Roblox di Order List Admin (Legacy + HPOS) | `mu-plugins/dyaastore-helpers.php` | KF-28 |
| FT-09 | Dashboard Widget Ringkasan untuk Admin | `mu-plugins/dyaastore-helpers.php` | KF-31 |
| FT-10 | Sidebar Custom 3-Grup + WhatsApp CTA | `functions.php` | KF-02, KF-35 |
| FT-11 | Floating WhatsApp Sticker | `functions.php` | KF-35 |
| FT-12 | Pre-Paint Script Anti-Flash Light Mode | `functions.php` | KF-05, KNF-07 |

---

## FT-01 — Custom Field "Username Roblox" di Checkout

**Lokasi**: `wp-content/themes/dyaastore-child/functions.php` (baris 340–415)
**KF**: KF-23 (Field tambahan di checkout dengan validasi)
**Antarmuka**: AT-05 (`docs/06-implementasi-antarmuka.md`)

### Alur Kerja
1. **Render** field di halaman checkout — hook `woocommerce_after_order_notes`.
2. **Validasi** saat submit — hook `woocommerce_checkout_process`.
3. **Simpan** ke order meta — hook `woocommerce_checkout_update_order_meta`.
4. **Tampilkan** di:
   - Detail order admin — hook `woocommerce_admin_order_data_after_billing_address`.
   - Email customer & admin — hook `woocommerce_email_order_meta`.
   - Halaman thank-you — hook `woocommerce_thankyou`.

### Snippet Render + Validasi

```340:374:wp-content/themes/dyaastore-child/functions.php
function dyaastore_add_roblox_field( $checkout ) {
    echo '<div id="dyaa_roblox_field" class="dyaa-roblox-field">';
    echo '<h3 class="dyaa-roblox-title">' . dyaa_icon( 'gamepad', 18 ) . ' ' . esc_html__( 'Data Akun Roblox', 'dyaastore-child' ) . '</h3>';

    woocommerce_form_field( 'roblox_username', array(
        'type'        => 'text',
        'class'       => array( 'form-row-wide' ),
        'label'       => __( 'Username Roblox', 'dyaastore-child' ),
        'placeholder' => __( 'Contoh: roblox_player123', 'dyaastore-child' ),
        'required'    => true,
    ), $checkout->get_value( 'roblox_username' ) );

    echo '<p class="dyaa-roblox-hint">' . dyaa_icon( 'alert', 14 )
        . ' <span>' . esc_html__( 'Pastikan username Roblox kamu benar. Robux akan dikirim ke akun ini, kesalahan input bukan tanggung jawab kami.', 'dyaastore-child' ) . '</span>'
        . '</p></div>';
}
add_action( 'woocommerce_after_order_notes', 'dyaastore_add_roblox_field' );

function dyaastore_validate_roblox_field() {
    if ( empty( $_POST['roblox_username'] ) ) {
        wc_add_notice( __( 'Username Roblox wajib diisi untuk pengiriman Robux.', 'dyaastore-child' ), 'error' );
        return;
    }

    $username = sanitize_text_field( wp_unslash( $_POST['roblox_username'] ) );

    if ( strlen( $username ) < 3 || strlen( $username ) > 20 ) {
        wc_add_notice( __( 'Username Roblox harus 3-20 karakter.', 'dyaastore-child' ), 'error' );
    }

    if ( ! preg_match( '/^[A-Za-z0-9_]+$/', $username ) ) {
        wc_add_notice( __( 'Username Roblox hanya boleh huruf, angka, dan underscore.', 'dyaastore-child' ), 'error' );
    }
}
add_action( 'woocommerce_checkout_process', 'dyaastore_validate_roblox_field' );
```

### Snippet Simpan ke Order Meta

```376:385:wp-content/themes/dyaastore-child/functions.php
function dyaastore_save_roblox_field( $order_id ) {
    if ( ! empty( $_POST['roblox_username'] ) ) {
        update_post_meta(
            $order_id,
            '_roblox_username',
            sanitize_text_field( wp_unslash( $_POST['roblox_username'] ) )
        );
    }
}
add_action( 'woocommerce_checkout_update_order_meta', 'dyaastore_save_roblox_field' );
```

### Aturan Validasi (untuk Pengujian Black Box)

| Pelanggaran | Notice | Test ID |
|---|---|---|
| Field kosong | "Username Roblox wajib diisi untuk pengiriman Robux." | TC-G02 |
| Panjang < 3 atau > 20 | "Username Roblox harus 3-20 karakter." | TC-G03 |
| Mengandung selain `[A-Za-z0-9_]` | "Username Roblox hanya boleh huruf, angka, dan underscore." | TC-G04 |

---

## FT-02 — Theme Toggle Pill Switch

**Lokasi**:
- Markup: `functions.php` (`dyaa_render_top_navigation()` & `dyaa_render_sidebar()`)
- CSS: `style.css` selector `.dyaa-theme-toggle*`
- JS: `assets/js/dyaastore.js` fungsi `syncToggle()`

**KF**: KF-05 (Toggle dark/light)
**Antarmuka**: AT-14a

### Markup (kedua toggle identik)

```266:272:wp-content/themes/dyaastore-child/functions.php
<button type="button" id="dyaa-darkmode-btn" class="dyaa-theme-toggle" role="switch" aria-checked="false" aria-label="Aktifkan mode terang">
    <span class="dyaa-theme-toggle-track">
        <span class="dyaa-theme-toggle-icon dyaa-theme-toggle-icon--sun" aria-hidden="true">{svg sun}</span>
        <span class="dyaa-theme-toggle-icon dyaa-theme-toggle-icon--moon" aria-hidden="true">{svg moon}</span>
        <span class="dyaa-theme-toggle-thumb" aria-hidden="true"></span>
    </span>
</button>
```

### Behavior JavaScript (sinkronisasi 2 toggle)

```js
const themeButtons = document.querySelectorAll('.dyaa-theme-toggle');
const sideLabel    = document.querySelector('[data-dyaa-theme-label]');

const syncToggle = (isLight) => {
    themeButtons.forEach((b) => {
        b.classList.toggle('is-light', isLight);
        b.setAttribute('aria-checked', isLight ? 'true' : 'false');
        b.setAttribute('aria-label', isLight ? 'Aktifkan mode gelap' : 'Aktifkan mode terang');
    });
    if (sideLabel) sideLabel.textContent = isLight ? 'Mode Gelap' : 'Mode Terang';
};

const applyTheme = (isLight, persist = true) => {
    document.body.classList.toggle('dyaa-light', isLight);
    document.documentElement.classList.toggle('dyaa-light-pre', isLight);
    if (persist) localStorage.setItem('dyaa-theme', isLight ? 'light' : 'dark');
    syncToggle(isLight);
};

themeButtons.forEach((b) => {
    b.addEventListener('click', () => {
        const isLight = !document.body.classList.contains('dyaa-light');
        applyTheme(isLight);
    });
});

const initial = localStorage.getItem('dyaa-theme') === 'light';
applyTheme(initial, false);
```

### Behavior CSS (posisi thumb)

```css
.dyaa-theme-toggle:not(.is-light) .dyaa-theme-toggle-thumb {
    /* Mode gelap: thumb di KANAN, di atas ikon bulan */
    transform: translateX(calc(var(--tt-w) - var(--tt-thumb) - (var(--tt-pad) * 2)));
}
.dyaa-theme-toggle.is-light .dyaa-theme-toggle-thumb {
    /* Mode terang: thumb di KIRI, di atas ikon matahari */
    transform: translateX(0);
}
```

---

## FT-03 — Auth Split-Screen + Tab Login/Daftar

**Lokasi**: `wp-content/themes/dyaastore-child/woocommerce/myaccount/form-login.php` (override default WooCommerce)
**KF**: KF-16, KF-17, KF-18
**Antarmuka**: AT-07

### Strategi Override
WooCommerce mencari template `myaccount/form-login.php` di child theme dulu (sebelum plugin path) — file inilah yang menggantikan markup default.

### Struktur

```php
<div class="dyaa-auth-shell" data-initial-tab="<?php echo esc_attr( $dyaa_initial_tab ); ?>">
    <aside class="dyaa-auth-hero" aria-hidden="true">
        {hero copy + 3 feature card}
    </aside>

    <section class="dyaa-auth-card">
        <?php if ( $dyaa_register_enabled ) : ?>
        <div class="dyaa-auth-tabs" role="tablist">
            <button role="tab" data-dyaa-tab="login"   aria-selected="...">Masuk</button>
            <button role="tab" data-dyaa-tab="register" aria-selected="...">Daftar</button>
            <span class="dyaa-auth-tab-indicator" aria-hidden="true"></span>
        </div>
        <?php endif; ?>

        <div id="dyaa-pane-login" class="dyaa-auth-pane <?php echo $dyaa_initial_tab === 'login' ? 'is-active' : ''; ?>" role="tabpanel">
            <form class="woocommerce-form woocommerce-form-login login dyaa-auth-form" method="post">
                {input email/username + password + remember + submit + lost-password}
            </form>
        </div>

        <div id="dyaa-pane-register" class="dyaa-auth-pane <?php echo $dyaa_initial_tab === 'register' ? 'is-active' : ''; ?>" role="tabpanel">
            <form class="woocommerce-form woocommerce-form-register register dyaa-auth-form" method="post">
                {input email + (opsional username + password) + submit}
            </form>
        </div>
    </section>
</div>
```

### Tab Switching Tanpa Reload (JS)

```js
const tabs  = document.querySelectorAll('[data-dyaa-tab]');
const panes = document.querySelectorAll('[data-dyaa-pane]');

const setTab = (target, updateUrl = true) => {
    tabs.forEach((t) => {
        const active = t.getAttribute('data-dyaa-tab') === target;
        t.classList.toggle('is-active', active);
        t.setAttribute('aria-selected', active ? 'true' : 'false');
    });
    panes.forEach((p) => {
        p.classList.toggle('is-active', p.getAttribute('data-dyaa-pane') === target);
    });
    if (updateUrl) {
        const url = new URL(window.location.href);
        if (target === 'register') url.searchParams.set('action', 'register');
        else url.searchParams.delete('action');
        window.history.replaceState({}, '', url.toString());
    }
};

tabs.forEach((t) => t.addEventListener('click', () => setTab(t.getAttribute('data-dyaa-tab'))));
```

### CSS Penting (sembunyikan judul default Woo)

```css
body.dyaa-auth-screen .page-header,
body.dyaa-auth-screen .entry-header,
body.dyaa-auth-screen h1.entry-title,
body.dyaa-auth-screen .woocommerce-products-header {
    display: none !important;
}
```

---

## FT-04 — Flash Sale + Countdown Timer

**Lokasi**: `functions.php → dyaastore_shortcode_flashsale()` + JS countdown di `dyaastore.js`
**KF**: KF-14
**Antarmuka**: AT-01 (section flash sale)

### Shortcode

```813:860:wp-content/themes/dyaastore-child/functions.php
function dyaastore_shortcode_flashsale( $atts ) {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return '';
    }
    $atts = shortcode_atts( array( 'limit' => 3, 'hours' => 6 ), $atts, 'dyaa_flashsale' );
    $limit = absint( $atts['limit'] );
    $hours = absint( $atts['hours'] );

    $sale_ids = wc_get_product_ids_on_sale();
    $has_sale = ! empty( $sale_ids );

    ob_start();
    ?>
    <section class="dyaa-flashsale">
        <div class="dyaa-flashsale-inner">
            <div class="dyaa-flashsale-header">
                <h2 class="dyaa-flashsale-title">
                    <span><?php esc_html_e( 'Flash Sale Robux', 'dyaastore-child' ); ?></span>
                </h2>
                <div class="dyaa-countdown" data-dyaa-countdown="<?php echo esc_attr( $hours ); ?>">
                    <span class="dyaa-countdown-label"><?php esc_html_e( 'Berakhir dalam', 'dyaastore-child' ); ?></span>
                    <span class="dyaa-countdown-time" aria-live="polite">
                        <span class="dyaa-countdown-box" data-cd="hours">00</span>:
                        <span class="dyaa-countdown-box" data-cd="minutes">00</span>:
                        <span class="dyaa-countdown-box" data-cd="seconds">00</span>
                    </span>
                </div>
            </div>
            <?php if ( $has_sale ) : ?>
                <?php echo do_shortcode( '[products on_sale="true" limit="' . $limit . '" columns="' . $limit . '"]' ); ?>
            <?php else : ?>
                <?php echo do_shortcode( '[products limit="' . $limit . '" columns="' . $limit . '" orderby="rand"]' ); ?>
            <?php endif; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}
add_shortcode( 'dyaa_flashsale', 'dyaastore_shortcode_flashsale' );
```

### JS Countdown (deadline disimpan di sessionStorage agar konsisten antar refresh dalam sesi)

```js
const counter = document.querySelector('.dyaa-countdown[data-dyaa-countdown]');
if (counter) {
    const hours = parseInt(counter.getAttribute('data-dyaa-countdown'), 10) || 6;
    const KEY   = 'dyaa-flashsale-deadline';
    let target  = parseInt(sessionStorage.getItem(KEY) || '0', 10);
    const now   = Date.now();
    if (!target || target < now) {
        target = now + hours * 3600 * 1000;
        sessionStorage.setItem(KEY, String(target));
    }
    const tick = () => {
        const diff = Math.max(0, target - Date.now());
        const h = Math.floor(diff / 3600000);
        const m = Math.floor((diff % 3600000) / 60000);
        const s = Math.floor((diff % 60000) / 1000);
        counter.querySelector('[data-cd="hours"]'  ).textContent = String(h).padStart(2,'0');
        counter.querySelector('[data-cd="minutes"]').textContent = String(m).padStart(2,'0');
        counter.querySelector('[data-cd="seconds"]').textContent = String(s).padStart(2,'0');
    };
    tick();
    setInterval(tick, 1000);
}
```

---

## FT-05 — Live Transaction Toast

**Lokasi**: `functions.php → dyaastore_render_live_toast()` (markup) + JS rotator
**KF**: KF-36
**Antarmuka**: AT-14c

### Markup

```464:482:wp-content/themes/dyaastore-child/functions.php
function dyaastore_render_live_toast() {
    ?>
    <div class="dyaa-live-toast" id="dyaa-live-toast" role="status" aria-live="polite">
        <button type="button" class="dyaa-live-close" aria-label="Tutup notifikasi">{svg x}</button>
        <span class="dyaa-live-avatar" data-live="avatar" aria-hidden="true">AP</span>
        <div class="dyaa-live-meta">
            <div class="dyaa-live-row">
                <strong data-live="name">Andi Pratama</strong>
                <span class="dyaa-live-verified">{svg verified}</span>
            </div>
            <div class="dyaa-live-product">baru saja membeli <strong data-live="product">800 Robux</strong></div>
            <div class="dyaa-live-time" data-live="time">baru saja</div>
        </div>
    </div>
    <?php
}
add_action( 'wp_footer', 'dyaastore_render_live_toast' );
```

### JS Rotator (data array hard-coded, tidak ada API)

```js
const toast = document.getElementById('dyaa-live-toast');
if (toast && !toast.classList.contains('dyaa-toast-dismissed')) {
    const events = [
        { name: 'Andi Pratama',    product: '1.700 Robux',  time: '2 menit yang lalu',  initials: 'AP' },
        { name: 'Maya Lestari',    product: '4.500 Robux',  time: '5 menit yang lalu',  initials: 'ML' },
        { name: 'Rizky Anggara',   product: '800 Robux',    time: '7 menit yang lalu',  initials: 'RA' },
        { name: 'Dewi Kusuma',     product: '100 Robux',    time: 'baru saja',          initials: 'DK' },
        { name: 'Bagas Triwibowo', product: 'Bundle 2x800', time: '12 menit yang lalu', initials: 'BT' },
    ];
    let i = 0;
    const render = (e) => {
        toast.querySelector('[data-live="name"]'   ).textContent = e.name;
        toast.querySelector('[data-live="product"]').textContent = e.product;
        toast.querySelector('[data-live="time"]'   ).textContent = e.time;
        toast.querySelector('[data-live="avatar"]' ).textContent = e.initials;
        toast.classList.add('is-visible');
    };
    const cycle = () => {
        toast.classList.remove('is-visible');
        setTimeout(() => render(events[i++ % events.length]), 350);
    };
    setTimeout(cycle, 4000);
    setInterval(cycle, 8000);

    toast.querySelector('.dyaa-live-close').addEventListener('click', () => {
        toast.classList.remove('is-visible');
        toast.classList.add('dyaa-toast-dismissed');
    });
}
```

---

## FT-06 — Auto-Create 5 Halaman Statis

**Lokasi**: `wp-content/mu-plugins/dyaastore-pages.php`
**KF**: KF-06, KF-07, KF-08, KF-09, KF-10
**Antarmuka**: AT-09

### Mekanisme
1. `add_action( 'admin_init', 'dyaastore_maybe_run_pages_seeder', 25 )`.
2. Cek opsi `DYAA_PAGES_FLAG` (`dyaastore_pages_seeded_v1`) — jika sudah set, *skip*.
3. Jika belum, panggil `dyaastore_seed_pages()` yang loop array konfigurasi (5 halaman).
4. Untuk halaman yang **belum ada** (cek `get_page_by_path`): `wp_insert_post()` dengan slug & content.
5. Untuk halaman **Privasi**: tambahan `update_option( 'wp_page_for_privacy_policy', $post_id )` agar terdaftar di WP Privacy.
6. Set flag `DYAA_PAGES_FLAG` agar tidak rerun.

### Trigger Manual
Admin bisa rerun lewat URL:
```
/wp-admin/?dyaa_pages=1
```
(yang sudah ada di-skip; hanya yang hilang dibuat).

### Halaman yang Dibuat

| Slug | Judul | Komponen |
|---|---|---|
| `tentang` | Tentang Dyaa Store | Visi, Misi, alasan memilih (4 list) |
| `faq` | Pertanyaan yang Sering Ditanyakan | 8 pertanyaan accordion |
| `syarat-ketentuan` | Syarat & Ketentuan | 6 section legal |
| `kebijakan-privasi` | Kebijakan Privasi | 5 section privasi |
| `dukungan` | Dukungan Pelanggan | Jam ops + cara kontak |

---

## FT-07 — Auto-Create 6 Kategori + 8 Produk Demo

**Lokasi**: `wp-content/mu-plugins/dyaastore-seeder.php`
**KF**: KF-13, KF-27
**Antarmuka**: AT-02 (shop terisi)

### Kategori yang Dibuat

| Slug | Nama |
|---|---|
| `paket-hemat` | Paket Hemat |
| `voucher-robux` | Voucher Robux |
| `gamepass` | Gamepass |
| `premium` | Premium |
| `bundle` | Bundle |
| `limited` | Limited |

### Produk yang Dibuat

| SKU | Nama | Harga | Sale | Kategori | Gambar |
|---|---|---|---|---|---|
| DYAA-100 | 100 Robux | 18.000 | 15.000 | paket-hemat | dyaa-robux-small.png |
| DYAA-400 | 400 Robux | 65.000 | 55.000 | paket-hemat | dyaa-robux-small.png |
| DYAA-800 | 800 Robux | 125.000 | — | voucher-robux | dyaa-robux-medium.png |
| DYAA-1700 | 1700 Robux | 245.000 | 220.000 | voucher-robux | dyaa-robux-medium.png |
| DYAA-4500 | 4500 Robux | 625.000 | — | gamepass | dyaa-robux-large.png |
| DYAA-10K | 10000 Robux | 1.350.000 | — | premium | dyaa-robux-large.png |
| DYAA-BUNDLE-2X800 | Bundle Hemat 2×800 Robux | 240.000 | 215.000 | bundle | dyaa-robux-bundle.png |
| DYAA-LIMITED-2200 | Limited 2200 Robux | 320.000 | — | limited | dyaa-robux-bundle.png |

### Mekanisme
1. Hook `admin_init` priority 30; cek flag `DYAA_SEEDER_FLAG`.
2. Loop `dyaastore_demo_categories()` → `wp_insert_term()` jika belum ada.
3. Loop `dyaastore_demo_products()`:
   - Cek apakah produk dengan SKU sama sudah ada (`wc_get_product_id_by_sku`).
   - Jika belum: `wp_insert_post()` post_type=product, lalu `update_post_meta` untuk price/sale/SKU/virtual.
   - Set kategori via `wp_set_object_terms`.
   - Sideload image dari `themes/dyaastore-child/assets/img/{nama}.png` → `media_handle_sideload` → set sebagai `_thumbnail_id`.
4. Set flag `DYAA_SEEDER_FLAG`.

### Trigger Manual
```
/wp-admin/?dyaa_seed=1
```

---

## FT-08 — Kolom Username Roblox di Listing Pesanan

**Lokasi**: `wp-content/mu-plugins/dyaastore-helpers.php`
**KF**: KF-28
**Antarmuka**: AT-15a

### Dual Filter (Legacy + HPOS)

```19:57:wp-content/mu-plugins/dyaastore-helpers.php
function dyaastore_orders_column( $columns ) {
    $new_columns = array();
    foreach ( $columns as $key => $label ) {
        $new_columns[ $key ] = $label;
        if ( 'order_status' === $key ) {
            $new_columns['roblox_username'] = __( 'Username Roblox', 'dyaastore' );
        }
    }
    return $new_columns;
}
add_filter( 'manage_edit-shop_order_columns', 'dyaastore_orders_column', 20 );          // Legacy CPT
add_filter( 'manage_woocommerce_page_wc-orders_columns', 'dyaastore_orders_column', 20 ); // HPOS

function dyaastore_orders_column_content( $column, $post_id ) {
    if ( 'roblox_username' === $column ) {
        $order_id = is_a( $post_id, 'WP_Post' ) ? $post_id->ID : $post_id;
        $username = get_post_meta( $order_id, '_roblox_username', true );
        echo $username
            ? '<span style="color:#00a86b;font-weight:600;">' . esc_html( $username ) . '</span>'
            : '<span style="color:#999;">—</span>';
    }
}
add_action( 'manage_shop_order_posts_custom_column', 'dyaastore_orders_column_content', 10, 2 ); // Legacy

function dyaastore_orders_column_content_hpos( $column, $order ) {
    if ( 'roblox_username' === $column ) {
        $username = $order->get_meta( '_roblox_username' );
        echo $username
            ? '<span style="color:#00a86b;font-weight:600;">' . esc_html( $username ) . '</span>'
            : '<span style="color:#999;">—</span>';
    }
}
add_action( 'manage_woocommerce_page_wc-orders_custom_column', 'dyaastore_orders_column_content_hpos', 10, 2 ); // HPOS
```

> Dukungan HPOS penting karena WooCommerce 8.x ke atas merekomendasikan storage baru (`wp_wc_orders`).

---

## FT-09 — Dashboard Widget Ringkasan untuk Admin

**Lokasi**: `wp-content/mu-plugins/dyaastore-helpers.php`
**KF**: KF-31
**Antarmuka**: AT-15c

### Mekanisme
- Hook `wp_dashboard_setup` → `wp_add_dashboard_widget()`.
- Render: jumlah produk publish + jumlah order per status (pending, processing, completed) yang diambil lewat `wc_get_products()` & `wc_get_orders()`.
- Hanya tampil bagi user dengan `manage_options`.

```php
function dyaastore_dashboard_widget() {
    if ( ! current_user_can( 'manage_options' ) ) return;
    wp_add_dashboard_widget(
        'dyaastore_summary',
        '🎮 Dyaa Store — Ringkasan',
        'dyaastore_dashboard_widget_render'
    );
}
add_action( 'wp_dashboard_setup', 'dyaastore_dashboard_widget' );
```

---

## FT-10 — Sidebar Custom 3-Grup + WhatsApp CTA

**Lokasi**: `functions.php → dyaastore_render_sidebar()`
**KF**: KF-02, KF-35
**Antarmuka**: AT-10

### Hook Render
- `add_action( 'wp_body_open', 'dyaastore_render_sidebar', 4 )` — tampil paling awal di body, sebelum konten utama.

### Komposisi
- 1 brand (logo + nama)
- 3 grup `<ul>` (Menu / Navigasi / Pengguna)
- 1 CTA WhatsApp Group di paling bawah (sticky di mobile)
- Tombol hamburger + overlay terpisah untuk mode mobile

### Aktif State
Setiap link memakai conditional `is-active` berdasarkan helper kondisional WordPress:

```php
class="dyaa-side-link<?php echo is_front_page() ? ' is-active' : ''; ?>"
class="dyaa-side-link<?php echo is_shop() ? ' is-active' : ''; ?>"
class="dyaa-side-link<?php echo is_page( 'faq' ) ? ' is-active' : ''; ?>"
```

---

## FT-11 — Floating WhatsApp Sticker

**Lokasi**: `functions.php → dyaastore_render_whatsapp_button()`
**KF**: KF-35
**Antarmuka**: AT-14b

### Snippet

```434:458:wp-content/themes/dyaastore-child/functions.php
function dyaastore_render_whatsapp_button() {
    $wa_link = sprintf(
        'https://wa.me/%s?text=%s',
        esc_attr( DYAA_WHATSAPP_NUMBER ),
        rawurlencode( DYAA_WHATSAPP_TEXT )
    );
    ?>
    <a href="<?php echo esc_url( $wa_link ); ?>"
       class="dyaa-wa-sticker"
       target="_blank"
       rel="noopener noreferrer"
       aria-label="Hubungi WhatsApp Dyaa Store">
        <span class="dyaa-wa-sticker-balloon">
            <span class="dyaa-wa-line-1">BUTUH BANTUAN</span>
            <span class="dyaa-wa-line-2">KLIK DISINI!!</span>
        </span>
        <span class="dyaa-wa-sticker-mascot" aria-hidden="true">{svg whatsapp logo putih}</span>
    </a>
    <?php
}
add_action( 'wp_footer', 'dyaastore_render_whatsapp_button' );
```

### Kustomisasi
Nomor & teks pre-filled diatur di konstanta:
```php
define( 'DYAA_WHATSAPP_NUMBER', '6289515881150' );
define( 'DYAA_WHATSAPP_TEXT',   'Halo Dyaa Store, saya mau tanya tentang Robux' );
```

---

## FT-12 — Pre-Paint Script Anti-Flash Light Mode

**Lokasi**: `functions.php → dyaastore_pre_paint_theme()` (hook `wp_head` priority 1)
**KF**: KF-05, KNF-07
**Antarmuka**: AT-01 (saat refresh halaman dalam mode terang)

### Mengapa Diperlukan
Tanpa script ini, user dalam mode terang akan mengalami **flash mode gelap** sepersekian detik saat refresh — karena CSS dimuat sesudah script inline. Pre-paint script membaca `localStorage` lebih dulu lalu memasang class `dyaa-light-pre` di `<html>` sebelum browser mulai paint.

### Snippet

```38:55:wp-content/themes/dyaastore-child/functions.php
function dyaastore_pre_paint_theme() {
    ?>
    <script>
        (function () {
            try {
                var t = localStorage.getItem('dyaa-theme');
                if (t === 'light') {
                    document.documentElement.classList.add('dyaa-light-pre');
                    document.addEventListener('DOMContentLoaded', function () {
                        document.body.classList.add('dyaa-light');
                    });
                }
            } catch (e) {}
        })();
    </script>
    <?php
}
add_action( 'wp_head', 'dyaastore_pre_paint_theme', 1 );
```

### CSS Pendamping

```css
html.dyaa-light-pre {
    background: #fff7ed; /* warna body light untuk mencegah flash */
}
```

---

## Daftar Hook WordPress yang Dipakai (Rekap)

Untuk menjelaskan di BAB IV bahwa implementasi mengikuti **best practice WordPress** (semua perilaku custom diintegrasikan lewat hook, bukan modifikasi core), berikut hook yang digunakan:

| Hook | Tipe | Dipakai untuk |
|---|---|---|
| `wp_enqueue_scripts` | action | Enqueue style.css child theme + Google Fonts + dyaastore.js |
| `wp_head` (prio 1) | action | Inject pre-paint theme script |
| `wp_body_open` (prio 4) | action | Render sidebar custom |
| `wp_body_open` (prio 5) | action | Render bilah navigasi atas |
| `wp_footer` (prio 1) | action | Render footer global Dyaa Store |
| `wp_footer` (prio 2) | action | Render bottom navigation mobile |
| `wp_footer` | action | Render WhatsApp sticker + Live toast |
| `body_class` | filter | Tambah class `.dyaa-site`, `.dyaa-landing`, `.dyaa-auth-screen` |
| `pre_option_woocommerce_enable_myaccount_registration` | filter | Paksa enable registrasi |
| `theme_page_templates` | filter | Daftarkan "Dyaa Store — Homepage" template |
| `template_include` | filter | Load template dari child theme directory |
| `admin_title` | filter | Custom title halaman admin |
| `admin_footer_text` | filter | Custom teks footer admin |
| `woocommerce_after_order_notes` | action | Render field Username Roblox |
| `woocommerce_checkout_process` | action | Validasi field Username Roblox |
| `woocommerce_checkout_update_order_meta` | action | Simpan field Username Roblox |
| `woocommerce_admin_order_data_after_billing_address` | action | Tampilkan Username Roblox di detail admin |
| `woocommerce_email_order_meta` | action | Tampilkan Username Roblox di email |
| `woocommerce_thankyou` | action | Tampilkan Username Roblox di halaman thank-you |
| `wp_insert_post` | action | Set produk default sebagai Virtual |
| `manage_edit-shop_order_columns` (filter) + `manage_shop_order_posts_custom_column` (action) | dual | Kolom Username Roblox di listing CPT legacy |
| `manage_woocommerce_page_wc-orders_columns` (filter) + `manage_woocommerce_page_wc-orders_custom_column` (action) | dual | Kolom Username Roblox di listing HPOS |
| `wp_dashboard_setup` | action | Tambah dashboard widget |
| `admin_init` (prio 25 & 30) | action | Trigger pages seeder & products seeder |
| `admin_notices` | action | Tampilkan notice setelah seeder jalan |

---

## Rangkuman untuk BAB IV §4.1.3

> Bagian §4.1.3 skripsi dapat menulis ringkas:

> Implementasi fitur custom Dyaa Store dilakukan dengan strategi **modular** dan **non-invasif** terhadap core WordPress. Sebanyak 12 fitur utama (FT-01 hingga FT-12) ditulis menggunakan **hook system** WordPress (action & filter) sehingga ketika WordPress / WooCommerce / Elementor melakukan pembaruan, kode kustom tidak terdampak. Seluruh kode berkonvensi prefix `dyaastore_` (function), `.dyaa-` (CSS class), dan `[dyaa_]` (shortcode) untuk menghindari kolisi dengan plugin pihak ketiga.

> Dua *must-use plugin* (`dyaastore-pages.php`, `dyaastore-seeder.php`) memastikan instalasi sistem **dapat direplikasi** karena 5 halaman statis dan 8 produk Robux demo dibuat secara otomatis tanpa intervensi manual. Untuk integrasi WooCommerce versi terbaru, kolom kustom "Username Roblox" pada listing pesanan didaftarkan ganda — pada filter legacy (`manage_edit-shop_order_columns`) dan filter HPOS (`manage_woocommerce_page_wc-orders_columns`) — sehingga sistem berfungsi pada kedua mode storage WooCommerce.

> Detail bukti kebenaran setiap fitur (apakah memang berjalan sesuai kebutuhan KF) dilakukan pada pengujian Black Box di `docs/03-pengujian-blackbox.md`.
