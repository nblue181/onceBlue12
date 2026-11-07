<?php
/**
 * CPT và taxonomy.
 *
 * Tất cả đều bật show_in_rest — nếu không, Next.js sẽ không đọc được qua REST API.
 *
 * @package anmitech
 */

defined( 'ABSPATH' ) || exit;

function anmi_register_post_types() {

	register_post_type( 'service', array(
		'labels' => array(
			'name'          => 'Dịch vụ',
			'singular_name' => 'Dịch vụ',
			'add_new_item'  => 'Thêm dịch vụ mới',
			'edit_item'     => 'Sửa dịch vụ',
			'all_items'     => 'Tất cả dịch vụ',
			'menu_name'     => 'Dịch vụ',
		),
		'public'        => true,
		'has_archive'   => false,
		'rewrite'       => array( 'slug' => 'dich-vu', 'with_front' => false ),
		'menu_icon'     => 'dashicons-screenoptions',
		'menu_position' => 21,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
		'show_in_rest'  => true,
		'rest_base'     => 'services',
	) );

	register_post_type( 'project', array(
		'labels' => array(
			'name'          => 'Dự án',
			'singular_name' => 'Dự án',
			'add_new_item'  => 'Thêm dự án mới',
			'edit_item'     => 'Sửa dự án',
			'all_items'     => 'Tất cả dự án',
			'menu_name'     => 'Dự án',
		),
		'public'        => true,
		'has_archive'   => 'du-an',
		'rewrite'       => array( 'slug' => 'du-an', 'with_front' => false ),
		'menu_icon'     => 'dashicons-portfolio',
		'menu_position' => 22,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
		'show_in_rest'  => true,
		'rest_base'     => 'projects',
	) );

	register_taxonomy( 'project_cat', 'project', array(
		'labels' => array(
			'name'          => 'Nhóm dự án',
			'singular_name' => 'Nhóm dự án',
			'menu_name'     => 'Nhóm dự án',
		),
		'public'            => true,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'rewrite'           => array( 'slug' => 'nhom-du-an', 'with_front' => false ),
		'show_in_rest'      => true,
		'rest_base'         => 'project-cats',
	) );
}
add_action( 'init', 'anmi_register_post_types' );

/**
 * Cột phụ trong danh sách, để soát dữ liệu nhanh mà không phải mở từng mục.
 */
add_filter( 'manage_service_posts_columns', function ( $cols ) {
	$new = array();
	foreach ( $cols as $k => $v ) {
		$new[ $k ] = $v;
		if ( 'title' === $k ) {
			$new['anmi_price'] = 'Chỉ từ';
			$new['anmi_pkgs']  = 'Số gói';
		}
	}
	return $new;
} );

add_action( 'manage_service_posts_custom_column', function ( $col, $id ) {
	if ( ! function_exists( 'get_field' ) ) {
		return;
	}
	if ( 'anmi_price' === $col ) {
		$v = get_field( 'price_from', $id );
		echo $v ? esc_html( number_format( (int) $v, 0, ',', '.' ) . 'đ' ) : '—';
	}
	if ( 'anmi_pkgs' === $col ) {
		$p = get_field( 'packages', $id );
		echo esc_html( is_array( $p ) ? (string) count( $p ) : '0' );
	}
}, 10, 2 );

add_filter( 'manage_project_posts_columns', function ( $cols ) {
	$new = array();
	foreach ( $cols as $k => $v ) {
		$new[ $k ] = $v;
		if ( 'title' === $k ) {
			$new['anmi_client']   = 'Khách hàng';
			$new['anmi_featured'] = 'Nổi bật';
		}
	}
	return $new;
} );

add_action( 'manage_project_posts_custom_column', function ( $col, $id ) {
	if ( ! function_exists( 'get_field' ) ) {
		return;
	}
	if ( 'anmi_client' === $col ) {
		echo esc_html( get_field( 'client', $id ) ?: '—' );
	}
	if ( 'anmi_featured' === $col ) {
		echo get_field( 'featured', $id ) ? '★' : '';
	}
}, 10, 2 );
