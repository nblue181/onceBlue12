<?php
/**
 * Chi tiết dự án (case study).
 *
 * @package nlw
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$nlw_terms   = get_the_terms( get_the_ID(), 'project_cat' );
	$nlw_cat     = ( $nlw_terms && ! is_wp_error( $nlw_terms ) ) ? $nlw_terms[0] : null;
	$nlw_solutions = nlw_rows( 'solutions' );
	$nlw_results   = nlw_rows( 'results' );
	$nlw_gallery   = array_filter( (array) nlw_field( 'gallery', null, array() ) );
	$nlw_services  = array_filter( array_map( 'absint', (array) nlw_field( 'related_services', null, array() ) ) );
	?>

	<div id="content" class="content-area page-wrapper nlw-single-project" role="main">

		<!-- HERO + OVERVIEW -->
		<section class="nlw-dh">
			<div class="nlw-wrap">
				<div class="nlw-crumb">
					<a href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ); ?>">Dự án</a>
					<span class="nlw-crumb-sep">/</span>
					<span><?php echo esc_html( $nlw_cat ? $nlw_cat->name : get_the_title() ); ?></span>
				</div>

				<?php if ( $nlw_cat ) : ?>
					<p class="nlw-eyebrow"><?php echo esc_html( $nlw_cat->name ); ?></p>
				<?php endif; ?>

				<h1><?php the_title(); ?></h1>

				<?php if ( has_excerpt() ) : ?>
					<p class="nlw-dh-intro"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>

				<div class="nlw-meta-inline">
					<?php
					$nlw_meta = array(
						'Khách hàng'   => nlw_field( 'client' ),
						'Lĩnh vực'     => nlw_field( 'industry' ),
						'Loại website' => nlw_field( 'website_type' ),
						'Thời gian'    => nlw_field( 'duration' ),
					);

					foreach ( $nlw_meta as $nlw_key => $nlw_value ) :
						if ( ! $nlw_value ) {
							continue;
						}
						?>
						<div>
							<div class="nlw-meta-k"><?php echo esc_html( $nlw_key ); ?></div>
							<div class="nlw-meta-v"><?php echo esc_html( $nlw_value ); ?></div>
						</div>
					<?php endforeach; ?>
				</div>

				<?php if ( nlw_field( 'website_url' ) ) : ?>
					<div class="nlw-visit">
						<?php echo nlw_button( 'Xem website', nlw_field( 'website_url' ), 'ghost', array( 'icon_after' => 'ext', 'target' => '_blank' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</div>
				<?php endif; ?>

				<div class="nlw-rv">
					<?php echo nlw_image( nlw_field( 'hero_image' ) ?: get_post_thumbnail_id(), 'nlw-r-16x8', 'Ảnh trang chủ dự án', 'full' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</div>
			</div>
		</section>

		<!-- VẤN ĐỀ -->
		<?php if ( nlw_field( 'problem_text' ) ) : ?>
			<section class="nlw-section nlw-block">
				<div class="nlw-wrap nlw-narrow nlw-rv">
					<span class="nlw-eyebrow">Vấn đề</span>
					<h2>Khách hàng gặp khó khăn gì?</h2>
					<?php echo wp_kses_post( wpautop( nlw_field( 'problem_text' ) ) ); ?>
				</div>
			</section>
		<?php endif; ?>

		<!-- GIẢI PHÁP -->
		<?php if ( $nlw_solutions || nlw_field( 'solution_intro' ) ) : ?>
			<section class="nlw-section nlw-alt nlw-block">
				<div class="nlw-wrap">
					<div class="nlw-narrow nlw-rv">
						<span class="nlw-eyebrow">Giải pháp</span>
						<h2>Cách tôi giải quyết</h2>
						<?php if ( nlw_field( 'solution_intro' ) ) : ?>
							<p><?php echo esc_html( nlw_field( 'solution_intro' ) ); ?></p>
						<?php endif; ?>
					</div>

					<?php foreach ( $nlw_solutions as $nlw_i => $nlw_solution ) : ?>
						<?php
						// Hàng chẵn: chữ trái – ảnh phải. Hàng lẻ: đảo lại.
						$nlw_reversed = ( 1 === $nlw_i % 2 );
						$nlw_text     = '<div><h3>' . esc_html( nlw_row( $nlw_solution, 'title' ) ) . '</h3>'
							. wp_kses_post( wpautop( esc_html( nlw_row( $nlw_solution, 'text' ) ) ) ) . '</div>';
						$nlw_media    = nlw_image( nlw_row( $nlw_solution, 'image' ), 'nlw-r-4x3', 'Ảnh giao diện' );
						?>
						<div class="nlw-detrow nlw-rv<?php echo $nlw_reversed ? ' is-rev' : ''; ?>">
							<?php
							echo $nlw_reversed // phpcs:ignore WordPress.Security.EscapeOutput
								? $nlw_media . $nlw_text
								: $nlw_text . $nlw_media;
							?>
						</div>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>

		<!-- KẾT QUẢ -->
		<?php if ( $nlw_results ) : ?>
			<section class="nlw-section nlw-block">
				<div class="nlw-wrap">
					<div class="nlw-narrow nlw-rv">
						<span class="nlw-eyebrow">Kết quả</span>
						<h2>Sau khi hoàn thành</h2>
					</div>

					<div class="nlw-results nlw-rv">
						<?php foreach ( $nlw_results as $nlw_result ) : ?>
							<div class="nlw-res">
								<span class="nlw-res-ck"><?php echo nlw_icon( 'ck' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
								<div>
									<b><?php echo esc_html( nlw_row( $nlw_result, 'title' ) ); ?></b>
									<span><?php echo esc_html( nlw_row( $nlw_result, 'desc' ) ); ?></span>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<!-- GALLERY -->
		<?php if ( $nlw_gallery ) : ?>
			<section class="nlw-section nlw-alt nlw-block">
				<div class="nlw-wrap">
					<div class="nlw-narrow nlw-rv"><h2>Một số hình ảnh</h2></div>
					<div class="nlw-gal nlw-rv">
						<?php foreach ( $nlw_gallery as $nlw_image_id ) : ?>
							<?php echo nlw_image( $nlw_image_id, 'nlw-r-16x10', 'Ảnh dự án' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<!-- DỊCH VỤ LIÊN QUAN -->
		<?php if ( $nlw_services ) : ?>
			<section class="nlw-section nlw-block">
				<div class="nlw-wrap">
					<div class="nlw-narrow nlw-rv">
						<span class="nlw-eyebrow">Dịch vụ liên quan</span>
						<h2>Dịch vụ đã dùng cho dự án này</h2>
					</div>

					<div class="nlw-rsv nlw-rv">
						<?php foreach ( array_slice( $nlw_services, 0, 3 ) as $nlw_i => $nlw_service_id ) : ?>
							<a href="<?php echo esc_url( get_permalink( $nlw_service_id ) ); ?>">
								<div class="nlw-rsv-no"><?php echo esc_html( str_pad( $nlw_i + 1, 2, '0', STR_PAD_LEFT ) ); ?></div>
								<h4><?php echo esc_html( get_the_title( $nlw_service_id ) ); ?></h4>
								<p><?php echo esc_html( nlw_field( 'short_desc', $nlw_service_id ) ); ?></p>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<!-- DỰ ÁN KHÁC -->
		<?php
		$nlw_others = get_posts(
			array(
				'post_type'      => 'project',
				'posts_per_page' => 3,
				'post__not_in'   => array( get_the_ID() ),
				'orderby'        => 'rand',
			)
		);

		if ( $nlw_others ) :
			?>
			<section class="nlw-section nlw-alt nlw-block">
				<div class="nlw-wrap">
					<div class="nlw-narrow nlw-rv"><h2>Dự án khác</h2></div>
					<div class="nlw-rpj nlw-rv">
						<?php foreach ( $nlw_others as $nlw_other ) : ?>
							<?php
							$nlw_other_terms = get_the_terms( $nlw_other, 'project_cat' );
							$nlw_other_cat   = ( $nlw_other_terms && ! is_wp_error( $nlw_other_terms ) ) ? $nlw_other_terms[0]->name : '';
							?>
							<a href="<?php echo esc_url( get_permalink( $nlw_other ) ); ?>">
								<?php echo nlw_image( get_post_thumbnail_id( $nlw_other ), 'nlw-r-16x10', 'Ảnh dự án' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<div class="nlw-rpj-b">
									<?php if ( $nlw_other_cat ) : ?>
										<div class="nlw-rpj-cat"><?php echo esc_html( $nlw_other_cat ); ?></div>
									<?php endif; ?>
									<h4><?php echo esc_html( get_the_title( $nlw_other ) ); ?></h4>
								</div>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php echo do_shortcode( '[nlw_cta]' ); ?>

	</div>

	<?php
endwhile;

get_footer();
