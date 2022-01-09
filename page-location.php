<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
	<header class="entry-header">
		<h1 class="p-name"><?php _e( 'Locations', 'simple-location' ); ?> </h1>
	</header><!-- .entry-header -->
			<div class="content">
		<?php 
		if ( class_exists( 'Location_Taxonomy' ) ) {
			if ( method_exists( 'list_locations', 'Location_Taxonomy' ) ) {
				Location_Taxonomy::list_locations();
			} else {
				wp_list_categories( 
					array(
						'taxonomy' => 'location',
						'title_li' => null,
					)
				);
			}
		}
			// Start the loop.
		while ( have_posts() ) :
			the_post();
			?>
				<?php the_content(); ?>
				</div><!-- .content -->
			<?php

			// End of the loop.
			endwhile;
		?>

	</main><!-- .site-main -->

	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
