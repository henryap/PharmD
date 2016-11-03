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

<div id="single-question">
	<h1><?php the_title(); ?></h1>
	<span id="qa-lastaction"><?php _e( 'Asked', QA_TEXTDOMAIN ); ?> <?php the_qa_time( get_the_ID() ); ?></span>
	<div id="single-question-container">
		<?php the_question_voting(); ?>
		<div id="question-body">
			<div id="question-content"><?php the_content(); ?></div>
			<?php the_question_category(  __( 'Category:', QA_TEXTDOMAIN ) . ' <span class="question-category">', '', '</span>' ); ?>
			<?php the_question_tags( __( 'Tags:', QA_TEXTDOMAIN ) . ' <span class="question-tags">', ' ', '</span>' ); ?>
			

			<?php the_qa_action_links( get_the_ID() ); ?>
			<?php the_qa_author_box( get_the_ID() ); ?>
			
		</div>
	</div>
</div>

<?php if ( is_question_answered() ) { ?>
<div id="answer-list">
	<h2><?php the_answer_count(); ?></h2>
	<?php the_answer_list(); ?>
</div>
<?php } ?>

<div id="edit-answer">
	<h2><?php _e( 'Your Answer', QA_TEXTDOMAIN ); ?></h2>
	
	
	<?php if (is_user_logged_in()) {
		the_answer_form(); 
	} else {
		$current_url = get_original_url('siteurl');
		$current_url .= "/wp-login.php?redirect_to=";
		$current_url .= get_original_url('siteurl') . $_SERVER["REQUEST_URI"];
		echo "<p><strong>Please <a href='".$current_url."'>login using your gatorlink information</a> before submitting a question</strong></p>";
	} ?>
	
</div>
<p><?php the_question_subscription(); ?></p>

</div><!--#qa-page-wrapper-->

			</article>
		
		<?php } ?>
	    </div>
	  </div>
	</div>
<?php include('user-role-menu.php'); ?>
<?php include("footer.php"); ?>
