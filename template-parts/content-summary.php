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
	if ( class_exists( 'Kind_Taxonomy' ) && 'article' === get_post_kind_slug() ) {
		the_title( sprintf( '<a class="entry-title p-name" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
	} else {
		echo iw26_post_link();
	}
	?>
	</li>
</article><!-- #post-## -->
