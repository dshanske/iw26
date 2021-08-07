/**
 * Live-update changed settings in real time in the Customizer preview.
 */

( function( $ ) {
	// Site title.
	api( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );

	// Site tagline.
	api( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Add custom-background-image body class when background image is added.
	api( 'background_image', function( value ) {
		value.bind( function( to ) {
			$( 'body' ).toggleClass( 'custom-background-image', '' !== to );
		} );
	} );

} )( jQuery );
