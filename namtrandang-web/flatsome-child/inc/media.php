<?php
/**
 * Tự đặt tiêu đề và alt cho ảnh theo tên file khi upload.
 *
 * Chuyển từ inc/custom/auto_alt_img.php của child theme cũ, có sửa lỗi:
 * bản cũ gọi wp_update_post() mà thiếu 'ID' nên WordPress hiểu là *tạo bài mới*
 * thay vì cập nhật ảnh vừa upload.
 *
 * @package nlw
 */

defined( 'ABSPATH' ) || exit;

/**
 * Đặt title + alt cho attachment ảnh, lấy từ tên file.
 *
 * surface-khong-nhan-ban-phim-2.webp → "surface-khong-nhan-ban-phim-2"
 *
 * @param int $post_id ID của attachment vừa thêm.
 */
function nlw_auto_image_title_alt( $post_id ) {
	if ( ! wp_attachment_is_image( $post_id ) ) {
		return;
	}

	// Dùng đường dẫn file thật trong uploads, ổn định hơn guid.
	$file_path = get_attached_file( $post_id );

	if ( ! $file_path ) {
		return;
	}

	$filename = sanitize_text_field( pathinfo( $file_path, PATHINFO_FILENAME ) );

	if ( '' === $filename ) {
		return;
	}

	wp_update_post(
		array(
			'ID'         => $post_id,
			'post_title' => $filename,
		)
	);

	// Alt để trống thì điền theo tên file; không ghi đè alt người dùng đã tự nhập.
	if ( ! get_post_meta( $post_id, '_wp_attachment_image_alt', true ) ) {
		update_post_meta( $post_id, '_wp_attachment_image_alt', $filename );
	}
}
add_action( 'add_attachment', 'nlw_auto_image_title_alt' );
