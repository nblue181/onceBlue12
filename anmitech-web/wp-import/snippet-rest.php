<?php
/**
 * Anmitech — endpoint REST cho frontend Next.js.
 *
 * Dán vào plugin Code Snippets, hoặc cuối functions.php của child theme.
 * KHÔNG dán dòng <?php ở trên nếu file đích đã có sẵn.
 *
 * Thêm hai endpoint:
 *   /wp-json/anmitech/v1/options   toàn bộ trường Cấu hình
 *   /wp-json/anmitech/v1/home      gộp Cấu hình + Dịch vụ + Dự án nổi bật
 *
 * Vì sao cần: REST API của WordPress không trả về giá trị Options Page,
 * và trang chủ cần dữ liệu từ 3 nguồn — gọi lần lượt sẽ chậm gấp ba.
 */

/**
 * Đọc toàn bộ trường của trang Cấu hình, luôn trả về array.
 *
 * Gom vào một hàm vì cả hai endpoint đều cần, và vì get_fields( 'option' )
 * quét toàn bộ bảng options — gọi hai lần trong cùng một request là truy vấn
 * gấp đôi mà kết quả y hệt.
 */
function anmi_get_options() {
	if ( ! function_exists( 'get_fields' ) ) {
		return array();
	}

	$fields = get_fields( 'option' );

	return is_array( $fields ) ? $fields : array();
}

add_action( 'rest_api_init', function () {

	register_rest_route( 'anmitech/v1', '/options', array(
		'methods'             => 'GET',
		'permission_callback' => '__return_true',
		'callback'            => function () {
			// Báo lỗi rõ ràng ở đây thay vì trả array rỗng — nếu thiếu SCF thì
			// toàn bộ nội dung site biến mất, cần biết ngay chứ không âm thầm.
			if ( ! function_exists( 'get_fields' ) ) {
				return new WP_Error( 'anmi_no_scf', 'Chưa bật Secure Custom Fields.', array( 'status' => 500 ) );
			}

			return rest_ensure_response( anmi_get_options() );
		},
	) );

	register_rest_route( 'anmitech/v1', '/home', array(
		'methods'             => 'GET',
		'permission_callback' => '__return_true',
		'callback'            => 'anmi_rest_home',
	) );
} );

/**
 * Rút gọn một bài viết về đúng những gì frontend cần.
 *
 * Trả nguyên object của WordPress sẽ kéo theo hàng chục khoá thừa
 * (_links, guid, ping_status…) làm payload phình ra vô ích.
 */
function anmi_prepare_post( $post ) {
	$thumb_id = get_post_thumbnail_id( $post );

	$out = array(
		'id'      => $post->ID,
		'slug'    => $post->post_name,
		'title'   => get_the_title( $post ),
		'excerpt' => wp_strip_all_tags( get_the_excerpt( $post ) ),
		'thumb'   => $thumb_id ? array(
			'url' => wp_get_attachment_image_url( $thumb_id, 'large' ),
			'alt' => get_post_meta( $thumb_id, '_wp_attachment_image_alt', true ),
		) : null,
	);

	if ( 'project' === $post->post_type ) {
		$terms = get_the_terms( $post, 'project_cat' );
		$out['categories'] = ( $terms && ! is_wp_error( $terms ) )
			? array_map( function ( $t ) { return array( 'slug' => $t->slug, 'name' => $t->name ); }, $terms )
			: array();
	}

	if ( function_exists( 'get_fields' ) ) {
		$acf = get_fields( $post->ID );
		$out['acf'] = is_array( $acf ) ? $acf : array();

		// Ở danh sách thì bỏ các trường nặng, chỉ trang chi tiết mới cần.
		// Hai post type nặng ở chỗ khác nhau:
		//   service — `packages` là repeater lồng repeater (mỗi gói kèm danh sách
		//             tính năng), trong khi thẻ ngoài trang chủ chỉ cần icon,
		//             short_desc và price_from.
		//   project — các khối case study, cộng `hero_image` để return_format
		//             'array' nên trả về metadata của MỌI kích cỡ ảnh, trùng vai
		//             trò với khoá `thumb` đã dựng ở trên.
		$heavy = array(
			'service' => array( 'packages' ),
			'project' => array( 'solutions', 'gallery', 'results', 'problem_text', 'hero_image' ),
		);

		foreach ( $heavy[ $post->post_type ] ?? array() as $key ) {
			unset( $out['acf'][ $key ] );
		}
	}

	return $out;
}

function anmi_rest_home() {
	$services = get_posts( array(
		'post_type'      => 'service',
		'posts_per_page' => 6,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	) );

	$featured = get_posts( array(
		'post_type'      => 'project',
		'posts_per_page' => 5,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
		'meta_query'     => array( array( 'key' => 'featured', 'value' => '1' ) ),
	) );

	// Chưa đánh dấu dự án nào thì lấy tạm các dự án mới nhất, để trang chủ không trống.
	if ( ! $featured ) {
		$featured = get_posts( array(
			'post_type'      => 'project',
			'posts_per_page' => 5,
			'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
		) );
	}

	return rest_ensure_response( array(
		'options'  => anmi_get_options(),
		'services' => array_map( 'anmi_prepare_post', $services ),
		'projects' => array_map( 'anmi_prepare_post', $featured ),
	) );
}

/**
 * Cho phép lọc dự án theo lĩnh vực: /wp-json/wp/v2/projects?industry=xay-dung
 */
add_filter( 'rest_project_query', function ( $args, $request ) {
	$industry = $request->get_param( 'industry' );

	if ( $industry ) {
		$args['meta_query'] = array( array( 'key' => 'industry', 'value' => sanitize_text_field( $industry ) ) );
	}

	return $args;
}, 10, 2 );
