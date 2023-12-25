<?php
/**
 * The template part for displaying a summary of conten)t
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<li class="kind-titles">
	<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
		<span class="sticky-post"><?php _e( 'Featured', 'iw26' ); ?></span>
	<?php endif; ?>
	<?php
	if ( ! class_exists( 'Kind_Taxonomy' ) ) {
		the_title( sprintf( '<a class="entry-title p-name u-url" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' );
	} else {
		if ( function_exists( 'kind_get_the_link' ) ) {
			echo kind_get_the_link( null, array( 'u-url', 'p-summary' ), 'dt-published' );
			$args = array(
				'text'             => false,
				'icons'            => false,
				'show_text_before' => false,
			);
			echo get_syndication_links( get_the_ID(), $args );
		} else {
			echo iw26_post_link();
		}
	}
	?>
	</li>
</article><!-- #post-## -->
