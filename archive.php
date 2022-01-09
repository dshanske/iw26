<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
			<?php
					the_archive_thumbnail();
					the_archive_title( '<h1 class="page-title p-name">', '</h1>' );
					the_archive_description( '<div class="taxonomy-description p-summary">', '</div>' );
					printf( '<a class="u-url" href="%1$s"></a>', get_self_link() );

			?>
			<?php 
			if ( class_exists( 'Simple_Location_Plugin' ) ) {
				$self = get_self_link();
				$self = trailingslashit( $self ) . 'map';
			} 
			?>

			<a href="<?php echo $self; ?>">Map</a>
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
					'prev_text'          => iw26_get_icon( 'previous' ) . '<span class="screen-reader-text">' . __( 'Previous page', 'iw26' ) . '</span>',
					'next_text'          => iw26_get_icon( 'next' ) . '<span class="screen-reader-text">' . __( 'Next page', 'iw26' ) . '</span>',
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
