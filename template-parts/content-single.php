<?php
/**
 * The template part for displaying single posts
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title p-name">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<?php iw26_post_thumbnail(); ?>

	<div class="content">
	<?php

	if ( ! has_content() && has_excerpt() ) {
		the_excerpt();
	} else {
		the_content();
	}
			 // Hide biography in favor of h-card widget
	if ( ! is_string( is_active_widget( false, false, 'hcard_widget', true ) ) ) {
		get_template_part( 'template-parts/biography' );
	}
	?>
	</div><!-- .content -->

	<footer class="entry-footer">
		<?php iw26_entry_meta(); ?>
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

			edit_post_link(
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
