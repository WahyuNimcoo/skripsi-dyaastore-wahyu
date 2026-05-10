<?php
/**
 * Plugin Name: Dyaa Store — Demo Seeder
 * Description: Otomatis membuat kategori & produk Robux demo (sekali jalan) supaya halaman Shop, Flash Sale, dan Kategori tidak kosong saat fresh install. Bagian dari Tugas Akhir Wahyu Akbar Pratama Siregar — STT-NF 2026.
 * Version: 1.0.0
 *
 * @package DyaaStoreSeeder
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

const DYAA_SEEDER_FLAG    = 'dyaastore_seeded_v1';
const DYAA_SEEDER_TRIGGER = 'dyaastore_seed_now';

/**
 * Daftar kategori Robux yang akan dibuat (sesuai shortcode [dyaa_categories]).
 */
function dyaastore_demo_categories() {
    return array(
        array( 'name' => 'Paket Hemat',     'slug' => 'paket-hemat',    'desc' => 'Paket Robux dengan harga paling hemat untuk pemula.' ),
        array( 'name' => 'Voucher Robux',   'slug' => 'voucher-robux',  'desc' => 'Voucher kode aktivasi instan untuk top up Robux.' ),
        array( 'name' => 'Gamepass',        'slug' => 'gamepass',       'desc' => 'Bundle Robux untuk pembelian gamepass favorit.' ),
        array( 'name' => 'Premium',         'slug' => 'premium',        'desc' => 'Paket Robux Premium dengan keuntungan ekstra.' ),
        array( 'name' => 'Bundle',          'slug' => 'bundle',         'desc' => 'Kombinasi paket Robux untuk hemat lebih banyak.' ),
        array( 'name' => 'Limited',         'slug' => 'limited',        'desc' => 'Paket Robux dengan stok / waktu terbatas.' ),
    );
}

/**
 * Daftar produk demo (8 paket, 3 di antaranya sale).
 *
 * Field "image" merujuk ke nama file di
 * `wp-content/themes/dyaastore-child/assets/img/`.
 */
function dyaastore_demo_products() {
    return array(
        array(
            'name'    => '100 Robux',
            'sku'     => 'DYAA-100',
            'price'   => 18000,
            'sale'    => 15000,
            'cat'     => 'paket-hemat',
            'image'   => 'dyaa-robux-small.png',
            'short'   => 'Paket starter 100 Robux untuk akun Roblox kamu.',
            'desc'    => 'Paket starter berisi 100 Robux. Cocok untuk pemain baru yang ingin mencoba upgrade akun Roblox. Pengiriman manual oleh tim Dyaa Store setelah pembayaran terverifikasi (sesuai batasan skripsi: tanpa integrasi API Roblox).',
        ),
        array(
            'name'    => '400 Robux',
            'sku'     => 'DYAA-400',
            'price'   => 65000,
            'sale'    => 55000,
            'cat'     => 'paket-hemat',
            'image'   => 'dyaa-robux-small.png',
            'short'   => 'Paket hemat 400 Robux — hemat 15%.',
            'desc'    => 'Paket 400 Robux yang ideal untuk pembelian gamepass kecil dan item dalam game. Pengiriman manual ±5 menit setelah pembayaran masuk.',
        ),
        array(
            'name'    => '800 Robux',
            'sku'     => 'DYAA-800',
            'price'   => 125000,
            'sale'    => 0,
            'cat'     => 'voucher-robux',
            'image'   => 'dyaa-robux-medium.png',
            'short'   => 'Voucher 800 Robux — populer untuk avatar item.',
            'desc'    => 'Voucher 800 Robux paling populer di Dyaa Store. Cocok untuk membeli avatar item premium atau gamepass.',
        ),
        array(
            'name'    => '1700 Robux',
            'sku'     => 'DYAA-1700',
            'price'   => 245000,
            'sale'    => 220000,
            'cat'     => 'voucher-robux',
            'image'   => 'dyaa-robux-medium.png',
            'short'   => 'Paket 1.700 Robux — best seller mingguan.',
            'desc'    => 'Best seller mingguan! Paket 1.700 Robux untuk pembelian limited item dan upgrade premium gamepass.',
        ),
        array(
            'name'    => '4500 Robux',
            'sku'     => 'DYAA-4500',
            'price'   => 625000,
            'sale'    => 0,
            'cat'     => 'gamepass',
            'image'   => 'dyaa-robux-large.png',
            'short'   => 'Paket 4.500 Robux untuk koleksi gamepass.',
            'desc'    => 'Paket besar 4.500 Robux untuk kamu yang serius main Roblox. Bisa dipakai untuk gamepass, limited UGC, atau item premium.',
        ),
        array(
            'name'    => '10000 Robux',
            'sku'     => 'DYAA-10K',
            'price'   => 1350000,
            'sale'    => 0,
            'cat'     => 'premium',
            'image'   => 'dyaa-robux-large.png',
            'short'   => 'Paket Premium 10.000 Robux.',
            'desc'    => 'Paket Premium untuk gamer profesional. Termasuk bonus pengiriman prioritas dan support eksklusif via WhatsApp Dyaa Store.',
        ),
        array(
            'name'    => 'Bundle Hemat 2x800 Robux',
            'sku'     => 'DYAA-BUN-1',
            'price'   => 240000,
            'sale'    => 215000,
            'cat'     => 'bundle',
            'image'   => 'dyaa-robux-bundle.png',
            'short'   => 'Beli 2 paket 800 Robux, hemat lebih banyak.',
            'desc'    => 'Bundle hemat 2x800 Robux dalam satu transaksi. Cocok untuk dipakai bersama teman atau dua akun Roblox.',
        ),
        array(
            'name'    => 'Limited Stok — 2200 Robux',
            'sku'     => 'DYAA-LIM-2200',
            'price'   => 320000,
            'sale'    => 0,
            'cat'     => 'limited',
            'image'   => 'dyaa-robux-large.png',
            'short'   => 'Stok mingguan terbatas hanya 50 paket.',
            'desc'    => 'Paket dengan stok mingguan terbatas — hanya 50 paket. First come, first served.',
        ),
    );
}

/**
 * Sideload gambar dari folder theme ke Media Library WordPress dan
 * kembalikan attachment ID. Cache via meta supaya tidak duplikat.
 *
 * @param string $filename Nama file gambar di assets/img/
 * @return int|false Attachment ID atau false bila gagal.
 */
function dyaastore_sideload_theme_image( $filename ) {
    $cache_key = 'dyaa_attach_' . sanitize_key( $filename );
    $cached    = get_option( $cache_key );

    if ( $cached && get_post( (int) $cached ) ) {
        return (int) $cached;
    }

    $source = get_stylesheet_directory() . '/assets/img/' . $filename;
    if ( ! file_exists( $source ) ) {
        return false;
    }

    if ( ! function_exists( 'media_handle_sideload' ) ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
    }

    $upload_dir = wp_upload_dir();
    $tmp_path   = trailingslashit( $upload_dir['path'] ) . wp_unique_filename( $upload_dir['path'], $filename );

    if ( ! @copy( $source, $tmp_path ) ) {
        return false;
    }

    $file_array = array(
        'name'     => $filename,
        'tmp_name' => $tmp_path,
    );

    $attach_id = media_handle_sideload( $file_array, 0, 'Dyaa Store — ' . $filename );

    if ( is_wp_error( $attach_id ) ) {
        @unlink( $tmp_path );
        return false;
    }

    update_option( $cache_key, $attach_id );
    return (int) $attach_id;
}

/**
 * Jalankan seeder.
 *
 * @return array Ringkasan jumlah kategori & produk yang dibuat.
 */
function dyaastore_run_seeder() {
    if ( ! function_exists( 'wc_get_product_id_by_sku' ) || ! taxonomy_exists( 'product_cat' ) ) {
        return array( 'cats' => 0, 'products' => 0, 'error' => 'WooCommerce belum aktif.' );
    }

    $created_cats     = 0;
    $created_products = 0;

    foreach ( dyaastore_demo_categories() as $cat ) {
        if ( term_exists( $cat['slug'], 'product_cat' ) ) {
            continue;
        }
        $res = wp_insert_term(
            $cat['name'],
            'product_cat',
            array(
                'slug'        => $cat['slug'],
                'description' => $cat['desc'],
            )
        );
        if ( ! is_wp_error( $res ) ) {
            $created_cats++;
        }
    }

    $updated_images = 0;

    foreach ( dyaastore_demo_products() as $p ) {
        $existing_id = wc_get_product_id_by_sku( $p['sku'] );

        if ( $existing_id ) {
            // Produk sudah ada — pastikan featured image juga sudah terpasang.
            if ( ! empty( $p['image'] ) && ! has_post_thumbnail( $existing_id ) ) {
                $attach_id = dyaastore_sideload_theme_image( $p['image'] );
                if ( $attach_id ) {
                    set_post_thumbnail( $existing_id, $attach_id );
                    $updated_images++;
                }
            }
            continue;
        }

        $product = new WC_Product_Simple();
        $product->set_name( $p['name'] );
        $product->set_status( 'publish' );
        $product->set_catalog_visibility( 'visible' );
        $product->set_sku( $p['sku'] );
        $product->set_short_description( $p['short'] );
        $product->set_description( $p['desc'] );
        $product->set_regular_price( (string) $p['price'] );

        if ( ! empty( $p['sale'] ) && $p['sale'] > 0 && $p['sale'] < $p['price'] ) {
            $product->set_sale_price( (string) $p['sale'] );
        }

        $product->set_virtual( true );
        $product->set_manage_stock( false );
        $product->set_stock_status( 'instock' );

        $term = get_term_by( 'slug', $p['cat'], 'product_cat' );
        if ( $term && ! is_wp_error( $term ) ) {
            $product->set_category_ids( array( (int) $term->term_id ) );
        }

        $pid = $product->save();
        if ( $pid ) {
            $created_products++;
            if ( ! empty( $p['image'] ) ) {
                $attach_id = dyaastore_sideload_theme_image( $p['image'] );
                if ( $attach_id ) {
                    set_post_thumbnail( $pid, $attach_id );
                }
            }
        }
    }

    update_option( DYAA_SEEDER_FLAG, time() );

    return array(
        'cats'           => $created_cats,
        'products'       => $created_products,
        'updated_images' => $updated_images,
    );
}

/**
 * Jalankan seeder otomatis pada pertama kali admin masuk dashboard.
 * Tidak akan jalan dua kali (flag DYAA_SEEDER_FLAG).
 */
function dyaastore_maybe_run_seeder() {
    if ( ! is_admin() ) {
        return;
    }
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    if ( ! class_exists( 'WC_Product_Simple' ) ) {
        return;
    }
    if ( get_option( DYAA_SEEDER_FLAG ) ) {
        return;
    }

    $result = dyaastore_run_seeder();

    if ( ! empty( $result['products'] ) || ! empty( $result['cats'] ) ) {
        set_transient(
            'dyaastore_seeder_notice',
            sprintf(
                'Dyaa Store — Seeder selesai. %d kategori dan %d produk Robux demo dibuat. Cek halaman Shop & Beranda.',
                (int) $result['cats'],
                (int) $result['products']
            ),
            60
        );
    }
}
add_action( 'admin_init', 'dyaastore_maybe_run_seeder', 20 );

/**
 * Tampilkan notice setelah seeder jalan.
 */
function dyaastore_seeder_admin_notice() {
    $msg = get_transient( 'dyaastore_seeder_notice' );
    if ( ! $msg ) {
        return;
    }
    echo '<div class="notice notice-success is-dismissible"><p>' . esc_html( $msg ) . '</p></div>';
    delete_transient( 'dyaastore_seeder_notice' );
}
add_action( 'admin_notices', 'dyaastore_seeder_admin_notice' );

/**
 * Trigger manual: tambahkan ?dyaa_seed=1 pada URL admin untuk re-run seeder.
 */
function dyaastore_seeder_manual_trigger() {
    if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
        return;
    }
    if ( empty( $_GET['dyaa_seed'] ) || '1' !== $_GET['dyaa_seed'] ) {
        return;
    }
    delete_option( DYAA_SEEDER_FLAG );
    $result = dyaastore_run_seeder();
    set_transient(
        'dyaastore_seeder_notice',
        sprintf(
            'Dyaa Store — Manual re-seed selesai. %d kategori baru, %d produk baru.',
            (int) $result['cats'],
            (int) $result['products']
        ),
        60
    );
}
add_action( 'admin_init', 'dyaastore_seeder_manual_trigger', 5 );
