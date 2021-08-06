<?php
/**
 * The template for displaying author archive pages
 *
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
<?php
			if ( class_exists( 'HCard_User' ) ) {
				echo HCard_User::hcard(
					get_the_author_meta( 'ID' ),
					array(
						'me' => false
					)
				);
			} else {
				get_template_part( 'template-parts/biography' );
			}

			?>
			</header><!-- .page-header -->

			<?php
			// Start the Loop.
			while ( have_posts() ) :
				the_post();

				/*
				 * Include the Post-Format or Post Kind-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				if ( class_exists( 'Kind_Taxonomy' ) ) {
					get_template_part( 'template-parts/content', get_post_kind() );
				} else {
					get_template_part( 'template-parts/content', get_post_format() );
				}
				// End the loop.
			endwhile;

			// Previous/next page navigation.
			the_posts_pagination(
				array(
					'prev_text'          => iw26_get_icon( 'previous' ). '<span class="screen-reader-text">' . __( 'Previous page', 'iw26' ) . '</span>',
					'next_text'          => iw26_get_icon( 'next') . '<span class="screen-reader-text">' . __( 'Next page', 'iw26' ) . '</span>',
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'iw26' ) . ' </span>',
				)
			);

			// If no content, include the "No posts found" template.
		else :
			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
