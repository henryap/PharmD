<?php include("header.php"); ?>
	
	<div id="content" class="visuallyhidden"></div> <!-- on homepage for accessibility -->

	 <?php // Set Featured Content vars 
	  
		$featured_content_category = of_get_option("opt_featured_category");
		$story_stacker = of_get_option("opt_story_stacker");
    	$cat_posts = get_categories("include=" . $featured_content_category);
		$cat_count = $cat_posts[0]->category_count; //Checks if any posts are assigned to the chosen Featured Content Category in Theme Options
    
		if( !empty($featured_content_category) && !($featured_content_category=="Choose a Category") && ($cat_count > 0)) {  
      
	?>
		<div id="feature-wrap" class="<?php if ($story_stacker) {echo "stacker";} ?> ribbon">
			<div id="feature" class="container" role="main1">
		  
			  <!-- Story Stacker / Slider -->
					<?php
					
					if ($story_stacker) {
						include('library/php/story-stacker.php');
					} else {
						include('library/php/feature-slider.php');
					}
					
					?>
			  <!-- end Story Stacker / Slider -->
			  
			</div><!-- end #feature -->
		</div><!-- end #feature-wrap --><!-- end ribbon-feature-wrap -->
	<?php } //endif for whether or not this is the 'choose a category' cat ?> 
	
	
	
  <?php include('user-role-menu.php'); ?>

  <?php include("apollo/custom_banner.php"); ?>

	<?php 
		
		// Secondary Widget Zone
		$disabled_secondary_widget_area = of_get_option("opt_disable_secondary_widget_area");
		
		if(!$disabled_secondary_widget_area) ufandshands_secondary_widget_area();

	?>
	
<?php include("footer.php"); ?>
