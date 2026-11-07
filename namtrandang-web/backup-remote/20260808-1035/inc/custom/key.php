<?php
update_option('flatsome_wup_purchase_code','B5E0B5F8DD8689E6ACA49DD6E6E1A930' );
update_option( 'flatsome_wup_supported_until', '01.01.2050' );
update_option( 'flatsome_wup_buyer', 'Licensed' );
update_option( 'flatsome_wup_sold_at', time() );
delete_option( 'flatsome_wup_errors', '' );
delete_option( 'flatsome_wupdates', '');
add_action('admin_head', 'custom_css_backend');
function custom_css_backend() { echo '<style>#flatsome-notice {display: none;}</style>'; }
?>