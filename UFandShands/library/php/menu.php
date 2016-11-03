<?php
// this document describes the logic for how our three menus are displayed
// 
// menu 1 - traditional dropdown with 1 level deep
// menu 2 - mega menu with 3 levels deep
// menu 3 - ubermenu with complete control 
echo "<nav id='primary-nav' class='white' role='navigation'>";

$mega_menu = of_get_option("opt_mega_menu");

  // UBER MENU: no menu containers
if (HasActiveUberMenu()) {
    wp_nav_menu( array(
        'theme_location' => 'main_menu',
        'depth' => 4 //Disable this arg in wp-uber-menu.php
        )
    );
  } else {
    
  // load normal menu containers
    echo "<ul class='container'>";
    echo "<li id='home' class='ir'><a href='/'>Home</a></li>";
	
    
  //MEGA MENU
    if($mega_menu) {
      include 'mega-menu.php';
    }  else {
      
  //STANDARD DROPDOWN MENU
        wp_list_pages(array(
          'walker'   => new ufandshands_page_walker,
          'title_li' => '',
          'depth'    => 3
        ));
    }
    echo "</ul>";
  }
    
echo "</nav><!-- end #primary-nav -->";
?>