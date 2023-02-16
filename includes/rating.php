<?php

function hmdqr_audiopress_display_rating_buttons() {
    // Get the current post ID
    $post_id = get_the_ID();

    // Get the current user ID
    $user_id = get_current_user_id();

    // Get the current like and dislike counts
    $like_count = get_post_meta( $post_id, 'hmdqr_audiopress_like_count', true );
    $dislike_count = get_post_meta( $post_id, 'hmdqr_audiopress_dislike_count', true );

    // Get the user's current vote (if any)
    $user_vote = '';
    if ( $user_id ) {
        $user_vote = get_user_meta( $user_id, 'hmdqr_audiopress_user_vote_' . $post_id, true );
    }

    // Output the like and dislike buttons
    ?>
    <div class="like-dislike-buttons">
        <button class="like-button <?php echo $user_vote === 'like' ? 'active' : ''; ?>" data-vote="like" data-post-id="<?php echo $post_id; ?>"><?php echo $like_count; ?> Likes</button>
        <button class="dislike-button <?php echo $user_vote === 'dislike' ? 'active' : ''; ?>" data-vote="dislike" data-post-id="<?php echo $post_id; ?>"><?php echo $dislike_count; ?> Dislikes</button>
    </div>
    <?php
}

function hmdqr_audiopress_process_vote() {
    // Make sure the request is valid
    if ( ! isset( $_POST['post_id'], $_POST['vote'] ) || ! is_numeric( $_POST['post_id'] ) ) {
        wp_send_json_error( 'Invalid request.' );
    }

    // Get the current post ID and user ID
    $post_id = intval( $_POST['post_id'] );
    $user_id = get_current_user_id();

    // Make sure the user is not a spam bot
    if ( isset( $_POST['hmdqr_audiopress_nickname'] ) && ! empty( $_POST['hmdqr_audiopress_nickname'] ) ) {
        wp_send_json_error( 'Spam detected.' );
    }

    // Make sure the user has not already voted on this post
    $user_vote = get_user_meta( $user_id, 'hmdqr_audiopress_user_vote_' . $post_id, true );
    if ( $user_vote ) {
        wp_send_json_error( 'You have already voted on this post.' );
    }

    // Get the current like and dislike counts
    $like_count = get_post_meta( $post_id, 'hmdqr_audiopress_like_count', true );
    $dislike_count = get_post_meta( $post_id, 'hmdqr_audiopress_dislike_count', true );

    // Update the appropriate count and save the user's vote
    if ( $_POST['vote'] === 'like' ) {
        $like_count++;
        update_post_meta( $post_id, 'hmdqr_audiopress_like_count', $like_count );
    } elseif ( $_POST['vote'] === 'dislike' ) {
        $dislike_count++;
        update_post_meta( $post_id, 'hmdqr_audiopress_dislike_count', $dislike_count );
    }
    update_user_meta( $user_id, 'hmdqr_audiopress_user_vote_' . $post_id, $_POST );

    // Return the updated counts
    wp_send_json_success( array(
        'like_count'    => $like_count,
        'dislike_count' => $dislike_count,
    ) );
}
add_action( 'wp_ajax_hmdqr_audiopress_process_vote', 'hmdqr_audiopress_process_vote' );
add_action( 'wp_ajax_nopriv_hmdqr_audiopress_process_vote', 'hmdqr_audiopress_process_vote' );
