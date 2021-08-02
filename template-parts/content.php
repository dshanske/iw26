<?php
/**
 * The template part for displaying content
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
			<span class="sticky-post"><?php _e( 'Featured', 'iw26' ); ?></span>
		<?php endif; ?>

		<?php
		if ( class_exists( 'Kind_Taxonomy' ) && 'article' === get_post_kind_slug() ) {
			the_title( sprintf( '<h2 class="entry-title p-name"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
		}
		?>
	</header><!-- .entry-header -->

	<?php iw26_post_thumbnail(); ?>

	<div class="content">
<?php
if ( ! has_content() && has_excerpt() ) {
	the_excerpt();
} else {
	/* translators: %s: Name of current post */
	the_content(
		sprintf(
			__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'iw26' ),
			get_the_title()
		)
	);
}
?>
	</div><!-- .content -->

	<footer class="entry-footer">
		<?php
			wp_link_pages(
				array(
					'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'iw26' ) . '</span>',
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
					'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'iw26' ) . ' </span>%',
					'separator'   => '<span class="screen-reader-text">, </span>',
				)
			);
			iw26_entry_meta();
			?>
		<?php
			edit_post_link(
				iw26_get_icon( 'edit' ) . 
				sprintf(
					/* translators: %s: Name of current post */
					__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'iw26' ),
					get_the_title()
				),
				'<span class="edit-link">',
				'</span>'
			);
			?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
