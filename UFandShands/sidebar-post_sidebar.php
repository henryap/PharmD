<div id="sidebar-post" class="span-7 alpha">
	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('post_sidebar') ) : ?>
				<?php the_widget('UFCOM_recent_posts','title=Recent News&numberofposts=3&showdate=on', array('before_title'=>'<h3 class="widgettitle">', 'after_title'=>'</h3>')); ?> 
				<?php the_widget('WP_Widget_Archives', 'title=News Archive&dropdown=1', array('before_widget'=>'<div class="widget archives_widget sidebar_widget">', 'before_title'=>'<h3 class="widgettitle">','after_title'=>'</h3>')); ?> 
	<?php endif; ?>
</div>