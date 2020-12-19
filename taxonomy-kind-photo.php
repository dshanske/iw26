<?php
/**
 * The template for displaying kind archive pages
 *
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
			<?php
					$term = get_queried_object();
					the_archive_title( '<h1 class="page-title">' . Kind_Taxonomy::get_icon( $term->slug ), '</h1>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );

			?>
			</header><!-- .page-header -->
			<div class="photogrid">
			<ul>
			<?php
			// Start the Loop.
			while ( have_posts() ) :
				the_post();
				if ( class_exists( 'Kind_Post' ) ) {
					$kind_post = new Kind_Post( get_the_ID() );
					$photos = $kind_post->get_photo();
					if ( ! empty( $photos ) ) {
						foreach( $photos as $photo ) {
							printf( '<li class="h-entry"><a class="u-url" href="%1$s"><img src="%2$s" srcset="%3$s" alt="%4$s" /></a></li>', get_permalink(), wp_get_attachment_image_url( $photo ), wp_get_attachment_image_srcset( $photo ), get_the_excerpt( $photo ) );
						}
					}
					// $view = new Kind_Media_View( $photos, 'photo' );
					
				}
				// End the loop.
			endwhile; 
			?> 
			</ul> 
			</div>
			<?php

			// Previous/next page navigation.
			the_posts_pagination(
				array(
					'prev_text'          => __( 'Previous page', 'iw26' ),
					'next_text'          => __( 'Next page', 'iw26' ),
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


<?php
	get_sidebar();
	get_footer(); 
?>
