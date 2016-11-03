<?php

class site_map {

    function site_map() {
	add_action('init', array(&$this, 'init'));
    }

    function init() {
	add_action('template_redirect', array(&$this, 'template_redirect'));
	add_filter('wp_title', array(&$this, 'wp_title'), 10, 3);
        
                
    }
    
    function template_redirect() {
	global $wp_query;

	if ($this->isSitemapPage()) {
            header('HTTP/1.1 200 OK');
	    $path = locate_template('site-map.php');
	    load_template($path);
            die();
	}
    }
    
    function wp_title($title, $sep, $seplocation) {
	
	if ($this->isSitemapPage())
		$title = 'Sitemap ' . $sep;
	
	return $title;
    }
    
    function isSitemapPage() {
	return preg_match('/\/sitemap(|\/)$/i', $_SERVER['REQUEST_URI']);
    }
}


$site_map = new site_map();
?>
