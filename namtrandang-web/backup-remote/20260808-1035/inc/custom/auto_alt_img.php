<?php
function auto_set_image_alt_keep_filename( $post_ID ) {
    if ( ! wp_attachment_is_image( $post_ID ) ) {
        return;
    }

    // Lấy đường dẫn file gốc trong uploads (ổn định hơn guid).
    $file_path = get_attached_file( $post_ID );
    if ( ! $file_path ) {
        return;
    }

    // surface-khong-nhan-ban-phim-2.webp  ->  surface-khong-nhan-ban-phim-2
    $filename  = pathinfo( $file_path, PATHINFO_FILENAME );

    // Nếu muốn đảm bảo sạch ký tự lạ (tuỳ bạn, có thể bỏ dòng này):
    $filename  = sanitize_text_field( $filename );

    // Set Title/Caption/Description
    wp_update_post( array(
        'post_title'   => $filename,
    ) );
}
add_action( 'add_attachment', 'auto_set_image_alt_keep_filename' );
?>