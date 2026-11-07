<?php
/**
 * Field groups cho SCF (Secure Custom Fields).
 *
 * Dùng đầy đủ Repeater / Gallery / Relationship. Options Page khai báo ở inc/options.php.
 *
 * @package nlw
 */

defined( 'ABSPATH' ) || exit;

/**
 * Đăng ký field group cho Dự án và Dịch vụ.
 */
function nlw_register_fields() {

	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	/* ---------------------------------------------------------------------
	 * DỰ ÁN
	 * ------------------------------------------------------------------- */
	acf_add_local_field_group(
		array(
			'key'      => 'group_nlw_project',
			'title'    => 'Thông tin dự án',
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'project',
					),
				),
			),
			'fields'   => array(

				array(
					'key'   => 'field_nlw_p_tab_overview',
					'label' => 'Tổng quan',
					'name'  => '',
					'type'  => 'tab',
				),
				array(
					'key'          => 'field_nlw_p_client',
					'label'        => 'Khách hàng',
					'name'         => 'client',
					'type'         => 'text',
					'instructions' => 'Hiển thị ở thẻ dự án và dải thông tin trang chi tiết.',
					'wrapper'      => array( 'width' => '25' ),
				),
				array(
					'key'     => 'field_nlw_p_industry',
					'label'   => 'Lĩnh vực',
					'name'    => 'industry',
					'type'    => 'text',
					'wrapper' => array( 'width' => '25' ),
				),
				array(
					'key'          => 'field_nlw_p_website_type',
					'label'        => 'Loại website',
					'name'         => 'website_type',
					'type'         => 'text',
					'instructions' => 'Ví dụ: Website giới thiệu, Landing page…',
					'wrapper'      => array( 'width' => '25' ),
				),
				array(
					'key'          => 'field_nlw_p_duration',
					'label'        => 'Thời gian thực hiện',
					'name'         => 'duration',
					'type'         => 'text',
					'instructions' => 'Ví dụ: 3 tuần.',
					'wrapper'      => array( 'width' => '25' ),
				),
				array(
					'key'     => 'field_nlw_p_year',
					'label'   => 'Năm hoàn thành',
					'name'    => 'year',
					'type'    => 'text',
					'wrapper' => array( 'width' => '25' ),
				),
				array(
					'key'     => 'field_nlw_p_url',
					'label'   => 'URL website',
					'name'    => 'website_url',
					'type'    => 'url',
					'wrapper' => array( 'width' => '50' ),
				),
				array(
					'key'          => 'field_nlw_p_featured',
					'label'        => 'Đưa lên showcase trang chủ',
					'name'         => 'featured',
					'type'         => 'true_false',
					'ui'           => 1,
					'instructions' => 'Trang chủ lấy tối đa 5 dự án được đánh dấu, sắp theo Thứ tự (Page Attributes).',
					'wrapper'      => array( 'width' => '25' ),
				),
				array(
					'key'           => 'field_nlw_p_hero_image',
					'label'         => 'Ảnh hero (16:8)',
					'name'          => 'hero_image',
					'type'          => 'image',
					'return_format' => 'id',
					'preview_size'  => 'medium',
					'instructions'  => 'Ảnh lớn đầu trang chi tiết. Ảnh đại diện (Featured image) dùng cho thẻ dự án ở lưới và showcase.',
				),

				array(
					'key'   => 'field_nlw_p_tab_problem',
					'label' => 'Vấn đề & Giải pháp',
					'name'  => '',
					'type'  => 'tab',
				),
				array(
					'key'          => 'field_nlw_p_problem',
					'label'        => 'Vấn đề của khách hàng',
					'name'         => 'problem_text',
					'type'         => 'wysiwyg',
					'toolbar'      => 'basic',
					'media_upload' => 0,
				),
				array(
					'key'   => 'field_nlw_p_sol_intro',
					'label' => 'Giới thiệu giải pháp',
					'name'  => 'solution_intro',
					'type'  => 'textarea',
					'rows'  => 3,
				),
				array(
					'key'          => 'field_nlw_p_solutions',
					'label'        => 'Các khối giải pháp',
					'name'         => 'solutions',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Thêm khối giải pháp',
					'instructions' => 'Thiết kế trình bày xen kẽ trái/phải: khối 1 chữ bên trái, khối 2 ảnh bên trái, và lặp lại.',
					'sub_fields'   => array(
						array(
							'key'     => 'field_nlw_p_sol_title',
							'label'   => 'Tiêu đề',
							'name'    => 'title',
							'type'    => 'text',
							'wrapper' => array( 'width' => '60' ),
						),
						array(
							'key'           => 'field_nlw_p_sol_image',
							'label'         => 'Ảnh (4:3)',
							'name'          => 'image',
							'type'          => 'image',
							'return_format' => 'id',
							'preview_size'  => 'thumbnail',
							'wrapper'       => array( 'width' => '40' ),
						),
						array(
							'key'   => 'field_nlw_p_sol_text',
							'label' => 'Mô tả',
							'name'  => 'text',
							'type'  => 'textarea',
							'rows'  => 4,
						),
					),
				),

				array(
					'key'   => 'field_nlw_p_tab_result',
					'label' => 'Kết quả',
					'name'  => '',
					'type'  => 'tab',
				),
				array(
					'key'          => 'field_nlw_p_results',
					'label'        => 'Kết quả sau khi hoàn thành',
					'name'         => 'results',
					'type'         => 'repeater',
					'layout'       => 'table',
					'button_label' => 'Thêm kết quả',
					'instructions' => 'Thiết kế trình bày lưới 2 cột — nên nhập 4 mục.',
					'sub_fields'   => array(
						array(
							'key'     => 'field_nlw_p_result_title',
							'label'   => 'Tiêu đề',
							'name'    => 'title',
							'type'    => 'text',
							'wrapper' => array( 'width' => '35' ),
						),
						array(
							'key'   => 'field_nlw_p_result_desc',
							'label' => 'Mô tả',
							'name'  => 'desc',
							'type'  => 'text',
						),
					),
				),

				array(
					'key'   => 'field_nlw_p_tab_gallery',
					'label' => 'Hình ảnh & liên kết',
					'name'  => '',
					'type'  => 'tab',
				),
				array(
					'key'           => 'field_nlw_p_gallery',
					'label'         => 'Thư viện ảnh',
					'name'          => 'gallery',
					'type'          => 'gallery',
					'return_format' => 'id',
					'preview_size'  => 'medium',
					'insert'        => 'append',
					'instructions'  => 'Thiết kế hiển thị lưới 2×2 — nên chọn 4 ảnh tỉ lệ 16:10.',
				),
				array(
					'key'           => 'field_nlw_p_rel_services',
					'label'         => 'Dịch vụ liên quan',
					'name'          => 'related_services',
					'type'          => 'relationship',
					'post_type'     => array( 'service' ),
					'filters'       => array( 'search' ),
					'max'           => 3,
					'return_format' => 'id',
				),
			),
		)
	);

	/* ---------------------------------------------------------------------
	 * DỊCH VỤ — gồm repeater bảng giá, lồng repeater tính năng
	 * ------------------------------------------------------------------- */
	acf_add_local_field_group(
		array(
			'key'      => 'group_nlw_service',
			'title'    => 'Thông tin dịch vụ',
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'service',
					),
				),
			),
			'fields'   => array(

				array(
					'key'   => 'field_nlw_s_tab_info',
					'label' => 'Giới thiệu',
					'name'  => '',
					'type'  => 'tab',
				),
				array(
					'key'           => 'field_nlw_s_icon',
					'label'         => 'Icon',
					'name'          => 'icon',
					'type'          => 'select',
					'choices'       => array(
						''         => 'Không dùng icon',
						'layout'   => 'Khung website',
						'cart'     => 'Giỏ hàng',
						'building' => 'Toà nhà (doanh nghiệp)',
						'target'   => 'Mục tiêu (landing page)',
						'chat'     => 'Trò chuyện',
						'square'   => 'Ô tích',
					),
					'default_value' => '',
					'instructions'  => 'Để trống thì thẻ không có icon. Hiển thị ở thẻ dịch vụ trên trang chủ và trang Dịch vụ.',
					'wrapper'       => array( 'width' => '30' ),
				),
				array(
					'key'          => 'field_nlw_s_short',
					'label'        => 'Mô tả ngắn (thẻ ở trang Dịch vụ)',
					'name'         => 'short_desc',
					'type'         => 'textarea',
					'rows'         => 2,
					'instructions' => 'Thiết kế giới hạn 46ch — giữ ngắn gọn một câu.',
				),
				array(
					'key'          => 'field_nlw_s_price_from',
					'label'        => 'Giá khởi điểm',
					'name'         => 'price_from',
					'type'         => 'text',
					'instructions' => 'Chỉ nhập số, ví dụ <code>4000000</code> — hệ thống tự định dạng thành 4.000.000đ.',
					'wrapper'      => array( 'width' => '35' ),
				),
				array(
					'key'          => 'field_nlw_s_name_short',
					'label'        => 'Tên dùng trong tiêu đề bảng giá',
					'name'         => 'name_in_title',
					'type'         => 'text',
					'instructions' => 'Ví dụ <code>Thiết kế Website theo yêu cầu</code>. Phần này hiển thị màu brand trong H1.',
					'wrapper'      => array( 'width' => '65' ),
				),
				array(
					'key'   => 'field_nlw_s_hero_desc',
					'label' => 'Mô tả ở đầu trang bảng giá',
					'name'  => 'hero_desc',
					'type'  => 'textarea',
					'rows'  => 3,
				),
				array(
					'key'          => 'field_nlw_s_chips',
					'label'        => 'Chip cam kết',
					'name'         => 'chips',
					'type'         => 'repeater',
					'layout'       => 'table',
					'button_label' => 'Thêm chip',
					'instructions' => 'Mặc định 3 chip: Giải pháp toàn diện / Bảo mật thông tin / Hoạt động mượt mà.',
					'sub_fields'   => array(
						array(
							'key'   => 'field_nlw_s_chip_label',
							'label' => 'Nội dung',
							'name'  => 'label',
							'type'  => 'text',
						),
					),
				),

				array(
					'key'   => 'field_nlw_s_tab_pricing',
					'label' => 'Bảng giá',
					'name'  => '',
					'type'  => 'tab',
				),
				array(
					'key'          => 'field_nlw_s_packages',
					'label'        => 'Các gói',
					'name'         => 'packages',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Thêm gói',
					'min'          => 1,
					'instructions' => 'Thiết kế trình bày 3 cột. Gói ở giữa nên bật “Gói phổ biến”.',
					'sub_fields'   => array(
						array(
							'key'      => 'field_nlw_k_name',
							'label'    => 'Tên gói',
							'name'     => 'name',
							'type'     => 'text',
							'required' => 1,
							'wrapper'  => array( 'width' => '50' ),
						),
						array(
							'key'          => 'field_nlw_k_popular',
							'label'        => 'Gói phổ biến',
							'name'         => 'popular',
							'type'         => 'true_false',
							'ui'           => 1,
							'instructions' => 'Có ribbon “Gói phổ biến”, viền brand và nút primary. Mỗi dịch vụ chỉ bật 1 gói.',
							'wrapper'      => array( 'width' => '50' ),
						),
						array(
							'key'          => 'field_nlw_k_tag',
							'label'        => 'Mô tả một dòng',
							'name'         => 'tag',
							'type'         => 'text',
							'instructions' => 'Ví dụ: Dành cho cá nhân, nhỏ lẻ.',
						),
						array(
							'key'          => 'field_nlw_k_price_prefix',
							'label'        => 'Tiền tố giá',
							'name'         => 'price_prefix',
							'type'         => 'text',
							'instructions' => 'Để trống, hoặc <code>chỉ từ</code> khi là giá sàn.',
							'wrapper'      => array( 'width' => '25' ),
						),
						array(
							'key'          => 'field_nlw_k_price',
							'label'        => 'Giá',
							'name'         => 'price',
							'type'         => 'text',
							'required'     => 1,
							'instructions' => 'Số (<code>4000000</code>) hoặc khoảng giá (<code>9.000.000 - 24.000.000</code>).',
							'wrapper'      => array( 'width' => '40' ),
						),
						array(
							'key'          => 'field_nlw_k_duration',
							'label'        => 'Thời gian thực hiện',
							'name'         => 'duration',
							'type'         => 'text',
							'instructions' => 'Ví dụ: 7 - 21 ngày.',
							'wrapper'      => array( 'width' => '35' ),
						),
						array(
							'key'   => 'field_nlw_k_blurb',
							'label' => 'Câu giới thiệu gói',
							'name'  => 'blurb',
							'type'  => 'textarea',
							'rows'  => 2,
						),
						array(
							'key'          => 'field_nlw_k_features',
							'label'        => 'Tính năng',
							'name'         => 'features',
							'type'         => 'repeater',
							'layout'       => 'table',
							'button_label' => 'Thêm tính năng',
							'sub_fields'   => array(
								array(
									'key'   => 'field_nlw_k_feature_label',
									'label' => 'Nội dung',
									'name'  => 'label',
									'type'  => 'text',
								),
							),
						),
						array(
							'key'           => 'field_nlw_k_cta',
							'label'         => 'Nhãn nút',
							'name'          => 'cta_label',
							'type'          => 'text',
							'default_value' => 'Tư vấn ngay',
							'wrapper'       => array( 'width' => '40' ),
						),
					),
				),

				array(
					'key'   => 'field_nlw_s_tab_projects',
					'label' => 'Dự án liên quan',
					'name'  => '',
					'type'  => 'tab',
				),
				array(
					'key'           => 'field_nlw_s_rel_projects',
					'label'         => 'Dự án liên quan',
					'name'          => 'related_projects',
					'type'          => 'relationship',
					'post_type'     => array( 'project' ),
					'taxonomy'      => array(),
					'filters'       => array( 'search', 'taxonomy' ),
					'max'           => 3,
					'return_format' => 'id',
					'instructions'  => 'Bỏ trống thì hệ thống tự lấy 3 dự án mới nhất.',
				),
			),
		)
	);
}
add_action( 'acf/init', 'nlw_register_fields' );

/**
 * Cảnh báo khi chưa cài SCF/ACF.
 */
function nlw_fields_notice() {
	if ( function_exists( 'acf_add_local_field_group' ) || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	echo '<div class="notice notice-warning"><p><strong>Nam làm WEB:</strong> Chưa phát hiện Secure Custom Fields (SCF). '
		. 'Toàn bộ nội dung của Dự án, Dịch vụ và trang Cấu hình sẽ không hoạt động cho tới khi bật plugin.</p></div>';
}
add_action( 'admin_notices', 'nlw_fields_notice' );
