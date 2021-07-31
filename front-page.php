<?php
/*
Template Name: HCard Page
* This template displays a full-width h-card of the author by default
*
*/

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php
			if ( class_exists( 'HCard_User' ) ) {
				if ( get_option( 'iw_single_author' ) ) {
					echo HCard_User::hcard(
						get_option( 'iw_default_author' ),
						array(
							'me' => true,
						)
					);
				}
			} else {
				get_template_part( 'template-parts/biography' );
			}
			
		?>
		<hr />
		<ul class="front-page-list">
		<?php
		if ( have_posts() && ! is_singular() ) {
			?><h2><?php _e( 'Recent Posts', 'iw26' );?></h2> <?php
			// Start the loop.
			while ( have_posts() ) {
				the_post();
				/*
				 * If installed, include the Post-Kind-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Kind name) and that will be used instead.
				 */
				if ( class_exists( 'Kind_Taxonomy' ) ) {
					get_template_part( 'template-parts/content-summary', get_post_kind() );
				} else {
					get_template_part( 'template-parts/content-summary' );
				}
				// End the loop.
				// End of the loop.
			}
		}
		?>
		</ul>
		<?php
			// Previous/next page navigation.
			the_posts_navigation(
				array(
					'prev_text'          => __( 'Older Posts', 'iw26' ),
					'next_text'          => __( 'Newer Posts', 'iw26' ),
				)
			);
		?>

	</main><!-- .site-main -->

	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
