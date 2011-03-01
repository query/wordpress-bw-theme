<?php
/** Tell WordPress to run twentyten_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'twentyten_setup' );

if ( ! function_exists( 'twentyten_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override twentyten_setup() in a child theme, add your own twentyten_setup to your child theme's
 * functions.php file.
 *
 * @uses add_theme_support() To add support for post thumbnails and automatic feed links.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_custom_background() To add support for a custom background.
 * @uses add_editor_style() To style the visual editor.
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_custom_image_header() To add support for a custom header.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_setup() {

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'twentyten', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'twentyten' ),
	) );

    define('HEADER_IMAGE', '%s/images/blank.png');
	define('HEADER_IMAGE_WIDTH', 700);
	define('HEADER_IMAGE_HEIGHT', 147);
	define('NO_HEADER_TEXT', true);

	set_post_thumbnail_size(684, 150, true);
	add_image_size('issue-thumbnail', 172, 9999);
	
	add_custom_image_header('', 'bw_admin_header_style');

	register_default_headers(array(
		'blank' => array(
			'url' => '%s/images/blank.png',
			'thumbnail_url' => '%s/images/blank-thumbnail.png',
			/* translators: header image description */
			'description' => __('Blank', 'bw')
		)
	));
}
endif;

function bw_admin_header_style() {
?>
<style type="text/css">
#headimg {
    background: <?php echo bw_get_spotcolor(); ?>;
}

label img {
    background: <?php echo bw_get_spotcolor(); ?>;
}
</style>
<?php
}

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * To override this in a child theme, remove the filter and optionally add
 * your own function tied to the wp_page_menu_args filter hook.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'twentyten_page_menu_args' );

/**
 * Sets the post excerpt length to 40 characters.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 *
 * @since Twenty Ten 1.0
 * @return int
 */
function twentyten_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'twentyten_excerpt_length' );

/**
 * Returns a "Continue Reading" link for excerpts
 *
 * @since Twenty Ten 1.0
 * @return string "Continue Reading" link
 */
function twentyten_continue_reading_link() {
	return ' <a href="'. get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentyten' ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and twentyten_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @since Twenty Ten 1.0
 * @return string An ellipsis
 */
function twentyten_auto_excerpt_more( $more ) {
	return ' &hellip;' . twentyten_continue_reading_link();
}
add_filter( 'excerpt_more', 'twentyten_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @since Twenty Ten 1.0
 * @return string Excerpt with a pretty "Continue Reading" link
 */
function twentyten_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= twentyten_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'twentyten_custom_excerpt_more' );

if ( ! function_exists( 'twentyten_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentyten_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
	<article id="comment-<?php comment_ID(); ?>">
		<header>
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, 40 ); ?>
			<?php printf( __( '%s <span class="says">said</span>', 'twentyten' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
			<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><?php
				/* translators: 1: date, 2: time */
				printf( __( 'on %1$s at %2$s', 'twentyten' ), get_comment_date(),  get_comment_time() ); ?></a>:
		</div><!-- .comment-author .vcard -->
		</header>

		<?php comment_text(); ?>

		<footer>
		    <?php if ( $comment->comment_approved == '0' ) : ?>
                <?php _e( 'This comment is awaiting moderation | ', 'twentyten' ); ?>
            <?php endif; ?>
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			<?php edit_comment_link( __( 'Edit', 'twentyten' ), ' | ' ); ?>
		</footer>
	</article> <!-- #comment-<?php comment_ID(); ?> -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'twentyten' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'twentyten'), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif;

/**
 * Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
 *
 * To override twentyten_widgets_init() in a child theme, remove the action hook and add your own
 * function tied to the init hook.
 *
 * @since Twenty Ten 1.0
 * @uses register_sidebar
 */
function twentyten_widgets_init() {
	// Area 1, located at the top of the sidebar.
	register_sidebar( array(
		'name' => __( 'Primary Widget Area', 'twentyten' ),
		'id' => 'primary-widget-area',
		'description' => __( 'The primary widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h1>',
		'after_title' => '</h1>',
	) );

	// Area 2, located below the Primary Widget Area in the sidebar. Empty by default.
	register_sidebar( array(
		'name' => __( 'Secondary Widget Area', 'twentyten' ),
		'id' => 'secondary-widget-area',
		'description' => __( 'The secondary widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h1>',
		'after_title' => '</h1>',
	) );
	
	// Front page widget area. Empty by default.
	register_sidebar( array(
		'name' => __( 'Home Widget Area', 'twentyten' ),
		'id' => 'home-widget-area',
		'description' => __( 'The front page widget area', 'twentyten' ),
		'before_widget' => '<section id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</section>',
		'before_title' => '<h1>',
		'after_title' => '</h1>',
	) );
}
/** Register sidebars by running twentyten_widgets_init() on the widgets_init hook. */
add_action( 'widgets_init', 'twentyten_widgets_init' );

/**
 * Removes the default styles that are packaged with the Recent Comments widget.
 *
 * To override this in a child theme, remove the filter and optionally add your own
 * function tied to the widgets_init action hook.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'twentyten_remove_recent_comments_style' );

/**
 * Gets the author from the post meta tag "author" if it exists;
 * otherwise, falls back to WordPress's "real" author information.
 */
function bw_get_author() {
    $custom = get_post_custom();
    if ($custom['author']) {
        if ($custom['author'][0] == '@none') {
            return '';
        } else {
            return $custom['author'][0];
        }
    }

    return sprintf('<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
                   get_author_posts_url( get_the_author_meta( 'ID' ) ),
                   sprintf( esc_attr__( 'View all posts by %s', 'twentyten' ), get_the_author() ),
                   get_the_author());
}

if ( ! function_exists( 'twentyten_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post—date/time and author.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_posted_on() {
	printf( __( '<span class="%1$s">Posted on</span> %2$s <span class="meta-sep">by</span> %3$s', 'twentyten' ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" rel="bookmark"><time datetime="%2$s" class="entry-date" pubdate>%3$s</time></a>',
			get_permalink(),
			esc_attr( get_the_time('c') ),
			get_the_date()
		),
		bw_get_author()
	);
}
endif;

if ( ! function_exists( 'twentyten_posted_in' ) ) :
/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 *
 * @since Twenty Ten 1.0
 */
function twentyten_posted_in() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'twentyten' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'twentyten' );
	} else {
		$posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'twentyten' );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}
endif;

function bw_get_spotcolor() {
    $spotcolor = '';
    
    if (is_page()) {
        $ancestors = $post->ancestors;
        if ($ancestors) {
            array_unshift($ancestors, $post->ID);
        
            foreach ($ancestors as $ancestor_id) {
                $new_spotcolor = get_post_meta($ancestor_id, 'spotcolor', true);
                if ($new_spotcolor) {
                    $spotcolor = $new_spotcolor;
                    break;
                }
            }
        }
    }
    
    if (!$spotcolor) {
        $options = get_option('bw_theme_options');
        
        switch ($options['spotcolor_from']) {
            case 'latest_subpage_of':
                $sc_posts = new WP_Query(array('numberposts' => -1,
                                               'orderby' => 'menu_order',
                                               'order' => 'ASC',
                                               'post_type' => 'page',
                                               'post_parent' => $options['spotcolor_page'],
                                               'meta_key' => 'spotcolor'));
                
                if ($sc_posts->have_posts()) {
                    $spotcolor = get_post_meta($sc_posts->post->ID,
                                               'spotcolor', true);
                }
            break;
            
            case 'specific_page':
                $spotcolor = get_post_meta($options['spotcolor_page'],
                                           'spotcolor', true);
            break;
            
            case 'constant':
                $spotcolor = $options['spotcolor_constant'];
            break;
        }
    }
    
    return $spotcolor;
}

//function bw_get_featured_pages

function bw_theme_options_init() {
    register_setting('bw_theme_options', 'bw_theme_options',
                     'bw_validate_theme_options');
}

function bw_theme_options_styles() {
    echo "<link rel='stylesheet' href='" .
         get_bloginfo( 'stylesheet_directory' ) .
         "/admin.css' type='text/css' />\n";
}

function bw_theme_options_add_page() {
    $page = add_theme_page(__('Theme Options'), __('Theme Options'),
                          'edit_theme_options',
                          'theme_options', 'bw_theme_options_page');
    add_action('admin_print_styles', 'bw_theme_options_styles');
}

add_action('admin_init', 'bw_theme_options_init');
add_action('admin_menu', 'bw_theme_options_add_page');

function bw_theme_options_page() {
    $options = get_option('bw_theme_options');
?>
<div class="wrap">
<?php screen_icon(); echo "<h2>" . get_current_theme() . __(' Theme Options') . "</h2>"; ?>

<?php if ( $_REQUEST['updated'] ) : ?>
<div id="message" class="updated"><p><?php _e('Options saved.'); ?></p></div>
<?php endif; ?>

<form method="post" action="options.php">
<?php settings_fields('bw_theme_options'); ?>
<table class="form-table">
<tr valign="top">
<th scope="row"><?php _e('Default spot color'); ?></th>
<td id="bw_theme_spotcolor"><fieldset><legend class="screen-reader-text"><span><?php _e( 'Default spot color' ); ?></span></legend>
<p>
<label for="bw_theme_spotcolor_latest_subpage_of">
<input id="bw_theme_spotcolor_latest_subpage_of" name="bw_theme_options[spotcolor_from]" type="radio" value="latest_subpage_of"<?php if ($options['spotcolor_from'] === 'latest_subpage_of') echo ' checked="checked"'; ?> />
<?php _e('Use the spot color of the first subpage of the page below'); ?>
</label>
</p>

<p>
<label for="bw_theme_spotcolor_specific_page">
<input id="bw_theme_spotcolor_specific_page" name="bw_theme_options[spotcolor_from]" type="radio" value="specific_page"<?php if ($options['spotcolor_from'] === 'specific_page') echo ' checked="checked"'; ?> />
<?php _e('Use the spot color of the specific page below'); ?>
</label>
</p>

<ul>
<li>
<?php printf(__('Reference page: %s'),
             wp_dropdown_pages(array('name' => 'bw_theme_options[spotcolor_page]',
                                     'show_option_none' => __( '&mdash; Select &mdash;' ),
                                     'echo' => 0, 'option_none_value' => '0',
                                     'selected' => $options['spotcolor_page'])));
?>
</li>
</ul>

<p>
<label for="bw_theme_spotcolor_constant">
<input id="bw_theme_spotcolor_constant" name="bw_theme_options[spotcolor_from]" type="radio" value="constant"<?php if ($options['spotcolor_from'] === 'constant') echo ' checked="checked"'; ?> />
<?php printf(__('Use a specific color: %s <span class="description">Hex triplet format (#rrggbb)</span>'),
             '<input name="bw_theme_options[spotcolor_constant]" ' .
              'type="text" size="7" value="' . esc_attr($options['spotcolor_constant']) . '" />'); ?>
</label>
</p>
</fieldset></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Front page feature carousel'); ?></th>
<td id="bw_theme_featured_pages"><fieldset><legend class="screen-reader-text"><span><?php _e('Front page feature carousel'); ?></span></legend>
<p>
<label for="bw_theme_featured_heading">
<?php printf(__('Carousel heading text: %s <span class="description">e.g. “Featured Articles”, “In This Issue”</span>'),
             '<input id="bw_theme_featured_heading" name="bw_theme_options[featured_heading]" ' .
              'type="text" size="20" value="' . esc_attr($options['featured_heading']) . '" />'); ?>
</label>
</p>

<p>
<?php _e('Display the following articles in the carousel:'); ?>
</p>

<ul>
<li>
<label for="bw_theme_featured_pages_root">
<input id="bw_theme_featured_pages_root" name="bw_theme_options[featured]" type="radio" value="root"<?php if ($options['featured'] === 'root') echo ' checked="checked"'; ?> />
<?php printf(__('Show the subpages of the first subpage of: %s'),
             wp_dropdown_pages(array('name' => 'bw_theme_options[featured_root]',
                                     'show_option_none' => __( '&mdash; Select &mdash;' ),
                                     'echo' => 0, 'option_none_value' => '0',
                                     'selected' => $options['featured_root'])));
?>
</label>
</li>

<li>
<label for="bw_theme_featured_pages_list">
<input id="bw_theme_featured_pages_list" name="bw_theme_options[featured]" type="radio" value="pages"<?php if ($options['featured'] === 'pages') echo ' checked="checked"'; ?> />
<?php _e('Show the following pages:'); ?>
</label>

<div id="bw_theme_featured_pages_container">
<ul>
<?php
// There's no easy way to build a hierarchical list of pages that don't have
// links, so we'll do it ourselves.
$page_list = wp_list_pages(array('title_li' => '', 'echo' => 0));

// Get rid of the <a> tags surrounding each item; replace them with <label>.
$page_list = preg_replace('/<(\/?)a.*?>/', '<$1label>', $page_list);

// Add a checkbox with the page ID from <li class="page-item-##">.
$page_list = preg_replace('/<li class=".*?page-item-([0-9]+).*?"><label>/',
                          '$0<input name="bw_theme_options[featured_pages][$1]" type="checkbox" /> ',
                          $page_list);

// Get already-selected values and add ' checked="checked"' to each.
foreach ($options['featured_pages'] as $page_id) {
    $page_list = str_replace('name="bw_theme_options[featured_pages][' . $page_id . ']"',
                             'name="bw_theme_options[featured_pages][' . $page_id . ']" checked="checked"',
                             $page_list);
}

echo $page_list;
?>
</ul>
</div>
</li>
</ul>
</fieldset></td>
</tr>
</table>

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Options'); ?>" />
</p>
</form>
</div>
<?php
}

function bw_validate_theme_options($input) {
    $options = get_option('bw_theme_options');
    $pages = get_pages();
    
    // Ensure the spot color preference is one of the three valid options.
    if (in_array($input['spotcolor_from'],
                 array('latest_subpage_of', 'specific_page', 'constant'))) {
        $options['spotcolor_from'] = $input['spotcolor_from'];
    }
    
    // Make sure the spot color reference page exists.
    foreach ($pages as $page) {
        if ($page->ID == $input['spotcolor_page']) {
            $options['spotcolor_page'] = $input['spotcolor_page'];
        }
    }
    
    // Is the constant spot color an actual spot color?
    $spotcolor_constant = trim($input['spotcolor_constant']);
    if (preg_match('/^#[0-9a-f]{6}$/i', $spotcolor_constant)) {
        $options['spotcolor_constant'] = $spotcolor_constant;
    }
    
    // Ensure the feature preference is one of the two valid options.
    if (in_array($input['featured'], array('root', 'pages'))) {
        $options['featured'] = $input['featured'];
    }
    
    // Any feature carousel heading is valid.
    $options['featured_heading'] = $input['featured_heading'];
    
    // Make sure the selected featured pages exist.
    foreach ($pages as $page) {
        if ($page->ID == $input['featured_root']) {
            $options['featured_root'] = $input['featured_root'];
        }
    }
    
    $options['featured_pages'] = array();
    foreach ($input['featured_pages'] as $page_id => $enabled) {
        if (!$enabled) {
            break;
        }
        
        foreach ($pages as $page) {
            if ($page->ID == $page_id) {
                $options['featured_pages'][] = $page_id;
            }
        }
    }
    
    return $options;
}
