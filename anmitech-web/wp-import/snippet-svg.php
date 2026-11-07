<?php
/**
 * Anmitech — cho phép tải SVG lên WordPress.
 *
 * Dán vào plugin Code Snippets, hoặc cuối functions.php của child theme.
 * KHÔNG dán dòng <?php ở trên nếu file đích đã có sẵn.
 *
 * ============================================================
 * CẢNH BÁO — ĐỌC TRƯỚC KHI DÙNG
 * ============================================================
 *
 * Snippet này KHÔNG làm sạch nội dung file. SVG là XML, chứa được thẻ <script>
 * và thuộc tính onload — tải lên một file độc là có stored XSS ngay trên domain
 * của mình, chạy với quyền của bất kỳ ai mở ảnh đó.
 *
 * Nó chỉ giới hạn quyền upload cho Administrator, tức là thu hẹp rủi ro
 * xuống còn: chính anh tự tải nhầm một file lấy từ nguồn không tin được.
 *
 * An toàn hơn nhiều và tốn đúng một phút: cài plugin "Safe SVG" từ kho
 * WordPress. Nó bóc script bằng thư viện sanitize trước khi lưu, và hiện
 * thumbnail SVG đúng trong media library. Cài Safe SVG rồi thì KHÔNG cần
 * snippet này nữa.
 *
 * Với kiến trúc headless, logo và icon nên nằm trong public/ của Next.js
 * chứ không upload lên WordPress — vừa không có rủi ro này, vừa chạy qua CDN.
 */

/**
 * Thêm image/svg+xml vào danh sách kiểu file được phép.
 */
add_filter( 'upload_mimes', function ( $mimes ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return $mimes;
	}

	$mimes['svg']  = 'image/svg+xml';
	$mimes['svgz'] = 'image/svg+xml';

	return $mimes;
} );

/**
 * WordPress đoán kiểu file bằng cách đọc vài byte đầu (finfo). Với SVG nó
 * thường đoán ra text/plain hoặc image/svg rồi chặn, dù mime đã được cho phép
 * ở trên. Đoạn này ép lại kết quả cho đúng đuôi .svg.
 */
add_filter( 'wp_check_filetype_and_ext', function ( $data, $file, $filename, $mimes ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return $data;
	}

	$ext = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );

	if ( 'svg' === $ext || 'svgz' === $ext ) {
		$data['ext']  = $ext;
		$data['type'] = 'image/svg+xml';
	}

	return $data;
}, 10, 4 );

/**
 * Chặn tối thiểu: từ chối file có <script>, thuộc tính on*, hoặc javascript:.
 *
 * Đây KHÔNG phải sanitize đầy đủ — chỉ bắt các mẫu phổ biến nhất. Một payload
 * viết khéo (mã hoá entity, dùng <foreignObject>, <use href="data:…">) sẽ lọt.
 * Vẫn nên dùng Safe SVG.
 */
add_filter( 'wp_handle_upload_prefilter', function ( $file ) {
	if ( empty( $file['type'] ) || 'image/svg+xml' !== $file['type'] ) {
		return $file;
	}

	$content = file_get_contents( $file['tmp_name'] );

	if ( false === $content ) {
		$file['error'] = 'Không đọc được file SVG.';
		return $file;
	}

	$patterns = array(
		'#<\s*script#i',
		'#\son[a-z]+\s*=#i',
		'#javascript\s*:#i',
		'#<\s*foreignObject#i',
		'#<\s*!ENTITY#i',
	);

	foreach ( $patterns as $pattern ) {
		if ( preg_match( $pattern, $content ) ) {
			$file['error'] = 'File SVG này chứa mã có thể thực thi nên đã bị từ chối. Hãy xuất lại SVG từ phần mềm thiết kế, hoặc cài plugin Safe SVG.';
			return $file;
		}
	}

	return $file;
} );

/**
 * Hiện SVG đúng kích thước trong media library.
 *
 * SVG không có chiều rộng/cao thật nên WordPress hiện nó bé xíu hoặc tràn ô.
 */
add_action( 'admin_head', function () {
	echo '<style>
		.attachment-266x266, .thumbnail img[src$=".svg"],
		.media-icon img[src$=".svg"], img[src$=".svg"].attachment-post-thumbnail {
			width: 100% !important; height: auto !important;
		}
	</style>';
} );
