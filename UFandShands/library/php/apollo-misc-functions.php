<?php
//==============================================================
// Functions used in Responsive Apollo Theme
//--------------------------------------------------------------

// Add hide-extras class to body
add_filter('body_class','add_custom_body_classes');
function add_custom_body_classes($classes) {
	// add classes to array
	$classes[] = 'hide-extras';

	// return the $classes array
	return $classes;
	}

//Make a gift fund URL
function apollo_makeagift_link() {
	$makeagift_url = of_get_option("opt_makeagift_url");
	if (!empty($makeagift_url)) {
	echo "<a href='" . $makeagift_url . "' rel='nofollow' target='_blank'>";
	} else {
	echo "<a id='makeagift_link' href='https://www.uff.ufl.edu/OnlineGiving/Advanced.asp' rel='nofollow' target='_blank'>";
	}
	//$template_url = bloginfo('template_directory');
  	//echo "<img src=\.$template_url."/images/footer-gift.jpg\" class='last' alt='Make a Gift' /></a>";
  	echo "<img src='".get_bloginfo('template_directory')."/images/footer-gift.jpg' class='last alt='Make a Gift' /></a>";

}


// Add orange header action item box
function add_action_item($actionitem_text, $actionitem_url) {
	if (!empty($actionitem_text)) {
  		echo "<a id='header-actionitem' href='" . $actionitem_url . "'>" . $actionitem_text . "</a>";
		}
	} //end add_action_item()



// Add Site Search either google / CSE / Wordpress
function apollo_search_form($useGoogleSearch, $useGoogleCSE){
		    if (!$useGoogleCSE && !$useGoogleSearch) {
				WPSearchForm();
		    } else {
				$html = display_search_box();
				echo $html;
		    }
		}

// WPSearchForm
// function WPSearchForm() {
//   echo '<form method="get" id="searchform" action="' . get_bloginfo('url') . '" role="search">
// 		  <input type="text" value="' . ufandshands_search_text(true) . '" id="header-search-field" name="s" />
// 		  <input type="image" src="' . get_bloginfo('template_url') . '/images/header-search-btn-orange.jpg" id="header-search-btn"  alt="Search Button" name="sa" />
// 		</form>';
// }



// Adds Utility Links if options are set
function apollo_utility_links() {
	if (has_nav_menu('header_links')) {
		echo '<nav id="utility-links" class="black-25" role="navigation"><ul>';
		echo wp_nav_menu(array('theme_location' => 'header_links', 'container' => false));
		echo '</ul></nav>';
		}
}

// Script Enqueues
if (!is_admin()) {
	wp_enqueue_script('responsive-flyout', get_bloginfo('template_url') . '/library/js/responsive-flyout.js', array('jquery'), false, true);
}

if (!is_admin()) {
	wp_enqueue_script('responsive-userrole', get_bloginfo('template_url') . '/library/js/responsive-userrole.js', array('jquery'), false, true);
}


//-------------------------------------------------------------------------------------------
//----------------[ Responsive Image Toggle ]------------------------------------------------
//-------------------------------------------------------------------------------------------

//echo ''.$enable_responsive_images;


function EnableResponsiveImages() {
	$disable_responsive_images = of_get_option('opt_responsive_images');
	if ($disable_responsive_images != 1) {
		wp_enqueue_style( 'EnableResponsiveImages', get_template_directory_uri() . '/library/css/responsive_images.css' );
	}
}
add_action('wp_enqueue_scripts', 'EnableResponsiveImages');

//-------------------------------------------------------------------------------------------
//----------------[ Disable Full Width Slides on Mobile Toggle ]------------------------------------------------
//-------------------------------------------------------------------------------------------

//echo ''.$enable_responsive_images;


function DisableFullWidthSlides() {
	$disable_fullwidthslides = of_get_option('opt_disable_fullwidthslides');
	if ($disable_fullwidthslides == 1) {
		wp_enqueue_style( 'DisableFullWidthSlides', get_template_directory_uri() . '/library/css/responsive_fullwidthslider.css' );
	}
}

add_action('wp_enqueue_scripts', 'DisableFullWidthSlides');

add_theme_support( 'post-thumbnails' );


// Multiple Featured Images registration
if( class_exists( 'kdMultipleFeaturedImages' ) ) {

        $args = array(
                'id' => 'featured-image-2',
                'post_type' => 'post',      // Set this to post or page
                'labels' => array(
                    'name'      => 'Mobile Slider Image',
                    'set'       => 'Set mobile slider image',
                    'remove'    => 'Remove mobile slider image',
                    'use'       => 'Use as mobile slider image',
                )
        );

        new kdMultipleFeaturedImages( $args );
}



// add_shortcode('wp_caption', 'fixed_img_caption_shortcode');
// add_shortcode('caption', 'fixed_img_caption_shortcode');
// function fixed_img_caption_shortcode($attr, $content = null) {
//  if ( ! isset( $attr['caption'] ) ) {
//  if ( preg_match( '#((?:<a [^>]+>\s*)?<img [^>]+>(?:\s*</a>)?)(.*)#is', $content, $matches ) ) {
//  $content = $matches[1];
//  $attr['caption'] = trim( $matches[2] );
//  }
//  }
//  $output = apply_filters('img_caption_shortcode', '', $attr, $content);
//  if ( $output != '' )
//  return $output;
//  extract(shortcode_atts(array(
//  'id' => '',
//  'align' => 'alignnone',
//  'width' => '',
//  'caption' => ''
//  ), $attr));
//  if ( 1 > (int) $width || empty($caption) )
//  return $content;
//  if ( $id ) $id = 'id="' . esc_attr($id) . '" ';
//  return '<div ' . $id . 'class="wp-caption ' . esc_attr($align) . '" >'
//  . do_shortcode( $content ) . '<p class="wp-caption-text">' . $caption . '</p></div>';
// }
?>
