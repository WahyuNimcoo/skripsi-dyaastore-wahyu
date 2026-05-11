<?php
/**
 * Custom WooCommerce Payment Gateway — QRIS Statis (verifikasi manual).
 *
 * Tugas Akhir: Rancang Bangun Website E-Commerce Dyaa Store
 * Berbasis WordPress menggunakan WooCommerce dengan Metode Waterfall
 * untuk Penjualan Robux.
 *
 * Penulis : Wahyu Akbar Pratama Siregar (NIM 0110122029)
 * Program : Sistem Informasi STT-NF 2026
 *
 * Model alur (sesuai batasan BAB I §1.4: tanpa integrasi API):
 *   1. Customer pilih metode QRIS di checkout.
 *   2. Order ter-set status "On hold" (menunggu verifikasi).
 *   3. Customer membuka halaman Thank You → scan QR yang sudah disediakan.
 *   4. Customer membayar lewat aplikasi e-wallet / m-banking favoritnya.
 *   5. Customer mengirim bukti transfer ke WhatsApp admin.
 *   6. Admin verifikasi → ubah status order ke "Processing" / "Completed".
 *
 * Karena QRIS bersifat statis (NMID milik Dyaa Store), tidak diperlukan
 * koneksi API ke payment gateway pihak ketiga. Cocok dengan model
 * pengiriman Robux manual yang sudah dipakai.
 *
 * @package DyaaStoreChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
	return;
}

class WC_Dyaa_QRIS_Gateway extends WC_Payment_Gateway {

	/**
	 * Konstruktor — daftarkan opsi gateway & hook ke WooCommerce.
	 */
	public function __construct() {
		$default_qr_url = get_stylesheet_directory_uri() . '/assets/img/dyaa-qris.png';

		$this->id                 = 'dyaa_qris';
		$this->icon               = $default_qr_url;
		$this->has_fields         = false;
		$this->method_title       = __( 'QRIS — Scan & Bayar', 'dyaastore-child' );
		$this->method_description = __( 'Pembayaran via QRIS statis. Customer scan QR lalu bayar pakai aplikasi e-wallet (DANA, OVO, GoPay, ShopeePay) atau m-banking apapun. Verifikasi dilakukan admin secara manual setelah customer mengirim bukti transfer.', 'dyaastore-child' );

		$this->init_form_fields();
		$this->init_settings();

		$this->title         = $this->get_option( 'title' );
		$this->description   = $this->get_option( 'description' );
		$this->instructions  = $this->get_option( 'instructions' );
		$this->merchant_name = $this->get_option( 'merchant_name' );
		$this->merchant_nmid = $this->get_option( 'merchant_nmid' );
		$this->qr_image_url  = $this->get_option( 'qr_image_url' );
		$this->wa_number     = $this->get_option( 'wa_number' );
		$this->order_status  = $this->get_option( 'order_status', 'on-hold' );

		if ( empty( $this->qr_image_url ) ) {
			$this->qr_image_url = $default_qr_url;
		}

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ), 5 );
		add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
	}

	/**
	 * Settings yang muncul di WooCommerce → Settings → Payments → QRIS.
	 */
	public function init_form_fields() {
		$default_qr_url   = get_stylesheet_directory_uri() . '/assets/img/dyaa-qris.png';
		$default_wa       = defined( 'DYAA_WHATSAPP_NUMBER' ) ? DYAA_WHATSAPP_NUMBER : '';

		$this->form_fields = array(
			'enabled' => array(
				'title'   => __( 'Aktifkan / Nonaktifkan', 'dyaastore-child' ),
				'type'    => 'checkbox',
				'label'   => __( 'Aktifkan pembayaran QRIS', 'dyaastore-child' ),
				'default' => 'yes',
			),
			'title' => array(
				'title'       => __( 'Judul di Checkout', 'dyaastore-child' ),
				'type'        => 'text',
				'description' => __( 'Teks yang dilihat customer saat memilih metode pembayaran.', 'dyaastore-child' ),
				'default'     => __( 'QRIS (Scan & Bayar)', 'dyaastore-child' ),
				'desc_tip'    => true,
			),
			'description' => array(
				'title'       => __( 'Deskripsi singkat di Checkout', 'dyaastore-child' ),
				'type'        => 'textarea',
				'description' => __( 'Tampil di bawah label QRIS saat customer memilihnya di checkout.', 'dyaastore-child' ),
				'default'     => __( 'Bayar dengan scan QRIS pakai aplikasi e-wallet (DANA, OVO, GoPay, ShopeePay) atau m-banking apapun. Setelah bayar, kirim bukti transfer via WhatsApp untuk konfirmasi cepat.', 'dyaastore-child' ),
			),
			'instructions' => array(
				'title'       => __( 'Instruksi di halaman Thank You & Email', 'dyaastore-child' ),
				'type'        => 'textarea',
				'description' => __( 'Tampil di halaman Thank You setelah customer place order, juga di-include ke email konfirmasi.', 'dyaastore-child' ),
				'default'     => __( "1. Buka aplikasi e-wallet / m-banking favorit kamu (DANA, OVO, GoPay, ShopeePay, BCA mobile, dll).\n2. Pilih menu Scan QRIS / Scan QR.\n3. Scan QR di bawah ini, lalu konfirmasi pembayaran sesuai nominal pesanan.\n4. Kirim bukti pembayaran via WhatsApp ke nomor admin agar pesanan segera diproses.", 'dyaastore-child' ),
			),
			'merchant_name' => array(
				'title'       => __( 'Nama Merchant QRIS', 'dyaastore-child' ),
				'type'        => 'text',
				'description' => __( 'Tampil di atas QR sebagai konfirmasi visual ke customer.', 'dyaastore-child' ),
				'default'     => 'dya store',
				'desc_tip'    => true,
			),
			'merchant_nmid' => array(
				'title'       => __( 'NMID QRIS', 'dyaastore-child' ),
				'type'        => 'text',
				'description' => __( 'National Merchant ID (NMID) — cek di QR fisik kamu.', 'dyaastore-child' ),
				'default'     => 'ID1026477730984',
				'desc_tip'    => true,
			),
			'qr_image_url' => array(
				'title'       => __( 'URL gambar QRIS', 'dyaastore-child' ),
				'type'        => 'text',
				'description' => __( 'URL ke gambar QR. Default: assets/img/dyaa-qris.png di child theme.', 'dyaastore-child' ),
				'default'     => $default_qr_url,
				'desc_tip'    => true,
			),
			'wa_number' => array(
				'title'       => __( 'Nomor WhatsApp Konfirmasi', 'dyaastore-child' ),
				'type'        => 'text',
				'description' => __( 'Format internasional tanpa "+" (mis. 6289515881150). Tombol "Kirim Bukti via WhatsApp" akan muncul di halaman Thank You.', 'dyaastore-child' ),
				'default'     => $default_wa,
				'desc_tip'    => true,
			),
			'order_status' => array(
				'title'   => __( 'Status order setelah submit', 'dyaastore-child' ),
				'type'    => 'select',
				'options' => array(
					'on-hold' => __( 'On hold (rekomendasi)', 'dyaastore-child' ),
					'pending' => __( 'Pending payment', 'dyaastore-child' ),
				),
				'default' => 'on-hold',
				'description' => __( '"On hold" mengunci stok sampai admin verifikasi. "Pending payment" mengikuti default Woo (otomatis cancel setelah 1 jam).', 'dyaastore-child' ),
				'desc_tip'    => true,
			),
		);
	}

	/**
	 * Proses ketika customer klik "Place Order" dengan QRIS.
	 *
	 * @param int $order_id
	 * @return array
	 */
	public function process_payment( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return array( 'result' => 'failure' );
		}

		$status = in_array( $this->order_status, array( 'on-hold', 'pending' ), true ) ? $this->order_status : 'on-hold';

		/* translators: %s: nominal pesanan. */
		$note = sprintf( __( 'Menunggu pembayaran QRIS sebesar %s. Customer akan mengirim bukti transfer via WhatsApp.', 'dyaastore-child' ), $order->get_formatted_order_total() );

		$order->update_status( $status, $note );

		if ( 'on-hold' === $status ) {
			wc_reduce_stock_levels( $order_id );
		}

		if ( WC()->cart ) {
			WC()->cart->empty_cart();
		}

		return array(
			'result'   => 'success',
			'redirect' => $this->get_return_url( $order ),
		);
	}

	/**
	 * Render panel QR + instruksi di halaman Thank You.
	 */
	public function thankyou_page( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order || $order->get_payment_method() !== $this->id ) {
			return;
		}

		$total       = $order->get_formatted_order_total();
		$order_num   = $order->get_order_number();
		$wa_url      = $this->build_wa_url( $order );
		$qr_url      = $this->qr_image_url ? $this->qr_image_url : ( get_stylesheet_directory_uri() . '/assets/img/dyaa-qris.png' );
		$instr_html  = wpautop( wptexturize( $this->instructions ) );
		?>
		<section class="dyaa-qris-panel" aria-labelledby="dyaa-qris-title">
			<header class="dyaa-qris-head">
				<div class="dyaa-qris-brandline">
					<span class="dyaa-qris-tag"><?php esc_html_e( 'QRIS', 'dyaastore-child' ); ?></span>
					<span class="dyaa-qris-sep" aria-hidden="true">·</span>
					<span class="dyaa-qris-merchant"><?php echo esc_html( $this->merchant_name ); ?></span>
					<?php if ( $this->merchant_nmid ) : ?>
						<span class="dyaa-qris-nmid">NMID <?php echo esc_html( $this->merchant_nmid ); ?></span>
					<?php endif; ?>
				</div>
				<h2 id="dyaa-qris-title"><?php esc_html_e( 'Scan QR untuk Membayar', 'dyaastore-child' ); ?></h2>
				<p class="dyaa-qris-sub">
					<?php
					/* translators: 1: nomor order, 2: total. */
					printf(
						wp_kses_post( __( 'Pesanan <strong>#%1$s</strong> menunggu pembayaran sebesar <strong>%2$s</strong>.', 'dyaastore-child' ) ),
						esc_html( $order_num ),
						wp_kses_post( $total )
					);
					?>
				</p>
			</header>

			<div class="dyaa-qris-grid">
				<div class="dyaa-qris-imagewrap">
					<img class="dyaa-qris-image" src="<?php echo esc_url( $qr_url ); ?>" alt="<?php esc_attr_e( 'QRIS Dyaa Store', 'dyaastore-child' ); ?>" loading="lazy" />
					<a class="dyaa-qris-download" href="<?php echo esc_url( $qr_url ); ?>" download="qris-dyaa-store-<?php echo esc_attr( $order_num ); ?>.png">
						<?php esc_html_e( 'Unduh gambar QR', 'dyaastore-child' ); ?>
					</a>
				</div>

				<div class="dyaa-qris-instr">
					<h3><?php esc_html_e( 'Langkah Pembayaran', 'dyaastore-child' ); ?></h3>
					<div class="dyaa-qris-instr-body"><?php echo wp_kses_post( $instr_html ); ?></div>

					<ul class="dyaa-qris-amount">
						<li>
							<span class="dyaa-qris-amount-label"><?php esc_html_e( 'Nominal yang harus dibayar', 'dyaastore-child' ); ?></span>
							<span class="dyaa-qris-amount-value"><?php echo wp_kses_post( $total ); ?></span>
						</li>
						<li>
							<span class="dyaa-qris-amount-label"><?php esc_html_e( 'Nomor Pesanan', 'dyaastore-child' ); ?></span>
							<span class="dyaa-qris-amount-value">#<?php echo esc_html( $order_num ); ?></span>
						</li>
					</ul>

					<?php if ( $wa_url ) : ?>
						<a class="dyaa-qris-wa-cta" href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener noreferrer">
							<?php esc_html_e( 'Kirim Bukti Pembayaran via WhatsApp', 'dyaastore-child' ); ?>
							<span aria-hidden="true">→</span>
						</a>
					<?php endif; ?>

					<p class="dyaa-qris-note">
						<?php esc_html_e( 'Robux akan dikirim setelah admin memverifikasi bukti pembayaran (rata-rata 5–10 menit pada jam kerja 08.00–22.00 WIB).', 'dyaastore-child' ); ?>
					</p>
				</div>
			</div>
		</section>
		<?php
	}

	/**
	 * Tambahkan instruksi pembayaran ke email customer.
	 */
	public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
		if ( ! $order || $order->get_payment_method() !== $this->id ) {
			return;
		}
		if ( $sent_to_admin ) {
			return;
		}

		$total     = $order->get_formatted_order_total();
		$order_num = $order->get_order_number();
		$wa_url    = $this->build_wa_url( $order );
		$qr_url    = $this->qr_image_url;

		if ( $plain_text ) {
			echo strtoupper( __( 'Instruksi Pembayaran QRIS', 'dyaastore-child' ) ) . "\n";
			echo str_repeat( '=', 40 ) . "\n";
			echo sprintf( __( 'Pesanan #%1$s — Total: %2$s', 'dyaastore-child' ), $order_num, wp_strip_all_tags( $total ) ) . "\n\n";
			echo wp_strip_all_tags( $this->instructions ) . "\n\n";
			if ( $qr_url ) {
				echo __( 'QR Code: ', 'dyaastore-child' ) . esc_url_raw( $qr_url ) . "\n";
			}
			if ( $wa_url ) {
				echo __( 'Kirim bukti via WhatsApp: ', 'dyaastore-child' ) . esc_url_raw( $wa_url ) . "\n";
			}
			echo "\n";
		} else {
			?>
			<div style="margin:24px 0;padding:18px;border:1px solid #e5e7eb;border-radius:12px;background:#fff7ed;">
				<h2 style="margin:0 0 8px;font-size:18px;color:#9a3412;">
					<?php esc_html_e( 'Instruksi Pembayaran QRIS', 'dyaastore-child' ); ?>
				</h2>
				<p style="margin:0 0 12px;color:#1f2937;">
					<?php
					printf(
						wp_kses_post( __( 'Pesanan <strong>#%1$s</strong> · Total <strong>%2$s</strong>', 'dyaastore-child' ) ),
						esc_html( $order_num ),
						wp_kses_post( $total )
					);
					?>
				</p>
				<div style="color:#1f2937;line-height:1.6;"><?php echo wpautop( esc_html( $this->instructions ) ); ?></div>
				<?php if ( $qr_url ) : ?>
					<p style="text-align:center;margin:16px 0;">
						<img src="<?php echo esc_url( $qr_url ); ?>" alt="QRIS Dyaa Store" style="max-width:280px;border:8px solid #fff;border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,.08);" />
					</p>
				<?php endif; ?>
				<?php if ( $wa_url ) : ?>
					<p style="margin:12px 0 0;">
						<a href="<?php echo esc_url( $wa_url ); ?>" style="display:inline-block;background:#25d366;color:#fff;text-decoration:none;padding:10px 18px;border-radius:999px;font-weight:600;">
							<?php esc_html_e( 'Kirim Bukti via WhatsApp', 'dyaastore-child' ); ?>
						</a>
					</p>
				<?php endif; ?>
			</div>
			<?php
		}
	}

	/**
	 * Bangun deeplink WhatsApp dengan pesan pre-filled untuk konfirmasi
	 * pembayaran.
	 */
	protected function build_wa_url( $order ) {
		if ( empty( $this->wa_number ) ) {
			return '';
		}

		$total     = wp_strip_all_tags( $order->get_formatted_order_total() );
		$order_num = $order->get_order_number();
		$roblox    = $order->get_meta( '_roblox_username' );
		if ( ! $roblox ) {
			$roblox = get_post_meta( $order->get_id(), '_roblox_username', true );
		}

		$lines = array(
			__( 'Halo admin Dyaa Store, saya mau konfirmasi pembayaran QRIS:', 'dyaastore-child' ),
			'',
			sprintf( __( '• Nomor Pesanan : #%s', 'dyaastore-child' ), $order_num ),
			sprintf( __( '• Total         : %s', 'dyaastore-child' ), $total ),
		);

		if ( $roblox ) {
			$lines[] = sprintf( __( '• Username Roblox: %s', 'dyaastore-child' ), $roblox );
		}

		$lines[] = '';
		$lines[] = __( 'Bukti pembayaran terlampir.', 'dyaastore-child' );

		$message = implode( "\n", $lines );
		return 'https://wa.me/' . rawurlencode( $this->wa_number ) . '?text=' . rawurlencode( $message );
	}
}
