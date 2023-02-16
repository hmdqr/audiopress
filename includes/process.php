<?php
if ( isset( $_POST['action'] ) && $_POST['action'] == 'hmdqr_audiopress_process_form' ) {
    if ( ! function_exists( 'wp_verify_nonce' ) ) {
        require_once ABSPATH . 'wp-includes/pluggable.php';
    }

    if ( ! wp_verify_nonce( $_POST['hmdqr_audiopress_upload_audio_nonce'], 'hmdqr_audiopress_upload_audio' ) ) {
        wp_die( esc_html__( 'Nonce verification failed', 'hmdqr-audiopress' ) );
    }

    $title = sanitize_text_field( $_POST['title'] );
    $description = sanitize_textarea_field( $_POST['description'] );

    if ( empty( $title ) ) {
        wp_die( esc_html__( 'Please enter a title', 'hmdqr-audiopress' ) );
    }

    if ( empty( $description ) ) {
        wp_die( esc_html__( 'Please enter a description', 'hmdqr-audiopress' ) );
    }

    $uploaded_file = $_FILES['audio'];

    if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
    }
    $upload_overrides = array( 'test_form' => false );
    $movefile = wp_handle_upload( $uploaded_file, $upload_overrides );

    if ( $movefile && ! isset( $movefile['error'] ) ) {
        $wp_upload_dir = wp_upload_dir();

        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $file_url = $wp_upload_dir['baseurl'] . '/audio/' . basename( $movefile['file'] );
        $file_path = $movefile['file'];

        $attachment_id = media_handle_sideload( array(
            'name' => basename( $movefile['file'] ),
            'type' => $movefile['type'],
            'tmp_name' => $movefile['file']
        ), 0 );

        if ( is_wp_error( $attachment_id ) ) {
            wp_die( esc_html__( 'Error creating attachment', 'hmdqr-audiopress' ) );
        }

        $metadata = wp_read_audio_metadata( $file_path );
        $duration = 0;
        if ( ! empty( $metadata['length_formatted'] ) ) {
            $duration = $metadata['length_formatted'];
        }

        $post = array(
            'post_title'   => $title,
            'post_content' => $description,
            'post_status'  => 'publish',
            'post_type'    => 'hmdqr_audio',
            'meta_input'   => array(
                '_audiopress_file' => $attachment_id,
                '_audiopress_duration' => $duration
            )
        );

        $post_id = wp_insert_post( $post );

        if ( $post_id ) {
            update_post_meta($post_id, '_hmdqr_audio_file_path', $file_url);
            wp_safe_redirect( get_permalink( $post_id ) );
            exit;
        } else {
            wp_die( esc_html__( 'Error creating post', 'hmdqr-audiopress' ) );
        }
    } else {
        wp_die( esc_html__( 'Error uploading file', 'hmdqr-audiopress' ) );
    }
}
?>
