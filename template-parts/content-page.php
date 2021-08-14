<?php
/**
 * The template used for displaying page content
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

	<?php
		iw26_entry_meta();

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
	</header><!-- .entry-header -->

	<?php iw26_post_thumbnail(); ?>

	<div class="content">
		<?php
		the_content();

		?>
	</div><!-- .content -->
	<footer class="entry-footer">
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
