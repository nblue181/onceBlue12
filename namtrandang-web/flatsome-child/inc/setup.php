<?php
/**
 * Enqueue assets + các chỉnh sửa cần thiết cho Flatsome.
 *
 * @package nlw
 */

defined( 'ABSPATH' ) || exit;

/**
 * Phiên bản của một file asset = thời điểm sửa file đó.
 *
 * Máy chủ đang đặt Cache-Control 7 ngày cho CSS/JS, nên nếu URL không đổi thì
 * trình duyệt vẫn dùng bản cũ dù đã xoá cache phía server. Lấy filemtime giúp
 * URL tự đổi mỗi lần deploy — không phải nhớ tăng số phiên bản bằng tay nữa.
 *
 * @param string $rel Đường dẫn tương đối trong child theme.
 * @return string
 */
function nlw_asset_ver( $rel ) {
	$path = NLW_DIR . $rel;

	return file_exists( $path ) ? (string) filemtime( $path ) : NLW_VERSION;
}

/**
 * Nạp CSS/JS.
 *
 * Không khai báo phụ thuộc vào handle của Flatsome: nếu handle đó đổi tên,
 * WordPress sẽ bỏ qua toàn bộ style phụ thuộc mà không báo lỗi.
 * Thay vào đó dùng priority 999 để chắc chắn nạp sau CSS của theme cha.
 */
function nlw_enqueue_assets() {
	wp_enqueue_style(
		'nlw-tokens',
		NLW_URI . '/assets/css/tokens.css',
		array(),
		nlw_asset_ver( '/assets/css/tokens.css' )
	);

	wp_enqueue_style(
		'nlw-theme',
		NLW_URI . '/assets/css/theme.css',
		array( 'nlw-tokens' ),
		nlw_asset_ver( '/assets/css/theme.css' )
	);

	// Hệ hai giao diện Sáng/Tối + nền aura. Bỏ khối này là về bản sáng gốc.
	wp_enqueue_style(
		'nlw-themes',
		NLW_URI . '/assets/css/themes.css',
		array( 'nlw-theme' ),
		nlw_asset_ver( '/assets/css/themes.css' )
	);

	wp_enqueue_script(
		'nlw-site',
		NLW_URI . '/assets/js/site.js',
		array(),
		nlw_asset_ver( '/assets/js/site.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'nlw_enqueue_assets', 999 );

/**
 * Đặt giao diện Sáng/Tối NGAY trước khi trang vẽ.
 *
 * Phải là script nội tuyến chạy sớm trong <head>: nếu đợi tới khi tải xong JS
 * thì trang đã kịp vẽ bằng giao diện mặc định rồi mới đổi, người dùng thấy
 * một nháy trắng/đen rất khó chịu.
 *
 * Thứ tự ưu tiên: lựa chọn đã lưu → thiết lập của hệ điều hành → sáng.
 */
function nlw_theme_boot_script() {
	?>
	<script>
	(function () {
		try {
			var saved = localStorage.getItem('nlw-theme');
			var theme = saved || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
			document.documentElement.setAttribute('data-theme', theme);
		} catch (e) {
			document.documentElement.setAttribute('data-theme', 'light');
		}
	})();
	</script>
	<?php
}
add_action( 'wp_head', 'nlw_theme_boot_script', 0 );

/**
 * Preload font để tránh nháy chữ (FOUT) ở hero.
 */
function nlw_preload_fonts() {
	$fonts = array( 'Archivo-Variable.ttf', 'SpaceGrotesk-Variable.ttf' );

	foreach ( $fonts as $font ) {
		printf(
			'<link rel="preload" as="font" type="font/ttf" href="%s" crossorigin>' . "\n",
			esc_url( NLW_URI . '/assets/fonts/' . $font )
		);
	}
}
add_action( 'wp_head', 'nlw_preload_fonts', 1 );

/**
 * Tắt Google Fonts của Flatsome — font đã self-host trong child theme.
 *
 * @param array $fonts Danh sách font Flatsome định nạp.
 * @return array
 */
function nlw_disable_flatsome_google_fonts( $fonts ) {
	return array();
}
add_filter( 'flatsome_google_fonts', 'nlw_disable_flatsome_google_fonts', 99 );

/**
 * Thêm class nhận diện vào <body> để CSS bám đúng ngữ cảnh.
 *
 * @param array $classes Class hiện có.
 * @return array
 */
function nlw_body_class( $classes ) {
	$classes[] = 'nlw';

	if ( is_singular( 'project' ) || is_post_type_archive( 'project' ) || is_tax( 'project_cat' ) ) {
		$classes[] = 'nlw-projects';
	}

	return $classes;
}
add_filter( 'body_class', 'nlw_body_class' );

/**
 * Nút liên hệ nổi (mobile) — Zalo + Messenger, luôn có ở mọi trang.
 */
function nlw_floating_contact() {
	if ( ! nlw_opt_bool( 'floating_buttons', true ) ) {
		return;
	}
	?>
	<div class="nlw-float" role="complementary" aria-label="Liên hệ nhanh">
		<?php echo nlw_theme_toggle_html(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<a class="nlw-float-btn nlw-float-zalo" href="<?php echo esc_url( nlw_contact_url( 'zalo' ) ); ?>" target="_blank" rel="noopener">
			<?php echo nlw_icon( 'zalo' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<span class="nlw-float-label">Nhắn Zalo</span>
		</a>
		<a class="nlw-float-btn nlw-float-mess" href="<?php echo esc_url( nlw_contact_url( 'messenger' ) ); ?>" target="_blank" rel="noopener">
			<?php echo nlw_icon( 'mess' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<span class="nlw-float-label">Messenger</span>
		</a>
	</div>
	<?php
}
add_action( 'wp_footer', 'nlw_floating_contact', 20 );

/**
 * Cho phép shortcode chạy trong widget text (dùng khi dựng footer bằng widget).
 */
add_filter( 'widget_text', 'do_shortcode' );

/**
 * Gỡ thẻ <p> mà wpautop bọc quanh các shortcode section.
 *
 * Section là block-level; nằm trong <p> sẽ bị trình duyệt tự đóng thẻ và vỡ bố cục.
 * WordPress có shortcode_unautop() nhưng không phải lúc nào cũng chạy với nội dung
 * do UX Builder sinh ra.
 *
 * @param string $content Nội dung.
 * @return string
 */
function nlw_unwrap_shortcodes( $content ) {
	if ( false === strpos( $content, '[nlw_' ) ) {
		return $content;
	}

	$content = preg_replace( '#<p>\s*(\[nlw_[^\]]+\])\s*</p>#', '$1', $content );
	$content = preg_replace( '#<p>\s*(\[/nlw_[^\]]+\])\s*</p>#', '$1', $content );
	$content = preg_replace( '#(\[nlw_[^\]]+\])\s*<br\s*/?>#', '$1', $content );

	return $content;
}
// Priority 10: đăng ký sau wpautop và shortcode_unautop (cùng mức 10) nên chạy sau cả hai,
// vẫn trước do_shortcode (mức 11).
add_filter( 'the_content', 'nlw_unwrap_shortcodes', 10 );

/**
 * Số dự án mỗi trang ở archive.
 *
 * @param WP_Query $query Query chính.
 */
function nlw_projects_per_page( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_post_type_archive( 'project' ) || $query->is_tax( 'project_cat' ) ) {
		$query->set( 'posts_per_page', (int) nlw_opt( 'projects_per_page', 9 ) );
		$query->set( 'orderby', array( 'menu_order' => 'ASC', 'date' => 'DESC' ) );

		// Lọc theo lĩnh vực: /du-an/?linh-vuc=Máy tính (chip ở trang Giới thiệu trỏ tới đây).
		$industry = nlw_current_industry();

		if ( $industry ) {
			$query->set(
				'meta_query', // phpcs:ignore WordPress.DB.SlowDBQuery
				array(
					array(
						'key'   => 'industry',
						'value' => $industry,
					),
				)
			);
		}
	}
}
add_action( 'pre_get_posts', 'nlw_projects_per_page' );
