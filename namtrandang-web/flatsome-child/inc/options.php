<?php
/**
 * Options Page "Nam làm WEB → Cấu hình" (SCF).
 *
 * Chứa thương hiệu, kênh liên hệ, trang đích và các nội dung cố định của trang chủ.
 * Mọi giá trị ở đây được dùng lại ở header, footer, CTA band, trang Liên hệ và nút nổi.
 *
 * @package nlw
 */

defined( 'ABSPATH' ) || exit;

/**
 * Đăng ký options page.
 */
function nlw_register_options_page() {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	acf_add_options_page(
		array(
			'page_title' => 'Nam làm WEB — Cấu hình',
			'menu_title' => 'Nam làm WEB',
			'menu_slug'  => 'nlw-options',
			'capability' => 'manage_options',
			'icon_url'   => 'dashicons-admin-site-alt3',
			'position'   => 20,
			'redirect'   => false,
			'updated_message' => 'Đã lưu cấu hình.',
		)
	);
}
add_action( 'acf/init', 'nlw_register_options_page', 5 );

/**
 * Field group cho options page.
 */
function nlw_register_options_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'      => 'group_nlw_options',
			'title'    => 'Cấu hình chung',
			'location' => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'nlw-options',
					),
				),
			),
			'fields'   => array(

				/* ---------- Thương hiệu ---------- */
				array(
					'key'   => 'field_nlw_o_tab_brand',
					'label' => 'Thương hiệu',
					'name'  => '',
					'type'  => 'tab',
				),
				array(
					'key'           => 'field_nlw_o_brand_name',
					'label'         => 'Tên thương hiệu',
					'name'          => 'brand_name',
					'type'          => 'text',
					'default_value' => 'Nam làm WEB',
					'wrapper'       => array( 'width' => '34' ),
				),
				array(
					'key'           => 'field_nlw_o_tagline',
					'label'         => 'Tagline',
					'name'          => 'tagline',
					'type'          => 'text',
					'default_value' => 'Hiểu đúng. Làm đúng.',
					'wrapper'       => array( 'width' => '33' ),
				),
				array(
					'key'           => 'field_nlw_o_domain',
					'label'         => 'Tên miền hiển thị ở footer',
					'name'          => 'domain',
					'type'          => 'text',
					'default_value' => 'namtrandang.com',
					'wrapper'       => array( 'width' => '33' ),
				),
				array(
					'key'           => 'field_nlw_o_footer_desc',
					'label'         => 'Mô tả ở footer',
					'name'          => 'footer_desc',
					'type'          => 'textarea',
					'rows'          => 2,
					'default_value' => 'Xây dựng niềm tin cho doanh nghiệp thông qua website.',
				),
				array(
					'key'           => 'field_nlw_o_footer_logo',
					'label'         => 'Logo footer (bản trắng)',
					'name'          => 'footer_logo',
					'type'          => 'image',
					'return_format' => 'url',
					'preview_size'  => 'thumbnail',
					'instructions'  => 'Bỏ trống sẽ dùng <code>assets/img/logo-horizontal-white.png</code> trong child theme.',
				),

				/* ---------- Liên hệ ---------- */
				array(
					'key'   => 'field_nlw_o_tab_contact',
					'label' => 'Liên hệ',
					'name'  => '',
					'type'  => 'tab',
				),
				array(
					'key'          => 'field_nlw_o_zalo_phone',
					'label'        => 'Số Zalo',
					'name'         => 'zalo_phone',
					'type'         => 'text',
					'instructions' => 'Ví dụ 0901234567. Dùng để sinh link zalo.me nếu không nhập URL riêng.',
					'wrapper'      => array( 'width' => '50' ),
				),
				array(
					'key'     => 'field_nlw_o_zalo_url',
					'label'   => 'Link Zalo (tuỳ chọn)',
					'name'    => 'zalo_url',
					'type'    => 'url',
					'wrapper' => array( 'width' => '50' ),
				),
				array(
					'key'          => 'field_nlw_o_messenger',
					'label'        => 'Link Messenger',
					'name'         => 'messenger_url',
					'type'         => 'url',
					'instructions' => 'Ví dụ https://m.me/tenpage',
					'wrapper'      => array( 'width' => '50' ),
				),
				array(
					'key'     => 'field_nlw_o_hotline',
					'label'   => 'Hotline',
					'name'    => 'hotline',
					'type'    => 'text',
					'wrapper' => array( 'width' => '25' ),
				),
				array(
					'key'     => 'field_nlw_o_email',
					'label'   => 'Email',
					'name'    => 'email',
					'type'    => 'email',
					'wrapper' => array( 'width' => '25' ),
				),
				array(
					'key'           => 'field_nlw_o_hours',
					'label'         => 'Thời gian làm việc',
					'name'          => 'working_hours',
					'type'          => 'text',
					'default_value' => 'Đang nhận dự án · Thứ 2 – Thứ 7, 8:00 – 20:00',
					'wrapper'       => array( 'width' => '50' ),
				),
				array(
					'key'     => 'field_nlw_o_area',
					'label'   => 'Khu vực làm việc',
					'name'    => 'work_area',
					'type'    => 'text',
					'wrapper' => array( 'width' => '50' ),
				),
				array(
					'key'          => 'field_nlw_o_map',
					'label'        => 'Mã nhúng bản đồ',
					'name'         => 'map_embed',
					'type'         => 'textarea',
					'rows'         => 3,
					'instructions' => 'Dán iframe Google Maps. Bỏ trống sẽ hiện khối placeholder.',
				),

				/* ---------- Trang đích ---------- */
				array(
					'key'   => 'field_nlw_o_tab_pages',
					'label' => 'Trang đích',
					'name'  => '',
					'type'  => 'tab',
				),
				array(
					'key'           => 'field_nlw_o_page_contact',
					'label'         => 'Trang Liên hệ',
					'name'          => 'page_contact',
					'type'          => 'post_object',
					'post_type'     => array( 'page' ),
					'return_format' => 'id',
					'ui'            => 1,
					'wrapper'       => array( 'width' => '25' ),
				),
				array(
					'key'           => 'field_nlw_o_page_services',
					'label'         => 'Trang Dịch vụ',
					'name'          => 'page_services',
					'type'          => 'post_object',
					'post_type'     => array( 'page' ),
					'return_format' => 'id',
					'ui'            => 1,
					'wrapper'       => array( 'width' => '25' ),
				),
				array(
					'key'           => 'field_nlw_o_page_about',
					'label'         => 'Trang Giới thiệu',
					'name'          => 'page_about',
					'type'          => 'post_object',
					'post_type'     => array( 'page' ),
					'return_format' => 'id',
					'ui'            => 1,
					'wrapper'       => array( 'width' => '25' ),
				),
				array(
					'key'           => 'field_nlw_o_page_projects',
					'label'         => 'Trang Dự án',
					'name'          => 'page_projects',
					'type'          => 'post_object',
					'post_type'     => array( 'page' ),
					'return_format' => 'id',
					'ui'            => 1,
					'instructions'  => 'Bỏ trống sẽ dùng archive <code>/du-an/</code> (khuyến nghị).',
					'wrapper'       => array( 'width' => '25' ),
				),
				array(
					'key'           => 'field_nlw_o_ppp',
					'label'         => 'Số dự án mỗi trang',
					'name'          => 'projects_per_page',
					'type'          => 'number',
					'default_value' => 9,
					'min'           => 3,
					'wrapper'       => array( 'width' => '50' ),
				),
				array(
					'key'           => 'field_nlw_o_float',
					'label'         => 'Hiện nút Zalo/Messenger nổi',
					'name'          => 'floating_buttons',
					'type'          => 'true_false',
					'ui'            => 1,
					'default_value' => 1,
					'wrapper'       => array( 'width' => '50' ),
				),

				/* ---------- Trang chủ ---------- */
				array(
					'key'   => 'field_nlw_o_tab_home',
					'label' => 'Trang chủ',
					'name'  => '',
					'type'  => 'tab',
				),
				array(
					'key'           => 'field_nlw_o_hero_eyebrow',
					'label'         => 'Hero — eyebrow',
					'name'          => 'hero_eyebrow',
					'type'          => 'text',
					'default_value' => 'Nam làm WEB',
				),
				array(
					'key'           => 'field_nlw_o_hero_title',
					'label'         => 'Hero — tiêu đề H1',
					'name'          => 'hero_title',
					'type'          => 'textarea',
					'rows'          => 2,
					'default_value' => 'Bạn sẽ hiểu website của mình trước khi sở hữu nó.',
					'instructions'  => 'Thông điệp lõi của thương hiệu — cân nhắc kỹ trước khi đổi.',
				),
				array(
					'key'           => 'field_nlw_o_hero_desc',
					'label'         => 'Hero — mô tả',
					'name'          => 'hero_desc',
					'type'          => 'textarea',
					'rows'          => 3,
					'default_value' => "Website không phải là điểm bắt đầu.\nTrước khi làm, mình cùng bạn làm rõ doanh nghiệp đang cần gì, khách hàng cần biết gì và website cần làm gì cho việc kinh doanh.",
					'instructions'  => 'Xuống dòng trong ô này sẽ được giữ nguyên khi hiển thị.',
				),
				array(
					'key'           => 'field_nlw_o_hero_note',
					'label'         => 'Hero — microcopy dưới nút',
					'name'          => 'hero_note',
					'type'          => 'text',
					'default_value' => 'Tư vấn miễn phí · Không ràng buộc',
				),
				array(
					'key'           => 'field_nlw_o_hero_image',
					'label'         => 'Hero — ảnh bên phải',
					'name'          => 'hero_image',
					'type'          => 'image',
					'return_format' => 'id',
					'preview_size'  => 'medium',
					'instructions'  => 'Có ảnh thì hero chia 2 cột, chữ bên trái - ảnh bên phải. Bỏ trống thì hero giữ 1 cột. Nên dùng ảnh dọc tỉ lệ 4/5 (ảnh chân dung hoặc ảnh làm việc thật).',
				),
				array(
					'key'          => 'field_nlw_o_promises',
					'label'        => '3 lời hứa (cột phải hero)',
					'name'         => 'promises',
					'type'         => 'repeater',
					'layout'       => 'table',
					'button_label' => 'Thêm lời hứa',
					'max'          => 3,
					'sub_fields'   => array(
						array(
							'key'     => 'field_nlw_o_promise_title',
							'label'   => 'Tiêu đề',
							'name'    => 'title',
							'type'    => 'text',
							'wrapper' => array( 'width' => '30' ),
						),
						array(
							'key'   => 'field_nlw_o_promise_desc',
							'label' => 'Mô tả',
							'name'  => 'desc',
							'type'  => 'text',
						),
					),
				),
				array(
					'key'          => 'field_nlw_o_values',
					'label'        => '4 giá trị cốt lõi',
					'name'         => 'values',
					'type'         => 'repeater',
					'layout'       => 'table',
					'button_label' => 'Thêm giá trị',
					'sub_fields'   => array(
						array(
							'key'     => 'field_nlw_o_value_icon',
							'label'   => 'Icon',
							'name'    => 'icon',
							'type'    => 'select',
							'choices' => array(
								''         => 'Không có',
								'eye'      => 'Con mắt (rõ ràng)',
								'chat'     => 'Trò chuyện (dễ hiểu)',
								'heart'    => 'Trái tim (tận tâm)',
								'square'   => 'Ô tích (thực tế)',
								'target'   => 'Mục tiêu',
								'layout'   => 'Khung website',
							),
							'wrapper' => array( 'width' => '20' ),
						),
						array(
							'key'     => 'field_nlw_o_value_title',
							'label'   => 'Tiêu đề',
							'name'    => 'title',
							'type'    => 'text',
							'wrapper' => array( 'width' => '25' ),
						),
						array(
							'key'   => 'field_nlw_o_value_desc',
							'label' => 'Mô tả',
							'name'  => 'desc',
							'type'  => 'text',
						),
					),
				),
				array(
					'key'          => 'field_nlw_o_process',
					'label'        => 'Quy trình 5 bước',
					'name'         => 'process',
					'type'         => 'repeater',
					'layout'       => 'row',
					'button_label' => 'Thêm bước',
					'sub_fields'   => array(
						array(
							'key'     => 'field_nlw_o_step_title',
							'label'   => 'Tên bước',
							'name'    => 'title',
							'type'    => 'text',
							'wrapper' => array( 'width' => '50' ),
						),
						array(
							'key'     => 'field_nlw_o_step_sub',
							'label'   => 'Nhãn phụ',
							'name'    => 'sub',
							'type'    => 'text',
							'wrapper' => array( 'width' => '50' ),
						),
						array(
							'key'   => 'field_nlw_o_step_desc',
							'label' => 'Mô tả',
							'name'  => 'desc',
							'type'  => 'textarea',
							'rows'  => 3,
						),
					),
				),

				/* ---------- SEO trang chủ ---------- */
				array(
					'key'   => 'field_nlw_o_tab_seo',
					'label' => 'SEO trang chủ',
					'name'  => '',
					'type'  => 'tab',
				),
				array(
					'key'           => 'field_nlw_o_seo_title',
					'label'         => 'SEO title',
					'name'          => 'seo_title',
					'type'          => 'text',
					'default_value' => 'Nam làm WEB | Website rõ ràng, dễ hiểu cho doanh nghiệp',
					'instructions'  => 'Chỉ áp dụng cho trang chủ, và tự động nhường quyền nếu cài Rank Math / Yoast.',
				),
				array(
					'key'           => 'field_nlw_o_seo_desc',
					'label'         => 'Meta description',
					'name'          => 'seo_desc',
					'type'          => 'textarea',
					'rows'          => 3,
					'default_value' => 'Nam làm WEB giúp doanh nghiệp xây dựng website phù hợp với khách hàng và mục tiêu kinh doanh — rõ ràng, dễ hiểu và đồng hành từ đầu đến khi bạn làm chủ website.',
				),
				array(
					'key'           => 'field_nlw_o_og_title',
					'label'         => 'OG title',
					'name'          => 'og_title',
					'type'          => 'text',
					'default_value' => 'Bạn sẽ hiểu website của mình trước khi sở hữu nó.',
				),
				array(
					'key'           => 'field_nlw_o_og_desc',
					'label'         => 'OG description',
					'name'          => 'og_desc',
					'type'          => 'textarea',
					'rows'          => 2,
					'default_value' => 'Hiểu đúng doanh nghiệp. Làm đúng website. Xây dựng niềm tin với khách hàng.',
				),
				array(
					'key'           => 'field_nlw_o_og_image',
					'label'         => 'Ảnh chia sẻ (OG image)',
					'name'          => 'og_image',
					'type'          => 'image',
					'return_format' => 'url',
					'preview_size'  => 'medium',
					'instructions'  => 'Khuyến nghị 1200×630px. Bỏ trống sẽ dùng logo.',
				),

				/* ---------- CTA ---------- */
				array(
					'key'   => 'field_nlw_o_tab_cta',
					'label' => 'CTA band',
					'name'  => '',
					'type'  => 'tab',
				),
				array(
					'key'           => 'field_nlw_o_cta_title',
					'label'         => 'Tiêu đề mặc định',
					'name'          => 'cta_title',
					'type'          => 'text',
					'default_value' => 'Bạn đang muốn làm một website?',
				),
				array(
					'key'           => 'field_nlw_o_cta_desc',
					'label'         => 'Mô tả mặc định',
					'name'          => 'cta_desc',
					'type'          => 'textarea',
					'rows'          => 3,
					'default_value' => "Không cần chuẩn bị sẵn mọi thứ. Hãy kể mình nghe một chút về doanh nghiệp của bạn.\nMình sẽ cùng bạn làm rõ website có thể giúp gì và nên bắt đầu từ đâu.",
				),
			),
		)
	);
}
add_action( 'acf/init', 'nlw_register_options_fields' );
