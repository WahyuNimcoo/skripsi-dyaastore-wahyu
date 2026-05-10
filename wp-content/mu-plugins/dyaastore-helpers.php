<?php
/**
 * Plugin Name: Dyaa Store — Site Helpers
 * Description: Must-Use plugin untuk Dyaa Store. Berisi helper yang harus selalu aktif (terlepas dari tema apa yang dipakai). Bagian dari Tugas Akhir Wahyu Akbar Pratama Siregar (NIM 0110122029) — STT-NF 2026.
 * Version: 1.0.0
 * Author: Wahyu Akbar Pratama Siregar
 *
 * @package DyaaStoreHelpers
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Tampilkan kolom "Username Roblox" pada listing pesanan admin.
 * Memudahkan admin lihat username tanpa harus klik tiap order.
 */
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
add_filter( 'manage_edit-shop_order_columns', 'dyaastore_orders_column', 20 );
add_filter( 'manage_woocommerce_page_wc-orders_columns', 'dyaastore_orders_column', 20 );

/**
 * Isi kolom Username Roblox di listing.
 */
function dyaastore_orders_column_content( $column, $post_id ) {
    if ( 'roblox_username' === $column ) {
        $order_id = is_a( $post_id, 'WP_Post' ) ? $post_id->ID : $post_id;
        $username = get_post_meta( $order_id, '_roblox_username', true );
        echo $username
            ? '<span style="color:#00a86b;font-weight:600;">' . esc_html( $username ) . '</span>'
            : '<span style="color:#999;">—</span>';
    }
}
add_action( 'manage_shop_order_posts_custom_column', 'dyaastore_orders_column_content', 10, 2 );

/**
 * Versi HPOS (High-Performance Order Storage) WooCommerce.
 */
function dyaastore_orders_column_content_hpos( $column, $order ) {
    if ( 'roblox_username' === $column ) {
        $username = $order->get_meta( '_roblox_username' );
        echo $username
            ? '<span style="color:#00a86b;font-weight:600;">' . esc_html( $username ) . '</span>'
            : '<span style="color:#999;">—</span>';
    }
}
add_action( 'manage_woocommerce_page_wc-orders_custom_column', 'dyaastore_orders_column_content_hpos', 10, 2 );

/**
 * Tambahkan dashboard widget custom dengan ringkasan singkat.
 */
function dyaastore_dashboard_widget() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    wp_add_dashboard_widget(
        'dyaastore_summary',
        '🎮 Dyaa Store — Ringkasan',
        'dyaastore_dashboard_widget_render'
    );
}
add_action( 'wp_dashboard_setup', 'dyaastore_dashboard_widget' );

function dyaastore_dashboard_widget_render() {
    $product_count = function_exists( 'wc_get_products' )
        ? count( wc_get_products( array( 'limit' => -1, 'status' => 'publish' ) ) )
        : 0;

    $order_args = array(
        'limit'  => -1,
        'status' => array( 'wc-processing', 'wc-on-hold' ),
    );
    $pending = function_exists( 'wc_get_orders' ) ? count( wc_get_orders( $order_args ) ) : 0;

    ?>
    <div style="padding: 8px 0;">
        <p style="margin:0 0 12px;">
            Selamat datang, <strong>Admin Dyaa Store</strong> 🎮
        </p>
        <ul style="list-style:none;padding:0;margin:0;">
            <li style="padding:8px 0;border-bottom:1px solid #eee;">
                📦 Produk Aktif: <strong style="color:#00a86b;"><?php echo esc_html( $product_count ); ?></strong>
            </li>
            <li style="padding:8px 0;border-bottom:1px solid #eee;">
                ⏳ Pesanan Menunggu Diproses: <strong style="color:#e67e22;"><?php echo esc_html( $pending ); ?></strong>
            </li>
            <li style="padding:8px 0;">
                <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=shop_order' ) ); ?>" class="button button-primary">
                    Kelola Pesanan
                </a>
                <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=product' ) ); ?>" class="button">
                    Kelola Produk
                </a>
            </li>
        </ul>
        <p style="margin-top:12px;font-size:12px;color:#999;">
            Tugas Akhir — STT-NF 2026
        </p>
    </div>
    <?php
}
