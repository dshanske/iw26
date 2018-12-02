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
		// Start the loop.
			while ( have_posts() ) :
			the_post();
			if ( class_exists( 'HCard_User' ) ) {
				echo HCard_User::hcard( 
					get_the_author_meta( 'ID' ),
					array(
						'me' => true
					)
				);
			} else {
				get_template_part( 'template-parts/biography' );
			}
		
			the_content();

			/* If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			 */

			// End of the loop.
		endwhile;
		?>

	</main><!-- .site-main -->

	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->

<?php get_footer(); ?>
