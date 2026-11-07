<?php
/**
 * Endpoint REST cho frontend Next.js.
 *
 * Vì sao cần endpoint riêng thay vì dùng /wp/v2 mặc định:
 *
 * 1. Options Page của SCF KHÔNG có trong REST mặc định. Không có endpoint này
 *    thì frontend không đọc được thông tin liên hệ, nội dung hero, số liệu.
 * 2. Trang chủ cần dữ liệu từ 4 nguồn. Gọi lần lượt sẽ mất 4 x 1.6 giây.
 *    Gộp một lần chỉ còn một lượt.
 *
 * @package anmitech
 */

defined( 'ABSPATH' ) || exit;

const ANMI_NS = 'anmitech/v1';

add_action( 'rest_api_init', function () {

	register_rest_route( ANMI_NS, '/options', array(
		'methods'             => WP_REST_Server::READABLE,
		'permission_callback' => '__return_true',
		'callback'            => 'anmi_rest_options',
	) );

	register_rest_route( ANMI_NS, '/home', array(
		'methods'             => WP_REST_Server::READABLE,
		'permission_callback' => '__return_true',
		'callback'            => 'anmi_rest_home',
	) );
} );

/**
 * Toàn bộ trường của Options Page.
 */
function anmi_rest_options() {
	if ( ! function_exists( 'get_fields' ) ) {
		return new WP_Error( 'anmi_no_scf', 'Chưa bật Secure Custom Fields.', array( 'status' => 500 ) );
	}

	$fields = get_fields( 'option' );

	return rest_ensure_response( is_array( $fields ) ? $fields : array() );
}

/**
 * Rút gọn một bài viết về đúng những gì frontend cần.
 *
 * Trả nguyên object của WordPress sẽ kéo theo hàng chục khoá thừa
 * (_links, guid, ping_status…) làm payload phình ra vô ích.
 */
function anmi_prepare_post( WP_Post $post, bool $full = false ) {
	$thumb_id = get_post_thumbnail_id( $post );

	$out = array(
		'id'      => $post->ID,
		'slug'    => $post->post_name,
		'title'   => get_the_title( $post ),
		'excerpt' => wp_strip_all_tags( get_the_excerpt( $post ) ),
		'thumb'   => $thumb_id ? array(
			'url'    => wp_get_attachment_image_url( $thumb_id, 'large' ),
			'alt'    => get_post_meta( $thumb_id, '_wp_attachment_image_alt', true ),
			'width'  => 0,
			'height' => 0,
		) : null,
	);

	if ( $thumb_id ) {
		$meta = wp_get_attachment_metadata( $thumb_id );
		if ( isset( $meta['sizes']['large'] ) ) {
			$out['thumb']['width']  = (int) $meta['sizes']['large']['width'];
			$out['thumb']['height'] = (int) $meta['sizes']['large']['height'];
		}
	}

	if ( 'project' === $post->post_type ) {
		$terms = get_the_terms( $post, 'project_cat' );
		$out['categories'] = ( $terms && ! is_wp_error( $terms ) )
			? array_map( fn( $t ) => array( 'slug' => $t->slug, 'name' => $t->name ), $terms )
			: array();
	}

	if ( function_exists( 'get_fields' ) ) {
		$acf = get_fields( $post->ID );
		$out['acf'] = is_array( $acf ) ? $acf : array();

		// Ở danh sách thì bỏ các trường nặng, chỉ trang chi tiết mới cần.
		if ( ! $full ) {
			unset( $out['acf']['solutions'], $out['acf']['gallery'], $out['acf']['results'], $out['acf']['problem_text'] );
		}
	}

	if ( $full ) {
		$out['content'] = apply_filters( 'the_content', $post->post_content );
	}

	return $out;
}

/**
 * Gộp toàn bộ dữ liệu trang chủ vào một lượt gọi.
 */
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
		'options'  => function_exists( 'get_fields' ) ? ( get_fields( 'option' ) ?: array() ) : array(),
		'services' => array_map( fn( $p ) => anmi_prepare_post( $p ), $services ),
		'projects' => array_map( fn( $p ) => anmi_prepare_post( $p ), $featured ),
		'meta'     => array(
			'generated_at' => current_time( 'c' ),
			'site_url'     => home_url( '/' ),
		),
	) );
}

/**
 * Cho phép Next.js lọc dự án theo lĩnh vực qua /wp/v2/projects?industry=...
 */
add_filter( 'rest_project_query', function ( $args, $request ) {
	$industry = $request->get_param( 'industry' );

	if ( $industry ) {
		$args['meta_query'] = array( array( 'key' => 'industry', 'value' => sanitize_text_field( $industry ) ) );
	}

	return $args;
}, 10, 2 );
