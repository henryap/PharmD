<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('home_left') ) : ?>
			<?php the_widget('UFCOM_recent_posts','title=Recent News&numberofposts=3&showdate=on&showexcerpt=on&showthumbnails=on', 'before_title=<h3>&after_title=</h3>&before_widget=<div class="widget-1 widget-first widget home_widget">'); ?> 
<?php endif; ?>