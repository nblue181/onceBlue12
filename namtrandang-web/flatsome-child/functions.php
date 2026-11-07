<?php
/**
 * Nam làm WEB — Flatsome Child theme bootstrap.
 *
 * @package nlw
 */

defined( 'ABSPATH' ) || exit;

define( 'NLW_VERSION', '1.4.1' );
define( 'NLW_DIR', get_stylesheet_directory() );
define( 'NLW_URI', get_stylesheet_directory_uri() );

require_once NLW_DIR . '/inc/helpers.php';
require_once NLW_DIR . '/inc/setup.php';
require_once NLW_DIR . '/inc/post-types.php';
require_once NLW_DIR . '/inc/fields.php';
require_once NLW_DIR . '/inc/options.php';
require_once NLW_DIR . '/inc/shortcodes.php';
require_once NLW_DIR . '/inc/media.php';
require_once NLW_DIR . '/inc/seo.php';

if ( is_admin() ) {
	require_once NLW_DIR . '/inc/seed.php';
}
