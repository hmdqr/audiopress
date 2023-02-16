<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

    <?php
    // Start the loop
    while ( have_posts() ) : the_post();

        // Get the audio file URL
        $audio_file = get_post_meta( get_the_ID(), 'audio_file', true );

        // Display the audio player and audio title
        if ( $audio_file ) {
            ?>
            <div class="audio-player">
                <audio src="<?php echo esc_url( $audio_file ); ?>" controls></audio>
                <h3 class="audio-title"><?php the_title(); ?></h3>
            </div>
            <?php
        }

        // Display the post content
        the_content();

        // Display like and dislike buttons
        if ( function_exists( 'hmdqr_audiopress_display_rating_buttons' ) ) {
            hmdqr_audiopress_display_rating_buttons();
        }

        // Display comments section
        if ( comments_open() || get_comments_number() ) {
            comments_template();
        }

    // End the loop
    endwhile;
    ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
