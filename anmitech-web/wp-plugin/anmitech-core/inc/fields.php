<?php
/**
 * Nhóm trường SCF cho Dịch vụ và Dự án.
 *
 * MẤU CHỐT: mọi field group phải có 'show_in_rest' => 1.
 * Thiếu dòng đó thì REST API vẫn trả về bài viết nhưng khoá "acf" sẽ rỗng,
 * và Next.js không có gì để render.
 *
 * @package anmitech
 */

defined( 'ABSPATH' ) || exit;

add_action( 'acf/init', function () {

	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	/* ------------------------------------------------------------------
	 * DỊCH VỤ
	 * --------------------------------------------------------------- */
	acf_add_local_field_group( array(
		'key'          => 'group_anmi_service',
		'title'        => 'Thông tin dịch vụ',
		'show_in_rest' => 1,
		'location'     => array( array( array(
			'param' => 'post_type', 'operator' => '==', 'value' => 'service',
		) ) ),
		'fields' => array(

			array( 'key' => 'f_svc_tab_info', 'label' => 'Giới thiệu', 'name' => '', 'type' => 'tab' ),

			array(
				'key' => 'f_svc_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'select',
				'choices' => array(
					''         => 'Không dùng icon',
					'layout'   => 'Khung website',
					'cart'     => 'Giỏ hàng',
					'building' => 'Toà nhà',
					'target'   => 'Mục tiêu',
					'chat'     => 'Trò chuyện',
					'square'   => 'Ô tích',
				),
				'instructions' => 'Tên icon trong bộ hiệu ứng frontend. Để trống thì thẻ không có icon.',
				'wrapper' => array( 'width' => '30' ),
			),
			array(
				'key' => 'f_svc_short', 'label' => 'Mô tả ngắn', 'name' => 'short_desc',
				'type' => 'textarea', 'rows' => 2,
				'instructions' => 'Một câu, hiển thị ở thẻ dịch vụ. Giữ dưới 100 ký tự.',
				'wrapper' => array( 'width' => '70' ),
			),
			array(
				'key' => 'f_svc_price_from', 'label' => 'Giá khởi điểm', 'name' => 'price_from',
				'type' => 'number',
				'instructions' => 'Chỉ nhập số, ví dụ 4000000. Frontend tự định dạng thành 4.000.000đ.',
				'wrapper' => array( 'width' => '35' ),
			),
			array(
				'key' => 'f_svc_name_title', 'label' => 'Tên dùng trong tiêu đề bảng giá',
				'name' => 'name_in_title', 'type' => 'text',
				'instructions' => 'Ví dụ: Thiết kế Website doanh nghiệp',
				'wrapper' => array( 'width' => '65' ),
			),
			array(
				'key' => 'f_svc_hero', 'label' => 'Mô tả đầu trang', 'name' => 'hero_desc',
				'type' => 'textarea', 'rows' => 3,
			),
			array(
				'key' => 'f_svc_chips', 'label' => 'Chip cam kết', 'name' => 'chips',
				'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Thêm chip',
				'sub_fields' => array(
					array( 'key' => 'f_svc_chip_label', 'label' => 'Nội dung', 'name' => 'label', 'type' => 'text' ),
				),
			),

			array( 'key' => 'f_svc_tab_price', 'label' => 'Bảng giá', 'name' => '', 'type' => 'tab' ),

			array(
				'key' => 'f_svc_packages', 'label' => 'Các gói', 'name' => 'packages',
				'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Thêm gói', 'min' => 1,
				'instructions' => 'Frontend hiển thị 3 cột. Gói giữa nên bật "Gói phổ biến".',
				'sub_fields' => array(
					array( 'key' => 'f_pkg_name', 'label' => 'Tên gói', 'name' => 'name', 'type' => 'text', 'required' => 1, 'wrapper' => array( 'width' => '50' ) ),
					array( 'key' => 'f_pkg_popular', 'label' => 'Gói phổ biến', 'name' => 'popular', 'type' => 'true_false', 'ui' => 1, 'wrapper' => array( 'width' => '50' ) ),
					array( 'key' => 'f_pkg_tag', 'label' => 'Mô tả một dòng', 'name' => 'tag', 'type' => 'text' ),
					array( 'key' => 'f_pkg_prefix', 'label' => 'Tiền tố giá', 'name' => 'price_prefix', 'type' => 'text', 'instructions' => 'Để trống, hoặc nhập: chỉ từ', 'wrapper' => array( 'width' => '25' ) ),
					array( 'key' => 'f_pkg_price', 'label' => 'Giá', 'name' => 'price', 'type' => 'text', 'required' => 1, 'instructions' => 'Số hoặc khoảng giá', 'wrapper' => array( 'width' => '40' ) ),
					array( 'key' => 'f_pkg_duration', 'label' => 'Thời gian', 'name' => 'duration', 'type' => 'text', 'wrapper' => array( 'width' => '35' ) ),
					array( 'key' => 'f_pkg_blurb', 'label' => 'Câu giới thiệu', 'name' => 'blurb', 'type' => 'textarea', 'rows' => 2 ),
					array(
						'key' => 'f_pkg_features', 'label' => 'Tính năng', 'name' => 'features',
						'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Thêm tính năng',
						'sub_fields' => array(
							array( 'key' => 'f_pkg_feat_label', 'label' => 'Nội dung', 'name' => 'label', 'type' => 'text' ),
						),
					),
					array( 'key' => 'f_pkg_cta', 'label' => 'Nhãn nút', 'name' => 'cta_label', 'type' => 'text', 'default_value' => 'Tư vấn ngay', 'wrapper' => array( 'width' => '40' ) ),
				),
			),

			array( 'key' => 'f_svc_tab_rel', 'label' => 'Liên kết', 'name' => '', 'type' => 'tab' ),

			array(
				'key' => 'f_svc_rel_projects', 'label' => 'Dự án liên quan', 'name' => 'related_projects',
				'type' => 'relationship', 'post_type' => array( 'project' ),
				'filters' => array( 'search', 'taxonomy' ), 'max' => 3, 'return_format' => 'id',
				'instructions' => 'Bỏ trống thì frontend tự lấy 3 dự án mới nhất.',
			),
			array(
				'key' => 'f_svc_seo_desc', 'label' => 'Meta description', 'name' => 'seo_desc',
				'type' => 'textarea', 'rows' => 2,
				'instructions' => 'Bỏ trống thì dùng Mô tả đầu trang.',
			),
		),
	) );

	/* ------------------------------------------------------------------
	 * DỰ ÁN
	 * --------------------------------------------------------------- */
	acf_add_local_field_group( array(
		'key'          => 'group_anmi_project',
		'title'        => 'Thông tin dự án',
		'show_in_rest' => 1,
		'location'     => array( array( array(
			'param' => 'post_type', 'operator' => '==', 'value' => 'project',
		) ) ),
		'fields' => array(

			array( 'key' => 'f_prj_tab_over', 'label' => 'Tổng quan', 'name' => '', 'type' => 'tab' ),

			array( 'key' => 'f_prj_client', 'label' => 'Khách hàng', 'name' => 'client', 'type' => 'text', 'wrapper' => array( 'width' => '25' ) ),
			array( 'key' => 'f_prj_industry', 'label' => 'Lĩnh vực', 'name' => 'industry', 'type' => 'text', 'wrapper' => array( 'width' => '25' ) ),
			array( 'key' => 'f_prj_type', 'label' => 'Loại website', 'name' => 'website_type', 'type' => 'text', 'wrapper' => array( 'width' => '25' ) ),
			array( 'key' => 'f_prj_duration', 'label' => 'Thời gian thực hiện', 'name' => 'duration', 'type' => 'text', 'wrapper' => array( 'width' => '25' ) ),
			array( 'key' => 'f_prj_year', 'label' => 'Năm hoàn thành', 'name' => 'year', 'type' => 'text', 'wrapper' => array( 'width' => '25' ) ),
			array( 'key' => 'f_prj_url', 'label' => 'URL website', 'name' => 'website_url', 'type' => 'url', 'wrapper' => array( 'width' => '50' ) ),
			array(
				'key' => 'f_prj_featured', 'label' => 'Đưa lên trang chủ', 'name' => 'featured',
				'type' => 'true_false', 'ui' => 1, 'wrapper' => array( 'width' => '25' ),
				'instructions' => 'Trang chủ lấy tối đa 5 dự án được đánh dấu.',
			),
			array(
				'key' => 'f_prj_hero_img', 'label' => 'Ảnh hero (16:8)', 'name' => 'hero_image',
				'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium',
				'instructions' => 'Ảnh đại diện (Featured image) dùng cho thẻ ở lưới.',
			),

			array( 'key' => 'f_prj_tab_case', 'label' => 'Nội dung case study', 'name' => '', 'type' => 'tab' ),

			array( 'key' => 'f_prj_problem', 'label' => 'Vấn đề của khách hàng', 'name' => 'problem_text', 'type' => 'textarea', 'rows' => 4 ),
			array( 'key' => 'f_prj_sol_intro', 'label' => 'Giới thiệu giải pháp', 'name' => 'solution_intro', 'type' => 'textarea', 'rows' => 3 ),
			array(
				'key' => 'f_prj_solutions', 'label' => 'Các khối giải pháp', 'name' => 'solutions',
				'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Thêm khối',
				'instructions' => 'Frontend trình bày xen kẽ trái/phải theo thứ tự.',
				'sub_fields' => array(
					array( 'key' => 'f_prj_sol_title', 'label' => 'Tiêu đề', 'name' => 'title', 'type' => 'text', 'wrapper' => array( 'width' => '60' ) ),
					array( 'key' => 'f_prj_sol_img', 'label' => 'Ảnh (4:3)', 'name' => 'image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'thumbnail', 'wrapper' => array( 'width' => '40' ) ),
					array( 'key' => 'f_prj_sol_text', 'label' => 'Mô tả', 'name' => 'text', 'type' => 'textarea', 'rows' => 4 ),
				),
			),
			array(
				'key' => 'f_prj_results', 'label' => 'Kết quả', 'name' => 'results',
				'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Thêm kết quả',
				'instructions' => 'Chỉ ghi kết quả có thật. Không bịa số liệu.',
				'sub_fields' => array(
					array( 'key' => 'f_prj_res_title', 'label' => 'Tiêu đề', 'name' => 'title', 'type' => 'text', 'wrapper' => array( 'width' => '35' ) ),
					array( 'key' => 'f_prj_res_desc', 'label' => 'Mô tả', 'name' => 'desc', 'type' => 'text' ),
				),
			),
			array(
				'key' => 'f_prj_gallery', 'label' => 'Thư viện ảnh', 'name' => 'gallery',
				'type' => 'gallery', 'return_format' => 'array', 'preview_size' => 'medium', 'insert' => 'append',
			),
			array(
				'key' => 'f_prj_rel_services', 'label' => 'Dịch vụ liên quan', 'name' => 'related_services',
				'type' => 'relationship', 'post_type' => array( 'service' ),
				'filters' => array( 'search' ), 'max' => 3, 'return_format' => 'id',
			),
		),
	) );
} );
