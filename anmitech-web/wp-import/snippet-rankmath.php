<?php
/**
 * Anmitech — đưa dữ liệu SEO của Rank Math vào REST API.
 *
 * Dán vào plugin Code Snippets, hoặc cuối functions.php của child theme.
 * KHÔNG dán dòng <?php ở trên nếu file đích đã có sẵn.
 *
 * Vì sao cần: Rank Math ghi thẻ meta vào <head> của WordPress. Với kiến trúc
 * headless, khách không bao giờ tải trang WordPress đó — nên thẻ meta không
 * tới được trình duyệt. Snippet này đưa dữ liệu thô ra REST để Next.js tự dựng
 * thẻ meta bằng generateMetadata().
 *
 * Sau khi dán, mỗi bài trong REST có thêm khoá "meta":
 *   /wp-json/wp/v2/services/12
 *   → { …, "meta": { "rank_math_title": "…", "rank_math_description": "…" } }
 */

/**
 * Các meta key của Rank Math cần lộ ra REST.
 *
 * Nếu sau này cần thêm (ví dụ twitter_title), kiểm tra tên thật trong bảng
 * wp_postmeta của một bài đã điền SEO rồi thêm vào mảng này.
 */
function anmi_rankmath_meta_keys() {
	return array(
		'rank_math_title',
		'rank_math_description',
		'rank_math_canonical_url',
		'rank_math_facebook_title',
		'rank_math_facebook_description',
		'rank_math_facebook_image',
		'rank_math_focus_keyword',
	);
}

add_action( 'init', function () {

	$post_types = array( 'service', 'project', 'page', 'post' );

	foreach ( $post_types as $post_type ) {
		foreach ( anmi_rankmath_meta_keys() as $key ) {
			register_post_meta( $post_type, $key, array(
				'type'          => 'string',
				'single'        => true,
				'show_in_rest'  => true,
				// Chỉ đọc từ frontend. Sửa SEO vẫn làm trong admin WordPress.
				'auth_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			) );
		}

		// robots lưu dạng mảng (noindex, nofollow…), phải khai schema riêng.
		register_post_meta( $post_type, 'rank_math_robots', array(
			'type'         => 'array',
			'single'       => true,
			'show_in_rest' => array(
				'schema' => array(
					'type'  => 'array',
					'items' => array( 'type' => 'string' ),
				),
			),
			'auth_callback' => function () {
				return current_user_can( 'edit_posts' );
			},
		) );
	}
}, 20 );

/**
 * Gộp SEO đã tính sẵn vào một khoá "seo" cho gọn.
 *
 * Rank Math để trống thì nó dùng template mặc định (%title% %sep% %sitename%),
 * và giá trị trống đó KHÔNG nằm trong postmeta. Nên phải tự lùi về tiêu đề bài
 * và excerpt, nếu không Next.js sẽ nhận null và trang mất thẻ title.
 */
add_action( 'rest_api_init', function () {

	foreach ( array( 'service', 'project', 'page', 'post' ) as $post_type ) {
		register_rest_field( $post_type, 'seo', array(
			'get_callback' => function ( $arr ) {
				$id = $arr['id'];

				$title = get_post_meta( $id, 'rank_math_title', true );
				$desc  = get_post_meta( $id, 'rank_math_description', true );
				$og_id = get_post_meta( $id, 'rank_math_facebook_image_id', true );

				if ( ! $og_id ) {
					$og_id = get_post_thumbnail_id( $id );
				}

				$robots = get_post_meta( $id, 'rank_math_robots', true );
				$robots = is_array( $robots ) ? $robots : array();

				return array(
					'title'       => $title ? $title : get_the_title( $id ),
					'description' => $desc ? $desc : wp_strip_all_tags( get_the_excerpt( $id ) ),
					'canonical'   => get_post_meta( $id, 'rank_math_canonical_url', true ),
					'og_image'    => $og_id ? wp_get_attachment_image_url( $og_id, 'full' ) : '',
					'noindex'     => in_array( 'noindex', $robots, true ),
					'schema'      => anmi_rankmath_schema( $id ),
				);
			},
			'schema' => null,
		) );
	}
} );

/**
 * Lấy JSON-LD đã dựng xong của Rank Math cho một bài.
 *
 * Vì sao không đọc thẳng postmeta: Rank Math lưu schema dạng template còn
 * nguyên biến — rank_math_schema_Article chứa "%title%", "%date%", chưa thay.
 * Chỉ khi dựng <head> ở frontend nó mới thay biến. Nên phải nhờ chính
 * Rank Math dựng qua endpoint getHead rồi bóc khối ld+json ra.
 *
 * Gọi nội bộ bằng rest_do_request nên không tốn thêm một vòng HTTP.
 *
 * Trả về mảng rỗng nếu Rank Math chưa cài hoặc chưa bật Headless CMS Support —
 * lúc đó frontend chỉ mất schema, các thẻ meta khác vẫn chạy.
 */
function anmi_rankmath_schema( $post_id ) {
	if ( ! class_exists( 'RankMath' ) ) {
		return array();
	}

	$permalink = get_permalink( $post_id );
	if ( ! $permalink ) {
		return array();
	}

	$request = new WP_REST_Request( 'GET', '/rankmath/v1/getHead' );
	$request->set_param( 'url', $permalink );

	$response = rest_do_request( $request );

	if ( $response->is_error() ) {
		return array();
	}

	$data = $response->get_data();
	$head = isset( $data['head'] ) ? $data['head'] : '';

	if ( ! $head ) {
		return array();
	}

	// Bóc mọi khối <script type="application/ld+json"> trong <head>.
	preg_match_all(
		'#<script[^>]*type=["\']application/ld\+json["\'][^>]*>(.*?)</script>#is',
		$head,
		$matches
	);

	$out = array();

	foreach ( $matches[1] as $json ) {
		$decoded = json_decode( trim( $json ), true );
		if ( JSON_ERROR_NONE === json_last_error() && $decoded ) {
			$out[] = $decoded;
		}
	}

	return $out;
}
