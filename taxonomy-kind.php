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
					the_archive_title( '<h1 class="page-title p-name">' . Kind_Taxonomy::get_icon( $term->slug ), '</h1>' );
					the_archive_description( '<div class="taxonomy-description p-summary">', '</div>' );
					printf( '<a class="u-url" href="%1$s"></a>', get_self_link() );

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
