<?php
/**
 * Hàm dùng chung.
 *
 * @package nlw
 */

defined( 'ABSPATH' ) || exit;

/**
 * Đọc field của SCF/ACF, có fallback về post meta khi plugin chưa bật.
 *
 * @param string   $key     Tên field.
 * @param int|null $post_id ID bài viết.
 * @param mixed    $default Giá trị mặc định.
 * @return mixed
 */
function nlw_field( $key, $post_id = null, $default = '' ) {
	$post_id = $post_id ?: get_the_ID();

	if ( function_exists( 'get_field' ) ) {
		$value = get_field( $key, $post_id );
	} else {
		$value = get_post_meta( $post_id, $key, true );
	}

	return ( '' === $value || null === $value || false === $value || array() === $value ) ? $default : $value;
}

/**
 * Đọc một tuỳ chọn ở Options Page "Nam làm WEB → Cấu hình".
 *
 * @param string $key     Tên field.
 * @param mixed  $default Giá trị mặc định.
 * @return mixed
 */
function nlw_opt( $key, $default = '' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}

	$value = get_field( $key, 'option' );

	return ( '' === $value || null === $value || false === $value || array() === $value ) ? $default : $value;
}

/**
 * Đọc một tuỳ chọn kiểu bật/tắt.
 *
 * Tách riêng vì nlw_opt() coi giá trị rỗng là "chưa đặt" và trả về mặc định —
 * với true_false thì false là một lựa chọn hợp lệ, không phải "chưa đặt".
 *
 * @param string $key     Tên field.
 * @param bool   $default Mặc định khi chưa từng lưu.
 * @return bool
 */
function nlw_opt_bool( $key, $default = true ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}

	$value = get_field( $key, 'option' );

	return ( null === $value ) ? $default : (bool) $value;
}

/**
 * Đọc một repeater và luôn trả về mảng (rỗng nếu chưa có dữ liệu).
 *
 * @param string          $key     Tên field repeater.
 * @param int|string|null $post_id ID bài viết, hoặc 'option'.
 * @return array<int,array>
 */
function nlw_rows( $key, $post_id = null ) {
	if ( ! function_exists( 'get_field' ) ) {
		return array();
	}

	$rows = get_field( $key, $post_id ?: get_the_ID() );

	return is_array( $rows ) ? $rows : array();
}

/**
 * Lấy một sub-field trong row của repeater.
 *
 * @param array  $row     Row.
 * @param string $key     Tên sub-field.
 * @param mixed  $default Mặc định.
 * @return mixed
 */
function nlw_row( $row, $key, $default = '' ) {
	if ( ! is_array( $row ) || ! isset( $row[ $key ] ) ) {
		return $default;
	}

	$value = $row[ $key ];

	return ( '' === $value || null === $value || array() === $value ) ? $default : $value;
}

/**
 * Tách một textarea nhiều dòng thành mảng (dùng cho các shortcode nhận nội dung tự do).
 *
 * @param string $raw  Nội dung textarea.
 * @param int    $cols Số cột mong muốn (ngăn bởi "|", đệm chuỗi rỗng nếu thiếu).
 * @return array<int,array<int,string>>
 */
function nlw_lines( $raw, $cols = 1 ) {
	$out = array();

	foreach ( preg_split( '/\r\n|\r|\n/', (string) $raw ) as $line ) {
		$line = trim( $line );
		if ( '' === $line ) {
			continue;
		}

		$parts = array_map( 'trim', explode( '|', $line ) );
		$parts = array_pad( $parts, $cols, '' );

		$out[] = $parts;
	}

	return $out;
}

/**
 * Escape văn bản, đồng thời hỗ trợ 2 quy ước viết trong shortcode:
 *
 *   **chữ**  → in đậm, màu brand (làm nổi một cụm từ)
 *   |        → ngắt dòng có chủ đích
 *
 * @param string $text Văn bản thô.
 * @param bool   $br   Cho phép ngắt dòng bằng "|" và xuống dòng thật.
 * @return string HTML an toàn.
 */
function nlw_rich_text( $text, $br = true ) {
	$out = esc_html( (string) $text );
	$out = preg_replace( '/\*\*(.+?)\*\*/u', '<b class="nlw-hl">$1</b>', $out );

	if ( $br ) {
		$out = preg_replace( '/\s*\|\s*/', '<br>', $out );
		$out = nl2br( $out );
	}

	return $out;
}

/**
 * Chuyển nội dung shortcode (đã qua wpautop của UX Builder) thành các khối.
 *
 * Đổi <br>, </p>… thành xuống dòng TRƯỚC khi bỏ thẻ, nếu không các câu sẽ dính
 * liền nhau. Dòng bắt đầu bằng "-" được gom thành danh sách.
 *
 * @param string $content Nội dung thô.
 * @return array<int,array{0:string,1:mixed}> Mỗi phần tử là ['p', string] hoặc ['list', string[]].
 */
function nlw_content_blocks( $content ) {
	$content = preg_replace( '#<br\s*/?>#i', "\n", (string) $content );
	$content = preg_replace( '#</(p|div|li|h[1-6])>#i', "\n", $content );
	$content = wp_strip_all_tags( $content );

	/*
	 * wptexturize đổi dấu "-" đầu dòng thành entity "&#8211;" trước khi shortcode
	 * chạy. Không giải mã thì chuỗi bắt đầu bằng ký tự "&" và không khớp mẫu
	 * danh sách — dòng vẫn ra như một đoạn văn thường.
	 */
	$content = html_entity_decode( $content, ENT_QUOTES | ENT_HTML5, 'UTF-8' );

	$blocks = array();
	$list   = array();

	// Tách dòng thủ công, KHÔNG dùng nlw_lines() vì hàm đó cắt theo dấu "|",
	// mà "|" ở đây mang nghĩa ngắt dòng trong câu.
	foreach ( preg_split( '/\r\n|\r|\n/', $content ) as $raw ) {
		$text = trim( $raw );

		if ( '' === $text ) {
			continue;
		}

		if ( preg_match( '/^[-–—•*]\s+(.+)$/u', $text, $m ) ) {
			$list[] = $m[1];
			continue;
		}

		if ( $list ) {
			$blocks[] = array( 'list', $list );
			$list     = array();
		}

		$blocks[] = array( 'p', $text );
	}

	if ( $list ) {
		$blocks[] = array( 'list', $list );
	}

	return $blocks;
}

/**
 * Render các khối do nlw_content_blocks() tạo ra.
 *
 * @param array  $blocks     Khối.
 * @param string $list_class Class cho <ul>.
 * @return string
 */
function nlw_render_blocks( $blocks, $list_class = 'nlw-qlist' ) {
	$out = '';

	foreach ( $blocks as $block ) {
		if ( 'list' === $block[0] ) {
			$out .= '<ul class="' . esc_attr( $list_class ) . '">';
			foreach ( $block[1] as $item ) {
				$out .= '<li>' . nlw_rich_text( $item, false ) . '</li>';
			}
			$out .= '</ul>';
			continue;
		}

		$out .= '<p>' . nlw_rich_text( $block[1] ) . '</p>';
	}

	return $out;
}

/**
 * Icon dạng CSS mask (không dùng icon font, không dùng emoji).
 *
 * @param string $name  zalo|mess|arr|ck|clock|ext|phone|mail|pin.
 * @param string $extra Class thêm.
 * @return string
 */
function nlw_icon( $name, $extra = '' ) {
	$allowed = array(
		'zalo', 'mess', 'arr', 'ck', 'clock', 'ext', 'phone', 'mail', 'pin',
		'layout', 'cart', 'building', 'target', 'eye', 'chat', 'heart', 'square',
		'sun', 'moon',
	);

	if ( ! in_array( $name, $allowed, true ) ) {
		return '';
	}

	return '<span class="nlw-ic nlw-ic-' . esc_attr( $name ) . ' ' . esc_attr( $extra ) . '" aria-hidden="true"></span>';
}

/**
 * Nút chuyển giao diện Sáng / Tối.
 *
 * Trạng thái thật nằm ở thuộc tính data-theme trên <html>; nút chỉ đảo giá trị
 * đó và ghi vào localStorage. CSS lo phần hiện icon mặt trời hay mặt trăng.
 *
 * @return string
 */
function nlw_theme_toggle_html() {
	return '<button type="button" class="nlw-theme-toggle" data-nlw-theme-toggle'
		. ' aria-label="Chuyển giao diện sáng / tối" title="Chuyển giao diện sáng / tối">'
		. nlw_icon( 'moon' )
		. nlw_icon( 'sun' )
		. '</button>';
}

/**
 * Link liên hệ theo kênh, lấy từ trang cấu hình.
 *
 * @param string $channel zalo|messenger|phone|email.
 * @return string
 */
function nlw_contact_url( $channel ) {
	switch ( $channel ) {
		case 'zalo':
			$zalo = nlw_opt( 'zalo_url' );
			if ( ! $zalo ) {
				$phone = preg_replace( '/\D/', '', nlw_opt( 'zalo_phone' ) );
				$zalo  = $phone ? 'https://zalo.me/' . $phone : '#';
			}
			return $zalo;

		case 'messenger':
			return nlw_opt( 'messenger_url', '#' );

		case 'phone':
			$phone = preg_replace( '/[^\d\+]/', '', nlw_opt( 'hotline' ) );
			return $phone ? 'tel:' . $phone : '#';

		case 'email':
			$email = nlw_opt( 'email' );
			return $email ? 'mailto:' . $email : '#';
	}

	return '#';
}

/**
 * Nút chuẩn của design system.
 *
 * @param string $label   Nhãn.
 * @param string $url     URL.
 * @param string $variant primary|onbrand|ghost|white-out.
 * @param array  $args    icon, icon_after, size, class, target.
 * @return string
 */
function nlw_button( $label, $url, $variant = 'primary', $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'icon'       => '',
			'icon_after' => '',
			'size'       => '',
			'class'      => '',
			'target'     => '',
		)
	);

	$classes = 'nlw-btn nlw-btn-' . $variant;
	$classes .= 'lg' === $args['size'] ? ' nlw-btn-lg' : '';
	$classes .= $args['class'] ? ' ' . $args['class'] : '';

	$target = $args['target'] ? ' target="' . esc_attr( $args['target'] ) . '" rel="noopener"' : '';

	return sprintf(
		'<a class="%1$s" href="%2$s"%3$s>%4$s%5$s%6$s</a>',
		esc_attr( $classes ),
		esc_url( $url ),
		$target,
		$args['icon'] ? nlw_icon( $args['icon'] ) : '',
		esc_html( $label ),
		$args['icon_after'] ? nlw_icon( $args['icon_after'] ) : ''
	);
}

/**
 * Ảnh của một field; nếu trống thì trả placeholder gạch chéo có nhãn.
 *
 * @param mixed  $image Field image (ID | array | URL).
 * @param string $ratio Class tỉ lệ, ví dụ nlw-r-16x10.
 * @param string $label Nhãn placeholder.
 * @param string $size  Kích thước ảnh WP.
 * @return string
 */
function nlw_image( $image, $ratio = 'nlw-r-16x10', $label = 'Ảnh dự án', $size = 'large' ) {
	$id  = 0;
	$url = '';

	if ( is_array( $image ) && isset( $image['ID'] ) ) {
		$id = (int) $image['ID'];
	} elseif ( is_numeric( $image ) ) {
		$id = (int) $image;
	} elseif ( is_string( $image ) && $image ) {
		$url = $image;
	}

	if ( $id ) {
		$url = wp_get_attachment_image_url( $id, $size );
		$alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
	} else {
		$alt = $label;
	}

	if ( ! $url ) {
		return '<div class="nlw-shot ' . esc_attr( $ratio ) . '">' . esc_html( $label ) . '</div>';
	}

	return sprintf(
		'<div class="nlw-shot nlw-shot-has %1$s"><img src="%2$s" alt="%3$s" loading="lazy"></div>',
		esc_attr( $ratio ),
		esc_url( $url ),
		esc_attr( $alt )
	);
}

/**
 * Định dạng giá: 4000000 -> "4.000.000". Chuỗi có sẵn định dạng thì giữ nguyên.
 *
 * @param string $price Giá.
 * @return string
 */
function nlw_price( $price ) {
	$price = trim( (string) $price );

	if ( '' === $price ) {
		return '';
	}

	if ( preg_match( '/^\d+$/', $price ) ) {
		return number_format( (int) $price, 0, ',', '.' );
	}

	return $price;
}

/**
 * Lĩnh vực đang được lọc ở archive dự án (?linh-vuc=…).
 *
 * @return string Chuỗi rỗng nếu không lọc.
 */
function nlw_current_industry() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- chỉ là bộ lọc hiển thị.
	return isset( $_GET['linh-vuc'] ) ? sanitize_text_field( wp_unslash( $_GET['linh-vuc'] ) ) : '';
}

/**
 * URL trang theo id lưu trong cấu hình (fallback về trang chủ).
 *
 * @param string $key page_contact|page_services|page_projects.
 * @return string
 */
function nlw_page_url( $key ) {
	$id = (int) nlw_opt( $key, 0 );

	return $id ? get_permalink( $id ) : home_url( '/' );
}
