<?php

/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 * 
 */

function optionsframework_option_name() {

	// This gets the theme name from the stylesheet (lowercase and without spaces)
	$themename = get_theme_data(STYLESHEETPATH . '/style.css');
	$themename = $themename['Name'];
	$themename = preg_replace("/\W/", "", strtolower($themename) );
	
	$optionsframework_settings = get_option('optionsframework');
	$optionsframework_settings['id'] = $themename;
	update_option('optionsframework', $optionsframework_settings);
	
	// echo $themename;
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 *  
 */
function optionsframework_options() {
	
	$parent_colleges_institutes = array(
				"UF Academic Health Center" => "UF Academic Health Center",
				"Shands HealthCare" => "Shands HealthCare",
	            "College of Dentistry" => "College of Dentistry",
	            "College of Medicine" => "College of Medicine",
	            "College of Nursing" => "College of Nursing",
	            "College of Pharmacy" => "College of Pharmacy",
	            "College of Public Health and Health Professions" => "College of Public Health and Health Professions",
	            "College of Veterinary Medicine" => "College of Veterinary Medicine",
	            "McKnight Brain Institute" => "McKnight Brain Institute",
	            "Genetics Institute" => "Genetics Institute",
	            "Institute on Aging" => "Institute on Aging",
	            "UF and Shands Cancer Center" => "UF and Shands Cancer Center",
                "UF Libraries" => "UF Libraries",
	            "Emerging Pathogens Institute" => "Emerging Pathogens Institute",
	            "Clinical and Translational Science Institute" => "Clinical and Translational Science Institute",
	            "None" => "None");
	
	// Multicheck Array
	$multicheck_array = array("one" => "French Toast", "two" => "Pancake", "three" => "Omelette", "four" => "Crepe", "five" => "Waffle");
	
	// Multicheck Defaults
	$multicheck_defaults = array("one" => "1", "five" => "1");
	
	// Background Defaults
	$background_defaults = array('color' => '', 'image' => '', 'repeat' => 'repeat','position' => 'top center','attachment'=>'scroll');
	
  // Pull all the categories into an array
	$options_categories = array("Choose a Category" => "Choose a Category");  
	$options_categories_obj = get_categories(array('hide_empty' => 0));
	foreach ($options_categories_obj as $category) {
    	$options_categories[$category->cat_ID] = $category->cat_name;
	}
        
    // Pull all the categories into an array for author information
	$options_author_categories = array("All Categories" => "All Categories");  
	$options_author_categories_obj = get_categories(array('hide_empty' => 0));
	foreach ($options_author_categories_obj as $author_category) {
    	$options_author_categories[$author_category->cat_ID] = $author_category->cat_name;
	}
	
	// Pull all the pages into an array
	$options_pages = array();
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
    	$options_pages[$page->ID] = $page->post_title;
	}
		
	// If using image radio buttons, define a directory path
	$imagepath =  get_bloginfo('stylesheet_directory') . '/library/images/';
		
	$options = array();
		
    /**
     * ===================================================================
     * General
     * ===================================================================
     */

    $options[] = array( "name" => "General",
        "type" => "heading");
            
    $options[] = array( "name" => "Parent College / Institute",
        "desc" => "Select your parent organization.",
        "id" => "opt_parent_colleges_institutes",
        "std" => "one",
        "type" => "select",
        "options" => $parent_colleges_institutes);

    $options[] = array( "name" => "Site Description",
        "desc" => "One or two sentence description of your site. Indexing sites such as Google and Bing display this information in their search result excerpts for your site's home page.",
        "id" => "opt_site_description",
        "rows" => '2',
        "type" => "textarea");
                   
    $options[] = array( "name" => "Google Analytics Account Number",
        "desc" => "Enter your account number for Google Analytics (e.g., 'UA-xxxxxxx-x' or 'UA-xxxxxxx-xx' )",
        "id" => "opt_analytics_acct",
        "std" => "",
        "type" => "text");
		  
    $options[] = array( "name" => "Enable Mega Drop Down Menu",
        "desc" => "Enable mega drop down menus for your main menu",
        "id" => "opt_mega_menu",
        "std" => "0",
        "type" => "checkbox");
    
    $options[] = array( "name" => "Collapse Sidebar Navigation",
        "desc" => "Useful for larger sites - keeps the sidebar navigation a manageable height",
        "id" => "opt_collapse_sidebar_nav",
        "std" => "0",
        "type" => "checkbox");

    $options[] = array( "name" => "Enable Previous and Next Page Buttons",
        "desc" => "Useful for sites that rely on linear content progression (e.g. textbooks, manuals, etc)",
        "id" => "opt_prev_next_page_nav",
        "std" => "0",
        "type" => "checkbox");
    
    $options[] = array( "name" => "Enable Author Information",
        "desc" => "Display byline and 'About the Author' section below posts, populated from user profile fields and Gravatar photos",
        "id" => "opt_about_author",
        "std" => "0",
        "type" => "checkbox");
    
    $options[] = array( "name" => "",
        "desc" => "Choose the post category to display author information. To show on all posts, simply set this dropdown to 'All Categories'",
        "id" => "opt_about_author_category",
        "type" => "select",
        "std" => array("All Categories" => "All Categories"),
        "options" => $options_author_categories);
    
	$options = apply_filters('ufhealth_theme_options_general', $options);
	
    /**
     * ===================================================================
     * Site Search
     * ===================================================================
     */
    
        $options[] = array( "name" => "Site Search",
	    "type" => "heading");
    
	$options[] = array( "name" => "Use Google to Search My Website",
	    "desc" => "Replace the normal WordPress search engine with Google. This will result in much better search results for your website. Website must be public (viewable by the world).",
	    "id" => "opt_google_site_search",
	    "std" => "0",
	    "type" => "checkbox");
	
	if (function_exists('domain_mapping_siteurl')) 
		$domain = domain_mapping_siteurl(null);
	else
		$domain = get_bloginfo('url');
	
	$options[] = array( "name" => "Multi-site Search",
        "desc" => "Enables your search box to search through multiple websites. Enter all of the websites your search results should include. Enter one URL per line and don't forget to include your own website. E.G.,<br /><br />".str_replace('https://', 'http://', $domain)."<br />https://UFandShands.org<br />http://webservices.ahc.ufl.edu<br /><br />*Requires that your website is using Google for it's site search, above",
	"std" => str_replace('https://', 'http://', $domain),
        "id" => "opt_sites_to_search",
        "rows" => '4',
        "type" => "textarea");

    $options = apply_filters('ufhealth_theme_options_site_search', $options);
	
    /**
     * ===================================================================
     * Site Title
     * ===================================================================
     */
            
    $options[] = array( "name" => "Site Title",
        "type" => "heading"); 

    $options[] = array( "name" => "Title Font Size",
        "desc" => "Enter a number that corresponds to the size of the font you would like for the title of your site (Default 2.6).",
        "id" => "opt_title_size",
        "class" => "mini",
        "std" => "",
        "type" => "text");

    $options[] = array( "name" => "Title Font Size on Mobile Devices",
        "desc" => "Enter a number that corresponds to the size of the font you would like for the title of your site when viewed on a mobile device. (Default 2.3).",
        "id" => "opt__mobile_title_size",
        "class" => "mini",
        "std" => "",
        "type" => "text");
    
    $options[] = array( "name" => "Title Padding",
        "desc" => "Enter the amount of padding the title should have (Default 6).",
        "id" => "opt_title_pad",
        "class" => "mini",
        "std" => "",
        "type" => "text");    
          
    $options[] = array( "name" => "Tagline Font Size",
        "desc" => "Enter a number that corresponds to the size of the font you would like for the tagline of your site (Default values 1.4).",
        "id" => "opt_tagline_size",
        "class" => "mini",
        "std" => "",
        "type" => "text");
    
	$options = apply_filters('ufhealth_theme_site_title', $options);
    /**
     * ===================================================================
     * Header Call to Action
     * ===================================================================
     */                 

    $options[] = array( "name" => "Header Call to Action",
        "type" => "heading");
		  
    $options[] = array( "name" => "Call to Action Text",
        "desc" => "The Call to Action text is the orange box above your main menu. Enter what you would like it to say here. Leave it blank to remove it.",
        "id" => "opt_actionitem_text",
        "std" => "",
        "type" => "text");

    $options[] = array( "name" => "Call to Action URL",
        "desc" => "Where visitors are taken when they click on your Header Action Item",
        "id" => "opt_actionitem_url",
        "std" => "",
        "type" => "url");
	
	$options = apply_filters('ufhealth_theme_options_header', $options);
    // Responsive call to Action                
    $options[] = array( "name" => "Header Action Item Mobile Location",
        "desc" => "Make your Header Action item appear on the page instead of in the off-canvas navigation menu when viewed on a mobile device.",
        "id" => "opt_actionitem_mobile_location",
        "std" => "0",
        "type" => "checkbox");
    /**
     * ===================================================================
     * Homepage Layout
     * ===================================================================
     */        

	  $options[] = array( "name" => "Homepage Layout",
        "type" => "heading");
   
    $options[] = array( "name" => "Homepage layout for widgets",
        "desc" => "Select which layout you want to use for your widgets on the homepage",
        "id" => "opt_homepage_layout",
        "std" => "3c-default",
        "type" => "images",
        "options" => array(
           '3c-default' => $imagepath . '3c-default.png',
           '3c-thirds' => $imagepath . '3c-thirds.png',
           '2c-bias' => $imagepath . '2c-bias.png',
           '2c-half' => $imagepath . '2c-half.png',
           '1c-100' => $imagepath . '1c-100.png')
        );
   
    $options[] = array( "name" => "Color Scheme (white background)",
        "desc" => "Use a white background for the homepage widget zone",
        "id" => "opt_homepage_layout_color",
        "std" => "0",
        "type" => "checkbox");
	
	$options = apply_filters('ufhealth_theme_options_homepage', $options);
        
    /**
     * ===================================================================
     * Featured Content
     * ===================================================================
     */           
    
    $options[] = array( "name" => "Featured Content",
       "type" => "heading");
       
    $options[] = array( "name" => "Select a Category",
        "desc" => "Choose a category from which featured posts are drawn. To remove the featured content area, simply set this dropdown to 'Choose a Category'",
        "id" => "opt_featured_category",
        "type" => "select",
        "std" => array("Choose a Category" => "Choose a Category"),
        "options" => $options_categories);
        
    $options[] = array( "name" => "Number of posts to display in slider",
        "desc" => "How many posts do you want to display in your slider (Story Stacker is fixed at 3)",
        "id" => "opt_number_of_posts_to_show",
        "std" => "3",
  		  "type" => "select",
  		  "class" => "mini",
  		  "options" => array("1" => "1", "2" => "2","3" => "3","4" => "4","5" => "5","6" => "6",));

    $options[] = array( "name" => "Slider/Stacker Speed",
        "desc" => "Set the slider/stacker transition speed in seconds (Default 5)",
        "id" => "opt_slider_speed",
        "class" => "mini",
        "std" => "5",
        "type" => "text");
	  
	  $options[] = array( "name" => "Disable Timeline Scrubber",
        "desc" => "Disable the long bar with dots underneath the images",
        "id" => "opt_featured_content_disable_timeline",
        "std" => "0",
        "type" => "checkbox");
		  
    $options[] = array( "name" => "Story Stacker",
        "desc" => "Check to enable the Featured Content Story Stacker for your home page (recommended image size for Story Stacker is <strong>630 x 305</strong>).",
        "id" => "opt_story_stacker",
        "std" => "0",
        "type" => "checkbox");
		 
    $options[] = array( "name" => "Story Stacker - Disable Dates",
        "desc" => "Disable dates from appearing underneath your post titles",
        "id" => "opt_story_stacker_disable_dates",
        "std" => "0",
        "type" => "checkbox");
	
	// $options[] = array( "name" => "Disable Full-Width Slides on mobile devices.",
 //        "desc" => "Disables Full Width Slides with only images and no HTML based text from displaying on mobile devices.",
 //        "id" => "opt_disable_fullwidthslides",
 //        "std" => "",
 //        "type" => "checkbox");

	$options = apply_filters('ufhealth_theme_options_featured_content', $options);



    /**
     * ===================================================================
     * Super Admin
     * ===================================================================
     */        
   
    $options[] = array( "name" => "Super Admin",
			  "super-admin-only" => "1",
			  "type" => "heading");
   
    $options[] = array( "name" => "Facebook",
		    "super-admin-only" => "1",
        "desc" => "Enter the url of your organization's Facebook page (e.g. http://facebook.com/uflorida)",
        "id" => "opt_facebook_url",
        "std" => "",
        "type" => "text");
            
    $options[] = array( "name" => "Twitter",
			  "super-admin-only" => "1",
        "desc" => "Enter the url of your organization's Twitter page (e.g. http://www.twitter.com/uflorida)",
        "id" => "opt_twitter_url",
        "std" => "",
        "type" => "text");
   
    $options[] = array( "name" => "Youtube",
			  "super-admin-only" => "1",
        "desc" => "Enter the url of your organization's Youtube page (e.g. http://www.youtube.com/universityofflorida)",
        "id" => "opt_youtube_url",
        "std" => "",
        "type" => "text");
		  
    $options[] = array( "name" => "Facebook Insights ID",
			  "super-admin-only" => "1",
        "desc" => "Enter the unique number ID for fb:admins, e.g., <meta property=\"fb:admins\" content=\"1138099648\", would be \"1138099648\" />",
        "id" => "opt_facebook_insights",
        "std" => "",
        "type" => "text");
  
    $options[] = array( "name" => "Custom Title",
     		"super-admin-only" => "1",
       	"desc" => "For use by Communications only.",
      	"id" => "opt_alternative_site_title",
        "rows" => '1',
      	"type" => "textarea");
  
	$options[] = array( "name" => "Custom Logo",
	    "super-admin-only" => "1",
      	"desc" => "For use by Communications only.",
      	"id" => "opt_alternative_site_logo",
      	"type" => "upload");		  
		
    $options[] = array( "name" => "Custom Logo Height",
			  "super-admin-only" => "1",
        "desc" => "For use by Communications only.",
        "id" => "opt_alternative_site_logo_height",
        "class" => "mini",
        "std" => "",
        "type" => "text");  
		  
    $options[] = array( "name" => "Custom Logo Width",
			  "super-admin-only" => "1",
        "desc" => "For use by Communications only.",
        "id" => "opt_alternative_site_logo_width",
        "class" => "mini",
        "std" => "",
        "type" => "text");
        
    $options[] = array( "name" => "Banner Logo 1",
	    "super-admin-only" => "1",
      	"desc" => "Top Logo on big banner",
      	"id" => "opt_banner_logo_1",
      	"type" => "upload");
      	
    $options[] = array( "name" => "Banner Logo 2",
	    "super-admin-only" => "1",
      	"desc" => "Second Logo on big banner",
      	"id" => "opt_banner_logo_2",
      	"type" => "upload");  	    

    $options[] = array( "name" => "Custom CSS",
        "super-admin-only" => "1",
        "desc" => "For use by Communications only.",
        "id" => "opt_custom_css",
        "std" => "",
        "type" => "textarea");
	
	$options[] = array( "name" => "Custom Javascript",
	"super-admin-only" => "1",
	"desc" => "Do not include open and closing &lt;script&gt; tags.",
	"id" => "opt_custom_js",
	"std" => "",
	"type" => "textarea");
	
	$options[] = array( "name" => "Google Survey Source",
	"super-admin-only" => "1",
	"desc" => "",
	"id" => "opt_google_survey_src",
	"std" => "",
	"type" => "text");
    
    $options[] = array( "name" => "Custom 'All Posts' Page Title",
        "super-admin-only" => "1",
        "desc" => "If blank, defaults to 'All Posts'",
        "id" => "opt_custom_posts_title",
        "type" => "text");
    
    $options[] = array( "name" => "Custom 'All Posts' Page URL Path",
        "super-admin-only" => "1",
        "desc" => "If blank, defaults to 'posts'",
        "id" => "opt_custom_posts_path",
        "type" => "text");

    $options[] = array( "name" => "Disable Secondary Widget Zone",
        "super-admin-only" => "1",
        "desc" => "",
        "id" => "opt_disable_secondary_widget_area",
        "std" => "0",
        "type" => "checkbox");
        
    $options[] = array( "name" => "Disable Global Elements",
        "super-admin-only" => "1",
        "desc" => "Disable the global header, footer, and social media icons from appearing",
        "id" => "opt_disable_global_elements",
        "std" => "0",
        "type" => "checkbox");
    
	$options[] = array( "name" => "Use Custom Google Search Engine",
	    "desc" => "",
	    "id" => "opt_google_cse",
	    "std" => "0",
	    "type" => "checkbox");
	
	$options[] = array( "name" => "Google CSE ID",
        "desc" => "",
        "id" => "opt_google_cse_id",
        "type" => "text");
		
	$options[] = array( "name" => "LiveChat License Number (livechatinc.com)",
        "desc" => "",
        "id" => "opt_livechatinc_id",
        "type" => "text");
    
	
	$options = apply_filters('ufhealth_theme_options_super_admin', $options);

    /**
     * ===================================================================
     * Sharepoint Calendar
     * ===================================================================
     */        
  
    $options[] = array( "name" => "Sharepoint Calendar",
        "type" => "heading");

    $options[] = array( "name" => "Sharepoint Calendar URL",
        "desc" => "The default Sharepoint calendar url.<br /><br />IMPORTANT:  Before changing this field, please visit <a href='http://webservices.ahc.ufl.edu/help-support/how-to/sharepoint-calendar/'>http://webservices.ahc.ufl.edu/help-support/how-to/sharepoint-calendar/</a> first, or it may not work!",
        "id" => "opt_sharepoint_url",
        "std" => "",
        "type" => "url"); 

	$options = apply_filters('ufhealth_theme_options_sharepoint_calendar', $options);
	
    /**
     * ===================================================================
     * Footer Contact Info
     * ===================================================================
     */                 

    $options[] = array( "name" => "Footer",
        "type" => "heading");
      
    $options[] = array( "name" => "Contact Info",
        "id" => "opt_contact_info",
        "desc" => "(e.g. Unit Name, Address, Phone/Fax, etc.)",
        "std" => "",
        "type" => "text");
        
    $options[] = array( "name" => "Intranet URL",
        "desc" => "Enter the URL to your unit's intranet. This will place a link at the bottom of the footer titled 'Intranet'",
        "id" => "opt_intranet_url",
        "std" => "",
        "type" => "text");

    $options[] = array( "name" => "Make a Gift URL",
        "desc" => "Enter the URL to your unit's specific fund/giving page at the UF Foundation. Find available online funds at the <a href='https://www.uff.ufl.edu/OnlineGiving/Advanced.asp'>UF Foundation</a>",
        "id" => "opt_makeagift_url",
        "std" => "",
        "type" => "text");

    /**
     * ===================================================================
     * Mobile Options
     * ===================================================================
     */    


    $options[] = array( "name" => "Mobile Options", "type" => "heading");
	
    $options[] = array( "name" => "Disable responsive images on this site.",
        "desc" => "Disable image scaling in posts and pages to better fit mobile devices.",
        "id" => "opt_responsive_images",
        "std" => "",
        "type" => "checkbox");



    $options = apply_filters('ufhealth_theme_options_footer', $options);


// problem with adding duplicates of options is that they both have to be unchecked before saving to make the save hold.

    // $options[] = array( "name" => "Header Action Item Mobile Location",
    //     "desc" => "Make your Header Action item appear on the page instead of in the off-canvas navigation menu when viewed on a mobile device.",
    //     "id" => "opt_actionitem_mobile_location",
    //     "std" => "0",
    //     "type" => "checkbox");





   // $options[] = array( "name" => "Input Radio (one)",
  //          "desc" => "Radio select with default options 'one'.",
  //          "id" => "example_radio",
  //          "std" => "one",
  //          "type" => "radio",
  
  //          "options" => $test_array);





  // Examples for Reference             
  //                         
  // $options[] = array( "name" => "Basic Settings",
  //          "type" => "heading");
  //            
  // $options[] = array( "name" => "Input Text Mini",
  //          "desc" => "A mini text input field.",
  //          "id" => "example_text_mini",
  //          "std" => "Default",
  //          "class" => "mini",
  //          "type" => "text");
  //              
  // $options[] = array( "name" => "Input Text",
  //          "desc" => "A text input field.",
  //          "id" => "example_text",
  //          "std" => "Default Value",
  //          "type" => "text");
  //            
  // $options[] = array( "name" => "Textarea",
  //          "desc" => "Textarea description.",
  //          "id" => "example_textarea",
  //          "std" => "Default Text",
  //          "type" => "textarea"); 
  //          
  // $options[] = array( "name" => "Input Select Small",
  //          "desc" => "Small Select Box.",
  //          "id" => "example_select",
  //          "std" => "three",
  //          "type" => "select",
  //          "class" => "mini", //mini, tiny, small
  //          "options" => $test_array);       
  //          
  // $options[] = array( "name" => "Input Select Wide",
  //          "desc" => "A wider select box.",
  //          "id" => "example_select_wide",
  //          "std" => "two",
  //          "type" => "select",
  //          "options" => $test_array);
  //          
  // $options[] = array( "name" => "Select a Category",
  //          "desc" => "Passed an array of categories with cat_ID and cat_name",
  //          "id" => "example_select_categories",
  //          "type" => "select",
  //          "options" => $options_categories);
  //          
  // $options[] = array( "name" => "Select a Page",
  //          "desc" => "Passed an pages with ID and post_title",
  //          "id" => "example_select_pages",
  //          "type" => "select",
  //          "options" => $options_pages);
  //          
  // $options[] = array( "name" => "Input Radio (one)",
  //          "desc" => "Radio select with default options 'one'.",
  //          "id" => "example_radio",
  //          "std" => "one",
  //          "type" => "radio",
  //          "options" => $test_array);
  //            
  // $options[] = array( "name" => "Example Info",
  //          "desc" => "This is just some example information you can put in the panel.",
  //          "type" => "info");
  //                    
  // $options[] = array( "name" => "Input Checkbox",
  //          "desc" => "Example checkbox, defaults to true.",
  //          "id" => "example_checkbox",
  //          "std" => "0",
  //          "type" => "checkbox");
  //          
  // $options[] = array( "name" => "Advanced Settings",
  //          "type" => "heading");
  //          
  // $options[] = array( "name" => "Check to Show a Hidden Text Input",
  //          "desc" => "Click here and see what happens.",
  //          "id" => "example_showhidden",
  //          "type" => "checkbox");
  // 
  // $options[] = array( "name" => "Hidden Text Input",
  //          "desc" => "This option is hidden unless activated by a checkbox click.",
  //          "id" => "example_text_hidden",
  //          "std" => "Hello",
  //          "class" => "hidden",
  //          "type" => "text");
  //          
  // $options[] = array( "name" => "Uploader Test",
  //          "desc" => "This creates a full size uploader that previews the image.",
  //          "id" => "example_uploader",
  //          "type" => "upload");
  //          
  // $options[] = array( "name" => "Example Image Selector",
  //          "desc" => "Images for layout.",
  //          "id" => "example_images",
  //          "std" => "2c-l-fixed",
  //          "type" => "images",
  //          "options" => array(
  //            '1col-fixed' => $imagepath . '1col.png',
  //            '2c-r-fixed' => $imagepath . '2cr.png',
  //            '2c-l-fixed' => $imagepath . '2cl.png',
  //            '3c-fixed' => $imagepath . '3cm.png',
  //            '3c-r-fixed' => $imagepath . '3cr.png')
  //          );
  //          
  // $options[] = array( "name" =>  "Example Background",
  //          "desc" => "Change the background CSS.",
  //          "id" => "example_background",
  //          "std" => $background_defaults, 
  //          "type" => "background");
  //              
  // $options[] = array( "name" => "Multicheck",
  //          "desc" => "Multicheck description.",
  //          "id" => "example_multicheck",
  //          "std" => $multicheck_defaults, // These items get checked by default
  //          "type" => "multicheck",
  //          "options" => $multicheck_array);
  //            
  // $options[] = array( "name" => "Colorpicker",
  //          "desc" => "No color selected by default.",
  //          "id" => "example_colorpicker",
  //          "std" => "",
  //          "type" => "color");
  //          
  // $options[] = array( "name" => "Typography",
  //          "desc" => "Example typography.",
  //          "id" => "example_typography",
  //          "std" => array('size' => '12px','face' => 'verdana','style' => 'bold italic','color' => '#123456'),
  //          "type" => "typography");      
	return $options;
}
