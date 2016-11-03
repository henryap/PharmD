<?php
/*
Template Name: Contact Page
*/
?>
<?php include("header.php"); ?>

	<?php ufandshands_breadcrumbs(); ?>
	
	<?php include("apollo/apply_custom_banner.php"); ?>
	
	
	<div id="content-wrap">
	  <div id="content-shadow">
		<div id="content" class="container">
		
		<?php $currenttemplate = get_post_meta($post->ID, '_wp_page_template', true); //members only template check ?>
				
		<?php if ( ($currenttemplate != "membersonly.php") || ( ($currenttemplate == "membersonly.php") && ufandshands_members_only() ) ) { //members only logic?>
		
			<?php //get_sidebar(); //call in the sidebar and navigation ?>
      
      <?php
	        
        $page_right_sidebar = ufandshands_sidebar_detector('page_right',false);
       
        $article_width = '';
     
        if(((!empty($ufandshands_sidebar_nav) || !empty($ufandshands_sidebar_widgets)) && $page_right_sidebar)) {
         $article_width = '12';
        } elseif (((!empty($ufandshands_sidebar_nav) || !empty($ufandshands_sidebar_widgets)) && !$page_right_sidebar)) {
         $article_width = '18';
        } elseif ((empty($ufandshands_sidebar_nav) && empty($ufandshands_sidebar_widgets) && $page_right_sidebar)) {
          $box_style = 'box';
          $article_width = '17';
        } else {
          $box_style = 'box';
          $article_width = '23';
        }
       
      ?>
	  <?php 
		/*previous template
		<article id="main-content" class="span-<?php// echo $article_width; ?>" role="main">
		<div class="<?php echo $box_style; ?>">
		*/
	   ?>	
		
		<article>
        <div class="main-content">

        <?php
        
        // Prev - Next Page Navigation (if enabled in Theme Options)
        
        $excluded = get_option('exclude_pages');
        
        $prev_next_nav = of_get_option("opt_prev_next_page_nav");
        
        if ($prev_next_nav) {
                
          if ($post->post_parent) {
            $ancestors = get_post_ancestors($post->ID);
            $root      = count($ancestors)-1;
            $parent    = $ancestors[$root];
          } else {
            $parent    = $post->ID;
          }

          $pagelist_args = array(
              'sort_column' => 'menu_order',
              //'exclude'    => $excluded,
          );
          $pagelist = get_pages($pagelist_args);
		  
          $pages = array();
          foreach ($pagelist as $page) {
            $pages[] += $page->ID;
          }
          
          $current = array_search($post->ID, $pages);
          $prevID = $pages[$current-1];
          $nextID = $pages[$current+1];

        }

        ?>

        <?php if ($prev_next_nav) { ?>
          <div class="next-prev-nav clearfix">
            <?php if (!empty($prevID)) { ?>
            <div class="alignleft"><a href="<?php echo get_permalink($prevID); ?>" title="<?php echo get_the_title($prevID); ?>">Previous</a></div>
            <?php }
            if (!empty($nextID)) { ?>
            <div class="alignright"><a href="<?php echo get_permalink($nextID); ?>" title="<?php echo get_the_title($nextID); ?>">Next</a></div>
            <?php } ?>
          </div>
        <?php } ?>
        
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				 
				 	<?php 
					 	//ufandshands_content_title(); 
					 	ufandshands_tabSystem();
					 	the_content('<p class="serif">Read the rest of this page &raquo;</p>');
					 	wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number'));
					?>
					
				<div class="single-meta">
				  <?php the_tags('<p class="tag black-50">Tagged as: ', ', ','</p>'); ?>
				</div>


				<?php endwhile; endif; //main article loop ends?>

        <?php if ($prev_next_nav) { ?>
          <div class="next-prev-nav clearfix">
            <?php if (!empty($prevID)) { ?>
            <div class="alignleft"><a href="<?php echo get_permalink($prevID); ?>" title="<?php echo get_the_title($prevID); ?>">Previous</a></div>
            <?php }
            if (!empty($nextID)) { ?>
            <div class="alignright"><a href="<?php echo get_permalink($nextID); ?>" title="<?php echo get_the_title($nextID); ?>">Next</a></div>
            <?php } ?>
          </div>
        <?php } ?>
			</div><!-- end box -->
			</article><!-- end #main-content --> 
      
      <?php //page right sidebar

		//dubrod theme additions  
		echo "<div class='main-sidebar'>";
		echo "<h2>At a Glance</h2><div class='sidebar-box'>";
		ufandshands_glancemenu();
		ufandshands_glancebox1();
		ufandshands_glancebox2();
		ufandshands_appProcedure();
		echo "</div>";
		
		//event list
		ufandshands_eventsWidget();
		
		//eof dubrod theme additions
					  
        global $ufandshands_sidebar_widgets;
        $ufandshands_sidebar_widgets = ufandshands_sidebar_detector('page_sidebar', false);
		if (!empty($ufandshands_sidebar_widgets) || !empty($page_right_sidebar)) {
        //echo "<div id='sidebar-right' class='span-6 alpha omega'>";
        
        //responsive repeat of left sidebar widgets
        if(!empty($ufandshands_sidebar_widgets)) {
          echo "<aside id='left-sidebar-widgets-responsive' class='span-6 omega hide-for-large'>";
          echo $ufandshands_sidebar_widgets;
          echo "</aside><!-- end #left-sidebar-widgets-responsive -->";
          }
				// normal page right sidebar
        if ($page_right_sidebar) {
					//echo "<aside id='local-sidebar' class='span-6 omega'>";
					echo $page_right_sidebar;
					//echo "</aside><!-- end #local-sidebar -->";
				  }

        //echo "</div>"; //end #sidebar-right
      }
      
      //dubrod theme additions
      echo "</div><!-- eof main-sidebar -->";
	  //eof dubrod theme additions
	  
	?>

			
		<?php } else { //end members only check ?>

				<!-- Non-Members -->
				<article name ="content" id="main-content" class="span-23 box" role="main">
					<p>This content can only be seen by users inside the UF/Shands network. Please use one of the following VPN services or <a href="/wp-admin">login as a user of this website</a></p>
					
					<ul>
						<li><a href="http://net-services.ufl.edu/provided_services/vpn/anyconnect/">UF VPN</a></li>
						<li><a href="https://security.health.ufl.edu/vpn/">UF HSC VPN</a></li>
						<li><a href="https://vpn.shands.org/">Shands HealthCare VPN</a></li>
					</ul>
				</article>
		
		<?php } ?>
	    </div>
	  </div>
	</div>
<?php include('user-role-menu.php'); ?>
<?php include("apollo/custom_footer.php"); ?>
<?php include("footer.php"); ?>
