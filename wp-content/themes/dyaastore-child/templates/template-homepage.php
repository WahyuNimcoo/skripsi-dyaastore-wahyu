<?php
/**
 * Template Name: Dyaa Store — Homepage
 *
 * Halaman utama Dyaa Store dengan layout terinspirasi dari
 * dyaastore.fusionifydigital.store namun fokus pada penjualan Robux
 * sesuai batasan Skripsi Bab 1.4.
 *
 * Catatan: Untuk URL `/`, gunakan juga front-page.php (otomatis).
 * Template ini berguna jika kamu membuat halaman statis dan memilih template ini.
 *
 * @package DyaaStoreChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
get_template_part( 'templates/part', 'home-main' );
get_footer();
