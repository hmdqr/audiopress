jQuery( function( $ ) {
  // Handle audio submission form submission
  $( '#audiopress-form' ).submit( function( e ) {
    e.preventDefault();

    var formData = new FormData( this );
    var $submitButton = $( this ).find( 'input[type="submit"]' );
    var originalButtonText = $submitButton.val();
    $submitButton.val( 'Submitting...' ).prop( 'disabled', true );

    $.ajax( {
      url: audiopress_ajax_data.ajax_url,
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function( data ) {
        $submitButton.val( originalButtonText ).prop( 'disabled', false );

        // Parse the response data
        var response = JSON.parse( data );

        // Check for errors
        if ( response.success === false ) {
          alert( response.data.message );
          return;
        }

        // Display a success message and clear the form
        alert( response.data.message );
        $( '#audiopress-form' )[0].reset();
      },
      error: function( jqXHR, textStatus, errorThrown ) {
        console.error( 'Ajax error: ' + textStatus );
        $submitButton.val( originalButtonText ).prop( 'disabled', false );
      }
    } );
  } );

  // Handle audio post rating
  $( '.audio-post' ).on( 'click', '.audio-like, .audio-dislike', function( e ) {
    e.preventDefault();

    var $button = $( this );
    var postID = $button.data( 'post-id' );
    var ratingType = $button.data( 'rating-type' );

    $button.prop( 'disabled', true );

    $.ajax( {
      url: audiopress_ajax_data.ajax_url,
      type: 'POST',
      data: {
        action: 'hmdqr_audiopress_rate_audio',
        post_id: postID,
        rating_type: ratingType,
      },
      success: function( data ) {
        $button.prop( 'disabled', false );

        // Parse the response data
        var response = JSON.parse( data );

        // Check for errors
        if ( response.success === false ) {
          alert( response.data.message );
          return;
        }

        // Update the UI with the new rating data
        $( '.audio-post[data-post-id="' + postID + '"] .audio-like-count' ).text( response.data.likes );
        $( '.audio-post[data-post-id="' + postID + '"] .audio-dislike-count' ).text( response.data.dislikes );
      },
      error: function( jqXHR, textStatus, errorThrown ) {
        console.error( 'Ajax error: ' + textStatus );
        $button.prop( 'disabled', false );
      }
    } );
  } );
} );
