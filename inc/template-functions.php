<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function iw26_body_classes( $classes ) {
		// Adds a class of custom-background-image to sites with a custom background image.
	if ( get_background_image() ) {
		$classes[] = 'custom-background-image';
	}

	// Adds a class of group-blog to sites with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}
	// Adds a class of no-sidebar to sites without active sidebar.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
		$classes[] = 'h-feed';
	} else {
		if ( 'page' !== get_post_type() ) {
			$classes[] = 'hentry';
			$classes[] = 'h-entry';
		}  else if ( 'page' === get_post_type() && ! is_front_page() ) {
			$classes[] = 'no-sidebar';
		}
	}
	return $classes;
}
add_filter( 'body_class', 'iw26_body_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function iw26_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%1$s" />', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'iw26_pingback_header' );

/**
 * Adds a rel-feed if the main page is not a list of posts
 */
function iw26_feed_header() {
	if ( is_front_page() && 0 !== (int) get_option( 'page_for_posts', 0 ) ) {
		printf( '<link rel="feed" type="text/html" href="%1$s" title="%2$s" />' . PHP_EOL, esc_url( get_post_type_archive_link( 'post' ) ), __( 'All Posts Feed', 'iw26' ) );
	}
}
add_action( 'wp_head', 'iw26_feed_header' );


/**
 * Adds custom classes to the array of post classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function iw26_post_classes( $classes ) {
	$classes = array_diff( $classes, array( 'hentry' ) );
	if ( ! is_singular() ) {
		if ( 'page' !== get_post_type() ) {
			// Adds a class for microformats v2
			$classes[] = 'h-entry';
			// add hentry to the same tag as h-entry
			$classes[] = 'hentry';
		}
	}
	return $classes;
}

add_filter( 'post_class', 'iw26_post_classes' );

/**
 * Adds mf2 to avatar
 *
 * @param array             $args Arguments passed to get_avatar_data(), after processing.
 * @param int|string|object $id_or_email A user ID, email address, or comment object
 * @return array $args
 */
function iw26_get_avatar_data( $args, $id_or_email ) {
	if ( ! isset( $args['class'] ) ) {
		$args['class'] = array();
	}
	if ( ! in_array( 'u-featured', $args['class'] ) ) {
		$args['class'][] = 'u-photo';
	}
	return $args;
}

add_filter( 'get_avatar_data', 'iw26_get_avatar_data', 11, 2 );

/**
 * Wraps the_content in e-content
 */
function iw26_the_content( $content ) {
	if ( is_feed() ) {
		return $content;
	}
	$wrap = '<div class="entry-content e-content">';
	if ( empty( $content ) ) {
		return $content;
	}
	return $wrap . $content . '</div>';
}
add_filter( 'the_content', 'iw26_the_content', 1 );

/**
 * Wraps the_excerpt in p-summary
 */
function iw26_the_excerpt( $content ) {
	if ( is_feed() ) {
		return $content;
	}
	$wrap = '<div class="entry-summary p-summary">';
	if ( ! empty( $content ) ) {
		return $wrap . $content . '</div>';
	}
	return $content;
}

add_filter( 'the_excerpt', 'iw26_the_excerpt', 1 );


function get_the_archive_thumbnail_url() {
	$image_id = null;
	if ( is_tax() || is_category() || is_tag() ) {
		$term     = get_queried_object();
		$image_id = get_term_meta( $term->term_id, 'image', true );
	}
	if ( $image_id ) {
		return wp_get_attachment_image_url( $image_id, 'thumbnail', true );
	}
}

function get_the_archive_thumbnail() {
	$image_id = null;
	if ( is_tax() || is_category() || is_tag() ) {
		$term     = get_queried_object();
		$image_id = get_term_meta( $term->term_id, 'image', true );
	}

	if ( $image_id ) {
		return wp_get_attachment_image( $image_id, 'thumbnail', true );
	}
}

function the_archive_thumbnail() {
	echo get_the_archive_thumbnail();
}

function iw26_image_rss() {
	$url = get_the_archive_thumbnail_url();
	if ( ! $url ) {
		return;
	}
	echo '<image>' . PHP_EOL;
	echo '<url>' . $url . '</url>' . PHP_EOL;
	echo '<title>' . get_the_archive_title() . '</title>' . PHP_EOL;
	echo '<link>';
	self_link();
	echo '</link>' . PHP_EOL;
	echo '</image>' . PHP_EOL;
}

add_action( 'rss2_head', 'iw26_image_rss' );
add_action( 'rss_head', 'iw26_image_rss)' );
add_action( 'commentsrss2_head', 'iw26_image_rss' );


function iw26_is_404_singular() {
	// If any of these are present it is an attempt to get a single object not an archive.
	foreach( array( 'name', 'page', 'p', 'pagename', 'attachment', 'attachment_id' ) as $var ) {
		if ( get_query_var( $var ) ) {
			return true;
		}
	}
	return false;
}

if ( ! function_exists( 'has_content' ) ) {
	function has_content( $post = 0 ) {
		$post = get_post( $post );
		return ( ! empty( $post->post_content ) );
	}
}

// Return the filename of an icon based on name if the file exists
function iw26_get_icon_filename( $name ) {
	$svg = sprintf( '%1$ssvg/%2$s.svg', plugin_dir_path( __DIR__ ), $name );
	if ( file_exists( $svg ) ) {
		return $svg;
	}
	return null;
}

function iw26_get_icon_svg( $icon ) {
	if ( ! is_string( $icon ) ) {
		return $icon;
	}

	$file = iw26_get_icon_filename( $icon );
	
	if ( $file ) {
		$icon = file_get_contents( $file ); // phpcs:ignore
		if ( $icon ) {
			return $icon;
		}
	}
	
	return null;
}

function iw26_get_icon( $name ) {
	$svg  = iw26_get_icon_svg( $name );
	if ( ! $svg ) {
		return '';
	}
	return sprintf( '<span class="icon icon-%1$s">%2$s</svg></span>', $name, $svg );
}

/**
 * Add dropdown icon if menu item has children.
 *
 * @param  string $title The menu item's title.
 * @param  object $item  The current menu item.
 * @param  array  $args  An array of wp_nav_menu() arguments.
 * @param  int    $depth Depth of menu item. Used for padding.
 * @return string $title The menu item's title with dropdown icon.
 */
function iw26_dropdown_icon_to_menu_link( $title, $item, $args, $depth ) {
	if ( 'primary' === $args->theme_location ) {
		foreach ( $item->classes as $value ) {
			if ( 'menu-item-has-children' === $value || 'page_item_has_children' === $value ) {
				$title = $title . iw26_get_icon( 'expand' );
			}
		}
	}

	return $title;
}
add_filter( 'nav_menu_item_title', 'iw26_dropdown_icon_to_menu_link', 10, 4 );

/**
 * Add icon to widget title for specific widgets.
**/

function iw26_widget_title( $title, $instance = null, $id_base = null ) {
	if ( 'tempus_thisweek_widget' === $id_base ) {
		$title = iw26_get_icon( 'week' ) . $title;
	}
	if ( 'tempus_onthisday_widget' === $id_base ) {
		$title = iw26_get_icon( 'day' ) . $title;
	}
	return $title;
}
add_filter( 'widget_title', 'iw26_widget_title', 10, 3 );

function iw26_comment_reply_link_args( $args, $comment, $post ) {
	$args['reply_text'] = iw26_get_icon( 'reply' ) . '<span class="screen-reader-text">' . $args['reply_text'] . '</span>';
	return $args;
}

add_filter( 'comment_reply_link_args', 'iw26_comment_reply_link_args', 10, 3 );

function iw26_adjacent_post_link( $output, $format, $link, $post, $adjacent ) {
	if ( ! $post ) {
		return $output;
	}
	$previous = ( 'previous' === $adjacent );
		
	if ( empty( $post->post_title ) ) {
		if ( ! empty( $post->post_excerpt ) ) {
			$title = mb_strimwidth( wp_strip_all_tags( $post->post_excerpt ), 0, 40, '...' );
		} elseif ( ! empty( $post->post_content ) ) {
			$title = mb_strimwidth( wp_strip_all_tags( $post->post_content ), 0, 40, '...' );
		} else {
			$title = get_the_date( 'Y ' . get_option( 'time_format' ), $post );
		}

		if( class_exists( 'Kind_Post' ) ) {
			$kind_post = new Kind_Post( $post );
			$content = $kind_post->get_name();
			$kind = $kind_post->get_kind();
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

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $title, $post->ID );
		       
		$date = mysql2date( get_option( 'date_format' ), $post->post_date );
		$rel  = $previous ? 'prev' : 'next';

		$string = '<a href="' . get_permalink( $post ) . '" rel="' . $rel . '">';
		
		$inlink = str_replace( '%title', $title, $link );
		$inlink = str_replace( '%date', $date, $inlink );
		$inlink = $string . $inlink . '</a>';
										 
		$output = str_replace( '%link', $inlink, $format );
	}						      

	return $output;
}

add_filter( 'next_post_link', 'iw26_adjacent_post_link', 11, 5 );
add_filter( 'previous_post_link', 'iw26_adjacent_post_link', 11, 5 );

function iw26_get_single_post_term_name( $taxonomy, $post = null ) {
	$_terms = get_the_terms( $post, $taxonomy );
	if ( ! empty( $_terms ) ) {
		$term = array_shift( $_terms );
		return $term->name;
	}
	return false;
}

		

