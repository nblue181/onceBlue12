<?php
/**
 * Plugin Name:       Anmitech Core
 * Description:       Cấu trúc dữ liệu cho anmitech.vn — CPT Dịch vụ / Dự án, trường SCF, trang Cấu hình và các endpoint REST cho frontend Next.js.
 * Version:           1.0.0
 * Requires PHP:      8.0
 * Author:            Anmitech
 * Text Domain:       anmitech
 *
 * Vì sao là plugin chứ không phải theme:
 * frontend nằm ở Next.js trên Vercel, WordPress chỉ còn vai trò nhập liệu.
 * Dữ liệu phải sống độc lập với theme, đổi theme không mất gì.
 */

defined( 'ABSPATH' ) || exit;

define( 'ANMI_VERSION', '1.0.0' );
define( 'ANMI_DIR', plugin_dir_path( __FILE__ ) );

require_once ANMI_DIR . 'inc/post-types.php';
require_once ANMI_DIR . 'inc/fields.php';
require_once ANMI_DIR . 'inc/options.php';
require_once ANMI_DIR . 'inc/rest.php';
require_once ANMI_DIR . 'inc/revalidate.php';

/**
 * Nạp lại rewrite rules một lần sau khi kích hoạt, để URL của CPT hoạt động ngay.
 */
register_activation_hook( __FILE__, function () {
	anmi_register_post_types();
	flush_rewrite_rules();
} );

register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );

/**
 * Cảnh báo nếu thiếu SCF — không có nó thì toàn bộ trường nội dung sẽ không hiện.
 */
add_action( 'admin_notices', function () {
	if ( function_exists( 'acf_add_local_field_group' ) || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	echo '<div class="notice notice-error"><p><strong>Anmitech Core:</strong> '
		. 'Chưa bật Secure Custom Fields. Các trường của Dịch vụ và Dự án sẽ không hiển thị.</p></div>';
} );
