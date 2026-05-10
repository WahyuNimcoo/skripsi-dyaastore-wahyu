<?php
/**
 * Template: Beranda situs (URL /)
 *
 * File ini memastikan tampilan Dyaa Store tampil di root domain meskipun
 * belum diatur "halaman statis" di Pengaturan → Membaca.
 *
 * @package DyaaStoreChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
get_template_part( 'templates/part', 'home-main' );
get_footer();
