<?php

class all_posts{

    function all_posts() {
	add_action('init', array(&$this, 'init'));
    }

    function init() {
	add_action('template_redirect', array(&$this, 'template_redirect'));
	add_filter('wp_title', array(&$this, 'wp_title'), 10, 3);
        
        $posts_path = of_get_option('opt_custom_posts_path');
            if (!empty($posts_path)) {
                $posts_slug_custom = preg_match("/^\/$posts_path\/?(.*)$/", $_SERVER['REQUEST_URI']);
            } else {  
                $posts_slug = preg_match("/^\/posts\/?(.*)$/", $_SERVER['REQUEST_URI']);
            }
            if ($posts_slug_custom || $posts_slug) { 
                remove_filter('template_redirect', 'redirect_canonical'); 
            }      
    }
    
    function template_redirect() {
	global $wp_query;

	if ($this->isAllPostsPage()) {
            header('HTTP/1.1 200 OK');
	    $path = locate_template('template-all-posts.php');
	    load_template($path);
            die();
	}
    }
    
    function wp_title($title, $sep, $seplocation) {
	
	if ($this->isAllPostsPage()) {
            $posts_title = of_get_option('opt_custom_posts_title'); 
                if (!empty($posts_title)) {
                    $title = $posts_title . ' ' . $sep; 
                } else {
                    $title = 'All Posts ' . $sep;
                }
        }
	return $title;
    }

    function isAllPostsPage() {
        $posts_path = of_get_option('opt_custom_posts_path');
            if (!empty($posts_path)) {
                return preg_match("/^\/$posts_path\/?(.*)$/", $_SERVER['REQUEST_URI']);
            } else {  
                return preg_match("/^\/posts\/?(.*)$/", $_SERVER['REQUEST_URI']);
            }
    }   
}
   
$all_posts = new all_posts();
?>
