<?php
/**
 * Trang chi tiết một dịch vụ — bảng giá 3 gói + dự án liên quan.
 *
 * @package nlw
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$nlw_name_in_title = nlw_field( 'name_in_title', null, get_the_title() );
	$nlw_chips         = nlw_rows( 'chips' );
	?>

	<div id="content" class="content-area page-wrapper nlw-single-service" role="main">

		<section class="nlw-sh">
			<div class="nlw-wrap">
				<div class="nlw-crumb nlw-crumb-center">
					<a href="<?php echo esc_url( nlw_page_url( 'page_services' ) ); ?>">Dịch vụ</a>
					<span class="nlw-crumb-sep">/</span>
					<span><?php the_title(); ?></span>
				</div>

				<p class="nlw-eyebrow">Dịch vụ &amp; bảng giá</p>
				<h1>Bảng giá <em><?php echo esc_html( $nlw_name_in_title ); ?></em></h1>

				<?php if ( nlw_field( 'hero_desc' ) ) : ?>
					<p><?php echo esc_html( nlw_field( 'hero_desc' ) ); ?></p>
				<?php endif; ?>

				<?php if ( $nlw_chips ) : ?>
					<div class="nlw-schips">
						<?php foreach ( $nlw_chips as $nlw_chip ) : ?>
							<span class="nlw-schip"><?php echo esc_html( nlw_row( $nlw_chip, 'label' ) ); ?></span>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</section>

		<?php echo do_shortcode( '[nlw_pricing]' ); ?>

		<?php if ( get_the_content() ) : ?>
			<section class="nlw-section nlw-block">
				<div class="nlw-wrap nlw-narrow nlw-rv nlw-richtext">
					<?php the_content(); ?>
				</div>
			</section>
		<?php endif; ?>

		<?php
		echo do_shortcode(
			sprintf(
				'[nlw_related_projects title="Những dự án %s đã hoàn thành"]',
				esc_attr( $nlw_name_in_title )
			)
		);
		?>

		<?php echo do_shortcode( '[nlw_cta title="Chưa chắc chọn gói nào?" desc="Nhắn tin mô tả nhu cầu của bạn — tôi sẽ tư vấn đúng gói phù hợp nhất, miễn phí và không ràng buộc."]' ); ?>

	</div>

	<?php
endwhile;

get_footer();
