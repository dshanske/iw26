<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<section class="error-404 not-found">
			<?php if ( iw26_is_404_singular() ) { ?>
					<header class="page-header">
						<h1 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'iw26' ); ?></h1>
					</header><!-- .page-header -->
					<div class="page-content">
						<p><?php _e( 'It looks like nothing was found at this location. Maybe try a search?', 'iw26' ); ?></p>
	
						<?php get_search_form(); ?>
	
						<br />

	
						<p><?php _e( 'Or search for posts by their syndicated copies', 'iw26' ); ?></p>					
	
						<?php
						if ( function_exists( 'get_original_of_form' ) ) {
							get_original_of_form();
						}
						?>
					</div><!-- .page-content -->
			<?php } else { ?>
					<header class="page-header">
						<h1 class="page-title"><?php _e( 'Oops! That archive can&rsquo;t be found.', 'iw26' ); ?></h1>
					</header><!-- .page-header -->
					<div class="page-content">
						<p><?php _e( 'It looks there are no posts available', 'iw26' ); ?></p>
	
						<?php get_search_form(); ?>
	
						<br />

	
						<p><?php _e( 'Or search for posts by their syndicated copies', 'iw26' ); ?></p>					
	
						<?php
						if ( function_exists( 'get_original_of_form' ) ) {
							get_original_of_form();
						}
						?>
					</div><!-- .page-content -->
			<?php } ?>
				</section><!-- .error-404 -->

		</main><!-- .site-main -->

		<?php get_sidebar( 'content-bottom' ); ?>

	</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
