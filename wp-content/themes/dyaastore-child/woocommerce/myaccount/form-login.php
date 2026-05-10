<?php
/**
 * Login + Register Form — Dyaa Store custom split-screen layout
 *
 * Override resmi dari `wp-content/plugins/woocommerce/templates/myaccount/form-login.php`.
 * Markup dirombak total mengikuti referensi auth split-screen
 * (https://dyaastore.fusionifydigital.store/auth) — hero kiri + tab card kanan
 * (Masuk / Daftar). Field, nonce, dan action hook WooCommerce tetap dipertahankan
 * supaya alur login + register & semua plugin tetap berfungsi.
 *
 * Bagian dari TA Wahyu Akbar Pratama Siregar — STT-NF 2026.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package DyaaStoreChild
 * @version 9.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

do_action( 'woocommerce_before_customer_login_form' );

// Pastikan helper ikon tersedia (kalau theme di-load tanpa enqueue).
if ( ! function_exists( 'dyaa_icon' ) && file_exists( get_stylesheet_directory() . '/inc/icons.php' ) ) {
    require_once get_stylesheet_directory() . '/inc/icons.php';
}

$dyaa_register_enabled = ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) );
$dyaa_initial_tab      = ( ! empty( $_GET['action'] ) && 'register' === $_GET['action'] && $dyaa_register_enabled )
    ? 'register'
    : 'login';
$dyaa_show_username    = ( 'no' === get_option( 'woocommerce_registration_generate_username' ) );
$dyaa_show_password    = ( 'no' === get_option( 'woocommerce_registration_generate_password' ) );
$dyaa_lostpw_url       = wp_lostpassword_url();
?>

<div class="dyaa-auth-shell" data-initial-tab="<?php echo esc_attr( $dyaa_initial_tab ); ?>">

    <!-- HERO KIRI -->
    <aside class="dyaa-auth-hero" aria-hidden="true">
        <div class="dyaa-auth-hero-inner">
            <span class="dyaa-auth-eyebrow"><?php esc_html_e( 'TOP UP ROBUX TERPERCAYA', 'dyaastore-child' ); ?></span>
            <h1 class="dyaa-auth-title">
                <?php esc_html_e( 'THE FAMOUS', 'dyaastore-child' ); ?><br>
                <span class="dyaa-auth-title-accent"><?php esc_html_e( 'TOP UP GAME!', 'dyaastore-child' ); ?></span>
            </h1>
            <p class="dyaa-auth-sub"><?php esc_html_e( 'Login atau buat akun untuk mulai top up Robux dengan harga termurah dan pengiriman tercepat.', 'dyaastore-child' ); ?></p>
        </div>

        <ul class="dyaa-auth-features" role="list">
            <li class="dyaa-auth-feature">
                <span class="dyaa-auth-feature-ico"><?php echo function_exists( 'dyaa_icon' ) ? dyaa_icon( 'credit-card', 22 ) : '&#128179;'; ?></span>
                <div>
                    <strong><?php esc_html_e( 'Fast Payment', 'dyaastore-child' ); ?></strong>
                    <span><?php esc_html_e( 'QRIS, e-wallet, transfer bank.', 'dyaastore-child' ); ?></span>
                </div>
            </li>
            <li class="dyaa-auth-feature">
                <span class="dyaa-auth-feature-ico"><?php echo function_exists( 'dyaa_icon' ) ? dyaa_icon( 'truck', 22 ) : '&#128666;'; ?></span>
                <div>
                    <strong><?php esc_html_e( 'Fast Process', 'dyaastore-child' ); ?></strong>
                    <span><?php esc_html_e( 'Proses 1 – 5 menit oleh admin.', 'dyaastore-child' ); ?></span>
                </div>
            </li>
            <li class="dyaa-auth-feature">
                <span class="dyaa-auth-feature-ico"><?php echo function_exists( 'dyaa_icon' ) ? dyaa_icon( 'message', 22 ) : '&#128172;'; ?></span>
                <div>
                    <strong><?php esc_html_e( 'Full Support', 'dyaastore-child' ); ?></strong>
                    <span><?php esc_html_e( 'Tim CS WhatsApp 7 hari/pekan.', 'dyaastore-child' ); ?></span>
                </div>
            </li>
        </ul>
    </aside>

    <!-- CARD KANAN -->
    <section class="dyaa-auth-card" aria-label="<?php esc_attr_e( 'Form akun Dyaa Store', 'dyaastore-child' ); ?>">

        <?php if ( $dyaa_register_enabled ) : ?>
        <div class="dyaa-auth-tabs" role="tablist">
            <button type="button" class="dyaa-auth-tab<?php echo 'login' === $dyaa_initial_tab ? ' is-active' : ''; ?>"
                    role="tab" aria-controls="dyaa-pane-login" aria-selected="<?php echo 'login' === $dyaa_initial_tab ? 'true' : 'false'; ?>"
                    data-dyaa-tab="login">
                <?php esc_html_e( 'Masuk', 'dyaastore-child' ); ?>
            </button>
            <button type="button" class="dyaa-auth-tab<?php echo 'register' === $dyaa_initial_tab ? ' is-active' : ''; ?>"
                    role="tab" aria-controls="dyaa-pane-register" aria-selected="<?php echo 'register' === $dyaa_initial_tab ? 'true' : 'false'; ?>"
                    data-dyaa-tab="register">
                <?php esc_html_e( 'Daftar', 'dyaastore-child' ); ?>
            </button>
            <span class="dyaa-auth-tab-indicator" aria-hidden="true"></span>
        </div>
        <?php endif; ?>

        <!-- ============ PANE: LOGIN ============ -->
        <div id="dyaa-pane-login" class="dyaa-auth-pane<?php echo 'login' === $dyaa_initial_tab ? ' is-active' : ''; ?>"
             role="tabpanel" data-dyaa-pane="login">
            <header class="dyaa-auth-pane-header">
                <h2><?php esc_html_e( 'Silahkan Login', 'dyaastore-child' ); ?></h2>
                <p><?php esc_html_e( 'Masuk menggunakan akun terdaftar kamu', 'dyaastore-child' ); ?></p>
            </header>

            <form class="woocommerce-form woocommerce-form-login login dyaa-auth-form" method="post" novalidate>

                <?php do_action( 'woocommerce_login_form_start' ); ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide dyaa-auth-field">
                    <label for="username"><?php esc_html_e( 'Email atau username', 'dyaastore-child' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
                    <span class="dyaa-auth-input-wrap">
                        <span class="dyaa-auth-input-ico" aria-hidden="true"><?php echo function_exists( 'dyaa_icon' ) ? dyaa_icon( 'user', 18 ) : ''; ?></span>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text dyaa-auth-input" name="username" id="username" autocomplete="username" placeholder="<?php esc_attr_e( 'Masukkan email atau username', 'dyaastore-child' ); ?>" value="<?php echo ( ! empty( $_POST['username'] ) && is_string( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required aria-required="true" />
                    </span>
                </p>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide dyaa-auth-field">
                    <label for="password"><?php esc_html_e( 'Password', 'dyaastore-child' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
                    <span class="dyaa-auth-input-wrap">
                        <span class="dyaa-auth-input-ico" aria-hidden="true"><?php echo function_exists( 'dyaa_icon' ) ? dyaa_icon( 'shield', 18 ) : ''; ?></span>
                        <input class="woocommerce-Input woocommerce-Input--text input-text dyaa-auth-input" type="password" name="password" id="password" autocomplete="current-password" placeholder="<?php esc_attr_e( 'Masukkan password', 'dyaastore-child' ); ?>" required aria-required="true" />
                    </span>
                </p>

                <?php do_action( 'woocommerce_login_form' ); ?>

                <div class="dyaa-auth-row-between">
                    <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme dyaa-auth-remember">
                        <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
                        <span><?php esc_html_e( 'Ingat Saya', 'dyaastore-child' ); ?></span>
                    </label>
                    <a class="dyaa-auth-lost" href="<?php echo esc_url( $dyaa_lostpw_url ); ?>"><?php esc_html_e( 'Lupa Password?', 'dyaastore-child' ); ?></a>
                </div>

                <p class="form-row dyaa-auth-submit-row">
                    <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
                    <button type="submit" class="woocommerce-button button woocommerce-form-login__submit dyaa-auth-submit<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"><?php esc_html_e( 'Masuk', 'dyaastore-child' ); ?></button>
                </p>

                <?php do_action( 'woocommerce_login_form_end' ); ?>

            </form>

            <?php if ( $dyaa_register_enabled ) : ?>
            <p class="dyaa-auth-switch">
                <?php esc_html_e( 'Belum punya akun?', 'dyaastore-child' ); ?>
                <a href="#" data-dyaa-tab-switch="register"><?php esc_html_e( 'Daftar Sekarang', 'dyaastore-child' ); ?></a>
            </p>
            <?php endif; ?>
        </div>

        <?php if ( $dyaa_register_enabled ) : ?>
        <!-- ============ PANE: REGISTER ============ -->
        <div id="dyaa-pane-register" class="dyaa-auth-pane<?php echo 'register' === $dyaa_initial_tab ? ' is-active' : ''; ?>"
             role="tabpanel" data-dyaa-pane="register">
            <header class="dyaa-auth-pane-header">
                <h2><?php esc_html_e( 'Buat Akun Baru', 'dyaastore-child' ); ?></h2>
                <p><?php esc_html_e( 'Daftar gratis untuk mulai top up Robux & cek riwayat pesanan', 'dyaastore-child' ); ?></p>
            </header>

            <form method="post" class="woocommerce-form woocommerce-form-register register dyaa-auth-form" <?php do_action( 'woocommerce_register_form_tag' ); ?>>

                <?php do_action( 'woocommerce_register_form_start' ); ?>

                <?php if ( $dyaa_show_username ) : ?>
                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide dyaa-auth-field">
                    <label for="reg_username"><?php esc_html_e( 'Username', 'dyaastore-child' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
                    <span class="dyaa-auth-input-wrap">
                        <span class="dyaa-auth-input-ico" aria-hidden="true"><?php echo function_exists( 'dyaa_icon' ) ? dyaa_icon( 'user', 18 ) : ''; ?></span>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text dyaa-auth-input" name="username" id="reg_username" autocomplete="username" placeholder="<?php esc_attr_e( 'Pilih username unik', 'dyaastore-child' ); ?>" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required aria-required="true" />
                    </span>
                </p>
                <?php endif; ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide dyaa-auth-field">
                    <label for="reg_email"><?php esc_html_e( 'Email', 'dyaastore-child' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
                    <span class="dyaa-auth-input-wrap">
                        <span class="dyaa-auth-input-ico" aria-hidden="true"><?php echo function_exists( 'dyaa_icon' ) ? dyaa_icon( 'mail', 18 ) : ''; ?></span>
                        <input type="email" class="woocommerce-Input woocommerce-Input--text input-text dyaa-auth-input" name="email" id="reg_email" autocomplete="email" placeholder="<?php esc_attr_e( 'nama@email.com', 'dyaastore-child' ); ?>" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" required aria-required="true" />
                    </span>
                </p>

                <?php if ( $dyaa_show_password ) : ?>
                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide dyaa-auth-field">
                    <label for="reg_password"><?php esc_html_e( 'Password', 'dyaastore-child' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
                    <span class="dyaa-auth-input-wrap">
                        <span class="dyaa-auth-input-ico" aria-hidden="true"><?php echo function_exists( 'dyaa_icon' ) ? dyaa_icon( 'shield', 18 ) : ''; ?></span>
                        <input type="password" class="woocommerce-Input woocommerce-Input--text input-text dyaa-auth-input" name="password" id="reg_password" autocomplete="new-password" placeholder="<?php esc_attr_e( 'Buat password yang kuat', 'dyaastore-child' ); ?>" required aria-required="true" />
                    </span>
                </p>
                <?php else : ?>
                    <p class="dyaa-auth-note"><?php esc_html_e( 'Link untuk membuat password baru akan dikirim ke email kamu.', 'dyaastore-child' ); ?></p>
                <?php endif; ?>

                <?php do_action( 'woocommerce_register_form' ); ?>

                <p class="woocommerce-form-row form-row dyaa-auth-submit-row">
                    <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
                    <button type="submit" class="woocommerce-Button woocommerce-button button dyaa-auth-submit<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?> woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Daftar Sekarang', 'dyaastore-child' ); ?></button>
                </p>

                <?php do_action( 'woocommerce_register_form_end' ); ?>

            </form>

            <p class="dyaa-auth-switch">
                <?php esc_html_e( 'Sudah punya akun?', 'dyaastore-child' ); ?>
                <a href="#" data-dyaa-tab-switch="login"><?php esc_html_e( 'Masuk Sekarang', 'dyaastore-child' ); ?></a>
            </p>
        </div>
        <?php endif; ?>

    </section>

</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
