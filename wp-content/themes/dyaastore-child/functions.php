<?php
/**
 * Dyaa Store Child Theme — Functions
 *
 * Tugas Akhir: Rancang Bangun Website E-Commerce Dyaa Store
 * Berbasis WordPress menggunakan WooCommerce dengan Metode Waterfall
 * untuk Penjualan Robux
 *
 * Penulis: Wahyu Akbar Pratama Siregar (NIM 0110122029)
 * Program Studi: Sistem Informasi — STT-NF 2026
 *
 * @package DyaaStoreChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once get_stylesheet_directory() . '/inc/icons.php';

/* =============================================================
 * KONSTANTA — Sesuaikan dengan toko kamu
 * ============================================================= */

define( 'DYAA_WHATSAPP_NUMBER', '6289515881150' ); // +62 895-1588-1150 — nomor WA Dyaa Store
define( 'DYAA_WHATSAPP_TEXT',   'Halo Dyaa Store, saya mau tanya tentang Robux' );
define( 'DYAA_BRAND_NAME',      'Dyaa Store' );
define( 'DYAA_BRAND_TAGLINE',   'Top Up Robux Termurah & Tercepat Se-Indonesia' );

/* =============================================================
 * Enqueue Styles & Scripts
 * ============================================================= */

/**
 * Inject pre-paint script: pasang class theme sebelum body render
 * agar tidak ada flash-of-light-mode.
 */
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

function dyaastore_enqueue_styles() {
    wp_enqueue_style(
        'dyaastore-google-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
        array(),
        null
    );

    wp_enqueue_style(
        'hello-elementor-parent',
        get_template_directory_uri() . '/style.css'
    );

    wp_enqueue_style(
        'dyaastore-child',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'hello-elementor-parent', 'dyaastore-google-fonts' ),
        wp_get_theme()->get( 'Version' )
    );

    wp_enqueue_script(
        'dyaastore-script',
        get_stylesheet_directory_uri() . '/assets/js/dyaastore.js',
        array(),
        wp_get_theme()->get( 'Version' ),
        true
    );
}
add_action( 'wp_enqueue_scripts', 'dyaastore_enqueue_styles' );

/**
 * Body class: tandai SEMUA halaman frontend agar header/footer minimal
 * Hello Elementor di-hide dan diganti dengan kustom Dyaa Store. Tetap tandai
 * 'dyaa-landing' khusus untuk URL `/`.
 */
function dyaastore_body_class_landing( $classes ) {
    if ( ! is_admin() ) {
        $classes[] = 'dyaa-site';
    }
    if ( is_front_page() ) {
        $classes[] = 'dyaa-landing';
    }

    // Halaman my-account saat user belum login → mode auth split-screen.
    if ( function_exists( 'is_account_page' ) && is_account_page() && ! is_user_logged_in() ) {
        $classes[] = 'dyaa-auth-screen';
    }

    return $classes;
}
add_filter( 'body_class', 'dyaastore_body_class_landing' );

/**
 * Aktifkan registrasi via halaman My Account secara default (untuk auth split-screen).
 * Tetap menghormati opsi yang sudah di-set admin di Settings → Accounts & Privacy
 * jika sudah pernah disimpan secara eksplisit.
 */
function dyaastore_force_enable_registration( $value ) {
    return 'yes';
}
add_filter( 'pre_option_woocommerce_enable_myaccount_registration', 'dyaastore_force_enable_registration' );

/**
 * Sidebar kiri — mirip dyaastore.fusionifydigital.store:
 * Logo + MENU + NAVIGASI + PENGGUNA + Join WhatsApp Group.
 */
function dyaastore_render_sidebar() {
    if ( is_admin() ) {
        return;
    }
    $is_logged_in = is_user_logged_in();
    $home         = home_url( '/' );
    $shop         = class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : $home;
    $cart         = class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'cart' ) : $home;
    $myaccount    = class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();
    $register_url = class_exists( 'WooCommerce' )
        ? add_query_arg( 'action', 'register', wc_get_page_permalink( 'myaccount' ) )
        : wp_registration_url();
    $wa_link      = 'https://wa.me/' . DYAA_WHATSAPP_NUMBER . '?text=' . rawurlencode( DYAA_WHATSAPP_TEXT );

    // URL halaman statis — di-seed otomatis oleh mu-plugin dyaastore-pages.php.
    $page_about   = function_exists( 'dyaastore_get_page_url' ) ? dyaastore_get_page_url( 'tentang', $home ) : $home;
    $page_faq     = function_exists( 'dyaastore_get_page_url' ) ? dyaastore_get_page_url( 'faq', $home . '#dyaa-faq' ) : $home . '#dyaa-faq';
    $page_terms   = function_exists( 'dyaastore_get_page_url' ) ? dyaastore_get_page_url( 'syarat-ketentuan', $home ) : $home;
    $page_privacy = function_exists( 'dyaastore_get_page_url' ) ? dyaastore_get_page_url( 'kebijakan-privasi', get_privacy_policy_url() ?: $home ) : ( get_privacy_policy_url() ?: $home );
    $page_support = function_exists( 'dyaastore_get_page_url' ) ? dyaastore_get_page_url( 'dukungan', $wa_link ) : $wa_link;
    ?>
    <button type="button" class="dyaa-sidebar-toggle" aria-label="<?php esc_attr_e( 'Buka menu', 'dyaastore-child' ); ?>" id="dyaa-sidebar-toggle">
        <span></span><span></span><span></span>
    </button>
    <div class="dyaa-sidebar-overlay" id="dyaa-sidebar-overlay" aria-hidden="true"></div>

    <aside class="dyaa-sidebar" id="dyaa-sidebar" role="navigation" aria-label="<?php esc_attr_e( 'Navigasi sidebar Dyaa Store', 'dyaastore-child' ); ?>">

        <a class="dyaa-sidebar-brand" href="<?php echo esc_url( $home ); ?>">
            <img class="dyaa-sidebar-logo" src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/dyaa-logo.png' ); ?>" alt="<?php echo esc_attr( DYAA_BRAND_NAME ); ?>" />
            <span class="dyaa-sidebar-brand-text"><?php echo esc_html( DYAA_BRAND_NAME ); ?></span>
        </a>

        <div class="dyaa-sidebar-scroll">

            <div class="dyaa-side-group">
                <div class="dyaa-side-group-label"><?php esc_html_e( 'Menu', 'dyaastore-child' ); ?></div>
                <ul class="dyaa-side-list">
                    <li><a href="<?php echo esc_url( $home ); ?>" class="dyaa-side-link<?php echo is_front_page() ? ' is-active' : ''; ?>">
                        <span class="dyaa-side-ico"><?php echo dyaa_icon( 'home', 18 ); ?></span><?php esc_html_e( 'Beranda', 'dyaastore-child' ); ?>
                    </a></li>
                    <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                    <li><a href="<?php echo esc_url( $shop ); ?>" class="dyaa-side-link<?php echo is_shop() ? ' is-active' : ''; ?>">
                        <span class="dyaa-side-ico"><?php echo dyaa_icon( 'gamepad', 18 ); ?></span><?php esc_html_e( 'Semua Paket', 'dyaastore-child' ); ?>
                    </a></li>
                    <li><a href="<?php echo esc_url( $shop ); ?>?orderby=popularity" class="dyaa-side-link">
                        <span class="dyaa-side-ico"><?php echo dyaa_icon( 'trophy', 18 ); ?></span><?php esc_html_e( 'Paket Terlaris', 'dyaastore-child' ); ?>
                    </a></li>
                    <li><a href="<?php echo esc_url( $myaccount ); ?>" class="dyaa-side-link">
                        <span class="dyaa-side-ico"><?php echo dyaa_icon( 'receipt', 18 ); ?></span><?php esc_html_e( 'Cek Pesanan', 'dyaastore-child' ); ?>
                    </a></li>
                    <?php endif; ?>
                    <li class="dyaa-side-theme-row">
                        <span class="dyaa-side-theme-label">
                            <span class="dyaa-side-ico"><?php echo dyaa_icon( 'sun', 18 ); ?></span>
                            <span class="dyaa-side-theme-text" data-dyaa-theme-label><?php esc_html_e( 'Mode Terang', 'dyaastore-child' ); ?></span>
                        </span>
                        <button type="button" id="dyaa-darkmode-btn-side" class="dyaa-theme-toggle" role="switch" aria-checked="false" aria-label="<?php esc_attr_e( 'Aktifkan mode terang', 'dyaastore-child' ); ?>">
                            <span class="dyaa-theme-toggle-track">
                                <span class="dyaa-theme-toggle-icon dyaa-theme-toggle-icon--sun" aria-hidden="true"><?php echo dyaa_icon( 'sun', 12 ); ?></span>
                                <span class="dyaa-theme-toggle-icon dyaa-theme-toggle-icon--moon" aria-hidden="true"><?php echo dyaa_icon( 'moon', 12 ); ?></span>
                                <span class="dyaa-theme-toggle-thumb" aria-hidden="true"></span>
                            </span>
                        </button>
                    </li>
                </ul>
            </div>

            <div class="dyaa-side-group">
                <div class="dyaa-side-group-label"><?php esc_html_e( 'Navigasi', 'dyaastore-child' ); ?></div>
                <ul class="dyaa-side-list">
                    <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                    <li><a href="<?php echo esc_url( $shop ); ?>" class="dyaa-side-link<?php echo ( is_shop() || is_product_category() || is_product() ) ? ' is-active' : ''; ?>"><span class="dyaa-side-ico"><?php echo dyaa_icon( 'list', 18 ); ?></span><?php esc_html_e( 'Daftar Layanan', 'dyaastore-child' ); ?></a></li>
                    <?php endif; ?>
                    <li><a href="<?php echo esc_url( $page_faq ); ?>" class="dyaa-side-link<?php echo is_page( 'faq' ) ? ' is-active' : ''; ?>"><span class="dyaa-side-ico"><?php echo dyaa_icon( 'help-circle', 18 ); ?></span><?php esc_html_e( 'FAQ', 'dyaastore-child' ); ?></a></li>
                    <li><a href="<?php echo esc_url( $page_support ); ?>" class="dyaa-side-link<?php echo is_page( 'dukungan' ) ? ' is-active' : ''; ?>"><span class="dyaa-side-ico"><?php echo dyaa_icon( 'message', 18 ); ?></span><?php esc_html_e( 'Dukungan Pelanggan', 'dyaastore-child' ); ?></a></li>
                    <li><a href="<?php echo esc_url( $page_about ); ?>" class="dyaa-side-link<?php echo is_page( 'tentang' ) ? ' is-active' : ''; ?>"><span class="dyaa-side-ico"><?php echo dyaa_icon( 'info', 18 ); ?></span><?php esc_html_e( 'Tentang', 'dyaastore-child' ); ?></a></li>
                    <li><a href="<?php echo esc_url( $page_terms ); ?>" class="dyaa-side-link<?php echo is_page( 'syarat-ketentuan' ) ? ' is-active' : ''; ?>"><span class="dyaa-side-ico"><?php echo dyaa_icon( 'file-text', 18 ); ?></span><?php esc_html_e( 'Syarat & Ketentuan', 'dyaastore-child' ); ?></a></li>
                    <li><a href="<?php echo esc_url( $page_privacy ); ?>" class="dyaa-side-link<?php echo is_page( 'kebijakan-privasi' ) ? ' is-active' : ''; ?>"><span class="dyaa-side-ico"><?php echo dyaa_icon( 'shield', 18 ); ?></span><?php esc_html_e( 'Kebijakan Privasi', 'dyaastore-child' ); ?></a></li>
                </ul>
            </div>

            <div class="dyaa-side-group">
                <div class="dyaa-side-group-label"><?php esc_html_e( 'Pengguna', 'dyaastore-child' ); ?></div>
                <ul class="dyaa-side-list">
                    <?php if ( $is_logged_in ) : ?>
                        <li><a href="<?php echo esc_url( $myaccount ); ?>" class="dyaa-side-link<?php echo is_account_page() ? ' is-active' : ''; ?>"><span class="dyaa-side-ico"><?php echo dyaa_icon( 'user', 18 ); ?></span><?php esc_html_e( 'Akun Saya', 'dyaastore-child' ); ?></a></li>
                        <li><a href="<?php echo esc_url( wp_logout_url( $home ) ); ?>" class="dyaa-side-link"><span class="dyaa-side-ico"><?php echo dyaa_icon( 'logout', 18 ); ?></span><?php esc_html_e( 'Keluar', 'dyaastore-child' ); ?></a></li>
                    <?php else : ?>
                        <li><a href="<?php echo esc_url( $myaccount ); ?>" class="dyaa-side-link"><span class="dyaa-side-ico"><?php echo dyaa_icon( 'login', 18 ); ?></span><?php esc_html_e( 'Masuk', 'dyaastore-child' ); ?></a></li>
                        <li><a href="<?php echo esc_url( $register_url ); ?>" class="dyaa-side-link"><span class="dyaa-side-ico"><?php echo dyaa_icon( 'user-plus', 18 ); ?></span><?php esc_html_e( 'Daftar', 'dyaastore-child' ); ?></a></li>
                    <?php endif; ?>
                </ul>
            </div>

        </div>

        <a href="<?php echo esc_url( $wa_link ); ?>" target="_blank" rel="noopener" class="dyaa-side-wa-cta">
            <span class="dyaa-side-wa-ico" aria-hidden="true"><?php echo dyaa_icon( 'whatsapp', 22 ); ?></span>
            <div class="dyaa-side-wa-meta">
                <strong><?php esc_html_e( 'Grup WhatsApp Member', 'dyaastore-child' ); ?></strong>
                <span class="dyaa-side-wa-btn"><?php esc_html_e( 'Gabung Grup', 'dyaastore-child' ); ?></span>
            </div>
        </a>

    </aside>
    <?php
}
add_action( 'wp_body_open', 'dyaastore_render_sidebar', 4 );

/**
 * Bilah navigasi atas — search center, theme toggle, Login (orange), Daftar (outline).
 */
function dyaastore_render_top_navigation() {
    if ( is_admin() ) {
        return;
    }

    $is_logged_in = is_user_logged_in();
    $myaccount    = class_exists( 'WooCommerce' )
        ? wc_get_page_permalink( 'myaccount' )
        : wp_login_url();
    $register_url = class_exists( 'WooCommerce' )
        ? add_query_arg( 'action', 'register', wc_get_page_permalink( 'myaccount' ) )
        : wp_registration_url();
    $cart_count = 0;
    if ( class_exists( 'WooCommerce' ) && function_exists( 'WC' ) && WC()->cart ) {
        $cart_count = WC()->cart->get_cart_contents_count();
    }
    $cart_url = class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'cart' ) : home_url( '/' );
    ?>
    <div class="dyaa-topnav-wrap" role="navigation" aria-label="<?php echo esc_attr__( 'Navigasi atas Dyaa Store', 'dyaastore-child' ); ?>">
        <div class="dyaa-topnav-inner">

            <?php if ( class_exists( 'WooCommerce' ) ) : ?>
            <form class="dyaa-topnav-search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
                <span class="dyaa-search-icon" aria-hidden="true"><?php echo dyaa_icon( 'search', 18 ); ?></span>
                <input type="search" name="s" placeholder="<?php esc_attr_e( 'Cari paket Robux, voucher, bundle…', 'dyaastore-child' ); ?>" aria-label="<?php esc_attr_e( 'Cari produk', 'dyaastore-child' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" />
                <input type="hidden" name="post_type" value="product" />
            </form>
            <?php endif; ?>

            <div class="dyaa-topnav-actions">
                <button type="button" id="dyaa-darkmode-btn" class="dyaa-theme-toggle" role="switch" aria-checked="false" aria-label="<?php esc_attr_e( 'Aktifkan mode terang', 'dyaastore-child' ); ?>">
                    <span class="dyaa-theme-toggle-track">
                        <span class="dyaa-theme-toggle-icon dyaa-theme-toggle-icon--sun" aria-hidden="true"><?php echo dyaa_icon( 'sun', 12 ); ?></span>
                        <span class="dyaa-theme-toggle-icon dyaa-theme-toggle-icon--moon" aria-hidden="true"><?php echo dyaa_icon( 'moon', 12 ); ?></span>
                        <span class="dyaa-theme-toggle-thumb" aria-hidden="true"></span>
                    </span>
                </button>

                <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                <a class="dyaa-topnav-icon-btn dyaa-cart-link" href="<?php echo esc_url( $cart_url ); ?>" aria-label="<?php esc_attr_e( 'Keranjang', 'dyaastore-child' ); ?>">
                    <span class="dyaa-cart-icon"><?php echo dyaa_icon( 'cart', 18 ); ?></span>
                    <?php if ( $cart_count > 0 ) : ?>
                        <span class="dyaa-cart-badge"><?php echo esc_html( $cart_count ); ?></span>
                    <?php endif; ?>
                </a>
                <?php endif; ?>

                <?php if ( $is_logged_in ) : ?>
                    <a href="<?php echo esc_url( $myaccount ); ?>" class="dyaa-cta dyaa-cta-solid">
                        <span class="dyaa-cta-ico"><?php echo dyaa_icon( 'user', 16 ); ?></span><?php esc_html_e( 'Akun Saya', 'dyaastore-child' ); ?>
                    </a>
                <?php else : ?>
                    <a href="<?php echo esc_url( $myaccount ); ?>" class="dyaa-cta dyaa-cta-solid">
                        <span class="dyaa-cta-ico"><?php echo dyaa_icon( 'login', 16 ); ?></span><?php esc_html_e( 'Masuk', 'dyaastore-child' ); ?>
                    </a>
                    <a href="<?php echo esc_url( $register_url ); ?>" class="dyaa-cta dyaa-cta-ghost"><?php esc_html_e( 'Daftar', 'dyaastore-child' ); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}
add_action( 'wp_body_open', 'dyaastore_render_top_navigation', 5 );

/**
 * Mobile bottom navigation (4 ikon) — UX khas e-commerce mobile.
 */
function dyaastore_render_bottom_nav() {
    if ( is_admin() ) {
        return;
    }
    ?>
    <nav class="dyaa-bottomnav" aria-label="<?php esc_attr_e( 'Navigasi bawah', 'dyaastore-child' ); ?>">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="dyaa-bn-item<?php echo is_front_page() ? ' is-active' : ''; ?>">
            <span class="dyaa-bn-icon"><?php echo dyaa_icon( 'home', 22 ); ?></span>
            <span class="dyaa-bn-label">Beranda</span>
        </a>
        <?php if ( class_exists( 'WooCommerce' ) ) : ?>
        <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="dyaa-bn-item<?php echo is_shop() ? ' is-active' : ''; ?>">
            <span class="dyaa-bn-icon"><?php echo dyaa_icon( 'gamepad', 22 ); ?></span>
            <span class="dyaa-bn-label">Shop</span>
        </a>
        <a href="<?php echo esc_url( wc_get_page_permalink( 'cart' ) ); ?>" class="dyaa-bn-item<?php echo is_cart() ? ' is-active' : ''; ?>">
            <span class="dyaa-bn-icon"><?php echo dyaa_icon( 'cart', 22 ); ?></span>
            <span class="dyaa-bn-label">Keranjang</span>
            <?php if ( function_exists( 'WC' ) && WC()->cart && WC()->cart->get_cart_contents_count() > 0 ) : ?>
                <span class="dyaa-bn-badge"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
            <?php endif; ?>
        </a>
        <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="dyaa-bn-item<?php echo is_account_page() ? ' is-active' : ''; ?>">
            <span class="dyaa-bn-icon"><?php echo dyaa_icon( 'user', 22 ); ?></span>
            <span class="dyaa-bn-label"><?php echo is_user_logged_in() ? 'Akun' : 'Masuk'; ?></span>
        </a>
        <?php endif; ?>
    </nav>
    <?php
}
add_action( 'wp_footer', 'dyaastore_render_bottom_nav', 2 );

/* =============================================================
 * KF-07 — Custom Field "Username Roblox" pada Halaman Checkout
 * Acuan: docs/01-analisis-kebutuhan.md
 * ============================================================= */

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

function dyaastore_display_roblox_in_admin( $order ) {
    $username = get_post_meta( $order->get_id(), '_roblox_username', true );
    if ( $username ) {
        echo '<p><strong>' . esc_html__( 'Username Roblox:', 'dyaastore-child' )
            . '</strong> <span style="color:#2563eb;font-weight:600;">'
            . esc_html( $username ) . '</span></p>';
    }
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'dyaastore_display_roblox_in_admin' );

function dyaastore_display_roblox_in_email( $order, $sent_to_admin, $plain_text, $email ) {
    $username = get_post_meta( $order->get_id(), '_roblox_username', true );
    if ( $username ) {
        if ( $plain_text ) {
            echo "Username Roblox: " . esc_html( $username ) . "\n";
        } else {
            echo '<p><strong>Username Roblox:</strong> ' . esc_html( $username ) . '</p>';
        }
    }
}
add_action( 'woocommerce_email_order_meta', 'dyaastore_display_roblox_in_email', 10, 4 );

function dyaastore_display_roblox_in_thankyou( $order_id ) {
    $username = get_post_meta( $order_id, '_roblox_username', true );
    if ( $username ) {
        echo '<p><strong>Username Roblox:</strong> ' . esc_html( $username ) . '</p>';
    }
}
add_action( 'woocommerce_thankyou', 'dyaastore_display_roblox_in_thankyou', 20 );

/* =============================================================
 * KF-12 — Set produk default sebagai Virtual
 * ============================================================= */

function dyaastore_default_product_virtual( $post_id, $post, $update ) {
    if ( $post->post_type !== 'product' || $update ) {
        return;
    }
    update_post_meta( $post_id, '_virtual', 'yes' );
}
add_action( 'wp_insert_post', 'dyaastore_default_product_virtual', 10, 3 );

/* =============================================================
 * KF-24 — QRIS Payment Gateway (Statis, verifikasi manual)
 * Acuan: docs/07-implementasi-fitur.md → FT-13
 * ============================================================= */

/**
 * Muat class gateway SEKARANG (immediate require). Child theme functions.php
 * dijalankan SETELAH plugins_loaded, jadi WC_Payment_Gateway sudah tersedia
 * di titik ini saat WooCommerce aktif.
 */
if ( class_exists( 'WC_Payment_Gateway' ) ) {
    require_once get_stylesheet_directory() . '/inc/class-wc-dyaa-qris-gateway.php';
}

/**
 * Daftarkan gateway ke daftar metode pembayaran WooCommerce.
 *
 * Filter ini akan dipanggil oleh WC saat membangun daftar gateway aktif
 * (di admin Settings → Payments maupun di checkout).
 *
 * @param array $gateways
 * @return array
 */
function dyaastore_register_qris_gateway( $gateways ) {
    if ( class_exists( 'WC_Dyaa_QRIS_Gateway' ) ) {
        $gateways[] = 'WC_Dyaa_QRIS_Gateway';
    }
    return $gateways;
}
add_filter( 'woocommerce_payment_gateways', 'dyaastore_register_qris_gateway' );

/**
 * Auto-aktifkan QRIS sekali (idempotent dengan flag
 * 'dyaastore_qris_default_enabled') supaya admin tidak perlu toggle manual
 * di Settings → Payments untuk demo skripsi. Admin tetap dapat menonaktifkan
 * via UI Woo kapan saja.
 */
function dyaastore_qris_auto_enable_once() {
    if ( get_option( 'dyaastore_qris_default_enabled' ) ) {
        return;
    }
    if ( ! class_exists( 'WC_Dyaa_QRIS_Gateway' ) ) {
        return;
    }

    $opts = get_option( 'woocommerce_dyaa_qris_settings', array() );
    if ( empty( $opts['enabled'] ) ) {
        $opts['enabled'] = 'yes';
        update_option( 'woocommerce_dyaa_qris_settings', $opts );
    }
    update_option( 'dyaastore_qris_default_enabled', 1 );
}
add_action( 'init', 'dyaastore_qris_auto_enable_once', 5 );

/**
 * KF-15 / KF-24 — Paksa halaman Cart & Checkout ke shortcode klasik
 * (`[woocommerce_cart]` & `[woocommerce_checkout]`).
 *
 * Sejak WC 9.x, halaman ini di-seed sebagai Block Checkout. Gateway
 * pembayaran custom (termasuk QRIS Dyaa Store) belum di-port ke Block
 * Integration API, jadi blok checkout tidak menampilkannya.
 *
 * Konversi ke shortcode klasik di sini bersifat idempoten (flag
 * 'dyaastore_classic_cart_checkout') sehingga aman dijalankan setiap
 * request: hanya jalan sekali. Admin tetap dapat mengubah konten
 * halaman secara manual setelahnya tanpa di-overwrite.
 */
function dyaastore_force_classic_cart_checkout() {
    if ( get_option( 'dyaastore_classic_cart_checkout' ) ) {
        return;
    }

    $pages = array(
        'woocommerce_cart_page_id'     => '[woocommerce_cart]',
        'woocommerce_checkout_page_id' => '[woocommerce_checkout]',
    );

    foreach ( $pages as $option_name => $shortcode ) {
        $page_id = (int) get_option( $option_name, 0 );
        if ( ! $page_id ) {
            continue;
        }
        $page = get_post( $page_id );
        if ( ! $page || $page->post_status !== 'publish' ) {
            continue;
        }
        if ( false !== strpos( $page->post_content, $shortcode ) ) {
            continue;
        }
        wp_update_post(
            array(
                'ID'           => $page_id,
                'post_content' => $shortcode,
            )
        );
    }

    update_option( 'dyaastore_classic_cart_checkout', 1 );
}
add_action( 'init', 'dyaastore_force_classic_cart_checkout', 6 );

/* =============================================================
 * Floating WhatsApp Button + Dark Mode Toggle
 * Inspirasi: dyaastore.fusionifydigital.store
 * ============================================================= */

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
            <span class="dyaa-wa-line-1"><?php esc_html_e( 'BUTUH BANTUAN', 'dyaastore-child' ); ?></span>
            <span class="dyaa-wa-line-2"><?php esc_html_e( 'KLIK DISINI!!', 'dyaastore-child' ); ?></span>
        </span>
        <span class="dyaa-wa-sticker-mascot" aria-hidden="true">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="32" height="32">
                <path fill="#fff" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
            </svg>
        </span>
    </a>
    <?php
}
add_action( 'wp_footer', 'dyaastore_render_whatsapp_button' );

/**
 * Floating live-transaction notification (bottom-left) — efek "social proof".
 * Datanya rotated client-side oleh dyaastore.js (lihat #dyaa-live-toast).
 */
function dyaastore_render_live_toast() {
    if ( is_admin() ) {
        return;
    }
    ?>
    <div class="dyaa-live-toast" id="dyaa-live-toast" role="status" aria-live="polite">
        <button type="button" class="dyaa-live-close" aria-label="<?php esc_attr_e( 'Tutup notifikasi', 'dyaastore-child' ); ?>"><?php echo dyaa_icon( 'x', 14 ); ?></button>
        <span class="dyaa-live-avatar" data-live="avatar" aria-hidden="true">AP</span>
        <div class="dyaa-live-meta">
            <div class="dyaa-live-row">
                <strong data-live="name">Andi Pratama</strong>
                <span class="dyaa-live-verified" title="<?php esc_attr_e( 'Pembelian terverifikasi', 'dyaastore-child' ); ?>"><?php echo dyaa_icon( 'verified', 12 ); ?></span>
            </div>
            <div class="dyaa-live-product"><?php esc_html_e( 'baru saja membeli', 'dyaastore-child' ); ?> <strong data-live="product">800 Robux</strong></div>
            <div class="dyaa-live-time" data-live="time"><?php esc_html_e( 'baru saja', 'dyaastore-child' ); ?></div>
        </div>
    </div>
    <?php
}
add_action( 'wp_footer', 'dyaastore_render_live_toast' );

/**
 * Footer global Dyaa Store (4 kolom) — tampil di semua halaman frontend.
 */
function dyaastore_render_global_footer() {
    if ( is_admin() ) {
        return;
    }
    ?>
    <footer class="dyaa-footer">
        <div class="dyaa-footer-grid">

            <div class="dyaa-footer-brand">
                <h3 class="dyaa-footer-brand-title">
                    <span class="dyaa-footer-brand-ico" aria-hidden="true"><?php echo dyaa_icon( 'gamepad', 22 ); ?></span>
                    <?php echo esc_html( DYAA_BRAND_NAME ); ?>
                </h3>
                <p><?php echo esc_html( DYAA_BRAND_TAGLINE ); ?></p>
                <p class="dyaa-footer-brand-small">Platform top up Robux yang dirancang sebagai studi kasus Tugas Akhir berbasis WooCommerce. Semua transaksi diverifikasi secara manual oleh admin.</p>
                <ul class="dyaa-footer-trust">
                    <li><?php echo dyaa_icon( 'shield-check', 16 ); ?> Pembayaran aman</li>
                    <li><?php echo dyaa_icon( 'check-circle', 16 ); ?> Garansi 100% saldo</li>
                </ul>
            </div>

            <div class="dyaa-footer-col">
                <h3>Peta Situs</h3>
                <ul>
                    <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Beranda</a></li>
                    <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                    <li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">Semua Paket Robux</a></li>
                    <li><a href="<?php echo esc_url( wc_get_page_permalink( 'cart' ) ); ?>">Keranjang</a></li>
                    <li><a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">Akun Saya</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="dyaa-footer-col">
                <h3>Bantuan</h3>
                <ul>
                    <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>#dyaa-cara-pesan">Cara Pesan Robux</a></li>
                    <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                    <li><a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">Cek Status Pesanan</a></li>
                    <?php endif; ?>
                    <li><a href="<?php echo esc_url( function_exists( 'dyaastore_get_page_url' ) ? dyaastore_get_page_url( 'dukungan', 'https://wa.me/' . DYAA_WHATSAPP_NUMBER ) : 'https://wa.me/' . DYAA_WHATSAPP_NUMBER ); ?>">Dukungan Pelanggan</a></li>
                    <li><a href="<?php echo esc_url( function_exists( 'dyaastore_get_page_url' ) ? dyaastore_get_page_url( 'faq', home_url( '/#dyaa-faq' ) ) : home_url( '/#dyaa-faq' ) ); ?>">Pertanyaan Umum (FAQ)</a></li>
                </ul>
            </div>

            <div class="dyaa-footer-col">
                <h3>Legal</h3>
                <ul>
                    <?php
                    $dyaa_privacy_url = function_exists( 'dyaastore_get_page_url' )
                        ? dyaastore_get_page_url( 'kebijakan-privasi', get_privacy_policy_url() ?: '#' )
                        : ( get_privacy_policy_url() ?: '#' );
                    $dyaa_terms_url = function_exists( 'dyaastore_get_page_url' )
                        ? dyaastore_get_page_url( 'syarat-ketentuan', home_url( '/#dyaa-syarat' ) )
                        : home_url( '/#dyaa-syarat' );
                    $dyaa_about_url = function_exists( 'dyaastore_get_page_url' )
                        ? dyaastore_get_page_url( 'tentang', home_url( '/' ) )
                        : home_url( '/' );
                    ?>
                    <li><a href="<?php echo esc_url( $dyaa_about_url ); ?>">Tentang Kami</a></li>
                    <li><a href="<?php echo esc_url( $dyaa_privacy_url ); ?>">Kebijakan Privasi</a></li>
                    <li><a href="<?php echo esc_url( $dyaa_terms_url ); ?>">Syarat &amp; Ketentuan</a></li>
                </ul>
            </div>

        </div>

        <div class="dyaa-footer-bottom">
            <span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( DYAA_BRAND_NAME ); ?>. Hak cipta dilindungi.</span>
            <small class="dyaa-footer-ta">Studi kasus Tugas Akhir &mdash; Wahyu Akbar Pratama Siregar &mdash; Sistem Informasi STT-NF</small>
        </div>
    </footer>
    <?php
}
// Render sebelum wp_footer scripts agar berada di akhir body.
add_action( 'wp_footer', 'dyaastore_render_global_footer', 1 );


/* =============================================================
 * Custom Page Templates (homepage)
 * ============================================================= */

/**
 * Daftarkan template halaman custom.
 */
function dyaastore_register_page_templates( $templates ) {
    $templates['templates/template-homepage.php'] = 'Dyaa Store — Homepage';
    return $templates;
}
add_filter( 'theme_page_templates', 'dyaastore_register_page_templates' );

/**
 * Load template dari child theme.
 */
function dyaastore_load_page_template( $template ) {
    if ( is_page() ) {
        $page_template = get_post_meta( get_the_ID(), '_wp_page_template', true );
        if ( 'templates/template-homepage.php' === $page_template ) {
            $custom = get_stylesheet_directory() . '/templates/template-homepage.php';
            if ( file_exists( $custom ) ) {
                return $custom;
            }
        }
    }
    return $template;
}
add_filter( 'template_include', 'dyaastore_load_page_template', 99 );

/* =============================================================
 * Branding & Admin Customization
 * ============================================================= */

function dyaastore_admin_title( $admin_title, $title ) {
    return $title . ' | ' . DYAA_BRAND_NAME . ' Admin';
}
add_filter( 'admin_title', 'dyaastore_admin_title', 10, 2 );

function dyaastore_admin_footer() {
    return DYAA_BRAND_NAME . ' &copy; ' . date( 'Y' ) . ' — Tugas Akhir STT-NF';
}
add_filter( 'admin_footer_text', 'dyaastore_admin_footer' );

/* =============================================================
 * Helper Functions untuk Template
 * ============================================================= */

/**
 * Render section testimoni.
 *
 * @return string HTML.
 */
function dyaastore_render_testimonials() {
    $testimonials = array(
        array(
            'name'    => 'Andi Pratama',
            'role'    => 'Pelanggan sejak 2024',
            'product' => '1.700 Robux',
            'text'    => 'Pertama kali coba karena harganya paling murah di marketplace. Order jam 9 malam, jam 9.07 sudah masuk akun Roblox. Kasih bintang 5 buat respon adminnya.',
            'rating'  => 5,
            'date'    => '3 hari lalu',
        ),
        array(
            'name'    => 'Maya Lestari',
            'role'    => 'Member premium',
            'product' => '4.500 Robux',
            'text'    => 'Sudah 6 kali transaksi di sini, tidak pernah ada masalah. Adminnya juga ramah, kalau ada error langsung dibantu via WhatsApp. Cocok buat yang sering top up.',
            'rating'  => 5,
            'date'    => '1 minggu lalu',
        ),
        array(
            'name'    => 'Rizky Anggara',
            'role'    => 'Pemain Roblox',
            'product' => '800 Robux',
            'text'    => 'Awalnya ragu karena baru pertama dengar Dyaa Store, tapi setelah baca review dan coba beli paket kecil dulu ternyata aman. Sekarang jadi langganan tetap.',
            'rating'  => 5,
            'date'    => '2 minggu lalu',
        ),
        array(
            'name'    => 'Siti Nurhaliza',
            'role'    => 'Pengguna baru',
            'product' => '400 Robux',
            'text'    => 'Pengiriman lumayan cepat, sekitar 10 menit di waktu siang. Cuma yang masih kurang menurut saya invoice email-nya, kayaknya bisa dibikin lebih rapi. Overall puas.',
            'rating'  => 4,
            'date'    => '3 minggu lalu',
        ),
        array(
            'name'    => 'Bagas Triwibowo',
            'role'    => 'Streamer Roblox',
            'product' => 'Bundle 2x800 Robux',
            'text'    => 'Sebagai streamer yang sering butuh Robux mendadak, di sini cocok karena adminnya stand by sampai malam. Bundle hemat-nya juga lumayan ngirit.',
            'rating'  => 5,
            'date'    => '1 bulan lalu',
        ),
        array(
            'name'    => 'Dewi Kusuma',
            'role'    => 'Orang tua pemain',
            'product' => '100 Robux',
            'text'    => 'Belikan untuk anak saya yang main Roblox, transaksi lewat QRIS gampang banget. Anaknya senang, prosesnya nggak sampai 15 menit dari pembayaran.',
            'rating'  => 5,
            'date'    => '1 bulan lalu',
        ),
    );

    ob_start();
    ?>
    <div class="dyaa-testimonials">
        <?php foreach ( $testimonials as $t ) :
            $rating = max( 1, min( 5, (int) $t['rating'] ) );
            ?>
            <div class="dyaa-testimonial-card">
                <div class="dyaa-testimonial-head">
                    <div class="dyaa-testimonial-author">
                        <div class="dyaa-testimonial-avatar">
                            <?php echo esc_html( strtoupper( substr( $t['name'], 0, 1 ) ) ); ?>
                        </div>
                        <div>
                            <div class="dyaa-testimonial-name">
                                <?php echo esc_html( $t['name'] ); ?>
                                <span class="dyaa-testimonial-verified" title="Pembelian terverifikasi"><?php echo dyaa_icon( 'verified', 14 ); ?></span>
                            </div>
                            <div class="dyaa-testimonial-role"><?php echo esc_html( $t['role'] ); ?></div>
                        </div>
                    </div>
                    <div class="dyaa-stars" aria-label="<?php echo esc_attr( $rating . ' dari 5 bintang' ); ?>">
                        <?php for ( $i = 1; $i <= 5; $i++ ) :
                            echo dyaa_icon( $i <= $rating ? 'star-filled' : 'star', 14 );
                        endfor; ?>
                    </div>
                </div>
                <p class="dyaa-testimonial-text"><?php echo esc_html( $t['text'] ); ?></p>
                <div class="dyaa-testimonial-meta">
                    <span class="dyaa-testimonial-product"><?php echo dyaa_icon( 'check-circle', 12 ); ?> <?php echo esc_html__( 'Pembelian:', 'dyaastore-child' ); ?> <strong><?php echo esc_html( $t['product'] ); ?></strong></span>
                    <span class="dyaa-testimonial-date"><?php echo esc_html( $t['date'] ); ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Render section stats.
 */
function dyaastore_render_stats() {
    ob_start();
    ?>
    <div class="dyaa-stats">
        <div class="dyaa-stats-grid">
            <div class="dyaa-stat-item">
                <span class="dyaa-stat-icon"><?php echo dyaa_icon( 'check-circle', 28 ); ?></span>
                <p class="dyaa-stat-number" data-count-to="8420" data-count-suffix="+">0</p>
                <p class="dyaa-stat-label">Pesanan Terkirim</p>
                <p class="dyaa-stat-sub">sejak Januari 2024</p>
            </div>
            <div class="dyaa-stat-item">
                <span class="dyaa-stat-icon"><?php echo dyaa_icon( 'user', 28 ); ?></span>
                <p class="dyaa-stat-number" data-count-to="1250" data-count-suffix="+">0</p>
                <p class="dyaa-stat-label">Pelanggan Aktif</p>
                <p class="dyaa-stat-sub">repeat order &gt; 2 kali</p>
            </div>
            <div class="dyaa-stat-item">
                <span class="dyaa-stat-icon"><?php echo dyaa_icon( 'star-filled', 28 ); ?></span>
                <p class="dyaa-stat-number" data-count-to="4.8" data-count-suffix="/5">0</p>
                <p class="dyaa-stat-label">Rating Kepuasan</p>
                <p class="dyaa-stat-sub">dari 320 ulasan terverifikasi</p>
            </div>
            <div class="dyaa-stat-item">
                <span class="dyaa-stat-icon"><?php echo dyaa_icon( 'truck', 28 ); ?></span>
                <p class="dyaa-stat-number" data-count-to="7" data-count-suffix=" mnt">0</p>
                <p class="dyaa-stat-label">Rata-rata Pengiriman</p>
                <p class="dyaa-stat-sub">setelah pembayaran masuk</p>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/* =============================================================
 * Shortcodes (untuk dipakai di Elementor / page editor)
 * ============================================================= */

add_shortcode( 'dyaa_testimonials', 'dyaastore_render_testimonials' );
add_shortcode( 'dyaa_stats', 'dyaastore_render_stats' );

/**
 * Shortcode hero section.
 *
 * Pakai: [dyaa_hero badge="Promo" title="Top Up <span class='accent'>Robux</span> Termurah" subtitle="..." cta1_label="Belanja" cta1_url="/shop" cta2_label="Hubungi" cta2_url="https://wa.me/..."]
 */
function dyaastore_shortcode_hero( $atts ) {
    $default_shop = class_exists( 'WooCommerce' )
        ? wc_get_page_permalink( 'shop' )
        : home_url( '/' );

    $atts = shortcode_atts( array(
        'badge'      => 'TOP UP CEPAT & TERPERCAYA',
        'title'      => 'TOP UP <span class="accent">ROBUX</span>',
        'subtitle'   => 'Nikmati pengalaman pembelian Robux otomatis kapan pun di manapun kamu mau. Proses cepat, harga bersaing, terpercaya.',
        'cta1_label' => 'Belanja Sekarang',
        'cta1_url'   => $default_shop,
        'cta2_label' => 'Hubungi Kami',
        'cta2_url'   => 'https://wa.me/' . DYAA_WHATSAPP_NUMBER,
    ), $atts, 'dyaa_hero' );

    $hero_img = get_stylesheet_directory_uri() . '/assets/img/dyaa-hero.png';

    ob_start();
    ?>
    <section class="dyaa-hero">
        <div class="dyaa-hero-grid">
            <div class="dyaa-hero-content">
                <span class="dyaa-hero-badge"><?php echo wp_kses_post( $atts['badge'] ); ?></span>
                <h1><?php echo wp_kses_post( $atts['title'] ); ?></h1>
                <p><?php echo esc_html( $atts['subtitle'] ); ?></p>
                <div class="dyaa-hero-cta">
                    <?php if ( $atts['cta1_label'] ) : ?>
                        <a href="<?php echo esc_url( $atts['cta1_url'] ); ?>" class="dyaa-btn dyaa-btn-primary">
                            <?php echo esc_html( $atts['cta1_label'] ); ?> →
                        </a>
                    <?php endif; ?>
                    <?php if ( $atts['cta2_label'] ) : ?>
                        <a href="<?php echo esc_url( $atts['cta2_url'] ); ?>" class="dyaa-btn dyaa-btn-secondary">
                            <?php echo esc_html( $atts['cta2_label'] ); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="dyaa-hero-illustration">
                <img src="<?php echo esc_url( $hero_img ); ?>" alt="<?php esc_attr_e( 'Top up Robux Dyaa Store', 'dyaastore-child' ); ?>" loading="eager" />
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}
add_shortcode( 'dyaa_hero', 'dyaastore_shortcode_hero' );

/**
 * Shortcode Flash Sale — menampilkan produk yang sedang sale
 * dengan countdown timer di header section.
 *
 * Pakai: [dyaa_flashsale limit="3" hours="6"]
 */
function dyaastore_shortcode_flashsale( $atts ) {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return '';
    }

    $atts = shortcode_atts( array(
        'limit' => 3,
        'hours' => 6,
    ), $atts, 'dyaa_flashsale' );

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
                    <span class="dyaa-flashsale-icon" aria-hidden="true"><?php echo dyaa_icon( 'zap', 22 ); ?></span>
                    <span><?php esc_html_e( 'Flash Sale Robux', 'dyaastore-child' ); ?></span>
                </h2>
                <div class="dyaa-countdown" data-dyaa-countdown="<?php echo esc_attr( $hours ); ?>">
                    <span class="dyaa-countdown-label"><?php esc_html_e( 'Berakhir dalam', 'dyaastore-child' ); ?></span>
                    <span class="dyaa-countdown-time" aria-live="polite">
                        <span class="dyaa-countdown-box" data-cd="hours">00</span>
                        <span class="dyaa-countdown-sep">:</span>
                        <span class="dyaa-countdown-box" data-cd="minutes">00</span>
                        <span class="dyaa-countdown-sep">:</span>
                        <span class="dyaa-countdown-box" data-cd="seconds">00</span>
                    </span>
                </div>
            </div>

            <?php if ( $has_sale ) : ?>
                <?php echo do_shortcode( '[products on_sale="true" limit="' . $limit . '" columns="' . $limit . '" orderby="popularity"]' ); ?>
            <?php else : ?>
                <?php echo do_shortcode( '[products limit="' . $limit . '" columns="' . $limit . '" orderby="rand"]' ); ?>
            <?php endif; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}
add_shortcode( 'dyaa_flashsale', 'dyaastore_shortcode_flashsale' );

/**
 * Shortcode Kategori Robux — 6 kartu kategori dengan badge.
 * Hanya kategori produk yang sebenarnya ada di WooCommerce
 * (bila belum ada, fallback ke kategori statis sesuai skripsi).
 */
function dyaastore_shortcode_categories( $atts ) {
    $defaults = array(
        array(
            'icon'  => 'coins',
            'name'  => 'Paket Hemat',
            'desc'  => 'Mulai dari 100 Robux',
            'badge' => 'PROMO',
            'slug'  => 'paket-hemat',
        ),
        array(
            'icon'  => 'ticket',
            'name'  => 'Voucher Robux',
            'desc'  => 'Aktivasi cepat 5–10 menit',
            'badge' => 'TERLARIS',
            'slug'  => 'voucher-robux',
        ),
        array(
            'icon'  => 'gamepad-2',
            'name'  => 'Gamepass',
            'desc'  => 'Untuk berbagai game Roblox',
            'badge' => '',
            'slug'  => 'gamepass',
        ),
        array(
            'icon'  => 'crown',
            'name'  => 'Premium',
            'desc'  => 'Membership eksklusif',
            'badge' => 'BARU',
            'slug'  => 'premium',
        ),
        array(
            'icon'  => 'gift',
            'name'  => 'Bundle',
            'desc'  => 'Hemat hingga 12%',
            'badge' => '',
            'slug'  => 'bundle',
        ),
        array(
            'icon'  => 'star',
            'name'  => 'Limited',
            'desc'  => 'Stok mingguan terbatas',
            'badge' => 'HOT',
            'slug'  => 'limited',
        ),
    );

    ob_start();
    ?>
    <section class="dyaa-categories">
        <div class="dyaa-categories-inner">
            <div class="dyaa-section-header">
                <h2><?php esc_html_e( 'Kategori Produk', 'dyaastore-child' ); ?></h2>
                <p><?php esc_html_e( 'Pilih kategori sesuai kebutuhan kamu — semua produk Robux resmi & legal.', 'dyaastore-child' ); ?></p>
            </div>

            <div class="dyaa-categories-grid">
                <?php foreach ( $defaults as $cat ) :
                    $url = class_exists( 'WooCommerce' )
                        ? wc_get_page_permalink( 'shop' ) . '?product_cat=' . $cat['slug']
                        : home_url( '/' );

                    if ( class_exists( 'WooCommerce' ) ) {
                        $term = get_term_by( 'slug', $cat['slug'], 'product_cat' );
                        if ( $term && ! is_wp_error( $term ) ) {
                            $url = get_term_link( $term );
                        }
                    }
                    ?>
                    <a href="<?php echo esc_url( $url ); ?>" class="dyaa-category-card">
                        <?php if ( ! empty( $cat['badge'] ) ) : ?>
                            <span class="dyaa-category-badge dyaa-badge-<?php echo esc_attr( sanitize_html_class( strtolower( $cat['badge'] ) ) ); ?>">
                                <?php echo esc_html( $cat['badge'] ); ?>
                            </span>
                        <?php endif; ?>
                        <span class="dyaa-category-icon" aria-hidden="true"><?php echo dyaa_icon( $cat['icon'], 32 ); ?></span>
                        <div class="dyaa-category-name"><?php echo esc_html( $cat['name'] ); ?></div>
                        <div class="dyaa-category-desc"><?php echo esc_html( $cat['desc'] ); ?></div>
                        <span class="dyaa-category-arrow" aria-hidden="true"><?php echo dyaa_icon( 'arrow-right', 16 ); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}
add_shortcode( 'dyaa_categories', 'dyaastore_shortcode_categories' );
