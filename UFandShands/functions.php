<?php

include_once('library/php/enhanced-custom-fields/enhanced-custom-fields.php');
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
include_once('library/php/apollo-misc-functions.php');


/* ----------------------------------------------------------------------------------- */
/* Options Framework Theme -- leave at top of functions.php
/* ----------------------------------------------------------------------------------- */

if (!function_exists('optionsframework_init')) {

	/* Set the file path based on whether the Options Framework Theme is a parent theme or child theme */

	if (STYLESHEETPATH == TEMPLATEPATH) {
		define('OPTIONS_FRAMEWORK_URL', TEMPLATEPATH . '/admin/');
		define('OPTIONS_FRAMEWORK_DIRECTORY', get_bloginfo('template_directory') . '/admin/');
	} else {
		define('OPTIONS_FRAMEWORK_URL', STYLESHEETPATH . '/admin/');
		define('OPTIONS_FRAMEWORK_DIRECTORY', get_bloginfo('stylesheet_directory') . '/admin/');
	}

	require_once (OPTIONS_FRAMEWORK_URL . 'options-framework.php');
}


add_action( 'init', 'enable_cors' );
function enable_cors() {
   if (function_exists('domain_mapping_siteurl'))
	   $domain = domain_mapping_siteurl(null);
   else
	   $domain = get_bloginfo('url');

	$domain = preg_replace('|http(s)?://|', '', $domain);

    header('Access-Control-Allow-Origin: ' . $domain);
}

/* ----------------------------------------------------------------------------------- */
/* Restore title attribute to Wordpress Gallery Images for Lightbox use
/* ----------------------------------------------------------------------------------- */

function ufandshands_add_title_attachment_link($link, $id = null) {
  $id = intval( $id );
  $_post = get_post( $id );
  $post_title = esc_attr( $_post->post_title );
  return str_replace('<a href', '<a title="'. $post_title .'" href', $link);
}
add_filter('wp_get_attachment_link', 'ufandshands_add_title_attachment_link', 10, 2);

// -------[ Responsive Include ]--------
include_once('library/php/apollo-misc-functions.php');

/*
add_filter('media_send_to_editor', 'test_media', 10, 3);

function test_media($html, $send_id, $attachment) {
echo $html . '<br /><br /><br />' . $send_id . '<br /><Br /><br />';
echo wp_get_attachment_url( $send_id );
die();
}

add_filter('file_send_to_editor_url', 'test_media_url', 10, 3);

function test_media_url($html, $src, $title ){
echo $html . '<br /><br /><br />' . $src . '<br /><Br /><br />' . $title;
die();
}
 */

/* ----------------------------------------------------------------------------------- */
/* Disabling the browser upgrade warning
/* ----------------------------------------------------------------------------------- */


function disable_browser_upgrade_warning() {
    remove_meta_box( 'dashboard_browser_nag', 'dashboard', 'normal' );
}
add_action( 'wp_dashboard_setup', 'disable_browser_upgrade_warning' );



/* ----------------------------------------------------------------------------------- */
/* Fix drop-down menu z-index issues in embedded movies
/* ----------------------------------------------------------------------------------- */

function add_video_wmode_transparent($html, $url, $attr) {

	if ( strpos( $html, "<embed src=" ) !== false )
	{ return str_replace('</param><embed', '</param><param name="wmode" value="opaque"></param><embed wmode="opaque" ', $html); }
	elseif ( strpos ( $html, 'feature=oembed' ) !== false )
	{ return str_replace( 'feature=oembed', 'feature=oembed&wmode=opaque', $html ); }
	else
	{ return $html; }
}
add_filter( 'embed_oembed_html', 'add_video_wmode_transparent', 10, 3);


/* ----------------------------------------------------------------------------------- */
/* Wrap embedded movies in flexible container to accommodate various layouts
/* ----------------------------------------------------------------------------------- */

function ufandshands_embed_oembed_html($html, $url, $attr, $post_id) {
	return '<div class="oembed-flex-container">' . $html . '</div>';
}
add_filter('embed_oembed_html', 'ufandshands_embed_oembed_html', 99, 4);


/* ----------------------------------------------------------------------------------- */
/* Sharepoint Calendar integration
/* ----------------------------------------------------------------------------------- */
include_once ('library/php/sharepoint-calendar/sharepoint-calendar.php');


/* ----------------------------------------------------------------------------------- */
/* Sitemap integration
/* ----------------------------------------------------------------------------------- */
include_once ('library/php/site-map/site-map.php');

/* ----------------------------------------------------------------------------------- */
/* Google Search integration
/* ----------------------------------------------------------------------------------- */
include_once ('library/php/google-search/google-custom-search.php');

/* ----------------------------------------------------------------------------------- */
/* All Posts URL integration
/* ----------------------------------------------------------------------------------- */
include_once ('library/php/all-posts.php');

/* ----------------------------------------------------------------------------------- */
/* Add Tags Metabox to Pages
/* ----------------------------------------------------------------------------------- */

// Shared tag taxonomy - Posts AND Pages -- How to change label???
function ufandshands_page_tags() {
	register_taxonomy_for_object_type('post_tag', 'page');
}
add_action('init', 'ufandshands_page_tags');

// When displaying a tag archive, also show pages
function ufandshands_tags_archives($wp_query) {
	if ( $wp_query->get('tag') )
		$wp_query->set('post_type', 'any');
}
add_action('pre_get_posts', 'ufandshands_tags_archives');

add_action( 'init', 'ufandshands_tag_labels');

// Rename 'Post Tags' to 'Tags'
function ufandshands_tag_labels()
{
    global $wp_taxonomies;

    //  http://codex.wordpress.org/Function_Reference/register_taxonomy
    $wp_taxonomies['post_tag']->labels = (object)array(
        'name' => 'Tags',
        'singular_name' => 'Tags',
        'search_items' => 'Search Tags',
        'popular_items' => 'Popular Tags',
        'all_items' => 'All Tags',
        'parent_item' => null, // Tags aren't hierarchical
        'parent_item_colon' => null,
        'edit_item' => 'Edit Tag',
        'update_item' => 'Update Tag',
        'add_new_item' => 'Add new Tag',
        'new_item_name' => 'New Tag Name',
        'separate_items_with_commas' => 'Separate tags with commas',
        'add_or_remove_items' => 'Add or remove tags',
        'choose_from_most_used' => 'Choose from the most used tags',
        'menu_name' => 'Tags'
    );

    $wp_taxonomies['post_tag']->label = 'Tags';
}

// Additional instructions for recommended Featured Image sizes
add_filter( 'admin_post_thumbnail_html', 'add_featured_image_instruction');
function add_featured_image_instruction( $content ) {
    return $content .= '<br />
                        <table width="100%">
                        <tr>Recommended Sizes (in pixels):</tr>
                        <tr>
                          <td>Half-Width Image</td>
                          <td align="right"><strong>450 x 305</strong></td>
                        </tr>
                        <tr>
                          <td>Full-Width Image</td>
                          <td align="right"><strong>930 x 325</strong></td>
                        </tr>
                        <tr>
                          <td>Story Stacker Image</td>
                          <td align="right"><strong>630 x 305</strong></td>
                        </tr>
                       </table>';
}


/* ----------------------------------------------------------------------------------- */
/* Custom TinyMCE styles
/* ----------------------------------------------------------------------------------- */

add_theme_support('editor_style');
//add_editor_style('library/css/editor-styles.css'); // custom css styles in the content editor


/* ----------------------------------------------------------------------------------- */
/* Custom TinyMCE buttons
/* ----------------------------------------------------------------------------------- */
include_once('library/php/tinymce-custombuttons/functions-custombuttons.php');

// set the "kitchen sink" visible as default
function unhide_kitchensink( $args ) {
	$args['wordpress_adv_hidden'] = false;
	return $args;
}
add_filter( 'tiny_mce_before_init', 'unhide_kitchensink' );


// function ufandshands_extend_elements($initArray) {
//   $ext = 'pre[id|name|class|style],iframe[align|longdesc|name|width|height|frameborder|scrolling|marginheight|marginwidth|src]';
//   if ( isset( $initArray['extended_valid_elements'] ) ) {
//     $initArray['extended_valid_elements'] .= ',' . $ext;
//   } else {
//     $initArray['extended_valid_elements'] = $ext;
//   }
//   // maybe; set tiny paramter verify_html
//   //$initArray['verify_html'] = false;
//   return $initArray;
// }
// add_filter('tiny_mce_before_init', 'fb_change_mce_options');

/* ----------------------------------------------------------------------------------- */
/* TinyMCE Lists Plugin - Revisit with WP v.3.4
/* ----------------------------------------------------------------------------------- */
// function add_tinymce_lists_plugin($plugin_array) {
//     $plugin_array['lists'] = get_bloginfo('template_directory') . "/library/php/tinymce-plugins/lists/editor_plugin.js";
//     return $plugin_array;
// }

// add_filter("mce_external_plugins", "add_tinymce_lists_plugin");


/* ----------------------------------------------------------------------------------- */
/* Add Lightbox rel attribute for Galleries
/* ----------------------------------------------------------------------------------- */

function ufandshands_lightbox_rel ($content) {
	global $post;
	$pattern = "/<a(.*?)href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>(.*?)<\/a>/i";
    $replacement = '<a$1href=$2$3.$4$5 rel="prettyPhoto['.$post->ID.']"$6>$7</a>';
    $rel_content = preg_replace($pattern, $replacement, $content, -1, $count);
    if ($count > 1) {
		$content = $rel_content;
    }
    return $content;
}
add_filter('the_content', 'ufandshands_lightbox_rel', 12);
add_filter('get_comment_text', 'ufandshands_lightbox_rel');


/* ----------------------------------------------------------------------------------- */
/* Misc. Header and Footer Items -- helps keep the template header and footer cleaner
/* ----------------------------------------------------------------------------------- */

// Enables post and comment RSS feed links to head
add_theme_support('automatic-feed-links');

// removes the white space from wp_title to allow for subtitles to be added where applicable
function ufandshands_title_space_rm($title) {
    return trim($title);
}
add_filter('wp_title', 'ufandshands_title_space_rm');

/* ----------------------------------------------------------------------------------- */
/* Enqueue Stylesheets
/* ----------------------------------------------------------------------------------- */
function ufandshands_styles() {

  wp_enqueue_style( 'style', get_stylesheet_uri() );

  // Sidebar Collapse Styles
  if (of_get_option('opt_collapse_sidebar_nav')) {
    wp_enqueue_style( 'sidebar-nav-collapse', get_template_directory_uri() . '/library/css/sidebar-nav-collapse.css' );
  }

  // Author Styles
  if (of_get_option('opt_about_author')) {
    wp_enqueue_style( 'author', get_template_directory_uri() . '/library/css/author.css' );
  }

  // Primary Nav Scripts
  if (HasActiveUberMenu() && !is_admin()) {

  } elseif (of_get_option('opt_mega_menu') && !is_admin()) {
    wp_enqueue_style( 'mega-menu', get_template_directory_uri() . '/library/css/mega-menu.css' );
  } elseif(!is_admin()) {
    wp_enqueue_style( 'navigation', get_template_directory_uri() . '/library/css/navigation.css' );
  }

}
add_action( 'wp_enqueue_scripts', 'ufandshands_styles' );

function ufandshands_header_adder() {

  $custom_meta = get_post_custom($post->ID);
    if(is_page($post->ID)) {
        $custom_subtitle = $custom_meta['custom_meta_page_subtitle'][0];
    }
    if(is_single($post->ID)) {
        $custom_subtitle = $custom_meta['custom_meta_post_subtitle'][0];
    }

  $bloginfo_url = get_bloginfo('template_url');
  $bloginfo_name = get_bloginfo('name');
  $parent_org = of_get_option('opt_parent_colleges_institutes');
  $custom_css = of_get_option('opt_custom_css');
  $custom_site_title = preg_replace('~<br( /)?>~', '&nbsp;&raquo;', of_get_option('opt_alternative_site_title'));
  // check for custom site title in theme options, remove <br> tags to display properly in site title


  // Site Name <title> logic
  echo "<title>";
  if (is_front_page() && ($custom_site_title)) { //if we are on the home page, and there is a custom site title, show the custom site title
        echo $custom_site_title;
  }
  if (is_front_page() && (!$custom_site_title)) { //if we are on the home page, and there is NOT a custom site title, show the default
        echo $bloginfo_name;
  }
  if (!is_front_page() && ($custom_site_title)) { //if we are on a page with a custom site title...
    if ($custom_subtitle) {
        echo wp_title('', false, 'right') . ": " . $custom_subtitle . " &raquo; " . $custom_site_title;
    } else {
        echo wp_title('&raquo;', false, 'right') . " " . $custom_site_title;
    }
  }
  if (!is_front_page() && (!$custom_site_title)) { //if we are on a page without a custom site title...
    if ($custom_subtitle) {
        echo wp_title('', false, 'right') . ": " . $custom_subtitle . " &raquo; " . $bloginfo_name;
    } else {
        if (is_author()) {
            $curauth = get_userdata(get_query_var('author'));
            echo $curauth->first_name . "&nbsp;" . $curauth->last_name . " &raquo; " . $bloginfo_name;
        } else {
            echo wp_title('&raquo;', false, 'right') . " " . $bloginfo_name;
        }
    }
  }

  // Site Parent Organization <title> logic
  if ($parent_org == "Shands HealthCare") {
    echo " &raquo; " . $parent_org; }
  if (($parent_org != "None") && ($parent_org !== $bloginfo_name)) {
    echo " &raquo; " . $parent_org . " &raquo; University of Florida"; }
  if (($parent_org != "None") && ($parent_org == $bloginfo_name)) {
    echo " &raquo; University of Florida"; }
  echo "</title>\n";


	// Meta Description

	$opt_meta_excerpt = of_get_option('opt_site_description');

	if ( is_home() && (!empty($opt_meta_excerpt)) ) {
		echo "<meta name='description' content='" . $opt_meta_excerpt . "' />\n";
	}

	if ( is_page() || is_single() ) {
		$current_post = get_post($post->ID);
		$meta_excerpt = $current_post->post_excerpt;
		if (!empty($meta_excerpt)) {
			echo "<meta name='description' content='" . $meta_excerpt . "' />\n";
		}
	}

	if ( is_category() && category_description() ) echo "<meta name='description' content='" . wp_specialchars( strip_tags( category_description() ), 1 ) . "' />\n";
	if ( is_tag() && tag_description() )           echo "<meta name='description' content='" . wp_specialchars( strip_tags( tag_description() ), 1 ) . "' />\n";

	// Facebook Insights fb:admins code allows you to enable this site to be analyzed by Facebook Insights
	// http://www.virante.com/blog/2011/02/03/how-to-track-shares-from-facebook-pages/
	$facebookinsights = of_get_option('opt_facebook_insights');
	if ($facebookinsights) {
		echo "<meta property=\"fb:admins\" content=\"".$facebookinsights."\" />\n";
	}


	//Custom CSS
	if(!empty($custom_css)) {
		echo '<style type="text/css">' . $custom_css . '</style>';
	}
	echo "<link rel='apple-touch-icon' href='/apple-touch-icon.png'>\n";

}

add_action('wp_head', 'ufandshands_header_adder', 1);


function ufandshands_add_footer() {
	$custom_js = of_get_option('opt_custom_js');
	$google_survey_src = of_get_option('opt_google_survey_src');

	if (!empty($custom_js)) {
		echo "<script type=\"text/javascript\">" . $custom_js . "</script>\n";
	}
	if (!empty($google_survey_src)) {
		echo "<script async=\"\" defer=\"\" src=\"". @$google_survey_src . "\"></script>\n";
	}


}
add_action('wp_footer', 'ufandshands_add_footer');



//custom favicon based on the parent organization
//default favicon.ico is the '&'
function ufandshands_favicon() {

	$bloginfo_url = get_bloginfo('template_url');
	$parent_org = of_get_option('opt_parent_colleges_institutes');

	switch ($parent_org) {
		case "UF Academic Health Center":
			echo "<link rel='shortcut icon' href='" . $bloginfo_url . "/favicon-ufhealth.ico' />\n"; //old: favicon-ahc.ico
			break;
		case "Shands HealthCare":
			echo "<link rel='shortcut icon' href='" . $bloginfo_url . "/favicon-ufhealth.ico' />\n"; //old: favicon-shands.ico
			break;
		default:
			echo "<link rel='shortcut icon' href='" . $bloginfo_url . "/favicon-uf.ico' />\n";
	}
}

add_action('wp_head', 'ufandshands_favicon', 2);
add_action('admin_head', 'ufandshands_favicon', 2);

//load common header scripts
function ufandshands_header_common_scripts() {
	if (!is_admin()) {
		wp_deregister_script('jquery'); //remove the built-in one first.
		wp_enqueue_script('jquery', "https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js");
		wp_enqueue_script('modernizr', get_template_directory_uri() . '/library/js/modernizr-1.7.min.js');
	}
}
add_action('wp_enqueue_scripts', 'ufandshands_header_common_scripts');

//load common footer scripts
function ufandshands_footer_common_scripts() {
	if (!is_admin()) { 
	 	wp_enqueue_script('hoverintent', get_template_directory_uri() . '/library/js/jquery.hoverIntent.minified.js', array('jquery'), false, true);
		wp_enqueue_script('institutional-nav',  get_template_directory_uri() . '/library/js/institutional-nav.js', array('jquery', 'hoverintent'), false, true);
		wp_enqueue_script('common-script',      get_template_directory_uri() . '/library/js/script.js', array('jquery'), false, true);
	}
}
add_action('wp_enqueue_scripts', 'ufandshands_footer_common_scripts');



function add_async_forscript($url)
{
    if (strpos($url, '#asyncload')===false)
        return $url;
    else if (is_admin())
        return str_replace('#asyncload', '', $url);
    else
        return str_replace('#asyncload', '', $url)."' async='async";
}
add_filter('clean_url', 'add_async_forscript', 11, 1);

// load single scripts only on single pages
function ufandshands_single_scripts() {
	if(is_singular()  && !is_admin()) {
		wp_enqueue_script('comment-reply'); // loads the javascript required for threaded comments
		wp_enqueue_script('plusone', "https://apis.google.com/js/plusone.js#asyncload");
		wp_enqueue_script('facebook', "https://connect.facebook.net/en_US/all.js#xfbml=1#asyncload");
		wp_enqueue_script('twitter', "https://platform.twitter.com/widgets.js#asyncload");
	}
}
add_action('wp_print_scripts', 'ufandshands_single_scripts');


// load mega-menu script
function ufandshands_mega_menu() {
	if(of_get_option('opt_mega_menu') && !HasActiveUberMenu() && !is_admin()) {
		wp_enqueue_script('megamenu', get_bloginfo('template_url') . '/library/js/mega-menu.js', array('jquery', 'hoverintent'), false, true);
	}
}
add_action('wp_enqueue_scripts', 'ufandshands_mega_menu');

// load default menu script
function ufandshands_default_menu()  {
	if(!of_get_option('opt_mega_menu') && !HasActiveUberMenu() && !is_admin()) {
		wp_enqueue_script('defaultmenu', get_bloginfo('template_url') . '/library/js/default-menu.js', array('jquery', 'hoverintent'), false, true);
	}
}
add_action('wp_enqueue_scripts', 'ufandshands_default_menu');


/*function ufandshands_front_page_scripts() {
    if( is_front_page() ) {

		// Load Story-Stacker script
		if( of_get_option('opt_story_stacker') && !is_admin() && !($featured_content_category=="Choose a Category")) {
			wp_enqueue_script('storystacker', get_bloginfo('template_url') . '/library/js/story-stacker.js', array('jquery'), false, true);
			wp_enqueue_script('featureslider', get_bloginfo('template_url') . '/library/js/feature-slider.js', array('jquery'), false, true);
		}

		// Load Slider script
		if( (of_get_option('opt_number_of_posts_to_show') > '1') && !(of_get_option('opt_story_stacker')) && !is_admin() && !($featured_content_category=="Choose a Category")) {
			wp_enqueue_script('featureslider', get_bloginfo('template_url') . '/library/js/feature-slider.js', array('jquery'), false, true);
		}
    }
}*/
/*add_action( 'wp_enqueue_scripts', 'ufandshands_front_page_scripts' );*/

// load story-stacker script
// function ufandshands_story_stacker() {
//   if(of_get_option('opt_story_stacker') && !is_admin()) {
//     wp_enqueue_script('storystacker', get_bloginfo('template_url') . '/library/js/story-stacker.js', array('jquery'), false, true);
//   }
// }
//add_action('init', 'ufandshands_story_stacker');

// load slider script
// function ufandshands_feature_slider() {
//   wp_reset_query();
//   if( is_front_page() && (of_get_option('opt_number_of_posts_to_show') > '1') && !(of_get_option('opt_story_stacker')) && !is_admin()) {
//     wp_enqueue_script('featureslider', get_bloginfo('template_url') . '/library/js/feature-slider.js', array('jquery'), false, true);
//   }
// }
// add_action('init', 'ufandshands_feature_slider');


/* ----------------------------------------------------------------------------------- */
/* 	Small Misc. Unrelated Directives
/* ----------------------------------------------------------------------------------- */

add_theme_support('post-thumbnails', array( 'post', 'page' ) ); // Enable 'Featured Image' box for this theme
add_filter('wp_feed_cache_transient_lifetime', create_function('$a', 'return 1800;')); // Change cache times (mostly used by RSS)
add_editor_style(); // Use custom CSS in the content editor -- FOLLOWUP WITH ACTUAL STYLES
add_filter('widget_text', 'do_shortcode'); // Enable shortcodes in widgets

remove_action('wp_head', 'wlwmanifest_link'); // Removes Windows Live Writer Link
remove_action('wp_head', 'wp_generator'); // Removes WP version from head
//remove_action( 'wp_head', 'rsd_link' ); // Removes the link to the Really Simple Discovery service endpoint, EditURI link
remove_action( 'wp_head', 'index_rel_link' ); // index link


/* ----------------------------------------------------------------------------------- */
/* 	Contact Webmaster Link -- Generates the proper link to contact the webmaster
/* ----------------------------------------------------------------------------------- */

function ufandshands_contact_webmaster_link() {
	if (function_exists('domain_mapping_siteurl'))
		$domain = domain_mapping_siteurl(null);
	else
		$domain = get_bloginfo('url');
	echo "http://webservices.ahc.ufl.edu/contact-webmaster/?website=" . $domain . $_SERVER['REQUEST_URI'];
}


/* ----------------------------------------------------------------------------------- */
/* 	WIDGET INCLUDES -- All functions related to Widgets should be here
/* ----------------------------------------------------------------------------------- */

include('library/php/include-widgets.php');


/* ----------------------------------------------------------------------------------- */
/* 	Change the 'from name' in emails to match blog name
/* ----------------------------------------------------------------------------------- */

//add_filter('wp_mail_from_name', 'new_mail_from_name');

function new_mail_from_name($old) {
	$blog_name = get_bloginfo('name');
	return $blog_name;
}


/* ----------------------------------------------------------------------------------- */
/* 	Removing some boxes from the write screens; less clutter
/* ----------------------------------------------------------------------------------- */

function remove_post_custom_fields() {
	remove_meta_box('postcustom', 'page', 'normal');
	remove_meta_box('authordiv', 'page', 'normal');
	remove_meta_box('commentstatusdiv', 'page', 'normal');
	remove_meta_box('commentsdiv', 'page', 'normal');
	remove_meta_box( 'postimagediv', 'page', 'normal' );
	// DOES NOT LET YOU RENAME PAGE SLUGS -- BUG   remove_meta_box( 'slugdiv' , 'page' , 'normal' );
	remove_meta_box('postcustom', 'post', 'normal');
	remove_meta_box('trackbacksdiv', 'post', 'normal');
}
add_action('admin_menu', 'remove_post_custom_fields');


function remove_featured_image_field() {
    remove_meta_box( 'postimagediv','page','side' );
}
add_action('do_meta_boxes', 'remove_featured_image_field');

/* ----------------------------------------------------------------------------------- */
/* 	Gravity Forms custom code section
/* ----------------------------------------------------------------------------------- */

// Gravity forms required inclusions
if (!is_admin()) {
 	wp_enqueue_style("gforms_css", plugins_url("gravityforms/css/forms.css"));
}

// Gravity Forms tabindex fix
add_filter("gform_tabindex", create_function("", "return 15;"));

// Gravity Forms confirmation anchor
add_filter("gform_confirmation_anchor", create_function("","return true;"));


/* ----------------------------------------------------------------------------------- */
/* 	Search Text
/* ----------------------------------------------------------------------------------- */

function ufandshands_search_text($output = false) {
  $blog_name = get_bloginfo('name');
  $blog_name_length = strlen($blog_name);
  $custom_site_title = preg_replace('~<br( /)?>~', '', of_get_option('opt_alternative_site_title'));
  $custom_site_title_length = strlen($custom_site_title);

  if ($blog_name_length > 22) {
    $blog_name = 'Search Our Site';
  }
  if ($custom_site_title_length > 24) {
    $blog_name = 'Search Our Site';
  }
  else {
    if (!$custom_site_title) {
      $blog_name = 'Search ' . $blog_name;
    }
    if ($custom_site_title) {
      $blog_name = 'Search ' . $custom_site_title;
    }
    if (!$custom_site_title && $blog_name_length > 22) {
      $blog_name = 'Search Our Site';
    }
  }
  if (!$output)
      echo $blog_name;
  else
      return $blog_name;
}


/* ----------------------------------------------------------------------------------- */
/* 	Members Only
/* ----------------------------------------------------------------------------------- */

function ufandshands_members_only() {
	$ip = $_SERVER['REMOTE_ADDR'];
	if ((preg_match("/(159\.178\.[0-9]{1,3}\.[0-9]{1,3})/", $ip) > 0 || preg_match("/(128\.227\.[0-9]{1,3}\.[0-9]{1,3})/", $ip) > 0 || preg_match("/(10\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})/", $ip) > 0) || preg_match("/(172\.16\.[0-9]{1,3}\.[0-9]{1,3})/", $ip) > 0 || is_user_logged_in()) {
		return true;
	} else {
		return false;
	}
}


/* ----------------------------------------------------------------------------------- */
/* 	Paginator
/* ----------------------------------------------------------------------------------- */

function ufandshands_pagination($pages = '', $range = 4) {
	$showitems = ($range * 2) + 1;

	global $paged;
	if (empty($paged))
		$paged = 1;

	if ($pages == '') {
		global $wp_query;
		$pages = $wp_query->max_num_pages;
		if (!$pages) {
			$pages = 1;
		}
	}

	if (1 != $pages) {
		echo "<div class=\"pagination\"><span class=\"page-of\">Page " . $paged . " of " . $pages . "</span>";
		if ($paged > 2 && $paged > $range + 1 && $showitems < $pages)
			echo "<a href='" . get_pagenum_link(1) . "'>&laquo; First</a>";
		if ($paged > 1 && $showitems < $pages)
			echo "<a href='" . get_pagenum_link($paged - 1) . "'>&lsaquo; Previous</a>";

		for ($i = 1; $i <= $pages; $i++) {
			if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems )) {
				echo ($paged == $i) ? "<span class=\"current\">" . $i . "</span>" : "<a href='" . get_pagenum_link($i) . "' class=\"inactive\">" . $i . "</a>";
			}
		}

		if ($paged < $pages && $showitems < $pages)
			echo "<a href=\"" . get_pagenum_link($paged + 1) . "\">Next &rsaquo;</a>";
		if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages)
			echo "<a href='" . get_pagenum_link($pages) . "'>Last &raquo;</a>";
		echo "</div>\n";
	}
}


/*-----------------------------------------------------------------------------------*/
/*	Individual Comment Styling
/*-----------------------------------------------------------------------------------*/

function ufandshands_comment($comment, $args, $depth) {

    $is_by_author = false;

    if($comment->comment_author_email == get_the_author_meta('email')) {
        $is_by_author = true;
    }

    $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">

     <div id="comment-<?php comment_ID(); ?>">
      <div class="line"></div>
      <?php echo get_avatar($comment, $size='40'); ?>
      <div class="comment-author vcard">
         <?php printf(__('<cite class="fn">%s</cite>'), get_comment_author_link()); ?>
         <?php if($is_by_author) { ?><span class="author-tag"><?php echo 'Author'; ?></span><?php } ?>
      </div>

      <div class="comment-meta commentmetadata">
        <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a>
        <?php edit_comment_link(__('(Edit)'),'  ','') ?><?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
      </div>

      <?php if ($comment->comment_approved == '0') : ?>
         <em class="moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
         <br />
      <?php endif; ?>

      <div class="comment-body">
      <?php comment_text() ?>
	  </div>

     </div>

<?php
}


/*-----------------------------------------------------------------------------------*/
/*	Separated Pings Styling
/*-----------------------------------------------------------------------------------*/

function tz_list_pings($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
<li id="comment-<?php comment_ID(); ?>"><?php comment_author_link(); ?>
<?php }


/* ----------------------------------------------------------------------------------- */
/* 	Thumbnail Generator
/* ----------------------------------------------------------------------------------- */

function ufandshands_post_thumbnail($preset, $alignment, $thumb_w, $thumb_h) {

	global $post;

	// 1. Check for featured image

	if (has_post_thumbnail()) {
		echo "<a href=\"" . get_permalink() . "\">";
		the_post_thumbnail($preset, array('class' => $alignment));
		echo "</a>";
		return true;

		// 2. Check for attached image or body content image
	} else {
		$img = '';
		$args = array(
			'post_type' => 'attachment',
			'post_mime_type' => 'image',
			'numberposts' => -1,
			'order' => 'ASC',
			'post_status' => null,
			'post_parent' => $post->ID
		);
		$attachments = get_posts($args);
		if ($attachments) {
			foreach ($attachments as $attachment) {
				$img = wp_get_attachment_image_src($attachment->ID, 'thumbnail');
				break;
			}
		} else {
			$pattern = '/src=[\'"]?([^\'" >]+)[\'" >]/';
			preg_match($pattern, $post->post_content, $img_matches);
			$trimmed_img_matches = trim($img_matches[0], "src=");
			if( function_exists('is_ssl') && is_ssl() ) {
				$trimmed_img_matches = str_replace('http://', 'https://', $trimmed_img_matches);
			}

			$image_file_extension = end(explode(".", $trimmed_img_matches));
			$chopend_img_matches = substr($trimmed_img_matches, 0, -12);

			// Only works on RE-SIZED images
			$edited_image_reg_pattern = '/[0-9][0-9][0-9]x[0-9][0-9][0-9]/';
			if ($c = preg_match_all($edited_image_reg_pattern, $trimmed_img_matches, $matches)) {
				$edited_image_reg = "true";
			}
		}

		// Display Thumbnail
		if (!empty($img)) {
      ?>
      <a href="<?php the_permalink() ?>"><img src="<?php echo $img[0]; ?>" class="<?php echo $alignment; ?>" alt="<?php the_title(); ?>" /></a>
      <?php
			return true;
		} elseif ($edited_image_reg) {

			if (strlen($img_matches[0]) > 7) {

				// width of the thumbnails
				$thumbwidth = $thumb_w;

				//  height of the thumbnails
				$thumbheight = $thumb_h;
        ?>
        <a href="<?php the_permalink() ?>"><img class="<?php echo $alignment ?>" src=<?php echo $chopend_img_matches . $thumbwidth . "x" . $thumbheight . "." . $image_file_extension; ?>" alt="<?php the_title(); ?>" /></a>
      <?php
				return true;
			}
		} else {
			return false;
		}
	}
}


/* ----------------------------------------------------------------------------------- */
/* 	ShortCodes: includes our custom short code library
/* ----------------------------------------------------------------------------------- */

include('library/php/shortcodes.php');


/* ----------------------------------------------------------------------------------- */
/* 	Custom Thumbnail Sizes
/* ----------------------------------------------------------------------------------- */

if (function_exists('add_image_size')) {
	add_image_size('full-width-thumb', 930, 325, true);
	add_image_size('half-width-thumb', 450, 305, true);
	add_image_size('stacker-thumb', 630, 298, true);
	add_image_size('stacker-thumb-small', 67, 67, true);
  add_image_size('mobile-full-width', 600, 325, true);
}


/* ----------------------------------------------------------------------------------- */
/* 	Custom Write Panels Meta Boxes
/* ----------------------------------------------------------------------------------- */

include_once ("library/php/functions-metabox.php");


/*-------------------------------------------------------------------------------------*/
/*	Change Default Excerpt Length
/*-------------------------------------------------------------------------------------*/

function ufandshands_excerpt_length($length) {
	return 30;
}
add_filter('excerpt_length', 'ufandshands_excerpt_length');


/* ----------------------------------------------------------------------------------- */
/* Excerpts for SEO
/* ----------------------------------------------------------------------------------- */

add_action('init', 'ufandshands_add_excerpts_to_pages');

function ufandshands_add_excerpts_to_pages() {
	add_post_type_support('page', 'excerpt');
}


/* ----------------------------------------------------------------------------------- */
/* Custom Menu Registration
/* ----------------------------------------------------------------------------------- */

add_action('init', 'ufandshands_register_menus');

function ufandshands_register_menus() {
	register_nav_menus(
			array(
				'header_links' => 'Header Links',
				'rolebased_nav' => 'Role-Based Navigation'
			)
	);

	if (is_plugin_active('ubermenu/ubermenu.php')) {
		register_nav_menus( array( 'main_menu' => 'Main Menu' ));
	}

}


/* ----------------------------------------------------------------------------------- */
/* Formerly: Social Media URLs
/* Now: Stores meta data about the institutional groups that we may need to access at a later date.
/* ----------------------------------------------------------------------------------- */

$college_inst_data = array(
    "UF Academic Health Center" => array(
        "facebook" => "http://www.facebook.com/UFHealth/",
        "twitter" => "http://twitter.com/ufhealth/",
        "youtube" => "http://www.youtube.com/UFHealthScience/",
		"analytics" => "UA-4276275-42"),
    "Shands HealthCare" => array(
        "facebook" => "http://www.facebook.com/UFHealth/",
        "twitter" => "http://twitter.com/ufhealth/",
        "youtube" => "http://www.youtube.com/UFHealthScience",
		"analytics" => "UA-4064627-1"),
    "College of Dentistry" => array(
        "facebook" => "http://www.facebook.com/UFDentistry/",
        "twitter" => "http://twitter.com/UFLDentistry/",
        "youtube" => ""),
    "College of Medicine" => array(
        "facebook" => "",
        "twitter" => "http://twitter.com/UFMedicine/",
        "youtube" => "",
		"analytics" => "UA-4276275-19"),
    "College of Nursing" => array(
        "facebook" => "http://www.facebook.com/ufcon/",
        "twitter" => "http://twitter.com/UFNursing/",
        "youtube" => ""),
    "College of Pharmacy" => array(
        "facebook" => "http://www.facebook.com/pages/UF-College-of-Pharmacy-Alumni-Friends/316851416575",
        "twitter" => "",
        "youtube" => ""),
    "College of Public Health and Health Professions" => array(
        "facebook" => "http://www.facebook.com/UFPHHP/",
        "twitter" => "",
        "youtube" => "",
		    "analytics" => "UA-4276275-18"),
    "College of Veterinary Medicine" => array(
        "facebook" => "http://www.facebook.com/UFvetmed/",
        "twitter" => "",
        "youtube" => "",
		    "analytics" => "UA-4276275-20"),
    "McKnight Brain Institute" => array(
        "facebook" => "",
        "twitter" => "",
        "youtube" => ""),
    "Genetics Institute" => array(
        "facebook" => "",
        "twitter" => "",
        "youtube" => ""),
    "Institute on Aging" => array(
        "facebook" => "",
        "twitter" => "",
        "youtube" => ""),
    "UF and Shands Cancer Center" => array(
        "facebook" => "",
        "twitter" => "",
        "youtube" => "",
        "analytics" => "UA-4276275-28"),
    "Emerging Pathogens Institute" => array(
        "facebook" => "http://www.facebook.com/pages/Emerging-Pathogens-Institute/108108999216487",
        "twitter" => "",
        "youtube" => ""),
    "Clinical and Translational Science Institute" => array(
        "facebook" => "",
        "twitter" => "",
        "youtube" => "")
);

function ufandshands_get_socialnetwork_url($type) {

	global $college_inst_data;

	$parent_org = of_get_option("opt_parent_colleges_institutes");
	$parent_org_socialnetwork = $college_inst_data[$parent_org][$type];

	$socialnetwork_type = of_get_option("opt_" . $type . "_url");

	if (!empty($socialnetwork_type)) {
		$output = $socialnetwork_type;
	} elseif (!empty($parent_org_socialnetwork)) {
		$output = $parent_org_socialnetwork;
	} elseif ($type == "facebook") {
		$output = $college_inst_data["UF Academic Health Center"][$type];
	} elseif ($type == "twitter") {
		$output = $college_inst_data["UF Academic Health Center"][$type];
	} elseif ($type == "youtube") {
		$output = $college_inst_data["UF Academic Health Center"][$type];
	}

	echo $output;
}


/* ----------------------------------------------------------------------------------- */
/* Custom Sidebar Navigation
/* ----------------------------------------------------------------------------------- */

function ufandshands_sidebar_navigation($post) {
	$sidebar_nav_walker = new ufandshands_sidebar_nav_walker;

	$children = wp_list_pages(array(
		'walker' => $sidebar_nav_walker,
		'title_li' => '',
		'child_of' => $post->ID,
		'echo' => 0
			));

	$post_ancestors = get_post_ancestors($post);

	if (count($post_ancestors)) {
		$top_page = array_pop($post_ancestors);

		$children = wp_list_pages(array(
			'walker' => $sidebar_nav_walker,
			'title_li' => '',
			'child_of' => $top_page,
			'echo' => 0
				));
	} elseif (is_page()) {
		$children = wp_list_pages(array(
			'walker' => new ufandshands_sidebar_nav_walker,
			'title_li' => '',
			'child_of' => $post->ID,
			'echo' => false,
			'depth' => 3,
				));
		$sect_title = the_title('', '', false);
	}
	if ($children || is_active_sidebar(page_sidebar)) {

		if ($children) {
			return $children;
		}
	}
}

/* ----------------------------------------------------------------------------------- */
/* Custom Breadcrumb Function
/* ----------------------------------------------------------------------------------- */

function ufandshands_breadcrumbs() {
	global $post;
	$post_ancestors = get_post_ancestors($post);
	if ($post_ancestors) {
		$top_page = array_pop($post_ancestors);
		$children = wp_list_pages('title_li=&child_of=' . $top_page . '&echo=0');
	} elseif (is_page()) {
		$children = wp_list_pages('title_li=&child_of=' . $post->ID . '&echo=0&depth=2');
	}

	if (is_page() && !is_front_page()) {
		$breadcrumb = "<nav id='breadcrumb' class='hide-for-small hide-for-medium'><div class='container'>";
		$breadcrumb .= '<a href="' . get_bloginfo('url') . '">Home</a> ';
		$post_ancestors = get_post_ancestors($post);
		if ($post_ancestors) {
			$post_ancestors = array_reverse($post_ancestors);
			foreach ($post_ancestors as $crumb)
				$breadcrumb .= ' <a href="' . get_permalink($crumb) . '">' . get_the_title($crumb) . '</a> ';
		}
		$breadcrumb .= '<strong>' . get_the_title() . '</strong>';
		$breadcrumb .= "</div></nav>";

		echo $breadcrumb;
	}
}

/* ----------------------------------------------------------------------------------- */
/* Imports Verbose Walker Classes for Navigation (Primary, Sidebar, Role-Based)
/* ----------------------------------------------------------------------------------- */

include('library/php/walkers.php');


/* ----------------------------------------------------------------------------------- */
/* <h1>TITLE</h1> functions
/* ----------------------------------------------------------------------------------- */

function ufandshands_content_title() {

	$custom_meta = get_post_custom($post->ID);

	if(is_page($post->ID)) {
		$custom_subtitle = $custom_meta['custom_meta_page_subtitle'][0];
	} elseif(is_single($post->ID)) {
		$custom_subtitle = $custom_meta['custom_meta_post_subtitle'][0];
	} else {
		return;
	}

	$title = '<h1>' . get_the_title();
	if(isset($custom_subtitle)) :
		$title .= "<span class='medium-blue'>: " . $custom_subtitle . "</span>";
	endif;
	$title .= '</h1>';

	echo apply_filters('ufandshands_title', $title);

}


/* ----------------------------------------------------------------------------------- */
/* Title (<title></title> Functions
/* ----------------------------------------------------------------------------------- */

// Title and tagline font size function

$site_title_size = of_get_option("opt_title_size");
$site_mobile_title_size = of_get_option("opt__mobile_title_size");
$site_title_padding = of_get_option("opt_title_pad");
$site_tagline_size = of_get_option("opt_tagline_size");

if (!empty($site_title_size) || !empty($site_title_padding) || !empty($site_tagline_size) || !empty($site_mobile_title_size)) {

	function ufandshands_site_title_size() {
		global $site_title_size;
		global $site_mobile_title_size;
		global $site_title_padding;
		global $site_tagline_size;

// logic
/*
if the mobile is set and desktop isnt set then use default
if the mobile is set and the desktop is set then use both
if only desktop is set

*/

		$site_title_embedded_css = "<style type='text/css'>";
		// mobile only (default desktop)
		if (!empty($site_mobile_title_size) && empty($site_title_size)) {
				$site_title_embedded_css .= "#header-title h1#header-title-text, #header-title h2#header-title-text { font-size: " . $site_mobile_title_size . "em !important;} ";
				$site_title_embedded_css .= "@media only screen and (min-width: 960px) { #header-title h1#header-title-text, #header-title h2#header-title-text { font-size:  2.6em !important; } }";
			}

		// desktop only (default mobile)
		if (empty($site_mobile_title_size) && !empty($site_title_size)) {
				$site_title_embedded_css .= "@media only screen and (min-width: 960px) { #header-title h1#header-title-text, #header-title h2#header-title-text { font-size: " . $site_title_size . "em !important; } }";
			}

		// mobile and desktop
		if (!empty($site_mobile_title_size) && !empty($site_title_size)) {
			$site_title_embedded_css .= "#header-title h1#header-title-text, #header-title h2#header-title-text { font-size: " . $site_mobile_title_size . "em !important; } ";
			$site_title_embedded_css .= "@media only screen and (min-width: 960px) { #header-title h1#header-title-text, #header-title h2#header-title-text { font-size: " . $site_title_size . "em !important; } }";
			}

		if (!empty($site_title_padding)) {
			//DISABLED - padding on wrong side of the street $site_title_embedded_css .= "header #header-title h1#header-title-text, #header-title h2#header-title-text { padding-bottom: " . $site_title_padding . "px !important; }";
			$site_title_embedded_css .= "header #header-title h2#header-title-tagline, #header-title h3#header-title-tagline { padding-top: " . $site_title_padding . "px !important; }";
		}
		if (!empty($site_tagline_size)) {
			$site_title_embedded_css .= "header #header-title h2#header-title-tagline, #header-title h3#header-title-tagline { font-size: " . $site_tagline_size . "em !important; }";
		}
		$site_title_embedded_css .= "</style>";

		echo $site_title_embedded_css;
	}

	add_action('wp_head', 'ufandshands_site_title_size');
}

// Alternate Logo
// Logic has to run outside of primary title function because function gets called AFTER wp_head is processsed
$ufandshands_alternate_logo = of_get_option("opt_alternative_site_logo");
if (!empty($ufandshands_alternate_logo)) {

	function ufandshands_alternate_logo() {
		global $ufandshands_alternate_logo;
		$alternative_site_logo_height = of_get_option("opt_alternative_site_logo_height");
		$alternative_site_logo_width = of_get_option("opt_alternative_site_logo_width");
		$alternate_logo_css = "<style type='text/css'>";
		$alternate_logo_css .= "header #header-title #header-parent-organization-logo.none {
													display: block !important;
													float: left;
													background-color: transparent;
													background-image: url(" . $ufandshands_alternate_logo . ");
													background-repeat: no-repeat;
													background-attachment: scroll;
													height: " . $alternative_site_logo_height . "px;
													width: " . $alternative_site_logo_width . "px;
													margin-right: 10px;	}";
		$alternate_logo_css .= "</style>";

		echo $alternate_logo_css;
	}

	add_action('wp_head', 'ufandshands_alternate_logo');
}
// Adds emphasis to certain words
function ufandshands_emphasis_adder($emphasis_text) {
    $emphasis_pattern = array("/ of /", "/ for /", "/ in /", "/ the /", "/ to /", "/ by /");
    $emphasis_replacement = array(" <em>of</em> ", " <em>for</em> ", " <em>in</em> ", " <em>the</em> ", " <em>to</em> ", " <em>by</em> ");
    $emphasis_text = preg_replace($emphasis_pattern, $emphasis_replacement, $emphasis_text);

    return $emphasis_text;
}

// Title and tagline generation function
function ufandshands_site_title() {



	$site_description = ufandshands_emphasis_adder(get_bloginfo('description'));
	$site_title = ufandshands_emphasis_adder(get_bloginfo('title'));
	$alt_site_title = ufandshands_emphasis_adder(trim(of_get_option('opt_alternative_site_title')));

	if(!empty($alt_site_title)) { $site_title = $alt_site_title; }

	// Begin to build $title string
	$title = "<div id='header-title' class='alpha omega span-15'><a href='" . get_bloginfo('url') . "' title='Go Home'>";

	// Build logo of parent organization
	$parent_org = of_get_option("opt_parent_colleges_institutes");
	if ($parent_org == "UF Academic Health Center") {
		$parent_org_logo = "none"; // ufandshands
		$header_title_text_right_class_size = "span-13";
	} elseif ($parent_org == "Shands HealthCare") {
		$parent_org_logo = "none"; //shands
		$header_title_text_right_class_size = "span-13";
	} elseif ($parent_org == "None") {
		$parent_org_logo = "none";
		$header_title_text_right_class_size = " ";
	} elseif ($parent_org == "UF and Shands Cancer Center") {
		$parent_org_logo = "none";
		$header_title_text_right_class_size = " ";
	} elseif ($parent_org == "UF Libraries") {
		$parent_org_logo = "uf";
		$header_title_text_right_class_size = "span-12";
	} else {
		$parent_org_logo = "uf";
		$parent_org = "University of Florida";
		$header_title_text_right_class_size = "span-12";
	}

	// Hide organizational logo if ufandshands or Shands Healthcare
	if ($parent_org == "UF Academic Health Center" || $parent_org == "Shands HealthCare" || $parent_org == "UF and Shands Cancer Center") {
		/* do not add logo H3 */
	} else {
		/* add logo H3 */
		$title .= "<h3 id='header-parent-organization-logo' class='ir " . $parent_org_logo . "'>" . $parent_org . "</h3>";  // logos
	}
		$title .= "<div id='header-title-text-right' class='alpha omega " . $header_title_text_right_class_size . " " . $parent_org_logo . "'>"; // logos


	// If we are on the front page, make the site a <h1>, otherwise make it a <h2> (...and description <h2>-><h3>)
	if (is_front_page()) {
		$title .= "<h1 id='header-title-text' class='palatino " . $parent_org_logo . "'>" . $site_title . "</h1>";
		if (!empty($site_description)) {
			$title .= "<h2 id='header-title-tagline' class='palatino'>" . $site_description . "</h2>";
		}
	} else {
		$title .= "<h2 id='header-title-text' class='palatino not-front " . $parent_org_logo . "'>" . $site_title . "</h2>";
		if (!empty($site_description)) {
			$title .= "<h3 id='header-title-tagline' class='palatino not-front'>" . $site_description . "</h3>";
		}
	}

	// Close our tags
	$title .= "</div></a></div>";

	// Display title
	echo $title;
}


// adds the page id to an "excludedPages" array, which is then used to prevent the search from showing up in
// WP search results
function ufandshands_update_search_filter($post_id) {
    global $blog_id;
    $excludedPages = get_bloginfo($blog_id, 'excludedPages');
    if (!is_array($excludedPages))
		$excludedPages = array();

    $custom_meta = get_post_custom($post->ID);
    if (isset($custom_meta['custom_meta_noindex'][0])) {
		if (!in_array($post_id,$excludedPages)) {
			$excludedPages[] = $post_id;
		}
    } else {

		if (($key = array_search($post_id, $excludedPages)) !== false) {
			unset($excludedPages[$key]);
		}
    }

    get_bloginfo($blog_id, 'excludedPages', $excludedPages);
}
add_action( 'save_post', 'ufandshands_update_search_filter' );


// filters search results using the "excludedPages" array that's saved in blog options.
function ufandshands_custom_search_filter($query) {

    global $blog_id;
    $excludedPages =get_bloginfo($blog_id, 'excludedPages');
    if ( isset($excludedPages) && !$query->is_admin && $query->is_search) {
        $query->set('post__not_in', $excludedPages ); // ID of pages or posts
    }
    return $query;
}
add_filter( 'pre_get_posts', 'ufandshands_custom_search_filter' );



function customized_form($content) {

    $path = get_bloginfo('template_directory') . '/PassPageLogin.php';
    if (file_exists(TEMPLATEPATH . '/PassPageLogin.php'))
		return preg_replace('|action="(.*)wp-login.php\?action=postpass"|', 'action="' . $path . '?action=postpass"', $content);
    else
		return $content;
}
add_filter('the_password_form', 'customized_form');

function add_allowed_hosts($content) {
	if (function_exists('domain_mapping_siteurl'))
		$url = domain_mapping_siteurl(null);
	else
		$url = get_bloginfo('url');
    $url = str_replace('http://', '', $url);
    $url = str_replace('https://', '', $url);
    $content[] = $url;

    return $content;
}
add_filter('allowed_redirect_hosts', 'add_allowed_hosts');


// prevent supercache from caching pages if the admin bar is rendered
add_action("admin_bar_menu", "customize_menu");
function customize_menu(){
    if (!defined('DONOTCACHEPAGE'))
		define('DONOTCACHEPAGE', 1);
}


// Displays LiveChatInc's chat javascript if the superadmin option is filled out (1629731)
$livechatinc_id = of_get_option('opt_livechatinc_id');
if(!empty($livechatinc_id) && !is_admin()) {

	function livechatinc() {
		$livechatinc_id = htmlspecialchars(of_get_option('opt_livechatinc_id'));
		echo <<<HTML
		<script type="text/javascript">
		  var __lc = {};
		  __lc.license = {$livechatinc_id};

		  (function() {
			var lc = document.createElement('script'); lc.type = 'text/javascript'; lc.async = true;
			lc.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.livechatinc.com/tracking.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(lc, s);
		  })();
		</script>
HTML;
	}
	add_action('wp_head', 'livechatinc');

}



// Added to extend allowed files types in Media upload
add_filter('upload_mimes', 'custom_upload_mimes');
function custom_upload_mimes ( $existing_mimes=array() ) {
    // Add *.sas files to Media upload
    $existing_mimes['sas'] = 'plain/text';

    // Add .ics files
    $existing_mimes['ics'] = 'text/calendar';

	// Add .swf files
    $existing_mimes['swf'] = 'application/x-shockwave-flash';

    return $existing_mimes;
}

function HasActiveUberMenu() {

	if (!is_plugin_active('ubermenu/ubermenu.php'))
		return false;

	$active = get_option( UBERMENU_NAV_LOCS, array());

	return (isset($active) && !empty($active[0]));
}


# Correct SSL Bug
function correct_url_ssl($url)
{
	if( function_exists('is_ssl') && is_ssl() )
	{
		return str_replace('http://', 'https://', $url);
	}
	return $url;
}
add_filter('wp_get_attachment_url', 'correct_url_ssl');

/* ----------------------------------------------------------------------------------- */
/* Allowed Tags
/* ----------------------------------------------------------------------------------- */
$allowedtags["br"] = array();
$allowedtags["sup"] = array();


// removing the General settings page from menu.  Disabling access is not ideal because options-general.php is used by other plugins as adminstrative interface as well.
add_action( 'admin_menu', 'hide_general_settings', 999 );
function hide_general_settings() {
	if (!is_super_admin())
		$page = remove_submenu_page( 'options-general.php', 'options-general.php' );
}


function tinymce_editor_settings($settings) {
	$settings['webkit_fake_resize'] = 1;
	return $settings;
}
add_filter('tiny_mce_before_init', 'tinymce_editor_settings');

/* ----------------------------------------------------------------------------------- */
/* Additional User Profile Fields
/* ----------------------------------------------------------------------------------- */

add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );

function extra_user_profile_fields( $user ) { ?>
<h3><?php _e("About the Author Information", "blank"); ?></h3>

<table class="form-table">
<tr>
<th><label for="title"><?php _e("Title"); ?></label></th>
<td>
<input type="text" name="title" id="title" value="<?php echo esc_attr( get_the_author_meta( 'title', $user->ID ) ); ?>" class="regular-text" /><br />
<span class="description"><?php _e("Enter your official title."); ?></span>
</td>
</tr>
<tr>
<th><label for="profile_link"><?php _e("Alternate Profile Link"); ?></label></th>
<td>
<input type="text" name="profile_link" id="profile_link" value="<?php echo esc_attr( get_the_author_meta( 'profile_link', $user->ID ) ); ?>" class="regular-text" /><br />
<span class="description"><?php _e("Enter an alternate link to your profile."); ?></span>
</td>
</tr>
</table>
<?php }

add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );

function save_extra_user_profile_fields( $user_id ) {

if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }

update_user_meta( $user_id, 'title', $_POST['title'] );
update_user_meta( $user_id, 'profile_link', $_POST['profile_link'] );

}


function alter_anchor_markup($initArray) {
$initArray['setup'] = 'function(ed) {
	ed.onInit.add(
	function(ed) {
	  ed.serializer.addNodeFilter(\'a\', function(nodes) {
	    tinymce.each(nodes, function(node) {
	        node.attr(\'id\', node.attr(\'name\'));
	    });
	  });
	});
  	}';

return $initArray;
}
add_filter('tiny_mce_before_init', 'alter_anchor_markup');


function ufh_search_form( $form ) {

	$useGoogleSearch = of_get_option('opt_google_site_search');
	$useGoogleCSE = of_get_option('opt_google_cse');

	if (!$useGoogleCSE && !$useGoogleSearch) {
		$form = '<form method="get" id="searchform" action="' . get_bloginfo('url') . '" role="search">
		  <input type="text" value="' . ufandshands_search_text(true) . '" id="header-search-field" name="s" />
		  <input type="image" src="' . get_bloginfo('template_url') . '/images/header-search-btn-orange.jpg" id="header-search-btn"  alt="Search Button" name="sa" />
		</form>';
	} else {
		$form = display_search_box();
	}

    return $form;
}
add_filter( 'get_search_form', 'ufh_search_form' );




function WPSearchForm() {
  echo '<form method="get" id="searchform" action="' . get_bloginfo('url') . '" role="search">
		  <input type="text" value="' . ufandshands_search_text(true) . '" id="header-search-field" name="s" />
		  <input type="image" src="' . get_bloginfo('template_url') . '/images/header-search-btn-orange.jpg" id="header-search-btn"  alt="Search Button" name="sa" />
		</form>';
}


/* ----------------------------------------------------------------------------------- */
/* Apollo custom banner logo1 functions
/* ----------------------------------------------------------------------------------- */

function ufandshands_banner_logos(){
	$pid = get_post_custom($post->ID);

	$c_lbox = $pid['custom_meta_hide_logos'][0];
	if($c_lbox == "on"){
		echo '<div style="height:250px;"></div>';
	} else {
		if(is_page($post->ID)) {
			$logo1 = $pid['custom_meta_page_logo1'][0];
			$logo2 = $pid['custom_meta_page_logo2'][0];
		} else {
			return;
		}

		if(!$logo1){ $logo1 = of_get_option("opt_banner_logo_1");}
		if(!$logo2){ $logo2 = of_get_option("opt_banner_logo_2");}

		$logo1_html = '<img src="'.$logo1.'" alt="" style="margin: 3.5em 0; display: block;">';
		$logo2_html = '<img src="'.$logo2.'" alt="" style="margin: 3.5em 0; display: block;">';

		echo $logo1_html;
		echo $logo2_html;
	}
}

/* ----------------------------------------------------------------------------------- */
/* Apollo custom banner background image
/* ----------------------------------------------------------------------------------- */

function ufandshands_bannerbg(){
	$pid = get_post_custom($post->ID);
	if(is_page($post->ID)) {
		$bannerbg = $pid['custom_meta_page_bannerbg'][0];
		$mobilebannerbg = $pid['custom_meta_page_mobilebannerbg'][0];
		if($bannerbg){
			echo '<style>
				.main-banner{background-image: url("'.$bannerbg.'"); background-position: top center; background-repeat: no-repeat;}';
			if($mobilebannerbg){echo '@media (max-width: 600px){.main-banner{background-image: url("'.$mobilebannerbg.'"); background-position: top center; background-repeat: no-repeat;}';}
			echo '</style>';

		}


	} else {
		return;
	}

}

/* ----------------------------------------------------------------------------------- */
/* Apollo custom banner background color
/* ----------------------------------------------------------------------------------- */

function ufandshands_bannercolor(){
	$pid = get_post_custom($post->ID);
	if(is_page($post->ID)) {
		$bannercolor = $pid['custom_meta_page_bannercolor'][0];
		if($bannercolor){ echo '<style>.main-banner{background: '.$bannercolor.';}</style>'; }

	} else {
		return;
	}

}

/* ----------------------------------------------------------------------------------- */
/* Apollo custom form background color
/* ----------------------------------------------------------------------------------- */

function ufandshands_formcolor(){
	$pid = get_post_custom($post->ID);
	if(is_page($post->ID)) {
		$formcolor = $pid['custom_meta_page_formcolor'][0];
		if($formcolor){ echo '<style>.main-banner .main-banner-form{background-color: '.$formcolor.';}</style>'; }

	} else {
		return;
	}

}

/* ----------------------------------------------------------------------------------- */
/* Apollo banner form
/* ----------------------------------------------------------------------------------- */
function bannerform_widgets_init() {
    register_sidebar( array(
        'name' => __( 'Banner Form', 'UFandShands' ),
        'id' => 'uf-bannerform',
        'description' => __( 'IFRAME or HTML for Banner Form.', 'UFandShands' ),
        'before_title' => '',
        'after_title' => '',
    ) );
}
add_action( 'widgets_init', 'bannerform_widgets_init' );

function ufandshands_bannerform(){
	$pid = get_post_custom($post->ID);

	if(is_page($post->ID)) {
		$c_tbox = $pid['custom_meta_page_bannerform'][0];
        $c_tbox_decode = htmlspecialchars_decode($c_tbox);

		if($c_tbox_decode){
			echo $c_tbox_decode;
		} else {
			dynamic_sidebar( 'uf-bannerform' );
		}

	} else {
		return;
	}

}


/* ----------------------------------------------------------------------------------- */
/* Apollo sidebar/glance menu
/* ----------------------------------------------------------------------------------- */

function ufandshands_glancemenu(){
	$pid = get_post_custom($post->ID);
	$c_lbox = $pid['custom_meta_hide_glance'][0];

	if(is_page($post->ID) && checked($c_lbox, '', '')) {
		$c_menu = $pid['custom_meta_page_glancemenu'][0];
		wp_nav_menu(array('menu' => $c_menu, 'menu_class' => 'glancemenu'));

	} else{
		return;
	}

}
/* ----------------------------------------------------------------------------------- */
/* Apollo glance box1
/* ----------------------------------------------------------------------------------- */
function glancebox1_widgets_init() {
    register_sidebar( array(
        'name' => __( 'Glance Text Box 1', 'UFandShands' ),
        'id' => 'glance-box1',
        'description' => __( 'Widgets in this area will be shown on sidebar.', 'UFandShands' ),
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ) );
}
add_action( 'widgets_init', 'glancebox1_widgets_init' );

function ufandshands_glancebox1(){
	$pid = get_post_custom($post->ID);

	if(is_page($post->ID)) {
		$c_tbox = $pid['custom_meta_page_glancebox1'][0];
        $c_tbox_decode = htmlspecialchars_decode($c_tbox);

		if($c_tbox_decode){
			echo '<div class="sidebar_widget">';
			echo $c_tbox_decode;
			echo '</div>';
		} else {
			dynamic_sidebar( 'glance-box1' );
		}

	} else {
		return;
	}

}

/* ----------------------------------------------------------------------------------- */
/* Apollo glance box2
/* ----------------------------------------------------------------------------------- */
function glancebox2_widgets_init() {
    register_sidebar( array(
        'name' => __( 'Glance Text Box 2', 'UFandShands' ),
        'id' => 'glance-box2',
        'description' => __( 'Widgets in this area will be shown on sidebar.', 'UFandShands' ),
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ) );
}
add_action( 'widgets_init', 'glancebox2_widgets_init' );

function ufandshands_glancebox2(){
	$pid = get_post_custom($post->ID);

	if(is_page($post->ID)) {
		$c_tbox = $pid['custom_meta_page_glancebox2'][0];
        $c_tbox_decode = htmlspecialchars_decode($c_tbox);

		if($c_tbox_decode){
			echo '<div class="sidebar_widget">';
			echo $c_tbox_decode;
			echo '</div>';
		} else {
			dynamic_sidebar( 'glance-box2' );
		}

	} else {
		return;
	}

}


/* ----------------------------------------------------------------------------------- */
/* Events Widget
/* ----------------------------------------------------------------------------------- */
function eventlist_widgets_init() {
    register_sidebar( array(
        'name' => __( 'Event Widget', 'UFandShands' ),
        'id' => 'event-list',
        'description' => __( 'Widgets to hold the Event List Plugin', 'UFandShands' ),
        'before_title' => '',
        'after_title' => '',
    ) );
}
add_action( 'widgets_init', 'eventlist_widgets_init' );

function ufandshands_eventsWidget(){
	$pid = get_post_custom($post->ID);

	if(is_page($post->ID)) {
		$c_ebox = $pid['custom_meta_hide_events'][0];
		if($c_ebox == "on"){
			return;
		} else {
			echo "<h2 style='margin-top:1.5rem;'>Key Dates</h2><div class='sidebar-box'>";
			dynamic_sidebar( 'event-list' );
			echo "</div><!-- eof sidebar-box -->";
		}

	} else {
		return;
	}

}

/* ----------------------------------------------------------------------------------- */
/* Application Procedure Widget
/* ----------------------------------------------------------------------------------- */
function appprocedure_widgets_init() {
    register_sidebar( array(
        'name' => __( 'Application Procedure', 'UFandShands' ),
        'id' => 'app-procedure',
        'description' => __( 'Widgets to hold the Application Procedure Text', 'UFandShands' ),
        'before_title' => '<h3 class="arrow">',
        'after_title' => '</h3>',
    ) );
}
add_action( 'widgets_init', 'appprocedure_widgets_init' );

function ufandshands_appProcedure(){
	$pid = get_post_custom($post->ID);

	if(is_page($post->ID)) {
		$c_abox = $pid['custom_meta_hide_appprocedure'][0];
		if($c_abox == "on"){
			return;
		} else {
			echo "<div class='app-procedure'>";
			dynamic_sidebar( 'app-procedure' );
			echo "</div>";
		}

	} else {
		return;
	}
}

/* ----------------------------------------------------------------------------------- */
/*  Apollo Tab System
/* ----------------------------------------------------------------------------------- */
function ufandshands_tabSystem(){
	$pid = get_post_custom($post->ID);

	$title_one = $pid['custom_meta_tab1_title'][0];
	$content_one = $pid['custom_meta_tab1_html'][0];
  $content_one_decode = htmlspecialchars_decode($content_one);

	$title_two = $pid['custom_meta_tab2_title'][0];
	$content_two = $pid['custom_meta_tab2_html'][0];
    $content_two_decode = htmlspecialchars_decode($content_two);

	$title_three = $pid['custom_meta_tab3_title'][0];
	$content_three = $pid['custom_meta_tab3_html'][0];
    $content_three_decode = htmlspecialchars_decode($content_three);

	$title_four = $pid['custom_meta_tab4_title'][0];
	$content_four = $pid['custom_meta_tab4_html'][0];
    $content_four_decode = htmlspecialchars_decode($content_four);

    if($title_one){
    $list_class = "single_list";
  }
  if ($title_two) {
    $list_class .= " double_list";
  }
  if ($title_three) {
    $list_class .= " triple_list";
  }
  if ($title_four){
    $list_class .= " quad_list";
  }

	if(is_page($post->ID)) {
		$c_tabbox = $pid['custom_meta_hide_tabsystem'][0];
		if($c_tabbox == "on"){
			return;
		} else {

			echo '<div class="content-tabs"><ul id="menu-custom-content-menu" class="menu">';

			if($title_one){
				echo '<li class="menu-item active ' . $list_class .'"><a href="tab-one">'.$title_one.'</a></li>';
			}
			if($title_two){
				echo '<li class="menu-item ' . $list_class .'"><a href="tab-two">'.$title_two.'</a></li>';
			}
			if($title_three){
				echo '<li class="menu-item ' . $list_class .'"><a href="tab-three">'.$title_three.'</a></li>';
			}
			if($title_four){
				echo '<li class="menu-item ' . $list_class .'"><a href="tab-four">'.$title_four.'</a></li>';
			}

			echo '</ul></div>';

			if($title_one){
				echo '<div id="tab-one" class="apollo-tabs">'.do_shortcode($content_one_decode).'</div>';
			}
			if($title_two){
				echo '<div id="tab-two" class="apollo-tabs">'.do_shortcode($content_two_decode).'</div>';
			}
			if($title_three){
				echo '<div id="tab-three" class="apollo-tabs">'.do_shortcode($content_three_decode).'</div>';
			}
			if($title_four){
				echo '<div id="tab-four" class="apollo-tabs">'.do_shortcode($content_four_decode).'</div>';
			}
		}

	} else {
		return;
	}

}

/* ----------------------------------------------------------------------------------- */
/*  Apollo Application System
/* ----------------------------------------------------------------------------------- */
function ufandshands_appSystem(){
  $pid = get_post_custom($post->ID);

  $content_one = $pid['custom_meta_step1_html'][0];
  $content_one_decode = htmlspecialchars_decode($content_one);


  $content_two = $pid['custom_meta_step2_html'][0];
  $content_two_decode = htmlspecialchars_decode($content_two);


  $content_three = $pid['custom_meta_step3_html'][0];
  $content_three_decode = htmlspecialchars_decode($content_three);


  if($title_one){
    $list_class = "single_list";
  }
  if ($title_two) {
    $list_class .= " double_list";
  }
  if ($title_three) {
    $list_class .= " triple_list";
  }

  if(is_page($post->ID)) {

        echo '<div id="tab-one" class="apollo-tabs">'.$content_one_decode.'</div>';

        echo '<div id="tab-two" class="apollo-tabs">'.$content_two_decode.'</div>';

        echo '<div id="tab-three" class="apollo-tabs">'.$content_three_decode.'</div>';

  } else {
    return;
  }

}
?>
