<?php
/**
 * Nhập dữ liệu mẫu: 4 nhóm dự án, 4 dịch vụ kèm 12 gói giá, và nội dung
 * cố định của trang chủ (3 lời hứa, 4 giá trị, 5 bước quy trình).
 *
 * Nội dung lấy nguyên văn từ bộ design handoff (pages/Service*.html, index.html).
 * Chạy một lần từ: Nam làm WEB → Dữ liệu mẫu. Bấm lại an toàn — mục đã có sẽ bỏ qua.
 *
 * @package nlw
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tiện ích: chuyển danh sách chuỗi thành các row của repeater một sub-field.
 *
 * @param array  $items Danh sách chuỗi.
 * @param string $key   Tên sub-field.
 * @return array
 */
function nlw_seed_simple_rows( $items, $key = 'label' ) {
	return array_map(
		function ( $item ) use ( $key ) {
			return array( $key => $item );
		},
		$items
	);
}

/**
 * Dữ liệu 4 dịch vụ và các gói.
 *
 * @return array
 */
function nlw_seed_services() {
	return array(

		array(
			'slug'          => 'website-theo-yeu-cau',
			'title'         => 'Website theo yêu cầu',
			'name_in_title' => 'Thiết kế Website theo yêu cầu',
			'short_desc'    => 'Một website được xây dựng riêng cho doanh nghiệp, dựa trên khách hàng, mục tiêu và cách bạn đang kinh doanh.',
			'price_from'    => '4000000',
			'hero_desc'     => 'Thiết kế website theo yêu cầu riêng, tinh chỉnh UI/UX hiện đại, đảm bảo tối ưu tốc độ và trải nghiệm người dùng. Mang đến giải pháp số hóa chuẩn Premium cho doanh nghiệp của bạn.',
			'menu_order'    => 1,
			'packages'      => array(
				array(
					'name'         => 'Thiết kế cá nhân',
					'tag'          => 'Dành cho cá nhân, nhỏ lẻ',
					'price_prefix' => '',
					'price'        => '4.000.000 - 8.000.000',
					'duration'     => '7 - 21 ngày',
					'popular'      => 0,
					'blurb'        => 'Giúp bạn nhanh chóng sở hữu website theo ý riêng, nhưng vẫn bảo đảm đẹp.',
					'features'     => array(
						'Giao diện cá nhân đẹp',
						'Phù hợp với mọi thiết bị',
						'Dễ quản lý bài viết',
						'Hỗ trợ khai báo Google',
						'Chi phí Hosting giá tiết kiệm',
						'Bảo hành & Bảo trì 3 tháng',
						'Có thể trao đổi tư vấn thêm …',
					),
				),
				array(
					'name'         => 'Yêu cầu doanh nghiệp',
					'tag'          => 'Thiết kế theo Figma doanh nghiệp có sẵn',
					'price_prefix' => '',
					'price'        => '9.000.000 - 24.000.000',
					'duration'     => '14 - 30 ngày',
					'popular'      => 1,
					'blurb'        => 'Tăng độ uy tín và nhận diện thương hiệu với website theo yêu cầu của doanh nghiệp.',
					'features'     => array(
						'Bám sát theo file thiết kế',
						'Tối ưu trải nghiệm người dùng',
						'Phù hợp xem trên đa thiết bị',
						'Tích hợp SEO và Google Analytics',
						'Miễn phí tên miền .com trong 1 năm',
						'Bảo hành & Bảo trì 6 tháng',
						'Có thể trao đổi tư vấn thêm …',
					),
				),
				array(
					'name'         => 'Yêu cầu chuyên nghiệp',
					'tag'          => 'Uy tín cao, dễ dàng tiếp cận khách hàng',
					'price_prefix' => 'chỉ từ',
					'price'        => '25.000.000',
					'duration'     => '14 - 60 ngày',
					'popular'      => 0,
					'blurb'        => 'Tăng lượt tiếp cận cực lớn với khách hàng, giúp giữ chân khách hàng được tốt hơn.',
					'features'     => array(
						'Bám sát theo file thiết kế',
						'Hỗ trợ tích hợp bên thứ 3',
						'Mở rộng và nâng cấp website dễ dàng',
						'Tốc độ tải nhanh <3s',
						'Bảo hành & Bảo trì 1 năm',
						'Đồng hành cùng doanh nghiệp',
						'Có thể trao đổi tư vấn thêm …',
					),
				),
			),
		),

		array(
			'slug'          => 'website-ban-hang',
			'title'         => 'Website bán hàng',
			'name_in_title' => 'Thiết kế Website bán hàng',
			'short_desc'    => 'Giúp khách hàng dễ tìm hiểu sản phẩm, dễ lựa chọn và dễ thực hiện bước tiếp theo.',
			'price_from'    => '4900000',
			'hero_desc'     => 'Tăng doanh thu bán hàng online vượt trội với trang web được thiết kế đẹp mắt và tối ưu trải nghiệm người dùng.',
			'menu_order'    => 2,
			'packages'      => array(
				array(
					'name'         => 'Store Startup',
					'tag'          => 'Dành cho cá nhân, shop nhỏ',
					'price_prefix' => 'chỉ từ',
					'price'        => '4.900.000',
					'duration'     => '5 - 10 ngày',
					'popular'      => 0,
					'blurb'        => 'Giúp bạn nhanh chóng sở hữu website bán hàng, tối ưu trải nghiệm người dùng để tăng trưởng doanh thu.',
					'features'     => array(
						'Giao diện bán hàng đẹp',
						'Phù hợp với mọi thiết bị',
						'Dễ quản lý sản phẩm & đơn hàng',
						'Tối ưu quy trình bán hàng',
						'Đăng ký Google Maps doanh nghiệp',
						'Chi phí Hosting giá tiết kiệm',
						'Bảo hành & Bảo trì 3 tháng',
					),
				),
				array(
					'name'         => 'Store Premium',
					'tag'          => 'Bán hàng chuyên nghiệp và nâng cao',
					'price_prefix' => '',
					'price'        => '8.900.000 - 15.900.000',
					'duration'     => '10 - 21 ngày',
					'popular'      => 1,
					'blurb'        => 'Tăng độ uy tín và nhận diện thương hiệu với website bán hàng chuyên nghiệp, tối ưu trải nghiệm người dùng và chuẩn SEO.',
					'features'     => array(
						'Các tính năng Store Startup',
						'Giao diện bán hàng đẹp và hiện đại',
						'Hỗ trợ đăng tải dưới 60 sản phẩm',
						'Kết nối hệ thống vận chuyển GHTK',
						'Tích hợp SEO và Google Analytics',
						'Hỗ trợ khai báo website với Google',
						'Miễn phí tên miền .com trong 1 năm',
						'Bảo hành & Bảo trì 6 tháng',
					),
				),
				array(
					'name'         => 'Store Professional',
					'tag'          => 'Uy tín cao, nhận diện thương hiệu vượt trội',
					'price_prefix' => 'chỉ từ',
					'price'        => '20.000.000',
					'duration'     => '14 - 30 ngày',
					'popular'      => 0,
					'blurb'        => 'Doanh thu bán hàng online vượt trội với website được thiết kế độc quyền, tối ưu trải nghiệm người dùng và chuẩn SEO.',
					'features'     => array(
						'Các tính năng Store Premium',
						'Giao diện bán hàng độc quyền và hiện đại',
						'Hỗ trợ đăng tải dưới 180 sản phẩm',
						'Tính giá vận chuyển tự động',
						'Tích hợp thanh toán online',
						'Mở rộng và nâng cấp website dễ dàng',
						'Bảo hành & Bảo trì 1 năm',
						'Đồng hành cùng doanh nghiệp',
					),
				),
			),
		),

		array(
			'slug'          => 'website-doanh-nghiep',
			'title'         => 'Website doanh nghiệp',
			'name_in_title' => 'Thiết kế Website doanh nghiệp',
			'short_desc'    => 'Một nơi để khách hàng hiểu doanh nghiệp của bạn là ai, đang làm gì và vì sao họ có thể tin tưởng.',
			'price_from'    => '6000000',
			'hero_desc'     => 'Giải pháp chuyên nghiệp giúp doanh nghiệp khẳng định vị thế và phát triển bền vững.',
			'menu_order'    => 3,
			'packages'      => array(
				array(
					'name'         => 'Business Startup',
					'tag'          => 'Tăng độ uy tín, tiết kiệm chi phí',
					'price_prefix' => 'chỉ từ',
					'price'        => '6.000.000',
					'duration'     => '7 - 14 ngày',
					'popular'      => 0,
					'blurb'        => 'Mỗi website được phát triển theo định hướng khách hàng mong muốn, tùy chỉnh đồng bộ cùng nội dung, hình ảnh và màu sắc nhận diện.',
					'features'     => array(
						'Giao diện thiết kế theo yêu cầu',
						'Tối ưu, hiển thị đẹp trên mọi thiết bị',
						'Triển khai linh hoạt, từ 7 đến 14 ngày',
						'Chứng chỉ SSL và bảo mật',
						'Đăng ký Google Maps doanh nghiệp',
						'Chi phí Hosting giá tiết kiệm',
						'Bảo hành & Bảo trì 3 tháng',
					),
				),
				array(
					'name'         => 'Business Premium',
					'tag'          => 'Độ uy tín cao, nhận diện thương hiệu',
					'price_prefix' => '',
					'price'        => '12.000.000 - 19.000.000',
					'duration'     => '7 - 21 ngày',
					'popular'      => 1,
					'blurb'        => 'Website được thiết kế tối ưu, đồng bộ nội dung, hình ảnh và màu sắc, giúp doanh nghiệp khẳng định vị thế và ghi dấu thương hiệu.',
					'features'     => array(
						'Các tính năng gói Startup',
						'Giao diện thiết kế UX/UI',
						'Hỗ trợ khai báo Google',
						'Hệ thống dễ mở rộng và nâng cấp',
						'Có bảng quản trị Website',
						'Chi phí Hosting giá ưu đãi',
						'Bảo hành & Bảo trì 6 tháng',
					),
				),
				array(
					'name'         => 'Business Professional',
					'tag'          => 'Giải pháp tùy biến hoàn toàn',
					'price_prefix' => 'chỉ từ',
					'price'        => '20.000.000',
					'duration'     => '14 - 30 ngày',
					'popular'      => 0,
					'blurb'        => 'Website được xây dựng độc quyền, tùy chỉnh theo phong cách, nội dung và màu sắc doanh nghiệp, tạo dấu ấn khác biệt.',
					'features'     => array(
						'Các tính năng gói Premium',
						'Giao diện thiết kế độc quyền',
						'Website kết nối với bên thứ 3',
						'Hệ thống dễ mở rộng và nâng cấp',
						'Miễn phí Hosting & tên miền .com trong 1 năm',
						'Hỗ trợ kỹ thuật cho doanh nghiệp',
						'Bảo hành & Bảo trì 1 năm',
					),
				),
			),
		),

		array(
			'slug'          => 'landing-page',
			'title'         => 'Landing Page',
			'name_in_title' => 'Thiết kế Landing Page',
			'short_desc'    => 'Trang đích tăng khách hàng và lợi nhuận hiệu quả.',
			'price_from'    => '2000000',
			'hero_desc'     => 'Giúp doanh nghiệp tăng khách hàng và lợi nhuận hiệu quả.',
			'menu_order'    => 4,
			'packages'      => array(
				array(
					'name'         => 'Landing Basic',
					'tag'          => 'Tối ưu chuyển đổi cho trang của bạn',
					'price_prefix' => 'chỉ từ',
					'price'        => '2.000.000',
					'duration'     => '2 - 4 ngày',
					'popular'      => 0,
					'blurb'        => 'Chuyển đổi tối ưu trang đích với cơ sở thiết kế chuyên nghiệp và phân tích hợp lý.',
					'features'     => array(
						'Làm theo thiết kế có sẵn',
						'Phù hợp với mọi thiết bị',
						'Tốc độ tải trang nhanh (<3s)',
						'SEO Onpage cơ bản',
						'Quản trị & chỉnh sửa đơn giản',
						'Hosting với chi phí thấp',
					),
				),
				array(
					'name'         => 'Landing Premium',
					'tag'          => 'Giải pháp toàn diện cho doanh nghiệp',
					'price_prefix' => '',
					'price'        => '4.000.000 - 7.000.000',
					'duration'     => '4 - 7 ngày',
					'popular'      => 1,
					'blurb'        => 'Landing Page chuẩn chuyển đổi, tối ưu chuyển đổi vượt trội.',
					'features'     => array(
						'Các tính năng gói Basic',
						'Thiết kế UI/UX mới',
						'Cài đặt Google Analytics',
						'SEO Onpage nâng cao',
						'Có thể thêm 1 ngôn ngữ khác',
						'Miễn phí Hosting 3 tháng',
						'Đồng hành cùng doanh nghiệp',
					),
				),
				array(
					'name'         => 'Landing Professional',
					'tag'          => 'Giải pháp tùy chỉnh hoàn toàn',
					'price_prefix' => 'chỉ từ',
					'price'        => '8.000.000',
					'duration'     => '7 - 14 ngày',
					'popular'      => 0,
					'blurb'        => 'Landing Page chuyên nghiệp với tích hợp & tùy chỉnh đặc biệt.',
					'features'     => array(
						'Các tính năng gói Premium',
						'Thiết kế UX/UI độc quyền',
						'SEO Onpage đầy đủ',
						'Tốc độ tải trang siêu nhanh (<2s)',
						'Giảm 500k khi làm thêm website',
						'Miễn phí Hosting & tên miền .com trong 1 năm',
						'Hỗ trợ kỹ thuật cho doanh nghiệp',
					),
				),
			),
		),
	);
}

/**
 * Nội dung cố định của trang chủ.
 *
 * @return array
 */
function nlw_seed_home_content() {
	return array(
		'promises' => array(
			array( 'title' => 'Rõ ràng', 'desc' => 'Báo giá và quy trình minh bạch từ đầu.' ),
			array( 'title' => 'Dễ hiểu', 'desc' => 'Giải thích bằng ngôn ngữ đời thường.' ),
			array( 'title' => 'Đồng hành', 'desc' => 'Ở cạnh bạn đến khi website chạy tốt.' ),
		),
		'values'   => array(
			array( 'title' => 'Rõ ràng', 'desc' => 'Bạn biết mình đang làm gì, vì sao cần làm và mình sẽ nhận được gì.' ),
			array( 'title' => 'Dễ hiểu', 'desc' => 'Không cần rành công nghệ. Mình giải thích website bằng ngôn ngữ dễ hiểu.' ),
			array( 'title' => 'Tận tâm', 'desc' => 'Không chỉ làm xong rồi bàn giao. Mình đồng hành để bạn có thể làm chủ website của mình.' ),
			array( 'title' => 'Thực tế', 'desc' => 'Không cố làm thật nhiều. Mình chỉ làm những gì thực sự cần cho doanh nghiệp của bạn.' ),
		),
		// Nhãn phụ (sub) để trống: Content Spec chỉ cung cấp tên bước và mô tả.
		'process'  => array(
			array(
				'title' => 'Hiểu doanh nghiệp',
				'sub'   => '',
				'desc'  => 'Mình tìm hiểu công việc kinh doanh, khách hàng và điều bạn muốn website giúp mình đạt được.',
			),
			array(
				'title' => 'Làm rõ website cần làm gì',
				'sub'   => '',
				'desc'  => 'Cùng xác định cấu trúc, nội dung và những điều khách hàng cần biết trước khi đưa ra quyết định.',
			),
			array(
				'title' => 'Xây dựng website',
				'sub'   => '',
				'desc'  => 'Từ những gì đã thống nhất, mình thiết kế và phát triển website phù hợp với doanh nghiệp.',
			),
			array(
				'title' => 'Kiểm tra cùng bạn',
				'sub'   => '',
				'desc'  => 'Cùng xem lại nội dung, trải nghiệm và các chức năng để đảm bảo website hoạt động đúng như mong muốn.',
			),
			array(
				'title' => 'Bàn giao & đồng hành',
				'sub'   => '',
				'desc'  => 'Bạn nhận website, tài khoản và những hướng dẫn cần thiết để có thể tự mình quản lý và sử dụng website.',
			),
		),
	);
}

/**
 * Menu con "Dữ liệu mẫu".
 *
 * Priority 100 để chạy sau khi SCF đã tạo menu cha `nlw-options`.
 */
function nlw_seed_menu() {
	add_submenu_page(
		'nlw-options',
		'Dữ liệu mẫu',
		'Dữ liệu mẫu',
		'manage_options',
		'nlw-seed',
		'nlw_seed_page'
	);
}
add_action( 'admin_menu', 'nlw_seed_menu', 100 );

/**
 * Trang chạy nhập dữ liệu.
 */
function nlw_seed_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$done = false;
	$log  = array();

	if ( isset( $_POST['nlw_seed_run'] ) && check_admin_referer( 'nlw_seed' ) ) {
		$log  = nlw_seed_run();
		$done = true;
	}
	?>
	<div class="wrap">
		<h1>Dữ liệu mẫu</h1>
		<p>Tạo sẵn <strong>4 nhóm dự án</strong>, <strong>4 dịch vụ kèm 12 gói giá</strong> và
			<strong>nội dung cố định của trang chủ</strong> (3 lời hứa, 4 giá trị, 5 bước quy trình)
			đúng nội dung trong bộ thiết kế. Chạy nhiều lần cũng an toàn: mục đã tồn tại sẽ được bỏ qua.</p>

		<?php if ( ! function_exists( 'update_field' ) ) : ?>
			<div class="notice notice-error"><p>Chưa bật Secure Custom Fields — không thể ghi dữ liệu.</p></div>
		<?php endif; ?>

		<?php if ( $done ) : ?>
			<div class="notice notice-success">
				<p>Đã chạy xong.</p>
				<ul style="list-style:disc;margin-left:20px">
					<?php foreach ( $log as $line ) : ?>
						<li><?php echo esc_html( $line ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

		<form method="post">
			<?php wp_nonce_field( 'nlw_seed' ); ?>
			<?php submit_button( 'Nhập dữ liệu mẫu', 'primary', 'nlw_seed_run' ); ?>
		</form>
	</div>
	<?php
}

/**
 * Thực hiện nhập.
 *
 * @return array Nhật ký.
 */
function nlw_seed_run() {
	$log = array();

	if ( ! function_exists( 'update_field' ) ) {
		return array( 'Chưa bật Secure Custom Fields — đã dừng.' );
	}

	/* ---------- Nhóm dự án ---------- */
	$categories = array(
		'website-doanh-nghiep' => 'Website doanh nghiệp',
		'landing-page'         => 'Landing page',
		'website-dich-vu'      => 'Website dịch vụ',
		'website-ban-hang'     => 'Website bán hàng',
	);

	foreach ( $categories as $slug => $name ) {
		if ( term_exists( $slug, 'project_cat' ) ) {
			continue;
		}

		wp_insert_term( $name, 'project_cat', array( 'slug' => $slug ) );
		$log[] = 'Tạo nhóm dự án: ' . $name;
	}

	/* ---------- Dịch vụ + bảng giá ---------- */
	foreach ( nlw_seed_services() as $service ) {
		if ( get_page_by_path( $service['slug'], OBJECT, 'service' ) ) {
			$log[] = 'Bỏ qua dịch vụ đã có: ' . $service['title'];
			continue;
		}

		$service_id = wp_insert_post(
			array(
				'post_type'   => 'service',
				'post_status' => 'publish',
				'post_title'  => $service['title'],
				'post_name'   => $service['slug'],
				'menu_order'  => $service['menu_order'],
			)
		);

		if ( is_wp_error( $service_id ) || ! $service_id ) {
			$log[] = 'Lỗi khi tạo dịch vụ: ' . $service['title'];
			continue;
		}

		update_field( 'short_desc', $service['short_desc'], $service_id );
		update_field( 'price_from', $service['price_from'], $service_id );
		update_field( 'hero_desc', $service['hero_desc'], $service_id );
		update_field( 'name_in_title', $service['name_in_title'], $service_id );
		update_field(
			'chips',
			nlw_seed_simple_rows( array( 'Giải pháp toàn diện', 'Bảo mật thông tin', 'Hoạt động mượt mà' ) ),
			$service_id
		);

		$packages = array();

		foreach ( $service['packages'] as $package ) {
			$package['features'] = nlw_seed_simple_rows( $package['features'] );
			$package['cta_label'] = 'Tư vấn ngay';
			$packages[]           = $package;
		}

		update_field( 'packages', $packages, $service_id );

		$log[] = sprintf( 'Tạo dịch vụ "%s" kèm %d gói giá.', $service['title'], count( $packages ) );
	}

	/* ---------- Nội dung cố định trang chủ ---------- */
	foreach ( nlw_seed_home_content() as $key => $rows ) {
		$existing = get_field( $key, 'option' );

		if ( ! empty( $existing ) ) {
			$log[] = 'Bỏ qua "' . $key . '" vì đã có dữ liệu.';
			continue;
		}

		update_field( $key, $rows, 'option' );
		$log[] = sprintf( 'Điền "%s": %d dòng.', $key, count( $rows ) );
	}

	return $log;
}
