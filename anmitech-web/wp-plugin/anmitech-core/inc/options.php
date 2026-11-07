<?php
/**
 * Trang Cấu hình — thương hiệu, liên hệ, nội dung cố định của trang chủ.
 *
 * Dùng Options Page của SCF. Frontend đọc qua endpoint /anmitech/v1/options
 * (xem inc/rest.php), vì REST mặc định của WordPress không lộ options page.
 *
 * @package anmitech
 */

defined( 'ABSPATH' ) || exit;

add_action( 'acf/init', function () {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	acf_add_options_page( array(
		'page_title'      => 'Anmitech — Cấu hình',
		'menu_title'      => 'Anmitech',
		'menu_slug'       => 'anmitech-options',
		'capability'      => 'manage_options',
		'icon_url'        => 'dashicons-admin-site-alt3',
		'position'        => 20,
		'redirect'        => false,
		'updated_message' => 'Đã lưu cấu hình.',
	) );
}, 5 );

add_action( 'acf/init', function () {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
		'key'          => 'group_anmi_options',
		'title'        => 'Cấu hình chung',
		'show_in_rest' => 1,
		'location'     => array( array( array(
			'param' => 'options_page', 'operator' => '==', 'value' => 'anmitech-options',
		) ) ),
		'fields' => array(

			array( 'key' => 'f_opt_tab_brand', 'label' => 'Thương hiệu', 'name' => '', 'type' => 'tab' ),

			array( 'key' => 'f_opt_brand', 'label' => 'Tên thương hiệu', 'name' => 'brand_name', 'type' => 'text', 'default_value' => 'Anmitech', 'wrapper' => array( 'width' => '34' ) ),
			array( 'key' => 'f_opt_tagline', 'label' => 'Tagline', 'name' => 'tagline', 'type' => 'text', 'wrapper' => array( 'width' => '33' ) ),
			array( 'key' => 'f_opt_domain', 'label' => 'Tên miền hiển thị', 'name' => 'domain', 'type' => 'text', 'default_value' => 'anmitech.vn', 'wrapper' => array( 'width' => '33' ) ),
			array( 'key' => 'f_opt_footer_desc', 'label' => 'Mô tả ở footer', 'name' => 'footer_desc', 'type' => 'textarea', 'rows' => 2 ),
			array( 'key' => 'f_opt_logo', 'label' => 'Logo', 'name' => 'logo', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'thumbnail', 'wrapper' => array( 'width' => '50' ) ),
			array( 'key' => 'f_opt_logo_light', 'label' => 'Logo bản trắng (cho nền tối)', 'name' => 'logo_light', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'thumbnail', 'wrapper' => array( 'width' => '50' ) ),

			array( 'key' => 'f_opt_tab_contact', 'label' => 'Liên hệ', 'name' => '', 'type' => 'tab' ),

			array( 'key' => 'f_opt_zalo_phone', 'label' => 'Số Zalo', 'name' => 'zalo_phone', 'type' => 'text', 'wrapper' => array( 'width' => '50' ) ),
			array( 'key' => 'f_opt_zalo_url', 'label' => 'Link Zalo', 'name' => 'zalo_url', 'type' => 'url', 'wrapper' => array( 'width' => '50' ) ),
			array( 'key' => 'f_opt_messenger', 'label' => 'Link Messenger', 'name' => 'messenger_url', 'type' => 'url', 'wrapper' => array( 'width' => '50' ) ),
			array( 'key' => 'f_opt_hotline', 'label' => 'Hotline', 'name' => 'hotline', 'type' => 'text', 'wrapper' => array( 'width' => '25' ) ),
			array( 'key' => 'f_opt_email', 'label' => 'Email', 'name' => 'email', 'type' => 'email', 'wrapper' => array( 'width' => '25' ) ),
			array( 'key' => 'f_opt_hours', 'label' => 'Thời gian làm việc', 'name' => 'working_hours', 'type' => 'text', 'wrapper' => array( 'width' => '50' ) ),
			array( 'key' => 'f_opt_address', 'label' => 'Địa chỉ / khu vực', 'name' => 'address', 'type' => 'text', 'wrapper' => array( 'width' => '50' ) ),

			array( 'key' => 'f_opt_tab_home', 'label' => 'Trang chủ', 'name' => '', 'type' => 'tab' ),

			array( 'key' => 'f_opt_hero_eyebrow', 'label' => 'Hero — eyebrow', 'name' => 'hero_eyebrow', 'type' => 'text' ),
			array( 'key' => 'f_opt_hero_title', 'label' => 'Hero — tiêu đề H1', 'name' => 'hero_title', 'type' => 'textarea', 'rows' => 2 ),
			array(
				'key' => 'f_opt_hero_karaoke', 'label' => 'Hero — cụm được làm nổi', 'name' => 'hero_karaoke', 'type' => 'text',
				'instructions' => 'Cụm từ này chạy hiệu ứng karaoke. Chỉ một cụm duy nhất trong H1.',
			),
			array(
				'key' => 'f_opt_hero_type', 'label' => 'Hero — các cụm đánh chữ', 'name' => 'hero_typewriter',
				'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Thêm cụm',
				'instructions' => 'Frontend gõ lần lượt từng cụm rồi lặp lại.',
				'sub_fields' => array(
					array( 'key' => 'f_opt_type_text', 'label' => 'Nội dung', 'name' => 'text', 'type' => 'text' ),
				),
			),
			array( 'key' => 'f_opt_hero_desc', 'label' => 'Hero — mô tả', 'name' => 'hero_desc', 'type' => 'textarea', 'rows' => 3 ),
			array( 'key' => 'f_opt_hero_micro', 'label' => 'Hero — microcopy dưới nút', 'name' => 'hero_note', 'type' => 'text' ),
			array(
				'key' => 'f_opt_values', 'label' => 'Giá trị cốt lõi', 'name' => 'values',
				'type' => 'repeater', 'layout' => 'row', 'button_label' => 'Thêm giá trị',
				'sub_fields' => array(
					array( 'key' => 'f_opt_val_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'select', 'choices' => array( '' => 'Không có', 'eye' => 'Con mắt', 'chat' => 'Trò chuyện', 'heart' => 'Trái tim', 'square' => 'Ô tích', 'target' => 'Mục tiêu' ), 'wrapper' => array( 'width' => '20' ) ),
					array( 'key' => 'f_opt_val_title', 'label' => 'Tiêu đề', 'name' => 'title', 'type' => 'text', 'wrapper' => array( 'width' => '25' ) ),
					array( 'key' => 'f_opt_val_desc', 'label' => 'Mô tả', 'name' => 'desc', 'type' => 'text' ),
				),
			),
			array(
				'key' => 'f_opt_stats', 'label' => 'Số liệu', 'name' => 'stats',
				'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Thêm số liệu',
				'instructions' => 'CHỈ điền số liệu có thật. Không có thì để trống, đừng bịa.',
				'sub_fields' => array(
					array( 'key' => 'f_opt_stat_num', 'label' => 'Con số', 'name' => 'number', 'type' => 'number', 'wrapper' => array( 'width' => '20' ) ),
					array( 'key' => 'f_opt_stat_suffix', 'label' => 'Hậu tố', 'name' => 'suffix', 'type' => 'text', 'wrapper' => array( 'width' => '15' ) ),
					array( 'key' => 'f_opt_stat_label', 'label' => 'Nhãn', 'name' => 'label', 'type' => 'text' ),
				),
			),

			array( 'key' => 'f_opt_tab_cta', 'label' => 'CTA & SEO', 'name' => '', 'type' => 'tab' ),

			array( 'key' => 'f_opt_cta_title', 'label' => 'CTA — tiêu đề', 'name' => 'cta_title', 'type' => 'text' ),
			array( 'key' => 'f_opt_cta_desc', 'label' => 'CTA — mô tả', 'name' => 'cta_desc', 'type' => 'textarea', 'rows' => 2 ),
			array( 'key' => 'f_opt_seo_title', 'label' => 'SEO title trang chủ', 'name' => 'seo_title', 'type' => 'text' ),
			array( 'key' => 'f_opt_seo_desc', 'label' => 'Meta description trang chủ', 'name' => 'seo_desc', 'type' => 'textarea', 'rows' => 2 ),
			array( 'key' => 'f_opt_og_image', 'label' => 'Ảnh chia sẻ (OG image)', 'name' => 'og_image', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium', 'instructions' => 'Khuyến nghị 1200×630px.' ),
		),
	) );
} );
