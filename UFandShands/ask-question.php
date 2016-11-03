<?php include("header.php"); ?>

	<?php ufandshands_breadcrumbs(); ?>
	
	<div id="content-wrap">
	  <div id="content-shadow">
		<div id="content" class="container">
		
		<?php $currenttemplate = get_post_meta($post->ID, '_wp_page_template', true); //members only template check ?>
				
		<?php if ( ($currenttemplate != "membersonly.php") || ( ($currenttemplate == "membersonly.php") && ufandshands_members_only() ) ) { //members only logic?>
		
			<?php get_sidebar(); //call in the sidebar and navigation ?>
      
      <?php
    
        $page_right_sidebar = ufandshands_sidebar_detector('page_right',false);
        
        $article_width = '';
     
        if((!empty($ufandshands_sidebar_nav) && $page_right_sidebar)) {
         $article_width = '12';
        } elseif ((!empty($ufandshands_sidebar_nav) && !$page_right_sidebar)) {
         $article_width = '17';
        } elseif ((empty($ufandshands_sidebar_nav) && $page_right_sidebar)) {
          $article_width = '17 box';
        } else {
          $article_width = '23 box';
        }
       
      ?>
      
			<article id="main-content" class="span-<?php echo $article_width; ?>" role="main">

<div id="qa-page-wrapper">

<?php the_qa_menu(); ?>

<div id="ask-question">

	<?php if (is_user_logged_in()) {
		the_question_form(); 
	} else {
		$current_url = get_original_url('siteurl');
		$current_url .= "/wp-login.php?redirect_to=";
		$current_url .= get_original_url('siteurl') . $_SERVER["REQUEST_URI"];
		echo "<p>&nbsp;</p><p><strong>Please <a href='".$current_url."'>login using your gatorlink information</a> before submitting a question</strong></p>";
	} ?>
	
</div>

</div><!--#qa-page-wrapper-->
			</article>
		
		<?php } ?>
	    </div>
	  </div>
	</div>
<?php include('user-role-menu.php'); ?>
<?php include("footer.php"); ?>