<?php
/** Make sure that the WordPress bootstrap has run before continuing. */
require( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
//
// Main
//

if (!isset($_REQUEST['action']) || $_REQUEST['action'] != 'postpass') {
   header("Location: /wp-login.php"); 
   exit();
}

$action = $_REQUEST['action'];
$errors = new WP_Error();

nocache_headers();

header('Content-Type: '.get_bloginfo('html_type').'; charset='.get_bloginfo('charset'));

if ( defined('RELOCATE') ) { // Move flag is set
	if ( isset( $_SERVER['PATH_INFO'] ) && ($_SERVER['PATH_INFO'] != $_SERVER['PHP_SELF']) )
		$_SERVER['PHP_SELF'] = str_replace( $_SERVER['PATH_INFO'], '', $_SERVER['PHP_SELF'] );

	$schema = is_ssl() ? 'https://' : 'http://';
	if ( dirname($schema . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']) != get_option('siteurl') )
		update_option('siteurl', dirname($schema . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']) );
}

//Set a cookie now to see if they are supported by the browser.
setcookie(TEST_COOKIE, 'WP Cookie check', 0, '/', COOKIE_DOMAIN);
if ( SITECOOKIEPATH != COOKIEPATH )
	setcookie(TEST_COOKIE, 'WP Cookie check', 0, SITECOOKIEPATH, COOKIE_DOMAIN);

// allow plugins to override the default actions, and to add extra actions if they want
do_action( 'login_init' );
do_action( 'login_form_' . $action );

$http_post = ('POST' == $_SERVER['REQUEST_METHOD']);
switch ($action) {

case 'postpass' :
	if ( empty( $wp_hasher ) ) {
		require_once( ABSPATH . 'wp-includes/class-phpass.php' );
		// By default, use the portable hash from phpass
		$wp_hasher = new PasswordHash(8, true);
	}

	// 10 days
	setcookie( 'wp-postpass_' . COOKIEHASH, $wp_hasher->HashPassword( stripslashes( $_POST['post_password'] ) ), time() + 864000, '/' );

	wp_safe_redirect( wp_get_referer() );
	exit();

break;
}

header("Location: /wp-login.php"); 
exit();
?>
