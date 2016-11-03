<?php
						
	$my_pages = wp_list_pages(array(
              'walker'   => new ufandshands_page_walker,
              'echo'     => 0,
              'title_li' => '',
              'depth'    => 3
              ));
  
  $parts = preg_split("/(<ul class='children'|<li|<\/ul>)/", $my_pages, null, PREG_SPLIT_DELIM_CAPTURE);

  $newmenu = "";
	$level = 0;
  $counter = 0;
	foreach ($parts as $part) {
	  if ("<ul class='children'" == $part) {++$level;}
      
      if ("</ul>" == $part) {--$level;}

        if( "<ul class='children'" == $part && $level == 1 ) {

          $var1 = "<ul class='children'";
          $var2 = "<div class='sub'><ul class='children'";
            $part = str_replace($var1, $var2, $part);
        }

        if( "<ul class='children'" == $part && $level == 2 ) {

          $var1 = "<ul class='children'";
          $var2 = "<ul class='subchildren'";
            $part = str_replace($var1, $var2, $part);
        }


        if( "</ul>" == $part && $level == 0 ) {

          $var1 = "</ul>";
          $var2 = "</ul></div>";
            $part = str_replace($var1, $var2, $part);
        }

	   $newmenu .= $part;
	}
	
	// replaces links in the megamenu that are still pointing to the non-mapped domain
	global $blog_id;
	$blog = get_blog_details($blog_id);
	if (function_exists('domain_mapping_siteurl')) 
		$domain = domain_mapping_siteurl(null);
	else
		$domain = get_bloginfo('url');
	
	$mappedDomain = preg_replace('/(https:\/\/|http:\/\/)/', '', $domain);
	$newmenu = str_replace($blog->domain, $mappedDomain, $newmenu);

	echo $newmenu;

?>
