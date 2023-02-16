<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

    <?php if ( have_posts() ) : ?>

        <header class="page-header">
            <h1 class="page-title"><?php post_type_archive_title(); ?></h1>
        </header>

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
                    <h3 class="audio-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                </div>
                <?php
            }

        // End the loop
        endwhile;

        // Display pagination links
        the_posts_pagination();

    // If no posts are found
    else :

        get_template_part( 'template-parts/content', 'none' );

    endif;
    ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
