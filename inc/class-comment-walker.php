<?php 
/** MF2Comment is a webmention enhanced version of Walker_Comment
*/
class MF2Comment extends Walker_Comment {
	/**
	 * What the class handles.
	 *
	 * @see Walker::$tree_type
	 *
	 * @since 2.7.0
	 * @var string
	 */
	public $tree_type = 'comment';

	/**
	 * DB fields to use.
	 *
	 * @see Walker::$db_fields
	 *
	 * @since 2.7.0
	 * @var array
	 */
	public $db_fields = array ('parent' => 'comment_parent', 'id' => 'comment_ID');

	/**
	 * Start the element output.
	 *
	 * @since 2.7.0
	 *
	 * @see Walker::start_el()
	 * @see wp_list_comments()
	 *
	 * @param string $output  Passed by reference. Used to append additional content.
	 * @param object $comment Comment data object.
	 * @param int    $depth   Depth of comment in reference to parents.
	 * @param array  $args    An array of arguments.
	 */
	public function start_el( &$output, $comment, $depth = 0, $args = array(), $id = 0 ) {
		$depth++;
		$GLOBALS['comment_depth'] = $depth;
		$GLOBALS['comment'] = $comment;

		if ( !empty( $args['callback'] ) ) {
			ob_start();
			call_user_func( $args['callback'], $comment, $args, $depth );
			$output .= ob_get_clean();
			return;
		}

		if ( ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type || 'webmention' == $comment->comment_type ) && $args['short_ping'] ) {
			ob_start();
			$this->ping( $comment, $depth, $args );
			$output .= ob_get_clean();
		} elseif ( 'html5' === $args['format'] ) {
			ob_start();
			$this->html5_comment( $comment, $depth, $args );
			$output .= ob_get_clean();
		} else {
			ob_start();
			$this->comment( $comment, $depth, $args );
			$output .= ob_get_clean();
		}
	}

	/**
	 * Ends the element output, if needed.
	 *
	 * @since 2.7.0
	 *
	 * @see Walker::end_el()
	 * @see wp_list_comments()
	 *
	 * @param string $output  Passed by reference. Used to append additional content.
	 * @param object $comment The comment object. Default current comment.
	 * @param int    $depth   Depth of comment.
	 * @param array  $args    An array of arguments.
	 */
	public function end_el( &$output, $comment, $depth = 0, $args = array() ) {
		if ( !empty( $args['end-callback'] ) ) {
			ob_start();
			call_user_func( $args['end-callback'], $comment, $args, $depth );
			$output .= ob_get_clean();
			return;
		}
		if ( 'div' == $args['style'] )
			$output .= "</div><!-- #comment-## -->\n";
		else
			$output .= "</li><!-- #comment-## -->\n";
	}

	public static function linkback_text($comment = null) {
  	// only change text for pingbacks/trackbacks/webmentions
    if (!$comment || $comment->comment_type == "" || !get_comment_meta($comment->comment_ID, "semantic_linkbacks_canonical", true)) {
			return "";
		}
		// check comment type
		$comment_type = Linkbacks_Handler::get_type($comment->comment_ID );
		if (!in_array($comment_type, array_keys(Linkbacks_Handler::get_comment_type_strings()))) {
			$comment_type = "mention";
		}
		$_kind = get_the_terms( $comment->comment_post_ID, 'kind' );
		if ( (!empty($_kind)) || (class_exists('Kind_Taxonomy')) ) {   
			$kind = array_shift($_kind);
			$post_format = Kind_Taxonomy::get_kind_info( $kind->slug, 'singular_name' );
		}
		else {
			$post_format = get_post_format($comment->comment_post_ID);
			// replace "standard" with "Article"
			if (!$post_format || $post_format == "standard") {
				$post_format = "Article";
			} else {
			$post_formatstrings = get_post_format_strings();
			// get the "nice" name
			$post_format = $post_formatstrings[$post_format];
			}
		}
		$post_format = apply_filters( 'semantic_linkbacks_post_type', $post_format, $comment->comment_post_ID );

		// generate the verb, for example "mentioned" or "liked"
		$comment_type_excerpts = Linkbacks_Handler::get_comment_type_excerpts();
		// get URL canonical url...
		$url = Linkbacks_Handler::get_url( $comment );
		// parse host
		$host = wp_parse_url($url, PHP_URL_HOST);
		// strip leading www, if any
		$host = preg_replace("/^www\./", "", $host);
		// generate output
		$text = sprintf($comment_type_excerpts[$comment_type], "", 'this ' . $post_format, $url, $host);
		return $text;
	}

	/**
	 * Output a linkback.
	 *
	 * @access protected
	 * @since 3.6.0
	 *
	 * @see wp_list_comments()
	 *
	 * @param object $comment The comment object.
	 * @param int    $depth   Depth of comment.
	 * @param array  $args    An array of arguments.
	 */
	protected function ping( $comment, $depth, $args ) {
        $tag = ( 'div' == $args['style'] ) ? 'div' : 'li';
    		$semantic_linkbacks_type = get_comment_meta($comment->comment_ID, "semantic_linkbacks_type", true);
?>
        <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class('h-cite'); ?>>
           <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
         		 <span class="comment-author vcard h-card p-author">
             	<?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>						
							<span class="p-name"><?php comment_author_link($comment->comment_ID) ?></span>
        	   </span><!-- .comment-author -->
					 	 <span class="p-summary p-name">
            	 <?php echo self::linkback_text($comment); ?>
        		 </span><!-- .p-summary -->
             <time class="dt-published" datetime="<?php comment_time( DATE_W3C ); ?>" > 
						</article>
<?php
    }
	/**
	 * Output a comment in the HTML5 format.
	 *
	 * @access protected
	 * @since 3.6.0
	 *
	 * @see wp_list_comments()
	 *
	 * @param object $comment Comment to display.
	 * @param int    $depth   Depth of comment.
	 * @param array  $args    An array of arguments.
	 */
	protected function html5_comment( $comment, $depth, $args ) {
		$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
		$semantic_linkbacks_type = get_comment_meta($comment->comment_ID, "semantic_linkbacks_type", true);
		?>
		<<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $this->has_children ? 'parent p-comment h-cite' : 'p-comment h-cite' ); ?>>
			<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
				<header class="comment-metadata">
					<span class="comment-author vcard h-card p-author">
						<?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
						<?php printf( __( '%s' ), sprintf( '<b class="fn">%s</b>', get_comment_author_link() ) ); ?>
					</span><!-- .comment-author -->
					<?php     
						if ($comment || $semantic_linkbacks_type || $comment->comment_type == "" || $semantic_linkbacks_type == "reply") {
							 // get URL canonical url...
    					 $semantic_linkbacks_canonical = Linkbacks_Handler::get_url( $comment );
							 if ($semantic_linkbacks_canonical) {
    					 	$host = wp_parse_url($semantic_linkbacks_canonical, PHP_URL_HOST);
    					 	// strip leading www, if any
    					 	$host = preg_replace("/^www\./", "", $host);
							 	echo '<small>&nbsp;@&nbsp;<cite><a class="u-url" href="' . $semantic_linkbacks_canonical . '">' . $host . '</a></cite></small>';
							}
						}
				 ?>
				</header>
				<p class="comment-content e-content p-name">
					<?php echo $comment->comment_content; ?>
				</p><!-- .comment-content -->

				<footer class="comment-metadata">
					<span class="reply">
						<a href="" class="in-reply-to"></a>
					<?php comment_reply_link( array_merge( $args, array( 'add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
					</span><!-- .reply -->
					&nbsp;&bull;&nbsp;
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID, $args ) ); ?>">
							<time class="dt-published" datetime="<?php comment_time( DATE_W3C ); ?>"></time>
								<?php printf( _x( '%1$s on %2$s', '1: date, 2: time' ), get_comment_time('g:iA T'), get_comment_date('Y-m-d') ); ?>
					</a>
					<?php edit_comment_link( __( 'Edit' ), '&nbsp;&bull;&nbsp;<span class="edit-link">', '</span>' ); ?>
					<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ); ?></p>
					<?php endif; ?>
				</footer><!-- .comment-meta -->


			</article><!-- .comment-body -->
<?php
	}
}

