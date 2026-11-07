<?php
/**
 * Báo cho Next.js làm mới trang khi nội dung thay đổi.
 *
 * Đây là mảnh ghép quan trọng nhất của kiến trúc headless: Next.js dựng sẵn
 * trang để SEO tốt, nhưng như vậy nó không tự biết khi nào WordPress đổi nội dung.
 * Không có phần này, người biên tập sửa bài mà ngoài site vẫn hiện bản cũ.
 *
 * Cách hoạt động: mỗi lần lưu bài hoặc lưu Cấu hình, gửi một request tới URL
 * revalidate của Next.js kèm khoá bí mật.
 *
 * @package anmitech
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_init', function () {
	register_setting( 'anmi_revalidate', 'anmi_revalidate_url', array( 'sanitize_callback' => 'esc_url_raw' ) );
	register_setting( 'anmi_revalidate', 'anmi_revalidate_secret', array( 'sanitize_callback' => 'sanitize_text_field' ) );
} );

add_action( 'admin_menu', function () {
	add_submenu_page(
		'anmitech-options',
		'Kết nối Next.js',
		'Kết nối Next.js',
		'manage_options',
		'anmitech-revalidate',
		'anmi_revalidate_page'
	);
}, 100 );

function anmi_revalidate_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$url    = get_option( 'anmi_revalidate_url', '' );
	$secret = get_option( 'anmi_revalidate_secret', '' );
	$last   = get_option( 'anmi_revalidate_last', array() );
	?>
	<div class="wrap">
		<h1>Kết nối Next.js</h1>
		<p>Mỗi lần lưu bài hoặc lưu Cấu hình, WordPress sẽ gọi URL này để Next.js dựng lại trang.
			Không cấu hình thì frontend vẫn chạy, nhưng nội dung mới chỉ xuất hiện ở lần deploy kế tiếp.</p>

		<form method="post" action="options.php">
			<?php settings_fields( 'anmi_revalidate' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="anmi_url">URL revalidate</label></th>
					<td>
						<input type="url" id="anmi_url" name="anmi_revalidate_url" value="<?php echo esc_attr( $url ); ?>" class="regular-text" placeholder="https://anmitech.vn/api/revalidate">
						<p class="description">Đường dẫn tới route handler trong Next.js.</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="anmi_secret">Khoá bí mật</label></th>
					<td>
						<input type="text" id="anmi_secret" name="anmi_revalidate_secret" value="<?php echo esc_attr( $secret ); ?>" class="regular-text">
						<p class="description">Phải trùng với biến môi trường <code>REVALIDATE_SECRET</code> trên Vercel.
							Không có khoá này thì bất kỳ ai cũng gọi được endpoint đó.</p>
					</td>
				</tr>
			</table>
			<?php submit_button( 'Lưu' ); ?>
		</form>

		<?php if ( ! empty( $last ) ) : ?>
			<h2>Lần gọi gần nhất</h2>
			<table class="widefat striped" style="max-width:760px">
				<tr><td style="width:160px">Thời điểm</td><td><?php echo esc_html( $last['time'] ?? '—' ); ?></td></tr>
				<tr><td>Nội dung</td><td><?php echo esc_html( $last['what'] ?? '—' ); ?></td></tr>
				<tr><td>Kết quả</td><td><strong><?php echo esc_html( $last['result'] ?? '—' ); ?></strong></td></tr>
			</table>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Gửi tín hiệu revalidate.
 *
 * Dùng blocking = false để người biên tập không phải chờ Next.js phản hồi
 * mỗi lần bấm Cập nhật.
 */
function anmi_ping_revalidate( string $what ) {
	$url    = get_option( 'anmi_revalidate_url', '' );
	$secret = get_option( 'anmi_revalidate_secret', '' );

	if ( ! $url ) {
		return;
	}

	$response = wp_remote_post( $url, array(
		'timeout'  => 5,
		'blocking' => false,
		'headers'  => array( 'Content-Type' => 'application/json' ),
		'body'     => wp_json_encode( array(
			'secret' => $secret,
			'what'   => $what,
		) ),
	) );

	update_option( 'anmi_revalidate_last', array(
		'time'   => current_time( 'mysql' ),
		'what'   => $what,
		'result' => is_wp_error( $response ) ? 'Lỗi: ' . $response->get_error_message() : 'Đã gửi',
	), false );
}

add_action( 'save_post', function ( $post_id, $post ) {
	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
		return;
	}
	if ( ! in_array( $post->post_type, array( 'service', 'project', 'page', 'post' ), true ) ) {
		return;
	}
	if ( 'publish' !== $post->post_status ) {
		return;
	}

	anmi_ping_revalidate( $post->post_type . ': ' . get_the_title( $post_id ) );
}, 20, 2 );

// Lưu Options Page cũng phải làm mới, vì trang chủ đọc dữ liệu từ đó.
add_action( 'acf/save_post', function ( $post_id ) {
	if ( 'options' === $post_id ) {
		anmi_ping_revalidate( 'Cấu hình chung' );
	}
}, 20 );
