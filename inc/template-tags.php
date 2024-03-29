<?php
/**
 * Custom IW26 template tags
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WordPress
 */

if ( ! function_exists( 'iw26_single_author_site' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags.
	 *
	 * Create your own iw26_single_author_site() function to override in a child theme.
	 *
	 * @since Twenty Sixteen 1.0
	 */
	function iw26_single_author_site() {
		if ( ! is_multi_author() && is_front_page() && class_exists( 'IndieWeb_Plugin' ) ) {
			$author_avatar_size = apply_filters( 'iw26_author_avatar_size', 49 );
			printf(
				'<span class="byline"><span class="p-author author vcard h-card">%1$s<span class="screen-reader-text">%2$s </span> <a class="url fn n u-url" href="%3$s">%4$s</a></span></span>',
				get_avatar( get_option( 'iw_default_author', get_the_author_meta( 'ID' ) ), $author_avatar_size ),
				_x( 'Author', 'Used before post author name.', 'iw26' ),
				esc_url( get_author_posts_url( get_option( 'iw_default_author' ) ) ),
				get_the_author()
			);
		}
	}
endif;

if ( ! function_exists( 'iw26_entry_meta' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags.
	 *
	 * Create your own iw26_entry_meta() function to override in a child theme.
	 *
	 * @since Twenty Sixteen 1.0
	 */
	function iw26_entry_meta() {
		if ( 'post' === get_post_type() ) {
			if ( ! is_singular() ) {
				$author_avatar_size = apply_filters( 'iw26_author_avatar_size', 49 );
				printf(
					'<span class="byline"><span class="screen-reader-text">%1$s</span><span class="author p-author vcard h-card">%2$s <a class="url fn n u-url" href="%3$s">%4$s</a></span></span>',    
					_x( 'Author', 'Used before post author name.', 'iw26' ),
					get_avatar( get_the_author_meta( 'ID' ), $author_avatar_size ),
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
					get_the_author()
				);
			}
		}

		if ( in_array( get_post_type(), array( 'post', 'attachment', 'page' ) ) ) {
			iw26_entry_date();
		}


		if ( 'page' === get_post_type() ) {
			echo '<span>';
			iw26_page_children();
			echo '</span>';
		}


		if ( 'post' === get_post_type() ) {
			if ( class_exists( 'Kind_Taxonomy' ) ) {
				$kind = get_post_kind();
				printf(
					'<span class="entry-kind">%1$s<a href="%2$s">%3$s %4$s</a></span>',
					sprintf( '<span class="screen-reader-text">%s </span>', _x( 'Kind', 'Used before post kind.', 'iw26' ) ),
					esc_url( get_post_kind_link( $kind ) ),
					Kind_Taxonomy::get_icon( get_post_kind_slug() ),
					get_post_kind_string( $kind )
				);
			}
			iw26_entry_taxonomies();
		}

		if ( class_exists( 'Simple_Location_Plugin' ) ) {
			echo get_post_location();
			if ( 'venue' === get_post_type() ) {
				$url = get_post_meta( get_the_ID(), 'venue_url', true );
				if ( $url ) {
					echo '<br /><span class="venue_url">';
					printf( __( 'Also at: <a href="%s">%s</a>', 'simple-location' ), $url, wp_parse_url( $url, PHP_URL_HOST ) );
					echo '</span>';
				}
				$types = Post_Venue::get_venue_type();
				if ( $types ) {
					echo '<br /><span class="venue_type">';
					foreach( $types as $type ) {
						printf( '<a href="%1$s">%2$s</a>', get_term_link( $type ), get_term( $type, 'venue_type' )->name );
					}
					echo '</span>';
				}
			}
		}

		if ( ! is_singular() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link( iw26_get_icon( 'comment' ) . sprintf( __( 'Leave a response<span class="screen-reader-text"> on %s</span>', 'iw26' ), get_the_title() ) );
			echo '</span>';
		}

		if ( function_exists( 'get_syndication_links' ) ) {
			iw26_syndication_links();
		}

		if ( function_exists( 'micropub_client_info' ) ) {
			micropub_client_info();
		}
	}
endif;

function iw26_related_venues() {
	$parents = Post_Venue::get_venue_ancestors();
	if ( ! empty( $parents ) ) {
		$parents = array_reverse( $parents );
		$return = array();
		foreach ( $parents as $ancestor ) {
			$return[] = sprintf( '<a href="%1$s" rel="up">%2$s</a>', esc_url( get_permalink( $ancestor ) ), get_the_title( $ancestor ) );
		}
		echo '<ul class="breadcrumbs"><li>' . implode( '</li><li>' . iw26_get_icon( 'next' ) . ' ', $return ) . '</li></ul>';
	}
	$checkins = Post_Venue::get_venue_posts();
	if ( empty ( $checkins ) ) {
		return '';
	}
	printf( '<h3>%1$s</h3>', __( 'Checkins at this Venue', 'simple-location' ) );
	echo '<ul class="h-feed">';
	foreach( $checkins as $checkin ) {
		printf( '<li class="h-entry"><a class="u-url" href="%1$s">%2$s</a></li>', get_permalink( $checkin ), get_the_date( '', $checkin ) );
	}
	echo '</ul>';


	$gallery = Post_Venue::get_venue_gallery( );

	if ( ! empty( $gallery ) ) {
		printf( '<h3>%1$s</h3>', __( 'Images from this Venue', 'simple-location' ) );
		echo $gallery;
	}
}

/*
 * Wrapper function for a possible custom display of Syndication Links output
  */
function iw26_syndication_links() {
	$args = array(
		'text'             => false,
		'icons'            => true,
		'show_text_before' => false,
	);
	if ( 'hidden' === Syn_Meta::get_syndication_links_display_option() ) {
		$args['icons'] = false;
	}
	
	echo get_syndication_links( get_the_ID(), $args );
}


if ( ! function_exists( 'iw26_attachment_datetime' ) ) :
	function iw26_attachment_datetime( $post ) {
		$post = get_post( $post );
		if ( ! 'attachment' === get_post_type( $post ) ) {
			return false;
		}
		$data = get_post_meta( $post->ID, '_wp_attachment_metadata', true );
		if ( ! $data ) {
			return;
		}
		$data      = $data['image_meta'];
		$created   = sloc_get_attachment_datetime( $post );
		return $created;
	}
endif;

if ( ! function_exists( 'iw26_date_format' ) ) :
	function iw26_date_format() {
		
		$format = get_option( 'date_format' );

		if ( is_singular( 'post' ) ) {
			$time_format = get_option( 'time_format' );
			$timezone    = get_post_meta( get_the_ID(), 'geo_timezone', true );
			if ( $timezone ) {
				$timezone = new DateTimeZone( $timezone );
				if ( $timezone->getOffset( new Datetime( 'now' ) ) !== wp_timezone()->getOffset( new DateTime( 'now' ) ) ) {
					$time_format = $time_format . ' T';
				}
			}
					
			return $format . ' ' . $time_format;
		}
		
		// This is only used for date archives.
		if ( ! is_date() ) {
			return $format;
		}


		// If this is a On This Week archive.
		if ( empty( get_query_var( 'year' ) ) && empty( get_query_var( 'monthnum' ) ) && ! empty( get_query_var( 'w' ) ) ) {
			return $format;
			// If this is a day archive.
		} elseif ( is_day() ) {
			// If this is an On This Day Archive.
			if ( empty( get_query_var( 'year' ) ) ) {
				$format = 'Y';
			} else {
				$format = get_option( 'time_format' );
			}
		} elseif ( is_year() ) {
			$format = 'M d';
		} elseif ( is_month() ) {
			$format = 'M d';
		}
		return $format;
	}
endif;


if ( ! function_exists( 'iw26_entry_date' ) ) :
	/**
	 * Prints HTML with date information for current post.
	 *
	 * Create your own iw26_entry_date() function to override in a child theme.
	 *
	 * @since Twenty Sixteen 1.0
	 */
	function iw26_entry_date() {
		$post   = get_post();
		$format = iw26_date_format();
		if ( 'attachment' === get_post_type( $post ) ) {
			$created = iw26_attachment_datetime( $post );
			if ( $created instanceof DateTime ) {
				$time_string = '<time class="published dt-published" datetime="%1$s">%2$s</time>';
				$time_string = sprintf(
					$time_string,
					esc_attr( $created->format( DATE_W3C ) ),
					$created->format( iw26_date_format() ),
				);
				printf(
					'<span class="posted-on"><span class="screen-reader-text">%1$s </span><a class="u-url" href="%2$s">%3$s</a></span>',
					_x( 'Taken on', 'Used before date taken.', 'iw26' ),
					esc_url( get_permalink() ),
					iw26_get_icon( 'time' ) .
					$time_string
				);
			}
		} elseif ( 'page' === get_post_type( $post ) ) {
			$modified    = get_post_datetime( $post, 'modified' );
			$time_string = '<time class="entry-date dt-updated" datetime="%1$s">%2$s</time>';

			$time_string = sprintf(
				$time_string,
				esc_attr( $modified->format( DATE_W3C ) ),
				get_the_modified_date( $format, $post ),
			);
			printf(
				'<span class="posted-on">%1$s<a class="u-url" href="%2$s">%3$s</a></span>',
				iw26_get_icon( 'time' ),
				esc_url( get_permalink() ),
				_x( 'Last Modified: ', 'Used before modified date.', 'iw26' ) .
				$time_string
			);

		} else {
			$published   = get_post_datetime( $post, 'date' );
			$modified    = get_post_datetime( $post, 'modified' );
			$time_string = '<time class="entry-date published dt-published" datetime="%1$s">%2$s</time>';

			if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
				$time_string = '<time class="entry-date published dt-published" datetime="%1$s">%2$s</time><time class="updated dt-updated" datetime="%3$s">%4$s</time>';
			}

			$time_string = sprintf(
				$time_string,
				esc_attr( $published->format( DATE_W3C ) ),
				get_the_date( $format, $post ),
				esc_attr( $modified->format( DATE_W3C ) ),
				get_the_modified_date( $format, $post )
			);
			printf(
				'<span class="posted-on"><span class="screen-reader-text">%1$s </span><a class="u-url" href="%2$s">%3$s</a></span>',
				_x( 'Posted on', 'Used before publish date.', 'iw26' ),
				esc_url( get_permalink() ),
				iw26_get_icon( 'time' ) .
				$time_string
			);
		}
	}
endif;

if ( ! function_exists( 'iw26_entry_taxonomies' ) ) :
	/**
	 * Prints HTML with category and tags for current post.
	 *
	 * Create your own iw26_entry_taxonomies() function to override in a child theme.
	 *
	 * @since Twenty Sixteen 1.0
	 */
	function iw26_entry_taxonomies() {
		// $categories_list = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'iw26' ) );
		// if ( $categories_list && iw26_categorized_blog() ) {
		// printf( '<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
		// _x( 'Categories', 'Used before category names.', 'iw26' ),
		// $categories_list
		// );
		// }
		if ( taxonomy_exists( 'series' ) ) {
			$series_list = get_the_term_list( get_the_ID(), 'series', iw26_get_icon( 'hierarchy' ), _x( ', ', 'Used between list items, there is a space after the comma.', 'iw26' ) );
			if ( $series_list ) {
				printf(
					'<span class="series-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
					_x( 'Series', 'Used before series names.', 'iw26' ),
					$series_list
				);
			}
		}
		$tags_list = get_the_tag_list( '', _x( ', ', 'Used between list items, there is a space after the comma.', 'iw26' ) );
		if ( $tags_list ) {
			printf(
				'<span class="tags-links">%1$s<span class="screen-reader-text">%2$s </span>%3$s</span>',
				iw26_get_icon( 'tag' ),
				_x( 'Tags', 'Used before tag names.', 'iw26' ),
				$tags_list
			);
		}
	}
endif;

if ( ! function_exists( 'iw26_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 *
	 * Create your own iw26_post_thumbnail() function to override in a child theme.
	 *
	 * @since Twenty Sixteen 1.0
	 */
	function iw26_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

		<div class="post-thumbnail">
			<?php the_post_thumbnail(); ?>
	</div><!-- .post-thumbnail -->

	<?php else : ?>

	<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
		<?php
			the_post_thumbnail(
				'post-thumbnail',
				array(
					'alt' => the_title_attribute(
						array(
							'echo' => false,
						)
					),
				)
			);
		?>
	</a>

		<?php
	endif; // End is_singular()
	}
endif;

if ( ! function_exists( 'iw26_excerpt' ) ) :
	/**
	 * Displays the optional excerpt.
	 *
	 * Wraps the excerpt in a div element.
	 *
	 * Create your own iw26_excerpt() function to override in a child theme.
	 *
	 * @since Twenty Sixteen 1.0
	 *
	 * @param string $class Optional. Class string of the div element. Defaults to 'entry-summary'.
	 */
	function iw26_excerpt( $class = 'entry-summary' ) {
		$class = esc_attr( $class );

		if ( has_excerpt() || is_search() ) :
			?>
			<div class="<?php echo $class; ?>">
				<?php the_excerpt(); ?>
			</div><!-- .<?php echo $class; ?> -->
			<?php
		endif;
	}
endif;

if ( ! function_exists( 'iw26_excerpt_more' ) && ! is_admin() ) :
	/**
	 * Replaces "[...]" (appended to automatically generated excerpts) with ... and
	 * a 'Continue reading' link.
	 *
	 * Create your own iw26_excerpt_more() function to override in a child theme.
	 *
	 * @since Twenty Sixteen 1.0
	 *
	 * @return string 'Continue reading' link prepended with an ellipsis.
	 */
	function iw26_excerpt_more() {
		$link = sprintf(
			'<a href="%1$s" class="more-link">%2$s</a>',
			esc_url( get_permalink( get_the_ID() ) ),
			/* translators: %s: Name of current post */
			sprintf( __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'iw26' ), get_the_title( get_the_ID() ) )
		);
		return ' &hellip; ' . $link;
	}
	add_filter( 'excerpt_more', 'iw26_excerpt_more' );
endif;

if ( ! function_exists( 'iw26_categorized_blog' ) ) :
	/**
	 * Determines whether blog/site has more than one category.
	 *
	 * Create your own iw26_categorized_blog() function to override in a child theme.
	 *
	 * @since Twenty Sixteen 1.0
	 *
	 * @return bool True if there is more than one category, false otherwise.
	 */
	function iw26_categorized_blog() {
		if ( false === ( $all_the_cool_cats = get_transient( 'iw26_categories' ) ) ) {
			// Create an array of all the categories that are attached to posts.
			$all_the_cool_cats = get_categories(
				array(
					'fields'     => 'ids',
					// We only need to know if there is more than one category.
					'number'     => 2,
				)
			);

			// Count the number of categories that are attached to the posts.
			$all_the_cool_cats = count( $all_the_cool_cats );

			set_transient( 'iw26_categories', $all_the_cool_cats );
		}

		if ( $all_the_cool_cats > 1 ) {
			// This blog has more than 1 category so iw26_categorized_blog should return true.
			return true;
		} else {
			// This blog has only 1 category so iw26_categorized_blog should return false.
			return false;
		}
	}
endif;

/**
 * Flushes out the transients used in iw26_categorized_blog().
 *
 * @since Twenty Sixteen 1.0
 */
function iw26_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'iw26_categories' );
}
add_action( 'edit_category', 'iw26_category_transient_flusher' );
add_action( 'save_post', 'iw26_category_transient_flusher' );

if ( ! function_exists( 'iw26_the_custom_logo' ) ) :
	/**
	 * Displays the optional custom logo.
	 *
	 * Does nothing if the custom logo is not available.
	 *
	 * @since Twenty Sixteen 1.2
	 */
	function iw26_the_custom_logo() {
		if ( function_exists( 'the_custom_logo' ) ) {
			the_custom_logo();
		}
	}
endif;

if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
endif;

function iw26_page_permalink() {
	$ancestors = get_ancestors( get_the_ID(), 'page', 'post_type' );
	if ( empty( $ancestors ) ) {
		array();
	}
	$ancestors = array_reverse( $ancestors );
	$return    = array();
	foreach ( $ancestors as $ancestor ) {
		$return[] = sprintf( '<a href="%1$s" rel="up">%2$s</a>', esc_url( get_permalink( $ancestor ) ), get_the_title( $ancestor ) );
	}
	$return[] = sprintf( '<a class="u-url" href="%1$s">%2$s</a>', esc_url( get_permalink() ), get_the_title() );
	echo '<ul class="breadcrumbs"><li>' . implode( '</li><li>' . iw26_get_icon( 'next' ) . ' ', $return ) . '</li></ul>';
}

function iw26_page_children() {
	$children = get_posts(
		array(
			'post_parent'    => get_the_ID(),
			'post_type'      => 'page',
			'fields'         => 'ids',
			'orderby'        => 'title',
			'order'          => 'ASC',
			'posts_per_page' => '-1',
		)
	);
	if ( empty( $children ) ) {
		return;
	}
	$return = array();
	foreach ( $children as $child ) {
		$return[] = sprintf( '<a href="%1$s" rel="down">%2$s</a>', get_permalink( $child ), get_the_title( $child ) );
	}
	echo '<ul class="page-children"><li>' . iw26_get_icon( 'fastforward' ) . implode( '</li><li>' . iw26_get_icon( 'fastforward' ), $return ) . '</li></ul>';
}

function iw26_post_link( $post = null ) {
	$post = get_post( $post );
	if ( ! empty( $post->post_title ) ) {
		$title = $post->post_title;
	} elseif ( ! empty( $post->post_excerpt ) ) {
		$title = $post->post_excerpt;
	} elseif ( ! empty( $post->post_content ) ) {
		$title = mb_strimwidth( wp_strip_all_tags( $post->post_content ), 0, 40, '...' );
	} else {
		$title = get_the_date( 'Y ' . get_option( 'time_format' ), $post );
	}

	if ( class_exists( 'Kind_Post' ) ) {
		$kind_post = new Kind_Post( $post );
		$content   = $kind_post->get_name();
		$kind      = $kind_post->get_kind();
		if ( ! in_array( $kind, array( 'note', 'article' ), true ) ) {
			$cite = $kind_post->get_cite( 'name' );
			if ( false === $cite ) {
				$content = Kind_View::get_post_type_string( $kind_post->get_cite( 'url' ) );
			} else {
				$content = $cite;
			}
		}
		if ( ! empty( $content ) ) {
			$title = $content;
		}
		$title = trim( Kind_Taxonomy::get_before_kind( $kind ) . $title );
	} 
	return trim( sprintf( '<a href="%1$s" class="p-name" rel="bookmark">%2$s</a>', get_the_permalink( $post ), $title ) );
}

function iw26_archive_title( $title, $original_title, $prefix ) {
	if ( get_query_var( 'kind_photos' ) ) {
		return iw26_get_icon( 'image' ) . $title;
	} elseif ( is_day() ) {
		return iw26_get_icon( 'day' ) . $title;
	} elseif ( is_month() ) {
		return iw26_get_icon( 'month' ) . $title;
	} elseif ( is_year() ) { 
		return iw26_get_icon( 'time' ) . $title;
	} elseif ( is_numeric( get_query_var( 'w' ) ) && is_date() ) {
		return iw26_get_icon( 'week' ) . $title;
	} elseif ( get_query_var( 'kind_photos' ) ) {
		return iw26_get_icon( 'picture' ) . $title;
	} elseif ( is_tag() ) {
		return iw26_get_icon( 'tag' ) . $title;
	} elseif ( is_category() ) {
		return iw26_get_icon( 'category' ) . $title;
	} elseif ( is_tax( 'series' ) ) {
		return iw26_get_icon( 'hierarchy' ) . $title;
	} elseif ( is_author() ) {
		return iw26_get_icon( 'user' ) . $title;
	} elseif ( is_tax( 'location' ) ) {
		return iw26_get_icon( 'location' ) . $title;
	}
	return $title;
		
}

add_filter( 'get_the_archive_title', 'iw26_archive_title', 11, 3 );

