(function($) {
	var taxToggle = $( '.js-has-taxonomy input' );
    console.log( 'totally loaded', taxToggle.val() );



    function handleVisibility( event ) {
        if ( taxToggle.is(':checked') ) {
            $( '.js-category' ).show();
        } else {
            $( '.js-category' ).hide();
        }
    }

    $(document).ready( handleVisibility );
    $(document).on('widget-updated widget-added', handleVisibility );
    taxToggle.change( handleVisibility );
      
})( jQuery );