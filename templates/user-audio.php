<?php
/**
 * Template Name: User Audio
 */

get_header(); ?>

<main id="primary" class="site-main">
    <?php while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
            </header>

            <div class="entry-content">
                <?php the_content(); ?>
                <?php echo do_shortcode( '[audiopress_user_audio]' ); ?>
            </div>

            <footer class="entry-footer">
                <?php edit_post_link( __( 'Edit', 'hmdqr-audiopress' ), '<span class="edit-link">', '</span>' ); ?>
            </footer>
        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
