<?php
/**
 * SEO metadata cho TRANG CHỦ.
 *
 * Website hiện chưa cài plugin SEO nên không có meta description và Open Graph.
 * File này bù phần tối thiểu theo Content Specification, và tự động im lặng
 * nếu sau này cài Rank Math / Yoast / SEOPress để tránh xuất trùng thẻ.
 *
 * Phạm vi: chỉ trang chủ. Các trang khác sẽ xử lý ở bước nội dung riêng.
 *
 * @package nlw
 */

defined( 'ABSPATH' ) || exit;

/*
 * Giá trị dự phòng theo Content Specification.
 * Cần thiết vì get_field() trên Options Page trả về rỗng cho tới khi trang
 * Cấu hình được Lưu lần đầu — `default_value` chỉ điền vào form, không lưu DB.
 */
define( 'NLW_SEO_TITLE', 'Nam làm WEB | Website rõ ràng, dễ hiểu cho doanh nghiệp' );
define( 'NLW_SEO_DESC', 'Mình xây dựng website cho doanh nghiệp nhỏ — rõ ràng, dễ hiểu và phù hợp với cách khách hàng tìm hiểu trước khi quyết định.' );
define( 'NLW_OG_TITLE', 'Bạn sẽ hiểu website của mình trước khi sở hữu nó.' );
define( 'NLW_OG_DESC', 'Hiểu đúng doanh nghiệp. Làm đúng website. Xây dựng niềm tin với khách hàng.' );

/**
 * Đã có plugin SEO lo phần meta chưa?
 *
 * @return bool
 */
function nlw_seo_plugin_active() {
	return class_exists( 'RankMath' )
		|| defined( 'WPSEO_VERSION' )
		|| defined( 'SEOPRESS_VERSION' )
		|| class_exists( 'All_in_One_SEO_Pack' );
}

/**
 * Thẻ <title> của trang chủ.
 *
 * @param array $parts Các phần của title.
 * @return array
 */
function nlw_seo_title( $parts ) {
	if ( ! is_front_page() || nlw_seo_plugin_active() ) {
		return $parts;
	}

	return array( 'title' => nlw_opt( 'seo_title', NLW_SEO_TITLE ) );
}
add_filter( 'document_title_parts', 'nlw_seo_title' );

/**
 * Meta description + Open Graph cho trang chủ.
 */
function nlw_seo_meta() {
	if ( ! is_front_page() || nlw_seo_plugin_active() ) {
		return;
	}

	$desc     = nlw_opt( 'seo_desc', NLW_SEO_DESC );
	$og_title = nlw_opt( 'og_title', NLW_OG_TITLE );
	$og_desc  = nlw_opt( 'og_desc', NLW_OG_DESC );
	$image    = nlw_opt( 'og_image' );

	if ( ! $image ) {
		$logo_id = (int) get_theme_mod( 'custom_logo' );
		$image   = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : NLW_URI . '/assets/img/logo-square.png';
	}

	if ( $desc ) {
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $desc ) );
	}

	printf( '<meta property="og:type" content="website">' . "\n" );
	printf( '<meta property="og:url" content="%s">' . "\n", esc_url( home_url( '/' ) ) );
	printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( nlw_opt( 'brand_name' ) ) );

	if ( $og_title ) {
		printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $og_title ) );
		printf( '<meta name="twitter:title" content="%s">' . "\n", esc_attr( $og_title ) );
	}

	if ( $og_desc ) {
		printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $og_desc ) );
		printf( '<meta name="twitter:description" content="%s">' . "\n", esc_attr( $og_desc ) );
	}

	if ( $image ) {
		printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
		printf( '<meta name="twitter:card" content="summary_large_image">' . "\n" );
	}
}
add_action( 'wp_head', 'nlw_seo_meta', 2 );
