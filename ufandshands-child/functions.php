<?php 


/* ----------------------------------------------------------------------------------- */
/* Load child theme custom styles and parent theme main style
/* ----------------------------------------------------------------------------------- */

function my_enqueue_styles() {

    /* If using a child theme, auto-load the parent theme style. */
    if ( is_child_theme() ) {
        wp_enqueue_style( 'parent-style', trailingslashit( get_template_directory_uri() ) . 'style.css' );
    }

    /* Always load active theme's style.css. */
    wp_enqueue_style( 'style', get_stylesheet_uri() );
    /* Adds the custom styles of the child theme */
    wp_enqueue_style( 'custom-style', get_stylesheet_directory_uri() . '/library/css/custom.css' );
 

}

add_action( 'wp_enqueue_scripts', 'my_enqueue_styles' );


/* ----------------------------------------------------------------------------------- */
/* Load child theme custom styles and parent theme main style
/* ----------------------------------------------------------------------------------- */
function wpdocs_dequeue_script() {
   wp_dequeue_script( 'autoclear' );
   wp_dequeue_script( 'common-script' );
   wp_dequeue_script( 'defaultmenu' );
}
add_action( 'wp_print_scripts', 'wpdocs_dequeue_script', 100 );

/* ----------------------------------------------------------------------------------- */
/* Load common header scripts
/* ----------------------------------------------------------------------------------- */

function ufandshands_child_header_common_scripts() {
	if (!is_admin()) {
		wp_enqueue_script('form-clear', get_stylesheet_directory_uri() . '/library/js/form-clear.js');
	}
}
add_action('wp_enqueue_scripts', 'ufandshands_child_header_common_scripts');
 

/* ----------------------------------------------------------------------------------- */
/* Load common footer scripts
/* ----------------------------------------------------------------------------------- */

function ufandshands_child_footer_common_scripts() {
	if (!is_admin()) {
        wp_enqueue_script('jquery-form-validate', get_stylesheet_directory_uri() . '/library/js/jquery.validate.min.js', array('jquery'), false, true);
        wp_enqueue_script('call-form-validation',    get_stylesheet_directory_uri() . '/library/js/call-validate.js', array('jquery'), false, true);
        /*wp_enqueue_script('cookie-jquery',    get_stylesheet_directory_uri() . '/library/js/js.cookie.js', array('jquery'), false, true);*/
        wp_enqueue_script('child-common-script',    get_stylesheet_directory_uri() . '/library/js/script.js', array('jquery'), false, true);
        
	}
}
add_action('wp_enqueue_scripts', 'ufandshands_child_footer_common_scripts');

/* ----------------------------------------------------------------------------------- */
/* Load footer style
/* ----------------------------------------------------------------------------------- */

function ufandshands_child_add_footer() {
    
	if (is_page( 'Apply' ) ) {
 	echo "<link rel=\"stylesheet\" href=\"//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css\">";
        
    }
}
add_action('wp_footer', 'ufandshands_child_add_footer');


?>