<?php
/**
 * The template part for displaying an Author biography
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<div class="p-author h-card author-info">
	<div class="author-avatar">
		<?php
		/**
		 * Filter the Twenty Sixteen author bio avatar size.
		 *
		 * @since Twenty Sixteen 1.0
		 *
		 * @param int $size The avatar height and width size in pixels.
		 */
		$author_bio_avatar_size = apply_filters( 'iw26_author_bio_avatar_size', 42 );
		$id = get_the_author_meta( 'ID' );
		if ( ! $id ) {
			$id = get_option( 'iw_default_author', 0 );
		}
		echo get_avatar( $id, $author_bio_avatar_size );
		?>
	</div><!-- .author-avatar -->

	<div class="author-description">
		<h2 class="author-title">
		<a class="p-name u-url author-link" href="<?php echo esc_url( get_author_posts_url( $id ) ); ?>"><?php echo get_the_author(); ?></a></h2>

		<p class="author-bio">
			<?php the_author_meta( 'description' ); ?>
		</p><!-- .author-bio -->
	</div><!-- .author-description -->
</div><!-- .author-info -->
