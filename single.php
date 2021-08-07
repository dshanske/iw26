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
		<?php
		// Start the loop.
		while ( have_posts() ) :
			the_post();

			// Include the single post content template.
			get_template_part( 'template-parts/content', 'single' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}

			if ( is_singular( 'attachment' ) ) {
				// Parent post navigation.
				the_post_navigation(
					array(
						'prev_text' => _x( '<span class="meta-nav">Published in</span><span class="post-title">%title</span>', 'Parent post link', 'iw26' ),
					)
				);
			} elseif ( is_singular( 'post' ) ) {
				
				$series = get_the_terms( get_the_ID(), 'series' );
				if( ! empty( $series ) ) {
					$series = iw26_get_single_post_term_name( 'series' );
					// Previous/next post navigation.
					the_post_navigation(
						array(
							'next_text' => '<span class="meta-nav" aria-hidden="true">' . 
								sprintf( __( 'Next in %1$s Series', 'iw26' ), $series ) . '</span> ' .
								'<span class="screen-reader-text">' . __( 'Next post in Series:', 'iw26' ) . '</span> ' .
								'<span class="post-title">%title</span>',
							'prev_text' => '<span class="meta-nav" aria-hidden="true">' . 
								sprintf( __( 'Previous in %1$s Series', 'iw26' ), $series ) . '</span> ' .
								'<span class="screen-reader-text">' . __( 'Previous post in Series:', 'iw26' ) . '</span> ' .
								'<span class="post-title">%title</span>',
							'in_same_term' => 'true',
							'taxonomy' => 'series'
						)
					);
				} else if ( class_exists( 'Kind_Taxonomy' ) ) {
					$kind = get_post_kind( get_post() );
					// Previous/next post navigation.
					the_post_navigation(
						array(
							'next_text' => '<span class="meta-nav" aria-hidden="true">' . 
								sprintf( __( 'Next %1s', 'iw26' ), $kind ) . '</span> ' .
								'<span class="screen-reader-text">' . 
								sprintf( __( 'Next %1s:', 'iw26' ), $kind ) . '</span> ' .
								'<span class="post-title">%title</span>',
							'prev_text' => '<span class="meta-nav" aria-hidden="true">' . 
								sprintf( __( 'Previous %1$s', 'iw26' ), $kind ) . '</span> ' .
								'<span class="screen-reader-text">' .
								sprintf( __( 'Previous %1s:', 'iw26' ), $kind ) . '</span> ' .
								'<span class="post-title">%title</span>',
							'in_same_term' => 'true',
							'taxonomy' => 'kind'
						)
					);
				} else {
					// Previous/next post navigation.
					the_post_navigation(
						array(
							'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next', 'iw26' ) . '</span> ' .
								'<span class="screen-reader-text">' . __( 'Next post:', 'iw26' ) . '</span> ' .
								'<span class="post-title">%title</span>',
							'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous', 'iw26' ) . '</span> ' .
								'<span class="screen-reader-text">' . __( 'Previous post:', 'iw26' ) . '</span> ' .
								'<span class="post-title">%title</span>',
						)
					);
				}
			}

			// End of the loop.
		endwhile;
		?>

	</main><!-- .site-main -->

	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
