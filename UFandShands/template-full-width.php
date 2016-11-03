<?php
/*
Template Name: Full Width Page (no sidebars or widgets)
*/
?>
  <?php include("header.php"); ?>

	<?php ufandshands_breadcrumbs(); ?>
	<?php include("apollo/custom_banner.php"); ?>

	<div id="content-wrap">
	  <div id="content-shadow">
		<div id="content" class="container">
		
      <?php
    
        $page_right_sidebar = ufandshands_sidebar_detector('page_right',false);
       
        $article_width = '23 box';
       
      ?>
      
			<article id="main-content" class="span-<?php echo $article_width; ?>" role="main">
        
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
				  <?php 
					  //ufandshands_content_title(); //page title 
					  ufandshands_tabSystem();
				   ?>
				  

						<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>
						<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
					
			  
				<?php endwhile; endif; //main article loop ends?>

		
			</article><!-- end #main-content --> 
      
	    </div>
	  </div>
	</div>

<?php include('user-role-menu.php'); ?>

<?php include("footer.php"); ?>