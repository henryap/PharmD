<?php
// Responsive menu
// echo "<nav id='primary-nav' class='white' role='navigation'>";

// $mega_menu = of_get_option("opt_mega_menu");

//   // UBER MENU: no menu containers
// if (HasActiveUberMenu()) {
//     wp_nav_menu( array(
//         'theme_location' => 'main_menu',
//         'depth' => 3 //Disable this arg in wp-uber-menu.php
//         )
//     );
//   } else {
    
//   // load normal menu containers
//     echo "<ul class='container'>";
//     echo "<li id='home' class='ir'><a href='/'>Home</a></li>";
    
//   //MEGA MENU
//     if($mega_menu) {
//       include 'mega-menu.php';
//     }  else {
      
//   //STANDARD DROPDOWN MENU
//         wp_list_pages(array(
//           'walker'   => new ufandshands_page_walker,
//           'title_li' => '',
//           'depth'    => 2
//         ));
//     }
//     echo "</ul>";
//   }
    
// echo "</nav><!-- end #primary-nav -->";



echo '<nav role="complementary" id="flyout-menu" class="hide-for-large magic-menu chop">';


// orange header action item box
  $actionitem_text = of_get_option(opt_actionitem_text);
  $actionitem_url = of_get_option(opt_actionitem_url);


// checks for a 0, if 0 then puts mobile item In page, else puts it in mobile nav there is another function that puts this in the menu-responsive-nav file
  //if (of_get_option(opt_actionitem_mobile_location) !== '0') { $actionitem_location_mobile_css ="hide-for-small hide-for-medium"; } 

  if (!empty($actionitem_text) && of_get_option(opt_actionitem_mobile_location) == '0') {
    
    //echo "<ul id='actionitem-responsive' class='".$actionitem_location_mobile_css."'><li><a class='".$actionitem_location_css."' href='" . $actionitem_url . "'>" . $actionitem_text . "</a></li></ul>";
    echo "<ul id='actionitem-responsive'><li><a class='".$actionitem_location_css."' href='" . $actionitem_url . "'>" . $actionitem_text . "</a></li></ul>";
  }


// checks for mobile location, if turned on, then adds 'Main Menu'
  if (!empty($actionitem_text) && of_get_option(opt_actionitem_mobile_location) == '0') {echo '<ul class="flyout-title" id="flyout-mainmenu-title"> <li>Main Menu</li> </ul>'; }

//Main Flyout Menu
echo '  <ul>';
  //STANDARD DROPDOWN MENU
        wp_list_pages(array(
          'walker'   => new ufandshands_page_walker,
          'title_li' => '',
          'depth'    => 3
        ));
echo '  </ul>';




//Header Links in flyout
if (has_nav_menu("header_links")) { //detects if the header_links menu is being used 
        ?><ul class="flyout-title" id="flyout-headerlinks-title"> <li>Additional Links</li></ul> <?php
        echo '<ul class="hide-for-large" id="flyout-header-links">';
        wp_nav_menu(array("theme_location" => "header_links", "container" => false));
        echo'</ul>';
    } 



//Social Media Links
if (!$disabled_global_elements) :  
?> 
      <ul class="flyout-title" id="flyout-socialmedia-title"> <li>Connect with us</li></ul>
      <ul id="flyout-socialmedia" class="hide-for-large">
          <li><a href="<?php ufandshands_get_socialnetwork_url("facebook"); ?>" class="facebook ir">Facebook</a></li>
          <li><a href="<?php ufandshands_get_socialnetwork_url("youtube"); ?>" class="youtube ir">YouTube</a></li>
          <li><a href="<?php ufandshands_get_socialnetwork_url("twitter"); ?>" class="twitter ir">Twitter</a></li>
      </ul>
<?php
      // echo '<ul id="flyout-socialmedia" class="hide-for-large">';
      // echo '    <li><a href="'.ufandshands_get_socialnetwork_url("facebook").'" class="facebook ir">Facebook</a></li>';
      // echo '    <li><a href="'.ufandshands_get_socialnetwork_url("youtube").'" class="youtube ir">YouTube</a></li>';
      // echo '    <li><a href="'.ufandshands_get_socialnetwork_url("twitter").'" class="twitter ir">Twitter</a></li>';
      // echo '</ul>';
endif;

echo '</nav>';
?>