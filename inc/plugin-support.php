<?php
/**
 * Code that improves theme support for various plugins
 */

function twenty_sixteen_indieweb_plugin_support() {
	/*
	 * Adds support for Syndication Links
	 */
	if ( class_exists( 'Syn_Meta' ) && has_filter( 'the_content', array( 'Syn_Config', 'the_content' ) ) ) {
		remove_filter( 'the_content', array( 'Syn_Config', 'the_content' ), 30 );
	}
	/*
	 * Adds support for Simple Location
	 */
	if ( class_exists( 'Simple_Location_Plugin' ) && has_filter( 'the_content', array( 'Geo_Data', 'location_content' ) ) ) {
		remove_filter( 'the_content', array( 'Geo_Data', 'location_content' ), 12 );
	}

}

add_action( 'init', 'twenty_sixteen_indieweb_plugin_support', 11 );
