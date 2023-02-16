<?php
function hmdqr_audiopress_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'posts_per_page' => 10,
    ), $atts );

    $args = array(
        'post_type'      => 'audio',
        'posts_per_page' => intval( $atts['posts_per_page'] ),
    );

    $query = new WP_Query( $args );

    if ( $query->have_posts() ) {
        ob_start();
        while ( $query->have_posts() ) {
            $query->the_post();
            ?>
            <div class="audio-post">
                <h3 class="audio-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="audio-thumbnail"><?php the_post_thumbnail( 'medium' ); ?></div>
                <?php endif; ?>
                <div class="audio-description"><?php the_content(); ?></div>
            </div>
            <?php
        }
        wp_reset_postdata();
        return ob_get_clean();
    } else {
        return __( 'No audio posts found', 'hmdqr-audiopress' );
    }
}
add_shortcode( 'hmdqr-audiopress', 'hmdqr_audiopress_shortcode' );

function hmdqr_audiopress_user_audio_shortcode( $atts ) {
    ob_start();

    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();

        $args = array(
            'post_type'      => 'audio',
            'author'         => $user_id,
            'posts_per_page' => -1,
        );

        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();

                $title = get_the_title();
                $description = get_the_content();
                $url = get_post_meta( get_the_ID(), 'audio_file', true );

                if ( $title && $description && $url ) {
                    ?>
                    <div class="user-audio">
                        <h3 class="audio-title"><?php echo esc_html( $title ); ?></h3>
                        <audio src="<?php echo esc_url( $url ); ?>" controls></audio>
                        <div class="audio-description"><?php echo wp_kses_post( $description ); ?></div>
                    </div>
                    <?php
                }
            }
            wp_reset_postdata();
        } else {
            echo esc_html__( 'You have not uploaded any audio yet.', 'hmdqr-audiopress' );
        }
    } else {
        echo esc_html__( 'You must be logged in to view your audio content.', 'hmdqr-audiopress' );
    }

    return ob_get_clean();
}
add_shortcode( 'audiopress_user_audio', 'hmdqr_audiopress_user_audio_shortcode' );

function hmdqr_audiopress_upload_audio_shortcode() {
    ob_start();
    ?>
    <form id="hmdqr-audiopress-upload-form" method="post" enctype="multipart/form-data" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <div>
            <label for="title"><?php esc_html_e( 'Title:', 'hmdqr-audiopress' ); ?></label>
            <input type="text" name="title" id="title" required>
        </div>
        <div>
            <label for="description"><?php esc_html_e( 'Description:', 'hmdqr-audiopress' ); ?></label>
            <textarea name="description" id="description" required></textarea>
        </div>
        <div>
            <label for="audio"><?php esc_html_e( 'Audio file:', 'hmdqr-audiopress' ); ?></label>
            <input type="file" name="audio" id="audio" accept=".mp3,.ogg,.wav" required>
        </div>
        <div>
            <input type="hidden" name="action" value="hmdqr_audiopress_process_form">
            <?php wp_nonce_field( 'hmdqr_audiopress_upload_audio', 'hmdqr_audiopress_upload_audio_nonce' ); ?>
            <button type="submit"><?php esc_html_e( 'Upload', 'hmdqr-audiopress' ); ?></button>
        </div>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode( 'hmdqr-audiopress-upload-audio', 'hmdqr_audiopress_upload_audio_shortcode' );
