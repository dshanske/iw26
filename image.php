<?php
/**
 * The template for displaying image attachments
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
				?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<nav id="image-navigation" class="navigation image-navigation">
					<div class="nav-links">
						<div class="nav-previous"><?php previous_image_link( false, __( 'Previous Image', 'iw26' ) ); ?></div>
						<div class="nav-next"><?php next_image_link( false, __( 'Next Image', 'iw26' ) ); ?></div>
					</div><!-- .nav-links -->
				</nav><!-- .image-navigation -->

				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header><!-- .entry-header -->

				<div class="entry-content">

					<div class="entry-attachment">
						<?php
							/**
							 * Filter the default iw26 image attachment size.
							 *
							 * @since Twenty Sixteen 1.0
							 *
							 * @param string $image_size Image size. Default 'large'.
							 */
							$image_size = apply_filters( 'iw26_attachment_size', 'large' );

							printf( '<a href="%1$s">%2$s</a>', esc_url( wp_get_attachment_url() ),	wp_get_attachment_image( get_the_ID(), $image_size ) );
						?>

						<?php iw26_excerpt( 'entry-caption' ); ?>

						</div><!-- .entry-attachment -->

						<?php
						the_content();
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
						?>
					</div><!-- .entry-content -->

					<footer class="entry-footer">
					<?php iw26_entry_meta(); ?>
						<?php
						// Retrieve attachment metadata.
						$metadata = wp_get_attachment_metadata();
						if ( $metadata ) {
							if ( array_key_exists( 'camera', $metadata['image_meta'] ) ) {
								printf(
									'<span class="camera-type"><span class="screen-reader-text">%1$s</span>%2$s</span>',
									_x( 'Camera Model', 'Used before camera model.', 'iw26' ),
									$metadata['image_meta']['camera']
								);
							}
						}
						$source = get_post_meta( get_the_ID(), '_source_url', true );
						if ( $source ) {
							printf(
								'<span class="original-media-url"><span class="screen-reader-text">%1$s</span><a href="%2$s">%3$s</a></span>',
								_x( 'Original Source URL', 'Used before original source URL.', 'iw26' ),
								esc_url( $source ),
								__( 'Original Source URL:', 'iw26' )
							);
						}
						?>
						<?php
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

				<?php
				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}

				// Parent post navigation.
				the_post_navigation(
					array(
						'prev_text' => _x( '<span class="meta-nav">Published in</span><span class="post-title">%title</span>', 'Parent post link', 'iw26' ),
					)
				);
				// End the loop.
				endwhile;
			?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
