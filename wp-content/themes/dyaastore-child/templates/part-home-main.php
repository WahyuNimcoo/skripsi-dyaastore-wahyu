<?php
/**
 * Markup inti beranda (hero, produk, stats, testimoni, FAQ).
 * Dipakai oleh front-page.php, template-homepage, dan sejenis.
 *
 * @package DyaaStoreChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<main id="dyaa-homepage" class="dyaa-homepage dyaa-container">

    <?php echo do_shortcode( '[dyaa_hero]' ); ?>

    <?php if ( class_exists( 'WooCommerce' ) ) : ?>

        <?php echo do_shortcode( '[dyaa_flashsale]' ); ?>

        <?php echo do_shortcode( '[dyaa_categories]' ); ?>

        <section class="dyaa-section" id="dyaa-paket">
            <div class="dyaa-section-header">
                <span class="dyaa-section-eyebrow"><?php echo dyaa_icon( 'sparkles', 14 ); ?> <?php esc_html_e( 'Pilihan Pelanggan', 'dyaastore-child' ); ?></span>
                <h2><?php esc_html_e( 'Paket Robux Terlaris Minggu Ini', 'dyaastore-child' ); ?></h2>
                <p><?php esc_html_e( 'Daftar paket dengan transaksi terbanyak dalam 7 hari terakhir. Harga sudah termasuk pajak & biaya admin.', 'dyaastore-child' ); ?></p>
            </div>

            <?php echo do_shortcode( '[products limit="8" columns="4" orderby="popularity"]' ); ?>

            <div class="dyaa-section-cta">
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="dyaa-btn dyaa-btn-primary">
                    <?php esc_html_e( 'Lihat Semua Paket', 'dyaastore-child' ); ?>
                    <?php echo dyaa_icon( 'arrow-right', 16 ); ?>
                </a>
            </div>
        </section>

    <?php else : ?>

        <section class="dyaa-section dyaa-wc-notice">
            <p><strong><?php esc_html_e( 'WooCommerce belum aktif.', 'dyaastore-child' ); ?></strong>
                <?php esc_html_e( 'Aktifkan plugin WooCommerce di wp-admin → Plugins agar katalog Robux tampil di sini.', 'dyaastore-child' ); ?></p>
        </section>

    <?php endif; ?>

    <section class="dyaa-section dyaa-howto" id="dyaa-cara-pesan">
        <div class="dyaa-section-header">
            <span class="dyaa-section-eyebrow"><?php echo dyaa_icon( 'check-circle', 14 ); ?> <?php esc_html_e( 'Mudah & Cepat', 'dyaastore-child' ); ?></span>
            <h2><?php esc_html_e( 'Cara Pesan Robux di Dyaa Store', 'dyaastore-child' ); ?></h2>
            <p><?php esc_html_e( 'Tiga langkah singkat — Robux langsung dikirim ke akun Roblox kamu.', 'dyaastore-child' ); ?></p>
        </div>

        <ol class="dyaa-steps-grid">
            <li class="dyaa-step-card">
                <span class="dyaa-step-num">01</span>
                <span class="dyaa-step-icon"><?php echo dyaa_icon( 'gamepad', 24 ); ?></span>
                <h3 class="dyaa-step-title"><?php esc_html_e( 'Pilih Paket Robux', 'dyaastore-child' ); ?></h3>
                <p class="dyaa-step-desc"><?php esc_html_e( 'Telusuri katalog dan pilih paket sesuai kebutuhan — mulai 100 Robux hingga 10.000 Robux.', 'dyaastore-child' ); ?></p>
            </li>
            <li class="dyaa-step-card">
                <span class="dyaa-step-num">02</span>
                <span class="dyaa-step-icon"><?php echo dyaa_icon( 'credit-card', 24 ); ?></span>
                <h3 class="dyaa-step-title"><?php esc_html_e( 'Isi Username & Bayar', 'dyaastore-child' ); ?></h3>
                <p class="dyaa-step-desc"><?php esc_html_e( 'Masukkan username Roblox kamu di checkout, lalu bayar via QRIS, transfer bank, atau e-wallet.', 'dyaastore-child' ); ?></p>
            </li>
            <li class="dyaa-step-card">
                <span class="dyaa-step-num">03</span>
                <span class="dyaa-step-icon"><?php echo dyaa_icon( 'send', 24 ); ?></span>
                <h3 class="dyaa-step-title"><?php esc_html_e( 'Robux Masuk ke Akun', 'dyaastore-child' ); ?></h3>
                <p class="dyaa-step-desc"><?php esc_html_e( 'Tim kami akan mengirim Robux secara manual rata-rata 5–10 menit setelah pembayaran terverifikasi.', 'dyaastore-child' ); ?></p>
            </li>
        </ol>
    </section>

    <?php echo do_shortcode( '[dyaa_stats]' ); ?>

    <section class="dyaa-section">
        <div class="dyaa-section-header">
            <span class="dyaa-section-eyebrow"><?php echo dyaa_icon( 'quote', 14 ); ?> <?php esc_html_e( 'Ulasan Pelanggan', 'dyaastore-child' ); ?></span>
            <h2><?php esc_html_e( 'Apa Kata Pelanggan Kami', 'dyaastore-child' ); ?></h2>
            <p><?php esc_html_e( 'Ulasan langsung dari pelanggan yang sudah berbelanja di Dyaa Store. Semua badge "verified" hanya muncul untuk akun dengan minimal satu transaksi sukses.', 'dyaastore-child' ); ?></p>
        </div>

        <?php echo do_shortcode( '[dyaa_testimonials]' ); ?>
    </section>

    <section class="dyaa-section dyaa-faq" id="dyaa-faq">
        <div class="dyaa-section-header">
            <span class="dyaa-section-eyebrow"><?php echo dyaa_icon( 'help-circle', 14 ); ?> <?php esc_html_e( 'FAQ', 'dyaastore-child' ); ?></span>
            <h2><?php esc_html_e( 'Pertanyaan yang Sering Ditanyakan', 'dyaastore-child' ); ?></h2>
            <p><?php esc_html_e( 'Jawaban cepat untuk pertanyaan paling sering dari pelanggan baru.', 'dyaastore-child' ); ?></p>
        </div>

        <div class="dyaa-faq-list">
            <details class="dyaa-faq-item" open>
                <summary><?php esc_html_e( 'Apakah Robux yang saya beli aman dan tidak terkena ban?', 'dyaastore-child' ); ?></summary>
                <p><?php esc_html_e( 'Aman. Kami menggunakan metode pengiriman manual lewat akun pembayaran resmi (gamepass), sehingga tidak melanggar Term of Service Roblox. Hingga saat ini belum pernah ada pelanggan yang mengalami ban karena membeli di Dyaa Store.', 'dyaastore-child' ); ?></p>
            </details>
            <details class="dyaa-faq-item">
                <summary><?php esc_html_e( 'Berapa lama proses pengiriman Robux setelah saya bayar?', 'dyaastore-child' ); ?></summary>
                <p><?php esc_html_e( 'Rata-rata 5–10 menit pada jam operasional (08.00–23.00 WIB). Jika di luar jam tersebut, paling lama 1 jam akan kami proses oleh admin yang sedang bertugas.', 'dyaastore-child' ); ?></p>
            </details>
            <details class="dyaa-faq-item">
                <summary><?php esc_html_e( 'Metode pembayaran apa saja yang tersedia?', 'dyaastore-child' ); ?></summary>
                <p><?php esc_html_e( 'QRIS (semua bank/e-wallet), transfer bank manual (BCA, Mandiri, BRI, BNI), dan e-wallet (DANA, OVO, GoPay, ShopeePay) lewat link payment.', 'dyaastore-child' ); ?></p>
            </details>
            <details class="dyaa-faq-item">
                <summary><?php esc_html_e( 'Apakah Robux bisa direfund kalau salah username?', 'dyaastore-child' ); ?></summary>
                <p><?php esc_html_e( 'Bisa, selama Robux belum kami kirim. Hubungi admin lewat WhatsApp dengan menyertakan nomor pesanan untuk perbaikan username. Kalau Robux sudah masuk ke username yang salah, refund tidak bisa diproses.', 'dyaastore-child' ); ?></p>
            </details>
            <details class="dyaa-faq-item">
                <summary><?php esc_html_e( 'Apakah saya wajib membuat akun di website ini?', 'dyaastore-child' ); ?></summary>
                <p><?php esc_html_e( 'Tidak wajib. Kamu bisa checkout sebagai guest. Tapi membuat akun memudahkan pelacakan riwayat pesanan dan akses promo khusus member.', 'dyaastore-child' ); ?></p>
            </details>
        </div>
    </section>

</main>
