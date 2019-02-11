<?php
/**
 * The template for displaying search forms in Indieweb Publisher
 *
 * @package Indieweb Publisher
 */
?>
<form method="get" id="originalofform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
	<label for="s" class="screen-reader-text"><?php esc_html_e( 'Search', 'indieweb-publisher' ); ?></label>
	<input type="text" class="field" name="original-of" value="<?php echo esc_attr( get_query_var( 'original-of' ) ); ?>" id="original-of" placeholder="<?php esc_attr_e( 'Original Of &hellip;', 'indieweb-publisher' ); ?>" />
	<input type="submit" class="submit" id="originalofsubmit" value="<?php esc_attr_e( 'Lookup Original', 'indieweb-publisher' ); ?>" />
</form>
