<?php
/**
 * Custom post type + taxonomy.
 *
 * project — Dự án (case study) + taxonomy project_cat.
 * service — Dịch vụ; bảng giá là repeater `packages` nằm trong chính bản ghi dịch vụ.
 *
 * @package nlw
 */

defined( 'ABSPATH' ) || exit;

/**
 * Đăng ký CPT và taxonomy.
 */
function nlw_register_post_types() {

	register_post_type(
		'project',
		array(
			'labels'        => array(
				'name'               => 'Dự án',
				'singular_name'      => 'Dự án',
				'add_new'            => 'Thêm dự án',
				'add_new_item'       => 'Thêm dự án mới',
				'edit_item'          => 'Sửa dự án',
				'all_items'          => 'Tất cả dự án',
				'search_items'       => 'Tìm dự án',
				'not_found'          => 'Chưa có dự án nào.',
				'menu_name'          => 'Dự án',
			),
			'public'        => true,
			'has_archive'   => 'du-an',
			'rewrite'       => array( 'slug' => 'du-an', 'with_front' => false ),
			'menu_icon'     => 'dashicons-portfolio',
			'menu_position' => 21,
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
			'show_in_rest'  => true,
		)
	);

	register_taxonomy(
		'project_cat',
		'project',
		array(
			'labels'            => array(
				'name'          => 'Nhóm dự án',
				'singular_name' => 'Nhóm dự án',
				'add_new_item'  => 'Thêm nhóm dự án',
				'menu_name'     => 'Nhóm dự án',
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'nhom-du-an', 'with_front' => false ),
			'show_in_rest'      => true,
		)
	);

	register_post_type(
		'service',
		array(
			'labels'        => array(
				'name'          => 'Dịch vụ',
				'singular_name' => 'Dịch vụ',
				'add_new'       => 'Thêm dịch vụ',
				'add_new_item'  => 'Thêm dịch vụ mới',
				'edit_item'     => 'Sửa dịch vụ',
				'all_items'     => 'Tất cả dịch vụ',
				'menu_name'     => 'Dịch vụ',
			),
			'public'        => true,
			'has_archive'   => false,
			'rewrite'       => array( 'slug' => 'dich-vu', 'with_front' => false ),
			'menu_icon'     => 'dashicons-screenoptions',
			'menu_position' => 22,
			'supports'      => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			'show_in_rest'  => true,
		)
	);

}
add_action( 'init', 'nlw_register_post_types' );

/**
 * Flush rewrite rules một lần sau khi kích hoạt theme.
 */
function nlw_maybe_flush_rewrites() {
	if ( get_option( 'nlw_rewrites_flushed' ) === NLW_VERSION ) {
		return;
	}

	nlw_register_post_types();
	flush_rewrite_rules();
	update_option( 'nlw_rewrites_flushed', NLW_VERSION );
}
add_action( 'after_switch_theme', 'nlw_maybe_flush_rewrites' );
add_action( 'admin_init', 'nlw_maybe_flush_rewrites' );

/**
 * Cột "Giá khởi điểm" và "Số gói" trong danh sách dịch vụ.
 *
 * @param array $cols Cột hiện có.
 * @return array
 */
function nlw_service_columns( $cols ) {
	$new = array();

	foreach ( $cols as $key => $label ) {
		$new[ $key ] = $label;

		if ( 'title' === $key ) {
			$new['nlw_price_from'] = 'Chỉ từ';
			$new['nlw_packages']   = 'Số gói';
		}
	}

	return $new;
}
add_filter( 'manage_service_posts_columns', 'nlw_service_columns' );

/**
 * Nội dung các cột tuỳ chỉnh.
 *
 * @param string $col     Tên cột.
 * @param int    $post_id ID bài.
 */
function nlw_service_column_content( $col, $post_id ) {
	if ( 'nlw_price_from' === $col ) {
		$price = nlw_price( nlw_field( 'price_from', $post_id ) );
		echo $price ? esc_html( $price . 'đ' ) : '—';
	}

	if ( 'nlw_packages' === $col ) {
		echo esc_html( (string) count( nlw_rows( 'packages', $post_id ) ) );
	}
}
add_action( 'manage_service_posts_custom_column', 'nlw_service_column_content', 10, 2 );
