<?php
/**
 * Archive dự án + lọc theo nhóm (taxonomy project_cat).
 *
 * @package nlw
 */

defined( 'ABSPATH' ) || exit;

get_header();

$nlw_current_term = is_tax( 'project_cat' ) ? get_queried_object() : null;
$nlw_terms        = get_terms(
	array(
		'taxonomy'   => 'project_cat',
		'hide_empty' => true,
	)
);
?>

<div id="content" class="content-area page-wrapper nlw-archive" role="main">

	<section class="nlw-page-hero">
		<div class="nlw-wrap">
			<p class="nlw-eyebrow">Dự án</p>
			<h1><?php echo esc_html( $nlw_current_term ? $nlw_current_term->name : 'Những website đã thực hiện' ); ?></h1>
			<p>
				<?php
				echo esc_html(
					$nlw_current_term && $nlw_current_term->description
						? $nlw_current_term->description
						: 'Một vài website mình đã thực hiện. Bấm vào từng dự án để xem cách mình giải quyết bài toán của khách hàng.'
				);
				?>
			</p>

			<?php $nlw_industry = nlw_current_industry(); ?>
			<?php if ( $nlw_industry ) : ?>
				<p class="nlw-active-filter">
					Đang lọc theo lĩnh vực: <b><?php echo esc_html( $nlw_industry ); ?></b>
					<a href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ); ?>">Bỏ lọc</a>
				</p>
			<?php endif; ?>
		</div>
	</section>

	<?php if ( ! is_wp_error( $nlw_terms ) && $nlw_terms ) : ?>
		<div class="nlw-filter">
			<div class="nlw-wrap">
				<a class="nlw-fchip<?php echo $nlw_current_term ? '' : ' is-on'; ?>" href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ); ?>">Tất cả</a>

				<?php foreach ( $nlw_terms as $nlw_term ) : ?>
					<a class="nlw-fchip<?php echo ( $nlw_current_term && $nlw_current_term->term_id === $nlw_term->term_id ) ? ' is-on' : ''; ?>"
						href="<?php echo esc_url( get_term_link( $nlw_term ) ); ?>">
						<?php echo esc_html( $nlw_term->name ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

	<section class="nlw-gridsec">
		<div class="nlw-wrap">
			<?php if ( have_posts() ) : ?>
				<div class="nlw-pgrid">
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/card', 'project' );
					endwhile;
					?>
				</div>

				<?php
				$nlw_pagination = paginate_links(
					array(
						'type'      => 'array',
						'prev_text' => '‹',
						'next_text' => '›',
					)
				);

				if ( $nlw_pagination ) :
					?>
					<nav class="nlw-pager" aria-label="Phân trang dự án">
						<?php foreach ( $nlw_pagination as $nlw_link ) : ?>
							<?php echo str_replace( array( 'page-numbers current', 'page-numbers' ), array( 'nlw-pg is-on', 'nlw-pg' ), $nlw_link ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<?php endforeach; ?>
					</nav>
				<?php endif; ?>

			<?php else : ?>
				<p class="nlw-empty">Chưa có dự án trong nhóm này.</p>
			<?php endif; ?>
		</div>
	</section>

	<?php echo do_shortcode( '[nlw_cta title="Dự án tiếp theo là của bạn?" desc="Nhắn tin để trao đổi về website bạn đang cần — tư vấn miễn phí, không ràng buộc."]' ); ?>

</div>

<?php
get_footer();
