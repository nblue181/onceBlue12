<?php
/**
 * Các section của design system, đóng gói thành shortcode để đặt vào UX Builder.
 *
 * Cách dùng trong Flatsome: thêm một element "Text" (hoặc "HTML"), dán shortcode.
 * Mỗi shortcode tự render đủ container — không đặt thêm Row/Column bao ngoài.
 *
 * @package nlw
 */

defined( 'ABSPATH' ) || exit;

/* =========================================================================
 * TRANG CHỦ
 * ====================================================================== */

/**
 * Hero trang chủ — nền gradient thương hiệu (chỉ dùng duy nhất ở đây).
 *
 * @return string
 */
function nlw_sc_hero() {
	$image = nlw_field( 'hero_image', 'option' );

	ob_start();
	?>
	<section class="nlw-hero">
		<div class="nlw-wrap nlw-hero-grid<?php echo $image ? ' has-image' : ''; ?>">
			<div class="nlw-hero-main">
				<p class="nlw-eyebrow"><?php echo esc_html( nlw_opt( 'hero_eyebrow' ) ); ?></p>
				<h1><?php echo esc_html( nlw_opt( 'hero_title' ) ); ?></h1>
				<p class="nlw-hero-desc"><?php echo nl2br( esc_html( nlw_opt( 'hero_desc' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>

				<div class="nlw-hero-cta">
					<?php
					echo nlw_button( 'Nhắn Zalo', nlw_contact_url( 'zalo' ), 'onbrand', array( 'icon' => 'zalo', 'size' => 'lg', 'target' => '_blank' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
					echo nlw_button( 'Khám phá dịch vụ', nlw_page_url( 'page_services' ), 'white-out', array( 'icon_after' => 'arr', 'size' => 'lg' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
					?>
				</div>

				<?php // Không kèm icon: microcopy hiện là "Tư vấn miễn phí · Không ràng buộc", không còn nhắc Messenger. ?>
				<p class="nlw-hero-note"><?php echo esc_html( nlw_opt( 'hero_note' ) ); ?></p>
			</div>

			<?php if ( $image ) : ?>
				<div class="nlw-hero-media">
					<?php echo nlw_image( $image, 'nlw-r-4x5', 'Ảnh', 'large' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'nlw_hero', 'nlw_sc_hero' );

/**
 * Khối giới thiệu ngắn (lưới bất đối xứng .4fr/.6fr).
 *
 * @param array  $atts    label, title, quote.
 * @param string $content Các đoạn văn, mỗi đoạn một dòng.
 * @return string
 */
function nlw_sc_intro( $atts, $content = '' ) {
	$atts = shortcode_atts(
		array(
			'label'   => 'Giới thiệu',
			'title'   => '',
			'quote'   => '',
			'cta'     => '',
			'cta_url' => '',
			'size'    => '', // "small" → tiêu đề nhỏ hơn một cấp.
			'bg'      => '', // "alt" → nền xám nhạt, dùng khi section liền trước cũng nền trắng.
		),
		$atts,
		'nlw_intro'
	);

	$blocks = nlw_content_blocks( $content );

	ob_start();
	?>
	<section class="nlw-section nlw-intro<?php echo 'small' === $atts['size'] ? ' is-small' : ''; ?><?php echo 'alt' === $atts['bg'] ? ' nlw-alt' : ''; ?>">
		<div class="nlw-wrap nlw-intro-grid">
			<div class="nlw-intro-side nlw-rv"><?php echo esc_html( $atts['label'] ); ?></div>
			<div class="nlw-rv">
				<?php if ( $atts['title'] ) : ?>
					<?php
					/*
					 * title="Phần nhỏ|Phần chính" → dòng đầu nhỏ hơn, dòng sau là trọng tâm.
					 * Không có "|" thì hiển thị như tiêu đề bình thường.
					 */
					$nlw_title = array_map( 'trim', explode( '|', $atts['title'], 2 ) );
					?>
					<h2>
						<?php if ( isset( $nlw_title[1] ) && '' !== $nlw_title[1] ) : ?>
							<span class="nlw-h-small"><?php echo esc_html( $nlw_title[0] ); ?></span>
							<?php echo esc_html( $nlw_title[1] ); ?>
						<?php else : ?>
							<?php echo esc_html( $nlw_title[0] ); ?>
						<?php endif; ?>
					</h2>
				<?php endif; ?>

				<?php echo nlw_render_blocks( $blocks ); // phpcs:ignore WordPress.Security.EscapeOutput ?>

				<?php if ( $atts['quote'] ) : ?>
					<div class="nlw-quote"><?php echo esc_html( $atts['quote'] ); ?></div>
				<?php endif; ?>

				<?php if ( $atts['cta'] ) : ?>
					<a class="nlw-more nlw-intro-cta" href="<?php echo esc_url( $atts['cta_url'] ?: nlw_page_url( 'page_about' ) ); ?>">
						<?php echo esc_html( $atts['cta'] ); ?><?php echo nlw_icon( 'arr' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'nlw_intro', 'nlw_sc_intro' );

/**
 * Dịch vụ nổi bật — 3 thẻ đánh số trên nền surface-alt.
 *
 * @param array $atts eyebrow, title, lead, count.
 * @return string
 */
function nlw_sc_featured_services( $atts ) {
	$atts = shortcode_atts(
		array(
			'eyebrow' => 'Dịch vụ',
			'title'   => 'Website phù hợp với điều doanh nghiệp của bạn thực sự cần',
			'lead'    => 'Không phải doanh nghiệp nào cũng cần cùng một kiểu website. Mình cùng bạn xác định website cần phục vụ việc gì trước, rồi mới chọn cách xây dựng phù hợp.',
			'count'   => 3,
		),
		$atts,
		'nlw_featured_services'
	);

	$services = get_posts(
		array(
			'post_type'      => 'service',
			'posts_per_page' => (int) $atts['count'],
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		)
	);

	if ( ! $services ) {
		return '';
	}

	ob_start();
	?>
	<section class="nlw-section nlw-alt">
		<div class="nlw-wrap">
			<div class="nlw-head nlw-rv">
				<p class="nlw-eyebrow"><?php echo esc_html( $atts['eyebrow'] ); ?></p>
				<h2><?php echo esc_html( $atts['title'] ); ?></h2>
				<?php if ( $atts['lead'] ) : ?>
					<p class="nlw-lead"><?php echo esc_html( $atts['lead'] ); ?></p>
				<?php endif; ?>
			</div>

			<div class="nlw-svc">
				<?php foreach ( $services as $i => $service ) : ?>
					<?php
					// Không có icon mặc định — để trống cho tới khi được chọn trong admin.
					$nlw_icon = nlw_field( 'icon', $service->ID );
					?>
					<div class="nlw-card nlw-rv">
						<?php if ( $nlw_icon ) : ?>
							<span class="nlw-card-ico"><?php echo nlw_icon( $nlw_icon ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<?php endif; ?>
						<div class="nlw-card-no"><?php echo esc_html( str_pad( $i + 1, 2, '0', STR_PAD_LEFT ) ); ?></div>
						<h3><?php echo esc_html( get_the_title( $service ) ); ?></h3>
						<p><?php echo esc_html( nlw_field( 'short_desc', $service->ID ) ); ?></p>
						<a class="nlw-more" href="<?php echo esc_url( get_permalink( $service ) ); ?>">
							Tìm hiểu thêm<?php echo nlw_icon( 'arr' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</a>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'nlw_featured_services', 'nlw_sc_featured_services' );

/**
 * Showcase dự án — section cao 440vh, sticky, đổi dự án theo tiến độ cuộn.
 * Trên ≤900px tự chuyển thành các thẻ xếp dọc (xử lý trong CSS + JS).
 *
 * @param array $atts eyebrow, title, count.
 * @return string
 */
function nlw_sc_projects_showcase( $atts ) {
	$atts = shortcode_atts(
		array(
			'eyebrow' => 'Dự án tiêu biểu',
			'title'   => 'Một số website mình đã thực hiện.',
			'lead'    => 'Mỗi dự án bắt đầu từ một nhu cầu khác nhau. Xem cách mình giải quyết từng bài toán qua những website thực tế.',
			'count'   => 5,
		),
		$atts,
		'nlw_projects_showcase'
	);

	$projects = get_posts(
		array(
			'post_type'      => 'project',
			'posts_per_page' => (int) $atts['count'],
			'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery
				array(
					'key'     => 'featured',
					'value'   => '1',
					'compare' => '=',
				),
			),
		)
	);

	if ( ! $projects ) {
		$projects = get_posts(
			array(
				'post_type'      => 'project',
				'posts_per_page' => (int) $atts['count'],
				'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
			)
		);
	}

	if ( ! $projects ) {
		return '';
	}

	ob_start();
	?>
	<section class="nlw-pjx" data-nlw-showcase>
		<div class="nlw-pjx-sticky">
			<div class="nlw-pjx-head">
				<div class="nlw-wrap">
					<p class="nlw-eyebrow"><?php echo esc_html( $atts['eyebrow'] ); ?></p>
					<h2><?php echo esc_html( $atts['title'] ); ?></h2>
					<?php if ( $atts['lead'] ) : ?>
						<p class="nlw-pjx-lead"><?php echo esc_html( $atts['lead'] ); ?></p>
					<?php endif; ?>
				</div>
			</div>

			<div class="nlw-pjx-stage">
				<?php foreach ( $projects as $i => $project ) : ?>
					<?php
					$terms = get_the_terms( $project, 'project_cat' );
					$cat   = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
					$thumb = get_the_post_thumbnail_url( $project, 'large' );
					$url   = nlw_field( 'website_url', $project->ID );
					?>
					<article class="nlw-pjx-card<?php echo 0 === $i ? ' is-on' : ''; ?>">
						<div class="nlw-pjx-shot"<?php echo $thumb ? ' style="background-image:url(' . esc_url( $thumb ) . ')"' : ''; ?>>
							<?php echo $thumb ? '' : 'Ảnh dự án'; ?>
						</div>
						<div class="nlw-pjx-over">
							<?php if ( $cat ) : ?>
								<span class="nlw-pjx-cat"><?php echo esc_html( $cat ); ?></span>
							<?php endif; ?>
							<h3><a href="<?php echo esc_url( get_permalink( $project ) ); ?>"><?php echo esc_html( get_the_title( $project ) ); ?></a></h3>
							<p><?php echo esc_html( get_the_excerpt( $project ) ); ?></p>
							<?php if ( $url ) : ?>
								<span class="nlw-pjx-url"><?php echo esc_html( $url ); ?></span>
							<?php endif; ?>
						</div>
					</article>
				<?php endforeach; ?>

				<div class="nlw-pjx-rail" aria-hidden="true"></div>
			</div>

			<div class="nlw-pjx-dots"></div>
			<div class="nlw-pjx-count">Cuộn để xem các dự án</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'nlw_projects_showcase', 'nlw_sc_projects_showcase' );

/**
 * Vì sao chọn — dải tối, 4 ô cách nhau 1px.
 *
 * @param array $atts eyebrow, title.
 * @return string
 */
function nlw_sc_values( $atts ) {
	$atts = shortcode_atts(
		array(
			'eyebrow' => 'Vì sao mình?',
			'title'   => 'Bốn điều mình luôn giữ trong mỗi dự án.',
			// Mặc định nền sáng để cắt mạch tối giữa hai section cuộn dài.
			// Muốn về nền tối như cũ: [nlw_values theme="dark"]
			'theme'   => 'light',
		),
		$atts,
		'nlw_values'
	);

	$values = nlw_rows( 'values', 'option' );

	if ( ! $values ) {
		return '';
	}

	ob_start();
	?>
	<section class="nlw-section nlw-values<?php echo 'dark' === $atts['theme'] ? '' : ' is-light'; ?>">
		<div class="nlw-wrap">
			<div class="nlw-head nlw-rv nlw-head-tight">
				<p class="nlw-eyebrow"><?php echo esc_html( $atts['eyebrow'] ); ?></p>
				<h2><?php echo esc_html( $atts['title'] ); ?></h2>
			</div>

			<div class="nlw-vgrid">
				<?php foreach ( $values as $i => $row ) : ?>
					<?php $nlw_icon = nlw_row( $row, 'icon' ); ?>
					<div class="nlw-v nlw-rv">
						<?php if ( $nlw_icon ) : ?>
							<span class="nlw-v-ico"><?php echo nlw_icon( $nlw_icon ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<?php endif; ?>
						<div class="nlw-v-n"><?php echo esc_html( str_pad( $i + 1, 2, '0', STR_PAD_LEFT ) ); ?></div>
						<h4><?php echo esc_html( nlw_row( $row, 'title' ) ); ?></h4>
						<p><?php echo esc_html( nlw_row( $row, 'desc' ) ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'nlw_values', 'nlw_sc_values' );

/**
 * Quy trình 5 bước — timeline ngang chạy theo cuộn dọc.
 *
 * @param array $atts eyebrow, title, lead.
 * @return string
 */
function nlw_sc_process( $atts ) {
	$atts = shortcode_atts(
		array(
			'eyebrow' => 'Cách mình làm việc',
			'title'   => 'Hiểu trước. Làm đúng. Đồng hành đến khi bạn làm chủ website.',
			'lead'    => 'Bạn không cần phải biết sẵn mình cần gì. Mình sẽ cùng bạn đi qua từng bước để mọi thứ được làm rõ trước khi bắt tay vào xây dựng.',
		),
		$atts,
		'nlw_process'
	);

	$steps = nlw_rows( 'process', 'option' );

	if ( ! $steps ) {
		return '';
	}

	ob_start();
	?>
	<section class="nlw-proc" data-nlw-process>
		<div class="nlw-proc-sticky">
			<div class="nlw-proc-head">
				<p class="nlw-eyebrow"><?php echo esc_html( $atts['eyebrow'] ); ?></p>
				<h2><?php echo esc_html( $atts['title'] ); ?></h2>
				<?php if ( $atts['lead'] ) : ?>
					<p><?php echo esc_html( $atts['lead'] ); ?></p>
				<?php endif; ?>
			</div>

			<div class="nlw-tl-view">
				<div class="nlw-tl-track">
					<div class="nlw-tl-line"><span class="nlw-tl-fill"></span></div>

					<?php foreach ( $steps as $i => $step ) : ?>
						<div class="nlw-tl-item">
							<div class="nlw-tl-node"><?php echo esc_html( $i + 1 ); ?></div>
							<div class="nlw-tl-card">
								<div class="nlw-tl-top">
									<h4><?php echo esc_html( nlw_row( $step, 'title' ) ); ?></h4>
								</div>
								<?php if ( nlw_row( $step, 'sub' ) ) : ?>
									<div class="nlw-tl-sub"><?php echo esc_html( nlw_row( $step, 'sub' ) ); ?></div>
								<?php endif; ?>
								<p><?php echo esc_html( nlw_row( $step, 'desc' ) ); ?></p>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'nlw_process', 'nlw_sc_process' );

/* =========================================================================
 * DÙNG CHUNG
 * ====================================================================== */

/**
 * CTA band cuối trang.
 *
 * @param array $atts title, desc.
 * @return string
 */
function nlw_sc_cta( $atts ) {
	$atts = shortcode_atts(
		array(
			'title'     => nlw_opt( 'cta_title' ),
			'desc'      => nlw_opt( 'cta_desc' ),
			'microcopy' => 'Tư vấn miễn phí · Không ràng buộc',
			'btn2'      => '',
			'btn2_url'  => '',
		),
		$atts,
		'nlw_cta'
	);

	ob_start();
	?>
	<section class="nlw-cta">
		<div class="nlw-wrap">
			<h2><?php echo nlw_rich_text( $atts['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></h2>
			<p><?php echo nlw_rich_text( $atts['desc'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
			<div class="nlw-cta-row">
				<?php
				echo nlw_button( 'Nhắn Zalo', nlw_contact_url( 'zalo' ), 'onbrand', array( 'icon' => 'zalo', 'size' => 'lg', 'target' => '_blank' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
				// Nút phụ: mặc định là Messenger, có thể đổi thành nút điều hướng khác.
				if ( $atts['btn2'] ) {
					echo nlw_button( // phpcs:ignore WordPress.Security.EscapeOutput
						$atts['btn2'],
						$atts['btn2_url'] ?: get_post_type_archive_link( 'project' ),
						'white-out',
						array( 'icon_after' => 'arr', 'size' => 'lg' )
					);
				} else {
					echo nlw_button( 'Messenger', nlw_contact_url( 'messenger' ), 'white-out', array( 'icon' => 'mess', 'size' => 'lg', 'target' => '_blank' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
				}
				?>
			</div>

			<?php if ( $atts['microcopy'] ) : ?>
				<p class="nlw-cta-micro"><?php echo esc_html( $atts['microcopy'] ); ?></p>
			<?php endif; ?>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'nlw_cta', 'nlw_sc_cta' );

/**
 * Hero dạng chữ cho các trang trong (Dịch vụ, Dự án, Liên hệ).
 *
 * @param array $atts eyebrow, title, desc, align.
 * @return string
 */
function nlw_sc_page_hero( $atts, $content = '' ) {
	$atts = shortcode_atts(
		array(
			'eyebrow' => '',
			'title'   => '',
			'desc'    => '',
			'align'   => 'left',
		),
		$atts,
		'nlw_page_hero'
	);

	// Nội dung bọc trong shortcode trở thành các đoạn phụ dưới mô tả chính.
	$blocks = $content ? nlw_content_blocks( $content ) : array();

	ob_start();
	?>
	<section class="nlw-page-hero<?php echo 'center' === $atts['align'] ? ' is-center' : ''; ?>">
		<div class="nlw-wrap">
			<?php if ( $atts['eyebrow'] ) : ?>
				<p class="nlw-eyebrow"><?php echo esc_html( $atts['eyebrow'] ); ?></p>
			<?php endif; ?>
			<h1><?php echo esc_html( $atts['title'] ); ?></h1>
			<?php if ( $atts['desc'] ) : ?>
				<p><?php echo esc_html( $atts['desc'] ); ?></p>
			<?php endif; ?>

			<?php if ( $blocks ) : ?>
				<div class="nlw-page-hero-more"><?php echo nlw_render_blocks( $blocks ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
			<?php endif; ?>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'nlw_page_hero', 'nlw_sc_page_hero' );

/**
 * Nút chuyển giao diện, để đặt vào Header của Flatsome nếu muốn.
 *
 * Mặc định nút đã có sẵn trong cụm nút nổi góc dưới phải. Dùng shortcode này
 * khi muốn đặt thêm ở header: Flatsome → Header → thêm element HTML.
 *
 * @return string
 */
function nlw_sc_theme_toggle() {
	return '<span class="nlw-theme-toggle-wrap">' . nlw_theme_toggle_html() . '</span>';
}
add_shortcode( 'nlw_theme_toggle', 'nlw_sc_theme_toggle' );

/**
 * Footer 4 cột — đặt vào một Flatsome Block rồi gán làm Footer.
 *
 * @return string
 */
function nlw_sc_footer() {
	$logo = nlw_opt( 'footer_logo' ) ?: NLW_URI . '/assets/img/logo-horizontal-white.png';

	$nav = array(
		'Trang chủ'  => home_url( '/' ),
		'Giới thiệu' => nlw_page_url( 'page_about' ),
		'Dịch vụ'    => nlw_page_url( 'page_services' ),
		'Dự án'      => get_post_type_archive_link( 'project' ),
		'Liên hệ'    => nlw_page_url( 'page_contact' ),
	);

	ob_start();
	?>
	<footer class="nlw-ft">
		<div class="nlw-wrap">
			<div class="nlw-ft-cols">
				<div>
					<img class="nlw-ft-logo" src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( nlw_opt( 'brand_name' ) ); ?>">
					<p><?php echo esc_html( nlw_opt( 'footer_desc' ) ); ?></p>
					<p class="nlw-ft-tag"><?php echo esc_html( nlw_opt( 'tagline' ) ); ?></p>
				</div>

				<div>
					<h5>Điều hướng</h5>
					<ul>
						<?php foreach ( $nav as $label => $url ) : ?>
							<li><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $label ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>

				<div>
					<h5>Liên hệ</h5>
					<ul>
						<?php if ( nlw_opt( 'zalo_phone' ) ) : ?>
							<li><a href="<?php echo esc_url( nlw_contact_url( 'zalo' ) ); ?>" target="_blank" rel="noopener">Zalo: <?php echo esc_html( nlw_opt( 'zalo_phone' ) ); ?></a></li>
						<?php endif; ?>
						<?php if ( nlw_opt( 'messenger_url' ) ) : ?>
							<li><a href="<?php echo esc_url( nlw_contact_url( 'messenger' ) ); ?>" target="_blank" rel="noopener">Messenger</a></li>
						<?php endif; ?>
						<?php if ( nlw_opt( 'hotline' ) ) : ?>
							<li><a href="<?php echo esc_url( nlw_contact_url( 'phone' ) ); ?>">Hotline: <?php echo esc_html( nlw_opt( 'hotline' ) ); ?></a></li>
						<?php endif; ?>
						<?php if ( nlw_opt( 'email' ) ) : ?>
							<li><a href="<?php echo esc_url( nlw_contact_url( 'email' ) ); ?>"><?php echo esc_html( nlw_opt( 'email' ) ); ?></a></li>
						<?php endif; ?>
					</ul>
				</div>

				<div>
					<h5>Bạn chưa biết nên bắt đầu từ đâu?</h5>
					<p>Nhắn mình để cùng làm rõ website phù hợp với doanh nghiệp của bạn.</p>
					<?php echo nlw_button( 'Nhắn Zalo', nlw_contact_url( 'zalo' ), 'primary', array( 'icon' => 'zalo', 'class' => 'nlw-ft-btn', 'target' => '_blank' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</div>
			</div>

			<div class="nlw-ft-bottom">
				<span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( nlw_opt( 'brand_name' ) ); ?></span>
				<span><?php echo esc_html( nlw_opt( 'domain' ) ); ?></span>
			</div>
		</div>
	</footer>
	<?php
	return ob_get_clean();
}
add_shortcode( 'nlw_footer', 'nlw_sc_footer' );

/* =========================================================================
 * DỊCH VỤ
 * ====================================================================== */

/**
 * Lưới 4 dịch vụ (trang Dịch vụ tổng).
 *
 * @return string
 */
function nlw_sc_services_grid() {
	$services = get_posts(
		array(
			'post_type'      => 'service',
			'posts_per_page' => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		)
	);

	if ( ! $services ) {
		return '';
	}

	ob_start();
	?>
	<section class="nlw-section nlw-services-hub">
		<div class="nlw-wrap">
			<div class="nlw-ocards">
				<?php foreach ( $services as $i => $service ) : ?>
					<?php $price = nlw_price( nlw_field( 'price_from', $service->ID ) ); ?>
					<a class="nlw-ocard nlw-rv" href="<?php echo esc_url( get_permalink( $service ) ); ?>">
						<div class="nlw-ocard-n"><?php echo esc_html( str_pad( $i + 1, 2, '0', STR_PAD_LEFT ) ); ?></div>
						<div class="nlw-ocard-nm"><?php echo esc_html( get_the_title( $service ) ); ?></div>
						<div class="nlw-ocard-hk"><?php echo esc_html( nlw_field( 'short_desc', $service->ID ) ); ?></div>
						<?php if ( $price ) : ?>
							<div class="nlw-ocard-fr">Chỉ từ <b><?php echo esc_html( $price ); ?>đ</b></div>
						<?php endif; ?>
						<span class="nlw-ocard-go">Xem bảng giá<?php echo nlw_icon( 'arr' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'nlw_services_grid', 'nlw_sc_services_grid' );

/**
 * Bảng giá 3 gói của một dịch vụ.
 *
 * @param array $atts service (ID hoặc slug); bỏ trống khi đang ở trang dịch vụ.
 * @return string
 */
function nlw_sc_pricing( $atts ) {
	$atts = shortcode_atts( array( 'service' => '' ), $atts, 'nlw_pricing' );

	$service_id = 0;

	if ( $atts['service'] ) {
		if ( is_numeric( $atts['service'] ) ) {
			$service_id = (int) $atts['service'];
		} else {
			$found      = get_page_by_path( $atts['service'], OBJECT, 'service' );
			$service_id = $found ? $found->ID : 0;
		}
	} elseif ( is_singular( 'service' ) ) {
		$service_id = get_the_ID();
	}

	if ( ! $service_id ) {
		return '';
	}

	$packages = nlw_rows( 'packages', $service_id );

	if ( ! $packages ) {
		return '';
	}

	$contact = nlw_page_url( 'page_contact' );

	ob_start();
	?>
	<section class="nlw-pricing">
		<div class="nlw-wrap">
			<div class="nlw-tiers">
				<?php foreach ( $packages as $package ) : ?>
					<?php
					$popular  = (bool) nlw_row( $package, 'popular', false );
					$prefix   = nlw_row( $package, 'price_prefix' );
					$duration = nlw_row( $package, 'duration' );
					$features = nlw_row( $package, 'features', array() );
					$label    = nlw_row( $package, 'cta_label', 'Tư vấn ngay' );
					?>
					<article class="nlw-tier nlw-rv<?php echo $popular ? ' is-popular' : ''; ?>">
						<?php if ( $popular ) : ?>
							<div class="nlw-ribbon">Gói phổ biến</div>
						<?php endif; ?>

						<div class="nlw-tier-name"><?php echo esc_html( nlw_row( $package, 'name' ) ); ?></div>
						<div class="nlw-tier-tag"><?php echo esc_html( nlw_row( $package, 'tag' ) ); ?></div>

						<div class="nlw-price">
							<?php if ( $prefix ) : ?>
								<span class="nlw-price-pre"><?php echo esc_html( $prefix ); ?></span>
							<?php endif; ?>
							<?php echo esc_html( nlw_price( nlw_row( $package, 'price' ) ) ); ?><span class="nlw-price-cur">đ</span>
						</div>

						<?php if ( $duration ) : ?>
							<div class="nlw-dur"><?php echo nlw_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $duration ); ?></div>
						<?php endif; ?>

						<p class="nlw-blurb"><?php echo esc_html( nlw_row( $package, 'blurb' ) ); ?></p>

						<?php if ( $features ) : ?>
							<ul class="nlw-feats">
								<?php foreach ( $features as $feature ) : ?>
									<li>
										<span class="nlw-ck"><?php echo nlw_icon( 'ck' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
										<?php echo esc_html( nlw_row( $feature, 'label' ) ); ?>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>

						<?php
						echo nlw_button( // phpcs:ignore WordPress.Security.EscapeOutput
							$label,
							$contact,
							$popular ? 'primary' : 'ghost',
							array(
								'icon'       => 'zalo',
								'icon_after' => 'arr',
								'class'      => 'nlw-btn-block',
							)
						);
						?>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'nlw_pricing', 'nlw_sc_pricing' );

/**
 * 3 thẻ dự án liên quan.
 *
 * @param array $atts service, ids, eyebrow, title.
 * @return string
 */
function nlw_sc_related_projects( $atts ) {
	$atts = shortcode_atts(
		array(
			'service' => '',
			'ids'     => '',
			'eyebrow' => 'Dự án liên quan',
			'title'   => '',
		),
		$atts,
		'nlw_related_projects'
	);

	$ids = array();

	if ( $atts['ids'] ) {
		$ids = array_filter( array_map( 'absint', explode( ',', $atts['ids'] ) ) );
	} else {
		$service_id = $atts['service'] ? absint( $atts['service'] ) : ( is_singular( 'service' ) ? get_the_ID() : 0 );

		if ( $service_id ) {
			$ids = (array) nlw_field( 'related_projects', $service_id, array() );
			$ids = array_filter( array_map( 'absint', $ids ) );
		}
	}

	$args = array(
		'post_type'      => 'project',
		'posts_per_page' => 3,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
	);

	if ( $ids ) {
		$args['post__in'] = $ids;
		$args['orderby']  = 'post__in';
	}

	$projects = get_posts( $args );

	if ( ! $projects ) {
		return '';
	}

	ob_start();
	?>
	<section class="nlw-related">
		<div class="nlw-wrap">
			<div class="nlw-rhead">
				<span class="nlw-eyebrow"><?php echo esc_html( $atts['eyebrow'] ); ?></span>
				<?php if ( $atts['title'] ) : ?>
					<h2><?php echo esc_html( $atts['title'] ); ?></h2>
				<?php endif; ?>
			</div>

			<div class="nlw-rgrid">
				<?php foreach ( $projects as $project ) : ?>
					<a class="nlw-rp nlw-rv" href="<?php echo esc_url( get_permalink( $project ) ); ?>">
						<?php echo nlw_image( get_post_thumbnail_id( $project ), 'nlw-r-16x10', 'Ảnh dự án' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<div class="nlw-rp-b">
							<h4><?php echo esc_html( get_the_title( $project ) ); ?></h4>
							<div class="nlw-rp-meta">
								<?php if ( nlw_field( 'client', $project->ID ) ) : ?>
									<div>Khách hàng: <b><?php echo esc_html( nlw_field( 'client', $project->ID ) ); ?></b></div>
								<?php endif; ?>
								<?php if ( nlw_field( 'year', $project->ID ) ) : ?>
									<div>Năm hoàn thành: <b><?php echo esc_html( nlw_field( 'year', $project->ID ) ); ?></b></div>
								<?php endif; ?>
							</div>
						</div>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'nlw_related_projects', 'nlw_sc_related_projects' );

/* =========================================================================
 * LIÊN HỆ
 * ====================================================================== */

/**
 * Cột "Cách nhanh nhất" — 4 kênh liên hệ trực tiếp.
 *
 * @param array $atts title.
 * @return string
 */
function nlw_sc_contact_channels( $atts ) {
	$atts = shortcode_atts( array( 'title' => 'Cách nhanh nhất' ), $atts, 'nlw_contact_channels' );

	ob_start();
	?>
	<div class="nlw-rv">
		<div class="nlw-col-h"><?php echo esc_html( $atts['title'] ); ?></div>

		<div class="nlw-chans">
			<a class="nlw-chan nlw-chan-zalo" href="<?php echo esc_url( nlw_contact_url( 'zalo' ) ); ?>" target="_blank" rel="noopener">
				<span class="nlw-chan-ico"><?php echo nlw_icon( 'zalo' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<span>
					<b>Nhắn Zalo</b>
					<span class="nlw-chan-sub"><?php echo esc_html( nlw_opt( 'zalo_phone' ) ); ?> · trả lời trong ngày</span>
				</span>
				<span class="nlw-chan-go"><?php echo nlw_icon( 'arr' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			</a>

			<a class="nlw-chan nlw-chan-mess" href="<?php echo esc_url( nlw_contact_url( 'messenger' ) ); ?>" target="_blank" rel="noopener">
				<span class="nlw-chan-ico"><?php echo nlw_icon( 'mess' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<span>
					<b>Nhắn Messenger</b>
					<span class="nlw-chan-sub"><?php echo esc_html( preg_replace( '#^https?://#', '', nlw_opt( 'messenger_url' ) ) ); ?></span>
				</span>
				<span class="nlw-chan-go"><?php echo nlw_icon( 'arr' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			</a>

			<?php if ( nlw_opt( 'hotline' ) ) : ?>
				<a class="nlw-chan nlw-chan-alt" href="<?php echo esc_url( nlw_contact_url( 'phone' ) ); ?>">
					<span class="nlw-chan-ico"><?php echo nlw_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<span>
						<b>Gọi hotline</b>
						<span class="nlw-chan-sub"><?php echo esc_html( nlw_opt( 'hotline' ) ); ?></span>
					</span>
					<span class="nlw-chan-go"><?php echo nlw_icon( 'arr' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				</a>
			<?php endif; ?>

			<?php if ( nlw_opt( 'email' ) ) : ?>
				<a class="nlw-chan nlw-chan-alt" href="<?php echo esc_url( nlw_contact_url( 'email' ) ); ?>">
					<span class="nlw-chan-ico"><?php echo nlw_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<span>
						<b>Gửi email</b>
						<span class="nlw-chan-sub"><?php echo esc_html( nlw_opt( 'email' ) ); ?></span>
					</span>
					<span class="nlw-chan-go"><?php echo nlw_icon( 'arr' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				</a>
			<?php endif; ?>
		</div>

		<?php if ( nlw_opt( 'working_hours' ) ) : ?>
			<div class="nlw-hours"><span class="nlw-dot"></span><?php echo esc_html( nlw_opt( 'working_hours' ) ); ?></div>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'nlw_contact_channels', 'nlw_sc_contact_channels' );

/**
 * Bọc form (Contact Form 7 / WPForms) trong khung "Hoặc để lại thông tin".
 *
 * @param array  $atts    title, note.
 * @param string $content Shortcode form, ví dụ [contact-form-7 id="123"].
 * @return string
 */
function nlw_sc_contact_form( $atts, $content = '' ) {
	$atts = shortcode_atts(
		array(
			'title' => 'Hoặc để lại thông tin',
			'note'  => 'Điền nhanh vài thông tin, tôi sẽ chủ động liên hệ lại với bạn.',
		),
		$atts,
		'nlw_contact_form'
	);

	ob_start();
	?>
	<div class="nlw-rv">
		<div class="nlw-col-h"><?php echo esc_html( $atts['title'] ); ?></div>
		<div class="nlw-form">
			<?php if ( $atts['note'] ) : ?>
				<p class="nlw-form-note"><?php echo esc_html( $atts['note'] ); ?></p>
			<?php endif; ?>
			<?php echo do_shortcode( $content ); ?>
			<p class="nlw-form-fine">Thông tin của bạn chỉ dùng để liên hệ tư vấn.</p>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'nlw_contact_form', 'nlw_sc_contact_form' );

/**
 * Bọc 2 cột của trang Liên hệ (trái: kênh, phải: form).
 *
 * @param array  $atts    Không dùng.
 * @param string $content Hai shortcode con.
 * @return string
 */
function nlw_sc_contact_grid( $atts, $content = '' ) {
	return '<section class="nlw-wrap nlw-cgrid">' . do_shortcode( $content ) . '</section>';
}
add_shortcode( 'nlw_contact_grid', 'nlw_sc_contact_grid' );

/**
 * Khu vực làm việc + bản đồ.
 *
 * @param array $atts title.
 * @return string
 */
function nlw_sc_map( $atts ) {
	$atts  = shortcode_atts( array( 'title' => 'Khu vực làm việc' ), $atts, 'nlw_map' );
	$embed = nlw_opt( 'map_embed' );

	ob_start();
	?>
	<section class="nlw-wrap nlw-map-sec">
		<div class="nlw-col-h nlw-rv nlw-col-h-pin">
			<?php echo nlw_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php echo esc_html( $atts['title'] ); ?>
			<?php if ( nlw_opt( 'work_area' ) ) : ?>
				<span class="nlw-col-h-note"><?php echo esc_html( nlw_opt( 'work_area' ) ); ?></span>
			<?php endif; ?>
		</div>

		<?php if ( $embed ) : ?>
			<div class="nlw-map nlw-map-embed nlw-rv"><?php echo $embed; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
		<?php else : ?>
			<div class="nlw-map nlw-rv">Bản đồ (tùy chọn)</div>
		<?php endif; ?>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'nlw_map', 'nlw_sc_map' );

/* =========================================================================
 * GIỚI THIỆU (About) — các khối editorial
 * ====================================================================== */

/**
 * Dải tối chứa một câu tuyên ngôn.
 *
 * @param array  $atts    eyebrow, by.
 * @param string $content Nội dung trích dẫn.
 * @return string
 */
function nlw_sc_vision( $atts, $content = '' ) {
	$atts = shortcode_atts(
		array(
			'eyebrow' => 'Tầm nhìn',
			'desc'    => '',
			'by'      => '',
		),
		$atts,
		'nlw_vision'
	);

	ob_start();
	?>
	<section class="nlw-section nlw-vision">
		<div class="nlw-wrap">
			<span class="nlw-eyebrow nlw-rv"><?php echo esc_html( $atts['eyebrow'] ); ?></span>
			<?php
			// Mỗi dòng trong thân shortcode là một dòng hiển thị — giữ đúng chỗ ngắt.
			$nlw_quote = array();

			foreach ( preg_split( '/\r\n|\r|\n/', wp_strip_all_tags( preg_replace( '#<br\s*/?>|</p>#i', "\n", (string) $content ) ) ) as $nlw_line ) {
				$nlw_line = trim( $nlw_line );

				if ( '' !== $nlw_line ) {
					$nlw_quote[] = esc_html( $nlw_line );
				}
			}
			?>
			<blockquote class="nlw-rv"><?php echo implode( '<br>', $nlw_quote ); // phpcs:ignore WordPress.Security.EscapeOutput ?></blockquote>
			<?php if ( $atts['desc'] ) : ?>
				<p class="nlw-vision-desc nlw-rv"><?php echo esc_html( $atts['desc'] ); ?></p>
			<?php endif; ?>
			<?php if ( $atts['by'] ) : ?>
				<div class="nlw-vision-by nlw-rv"><?php echo esc_html( $atts['by'] ); ?></div>
			<?php endif; ?>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'nlw_vision', 'nlw_sc_vision' );

/**
 * Danh sách đánh số kiểu editorial (giá trị cốt lõi ở trang Giới thiệu).
 *
 * @param array  $atts    eyebrow, title, alt.
 * @param string $content Mỗi dòng: Tiêu đề | Mô tả.
 * @return string
 */
function nlw_sc_numbered_list( $atts, $content = '' ) {
	$atts = shortcode_atts(
		array(
			'eyebrow' => '',
			'title'   => '',
			'alt'     => '1',
		),
		$atts,
		'nlw_numbered_list'
	);

	$rows = nlw_lines( wp_strip_all_tags( $content ), 2 );

	if ( ! $rows ) {
		return '';
	}

	ob_start();
	?>
	<section class="nlw-section<?php echo '1' === $atts['alt'] ? ' nlw-alt' : ''; ?>">
		<div class="nlw-wrap">
			<div class="nlw-head nlw-rv">
				<?php if ( $atts['eyebrow'] ) : ?>
					<p class="nlw-eyebrow"><?php echo esc_html( $atts['eyebrow'] ); ?></p>
				<?php endif; ?>
				<?php if ( $atts['title'] ) : ?>
					<h2><?php echo esc_html( $atts['title'] ); ?></h2>
				<?php endif; ?>
			</div>

			<div class="nlw-vlist">
				<?php foreach ( $rows as $i => $row ) : ?>
					<div class="nlw-vrow nlw-rv">
						<div class="nlw-vrow-n"><?php echo esc_html( str_pad( $i + 1, 2, '0', STR_PAD_LEFT ) ); ?></div>
						<div>
							<h3><?php echo esc_html( $row[0] ); ?></h3>
							<p><?php echo esc_html( $row[1] ); ?></p>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'nlw_numbered_list', 'nlw_sc_numbered_list' );

/**
 * 3 cột "Cách làm việc" — viền trên 3px brand.
 *
 * @param array  $atts    eyebrow, title, alt.
 * @param string $content Mỗi dòng: Tiêu đề | Mô tả.
 * @return string
 */
function nlw_sc_ways( $atts, $content = '' ) {
	$atts = shortcode_atts(
		array(
			'eyebrow' => '',
			'title'   => 'Cách mình làm việc',
			'lead'    => '',
			'alt'     => '1',
		),
		$atts,
		'nlw_ways'
	);

	$rows = nlw_lines( wp_strip_all_tags( $content ), 2 );

	if ( ! $rows ) {
		return '';
	}

	ob_start();
	?>
	<section class="nlw-section<?php echo '1' === $atts['alt'] ? ' nlw-alt' : ''; ?>">
		<div class="nlw-wrap">
			<div class="nlw-head nlw-rv">
				<?php if ( $atts['eyebrow'] ) : ?>
					<p class="nlw-eyebrow"><?php echo esc_html( $atts['eyebrow'] ); ?></p>
				<?php endif; ?>
				<h2><?php echo esc_html( $atts['title'] ); ?></h2>
				<?php if ( $atts['lead'] ) : ?>
					<p class="nlw-lead"><?php echo esc_html( $atts['lead'] ); ?></p>
				<?php endif; ?>
			</div>

			<div class="nlw-ways">
				<?php foreach ( $rows as $i => $row ) : ?>
					<div class="nlw-way nlw-rv">
						<div class="nlw-way-s"><?php echo esc_html( str_pad( $i + 1, 2, '0', STR_PAD_LEFT ) ); ?></div>
						<h4><?php echo esc_html( $row[0] ); ?></h4>
						<p><?php echo esc_html( $row[1] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'nlw_ways', 'nlw_sc_ways' );

/**
 * Dãy chip lĩnh vực.
 *
 * @param array  $atts    eyebrow, title, lead.
 * @param string $content Mỗi dòng một chip.
 * @return string
 */
function nlw_sc_chips( $atts, $content = '' ) {
	$atts = shortcode_atts(
		array(
			'eyebrow' => '',
			'title'   => 'Lĩnh vực đã làm',
			'lead'    => '',
			'source'  => '',
		),
		$atts,
		'nlw_chips'
	);

	if ( 'projects' === $atts['source'] ) {
		/*
		 * Lấy thẳng từ trường "Lĩnh vực" của các dự án đã đăng.
		 * Chỉ hiện ngành thật sự có dự án — không tự thêm ngành nào.
		 */
		$chips = array();

		foreach ( get_posts( array( 'post_type' => 'project', 'posts_per_page' => -1, 'fields' => 'ids' ) ) as $project_id ) {
			$industry = trim( (string) nlw_field( 'industry', $project_id ) );

			if ( '' !== $industry ) {
				$chips[ mb_strtolower( $industry ) ] = $industry;
			}
		}

		$chips = array_values( $chips );
		sort( $chips, SORT_NATURAL | SORT_FLAG_CASE );
		$chips = array_map( function ( $c ) { return array( $c ); }, $chips );
	} else {
		$chips = nlw_lines( wp_strip_all_tags( $content ) );
	}

	if ( ! $chips ) {
		return '';
	}

	ob_start();
	?>
	<section class="nlw-section nlw-exp">
		<div class="nlw-wrap">
			<?php if ( $atts['eyebrow'] ) : ?>
				<p class="nlw-eyebrow nlw-rv"><?php echo esc_html( $atts['eyebrow'] ); ?></p>
			<?php endif; ?>
			<h2 class="nlw-rv"><?php echo esc_html( $atts['title'] ); ?></h2>
			<?php if ( $atts['lead'] ) : ?>
				<p class="nlw-lead nlw-rv"><?php echo esc_html( $atts['lead'] ); ?></p>
			<?php endif; ?>

			<div class="nlw-chips nlw-rv">
				<?php foreach ( $chips as $chip ) : ?>
					<?php if ( 'projects' === $atts['source'] ) : ?>
						<a class="nlw-chip nlw-chip-link"
							href="<?php echo esc_url( add_query_arg( 'linh-vuc', rawurlencode( $chip[0] ), get_post_type_archive_link( 'project' ) ) ); ?>">
							<?php echo esc_html( $chip[0] ); ?><?php echo nlw_icon( 'arr' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</a>
					<?php else : ?>
						<span class="nlw-chip"><?php echo esc_html( $chip[0] ); ?></span>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'nlw_chips', 'nlw_sc_chips' );
