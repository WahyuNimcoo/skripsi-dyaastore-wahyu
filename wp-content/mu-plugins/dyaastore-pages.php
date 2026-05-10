<?php
/**
 * Plugin Name: Dyaa Store — Pages Seeder
 * Description: Otomatis membuat halaman-halaman statis (FAQ, Tentang, Syarat & Ketentuan, Kebijakan Privasi, Dukungan Pelanggan) sehingga semua link di sidebar mengarah ke halaman beneran. Bagian dari TA Wahyu Akbar Pratama Siregar — STT-NF 2026.
 * Version: 1.0.0
 *
 * @package DyaaStorePages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

const DYAA_PAGES_FLAG = 'dyaastore_pages_seeded_v1';

/**
 * Daftar halaman statis Dyaa Store.
 * Konten memakai class CSS dari child theme supaya tampilannya sejalan.
 */
function dyaastore_static_pages() {
    return array(
        // 1) Tentang Kami
        array(
            'slug'    => 'tentang',
            'title'   => 'Tentang Dyaa Store',
            'option'  => 'dyaastore_page_about',
            'content' => '
<div class="dyaa-page dyaa-page-static">
    <header class="dyaa-section-header">
        <span class="dyaa-section-eyebrow">Tentang Kami</span>
        <h2>Dyaa Store — Toko Robux Tepercaya untuk Komunitas Roblox Indonesia</h2>
        <p class="dyaa-section-sub">Kami adalah toko Robux yang dibangun untuk menjawab kebutuhan top up Robux yang aman, cepat, dan tanpa ribet.</p>
    </header>

    <div class="dyaa-page-block">
        <h3>Visi Kami</h3>
        <p>Menjadi toko Robux #1 di Indonesia dengan layanan yang ramah, harga jujur, dan pengiriman tercepat di kelasnya.</p>

        <h3>Misi Kami</h3>
        <ul>
            <li>Memberikan harga Robux yang paling kompetitif tanpa biaya tersembunyi.</li>
            <li>Memastikan setiap pesanan diproses manual oleh admin dalam ≤ 5 menit pada jam kerja.</li>
            <li>Menjaga keamanan data pelanggan dengan tidak meminta password Roblox.</li>
            <li>Memberikan dukungan pelanggan via WhatsApp 7 hari seminggu.</li>
        </ul>

        <h3>Mengapa Memilih Dyaa Store?</h3>
        <ul>
            <li><strong>Pengiriman Manual oleh Admin Bersertifikat.</strong> Tidak menggunakan API ilegal — sesuai TOS Roblox.</li>
            <li><strong>Harga Transparan.</strong> Sudah termasuk pajak &amp; biaya admin, tidak ada charge tambahan saat checkout.</li>
            <li><strong>Banyak Pilihan Pembayaran.</strong> Transfer bank (BCA, Mandiri, BRI, BNI), e-wallet (DANA, OVO, GoPay, ShopeePay), QRIS, dan minimarket (Alfamart, Indomaret).</li>
            <li><strong>Dukungan Cepat.</strong> Tim CS kami siap membantu via WhatsApp setiap hari pukul 08.00 — 22.00 WIB.</li>
        </ul>

        <p><em>Dyaa Store adalah platform e-commerce yang dibangun di atas WordPress + WooCommerce. Repositori dan dokumentasi lengkap merupakan bagian dari Tugas Akhir Wahyu Akbar Pratama Siregar di STT Terpadu Nurul Fikri (2026).</em></p>
    </div>
</div>',
        ),

        // 2) FAQ
        array(
            'slug'    => 'faq',
            'title'   => 'Pertanyaan yang Sering Ditanyakan',
            'option'  => 'dyaastore_page_faq',
            'content' => '
<div class="dyaa-page dyaa-page-static">
    <header class="dyaa-section-header">
        <span class="dyaa-section-eyebrow">FAQ</span>
        <h2>Pertanyaan yang Sering Ditanyakan</h2>
        <p class="dyaa-section-sub">Kumpulan jawaban atas pertanyaan paling umum dari pelanggan Dyaa Store.</p>
    </header>

    <div class="dyaa-faq-list">
        <details>
            <summary>Apakah pengiriman Robux aman dan sesuai TOS Roblox?</summary>
            <p>Ya. Kami mengirim Robux melalui mekanisme gamepass / group payout resmi. Kami tidak meminta password akun, tidak login ke akun Anda, dan tidak menggunakan tools ilegal apa pun. Cukup berikan username Roblox saat checkout.</p>
        </details>
        <details>
            <summary>Berapa lama proses pengiriman setelah saya bayar?</summary>
            <p>Pada jam kerja (08.00 — 22.00 WIB), pesanan diproses dalam 1 — 5 menit. Di luar jam tersebut, pesanan akan diproses secepatnya keesokan harinya. Anda akan menerima notifikasi WhatsApp begitu Robux dikirim.</p>
        </details>
        <details>
            <summary>Metode pembayaran apa saja yang diterima?</summary>
            <p>Transfer bank (BCA, Mandiri, BRI, BNI), e-wallet (DANA, OVO, GoPay, ShopeePay), QRIS, dan tunai via minimarket (Alfamart, Indomaret). Semua metode bisa dipilih saat checkout.</p>
        </details>
        <details>
            <summary>Apakah ada biaya tersembunyi?</summary>
            <p>Tidak. Harga di halaman produk sudah termasuk pajak &amp; biaya admin. Yang Anda bayar = yang tertera saat checkout, tanpa charge tambahan.</p>
        </details>
        <details>
            <summary>Bagaimana jika Robux belum masuk ke akun saya?</summary>
            <p>Hubungi kami via WhatsApp dengan menyertakan nomor pesanan. Tim CS akan mengecek status manual dan memastikan Robux dikirim. Jika terjadi kendala dari sisi kami, refund 100% dijamin.</p>
        </details>
        <details>
            <summary>Apakah saya bisa membeli untuk akun teman?</summary>
            <p>Bisa. Cukup isi username Roblox milik teman Anda di kolom yang tersedia saat checkout. Pastikan username sudah benar — Robux yang sudah terkirim ke username yang salah tidak bisa dibatalkan.</p>
        </details>
        <details>
            <summary>Apakah ada paket Robux yang lebih murah dari yang ditampilkan?</summary>
            <p>Setiap minggu kami merilis flash sale untuk paket-paket tertentu. Aktifkan notifikasi WhatsApp atau cek halaman Beranda untuk melihat paket promo terbaru.</p>
        </details>
        <details>
            <summary>Bagaimana cara melacak status pesanan saya?</summary>
            <p>Anda bisa login ke menu <a href="' . esc_url( home_url( '/my-account/' ) ) . '">My Account</a> untuk melihat semua riwayat pesanan, atau gunakan halaman <a href="' . esc_url( home_url( '/cek-pesanan/' ) ) . '">Cek Pesanan</a> jika checkout sebagai tamu.</p>
        </details>
    </div>
</div>',
        ),

        // 3) Syarat & Ketentuan
        array(
            'slug'    => 'syarat-ketentuan',
            'title'   => 'Syarat & Ketentuan',
            'option'  => 'dyaastore_page_terms',
            'content' => '
<div class="dyaa-page dyaa-page-static">
    <header class="dyaa-section-header">
        <span class="dyaa-section-eyebrow">Legal</span>
        <h2>Syarat &amp; Ketentuan Layanan</h2>
        <p class="dyaa-section-sub">Dengan menggunakan layanan Dyaa Store, Anda dianggap telah membaca, memahami, dan menyetujui syarat &amp; ketentuan berikut.</p>
    </header>

    <div class="dyaa-page-block">
        <h3>1. Definisi</h3>
        <p>"Dyaa Store" merujuk pada toko online yang dijalankan melalui domain ini, yang menyediakan produk berupa Robux untuk pengguna game Roblox.</p>

        <h3>2. Produk &amp; Pengiriman</h3>
        <ul>
            <li>Produk yang dijual adalah Robux dalam berbagai paket nominal.</li>
            <li>Pengiriman dilakukan secara manual oleh admin melalui mekanisme gamepass atau group payout Roblox.</li>
            <li>Estimasi pengiriman 1 — 5 menit pada jam kerja (08.00 — 22.00 WIB). Di luar jam kerja akan diproses pada hari berikutnya.</li>
        </ul>

        <h3>3. Kewajiban Pembeli</h3>
        <ul>
            <li>Pembeli wajib mengisi username Roblox dengan benar saat checkout.</li>
            <li>Pembeli wajib melakukan pembayaran sesuai instruksi yang diberikan.</li>
            <li>Robux yang sudah terkirim ke username yang salah <strong>bukan</strong> tanggung jawab Dyaa Store.</li>
        </ul>

        <h3>4. Pembatalan &amp; Refund</h3>
        <ul>
            <li>Pembatalan hanya berlaku jika pesanan belum diproses oleh admin.</li>
            <li>Refund 100% diberikan jika kegagalan pengiriman disebabkan oleh kesalahan dari pihak Dyaa Store.</li>
            <li>Refund tidak berlaku untuk kesalahan input username dari pihak pembeli.</li>
        </ul>

        <h3>5. Penyalahgunaan Layanan</h3>
        <p>Dyaa Store berhak menolak atau membatalkan transaksi yang terindikasi penipuan, pencucian uang, atau pelanggaran TOS Roblox. Akun yang melakukan chargeback tanpa alasan sah akan masuk daftar blacklist.</p>

        <h3>6. Perubahan Syarat</h3>
        <p>Dyaa Store dapat mengubah syarat &amp; ketentuan ini sewaktu-waktu. Perubahan akan diumumkan di halaman ini.</p>

        <p><em>Terakhir diperbarui: ' . esc_html( date_i18n( 'd F Y' ) ) . '</em></p>
    </div>
</div>',
        ),

        // 4) Kebijakan Privasi
        array(
            'slug'    => 'kebijakan-privasi',
            'title'   => 'Kebijakan Privasi',
            'option'  => 'dyaastore_page_privacy',
            'content' => '
<div class="dyaa-page dyaa-page-static">
    <header class="dyaa-section-header">
        <span class="dyaa-section-eyebrow">Privasi</span>
        <h2>Kebijakan Privasi Dyaa Store</h2>
        <p class="dyaa-section-sub">Kami menghargai privasi pelanggan dan berkomitmen melindungi data pribadi Anda.</p>
    </header>

    <div class="dyaa-page-block">
        <h3>1. Data yang Kami Kumpulkan</h3>
        <ul>
            <li>Nama, alamat email, dan nomor WhatsApp untuk keperluan kontak pesanan.</li>
            <li>Username Roblox sebagai tujuan pengiriman Robux.</li>
            <li>Riwayat pesanan dan log transaksi.</li>
            <li><strong>Kami tidak pernah meminta password akun Roblox Anda.</strong></li>
        </ul>

        <h3>2. Penggunaan Data</h3>
        <ul>
            <li>Memproses pesanan dan mengirim Robux ke akun yang ditentukan.</li>
            <li>Menghubungi pelanggan jika ada kendala pesanan.</li>
            <li>Mengirim informasi promo (hanya jika Anda berlangganan).</li>
        </ul>

        <h3>3. Keamanan Data</h3>
        <p>Data pelanggan disimpan di server dengan akses terbatas. Kami tidak menjual atau membagikan data pelanggan kepada pihak ketiga, kecuali jika diwajibkan oleh hukum.</p>

        <h3>4. Hak Pelanggan</h3>
        <ul>
            <li>Anda berhak meminta penghapusan data pribadi Anda dari sistem kami.</li>
            <li>Anda berhak mengakses dan memperbarui data Anda kapan saja melalui menu My Account.</li>
        </ul>

        <h3>5. Kontak</h3>
        <p>Untuk pertanyaan terkait kebijakan privasi, hubungi kami melalui WhatsApp di tombol bantuan.</p>

        <p><em>Terakhir diperbarui: ' . esc_html( date_i18n( 'd F Y' ) ) . '</em></p>
    </div>
</div>',
        ),

        // 5) Dukungan Pelanggan
        array(
            'slug'    => 'dukungan',
            'title'   => 'Dukungan Pelanggan',
            'option'  => 'dyaastore_page_support',
            'content' => '
<div class="dyaa-page dyaa-page-static">
    <header class="dyaa-section-header">
        <span class="dyaa-section-eyebrow">Bantuan</span>
        <h2>Dukungan Pelanggan</h2>
        <p class="dyaa-section-sub">Tim CS Dyaa Store siap membantu Anda 7 hari seminggu.</p>
    </header>

    <div class="dyaa-page-block">
        <h3>Jam Operasional</h3>
        <p>Setiap hari pukul <strong>08.00 — 22.00 WIB</strong>. Pesanan di luar jam tersebut akan diproses pada keesokan harinya.</p>

        <h3>Cara Menghubungi Kami</h3>
        <ul>
            <li><strong>WhatsApp:</strong> klik tombol "BUTUH BANTUAN" yang muncul di pojok kanan bawah halaman, atau scan QR code di halaman checkout.</li>
            <li><strong>Email:</strong> support@dyaastore.local (balasan dalam 1×24 jam).</li>
        </ul>

        <h3>Pertanyaan Cepat</h3>
        <p>Sebelum menghubungi CS, cek dulu halaman <a href="' . esc_url( home_url( '/faq/' ) ) . '">FAQ</a> kami — kemungkinan besar pertanyaan Anda sudah terjawab di sana.</p>

        <h3>Status Pesanan</h3>
        <p>Anda bisa cek status pesanan kapan saja di:</p>
        <ul>
            <li><a href="' . esc_url( home_url( '/my-account/' ) ) . '">My Account</a> — jika sudah login.</li>
            <li><a href="' . esc_url( home_url( '/cek-pesanan/' ) ) . '">Cek Pesanan</a> — untuk checkout tamu (masukkan ID pesanan dan email).</li>
        </ul>
    </div>
</div>',
        ),
    );
}

/**
 * Buat / update halaman jika belum ada.
 */
function dyaastore_seed_pages() {
    $created = 0;

    foreach ( dyaastore_static_pages() as $page ) {
        $existing = get_page_by_path( $page['slug'] );

        if ( $existing ) {
            // Update post id ke option supaya bisa dipanggil di tempat lain.
            if ( ! empty( $page['option'] ) ) {
                update_option( $page['option'], (int) $existing->ID );
            }
            continue;
        }

        $post_id = wp_insert_post(
            array(
                'post_title'   => $page['title'],
                'post_name'    => $page['slug'],
                'post_content' => $page['content'],
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_author'  => 1,
            )
        );

        if ( $post_id && ! is_wp_error( $post_id ) ) {
            $created++;
            if ( ! empty( $page['option'] ) ) {
                update_option( $page['option'], (int) $post_id );
            }

            // Khusus halaman Privasi: set sebagai WordPress Privacy Policy Page.
            if ( 'kebijakan-privasi' === $page['slug'] ) {
                update_option( 'wp_page_for_privacy_policy', (int) $post_id );
            }
        }
    }

    update_option( DYAA_PAGES_FLAG, time() );

    return $created;
}

/**
 * Auto-run pada admin pertama kali.
 */
function dyaastore_maybe_run_pages_seeder() {
    if ( ! is_admin() ) {
        return;
    }
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    if ( get_option( DYAA_PAGES_FLAG ) ) {
        return;
    }

    $created = dyaastore_seed_pages();

    if ( $created > 0 ) {
        set_transient(
            'dyaastore_pages_notice',
            sprintf(
                'Dyaa Store — %d halaman statis baru telah dibuat (Tentang, FAQ, Syarat & Ketentuan, Kebijakan Privasi, Dukungan).',
                (int) $created
            ),
            60
        );
    }
}
add_action( 'admin_init', 'dyaastore_maybe_run_pages_seeder', 25 );

/**
 * Notice setelah pages seeder jalan.
 */
function dyaastore_pages_admin_notice() {
    $msg = get_transient( 'dyaastore_pages_notice' );
    if ( ! $msg ) {
        return;
    }
    echo '<div class="notice notice-success is-dismissible"><p>' . esc_html( $msg ) . '</p></div>';
    delete_transient( 'dyaastore_pages_notice' );
}
add_action( 'admin_notices', 'dyaastore_pages_admin_notice' );

/**
 * Trigger manual: tambahkan ?dyaa_pages=1 pada URL admin untuk re-run pages seeder.
 */
function dyaastore_pages_manual_trigger() {
    if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
        return;
    }
    if ( empty( $_GET['dyaa_pages'] ) || '1' !== $_GET['dyaa_pages'] ) {
        return;
    }
    delete_option( DYAA_PAGES_FLAG );
    $created = dyaastore_seed_pages();
    set_transient(
        'dyaastore_pages_notice',
        sprintf( 'Dyaa Store — Manual re-seed pages: %d halaman baru dibuat (yang sudah ada di-skip).', (int) $created ),
        60
    );
}
add_action( 'admin_init', 'dyaastore_pages_manual_trigger', 5 );

/**
 * Helper: dapatkan permalink halaman statis berdasarkan slug.
 *
 * @param string $slug     Slug halaman.
 * @param string $fallback URL fallback jika halaman tidak ada.
 * @return string
 */
function dyaastore_get_page_url( $slug, $fallback = '' ) {
    $page = get_page_by_path( $slug );
    if ( $page ) {
        return get_permalink( $page->ID );
    }
    return $fallback ? $fallback : home_url( '/' );
}
