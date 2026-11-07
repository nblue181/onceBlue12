<?php
/**
 * Thẻ dự án ở lưới archive.
 *
 * @package nlw
 */

defined( 'ABSPATH' ) || exit;

$nlw_terms = get_the_terms( get_the_ID(), 'project_cat' );
$nlw_cat   = ( $nlw_terms && ! is_wp_error( $nlw_terms ) ) ? $nlw_terms[0]->name : '';
?>

<a class="nlw-pcard nlw-rv" href="<?php the_permalink(); ?>">
	<?php echo nlw_image( get_post_thumbnail_id(), 'nlw-r-16x10', 'Ảnh dự án' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>

	<div class="nlw-pcard-body">
		<?php if ( $nlw_cat ) : ?>
			<div class="nlw-pcard-cat"><?php echo esc_html( $nlw_cat ); ?></div>
		<?php endif; ?>

		<h3><?php the_title(); ?></h3>

		<div class="nlw-pcard-info">
			<?php if ( nlw_field( 'client' ) ) : ?>
				<div><span>Khách hàng</span><b><?php echo esc_html( nlw_field( 'client' ) ); ?></b></div>
			<?php endif; ?>
			<?php if ( nlw_field( 'industry' ) ) : ?>
				<div><span>Lĩnh vực</span><b><?php echo esc_html( nlw_field( 'industry' ) ); ?></b></div>
			<?php endif; ?>
			<?php if ( nlw_field( 'year' ) ) : ?>
				<div><span>Năm</span><b><?php echo esc_html( nlw_field( 'year' ) ); ?></b></div>
			<?php endif; ?>
		</div>

		<span class="nlw-more">Xem chi tiết<?php echo nlw_icon( 'arr' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
	</div>
</a>
