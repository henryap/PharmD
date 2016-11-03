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

<?php the_post(); ?>

<div id="answer-form">
	<h2><?php _e( 'Answer for ', QA_TEXTDOMAIN ); the_question_link( $post->post_parent ); ?></h2>
	<?php the_answer_form(); ?>
</div>

</div><!--#qa-page-wrapper-->

			</article>
		
		<?php } ?>
	    </div>
	  </div>
	</div>
<?php include('user-role-menu.php'); ?>
<?php include("footer.php"); ?>
