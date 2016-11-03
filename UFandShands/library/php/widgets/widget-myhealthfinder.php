<?php

class UFCOM_myhealthfinder extends WP_Widget {
	function UFCOM_myhealthfinder() {
		$widget_ops = array('classname' => 'widget_ufcom_myhealthfinder', 'description' => 'The myhealthfinder widget provides personalized recommendations from the U.S. Preventive Services Taskforce based on age and sex. ' );
		$this->WP_Widget('UFCOM_myhealthfinder', 'HHS.Gov MyHealthFinder Widget', $widget_ops);
	}
 
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
 
		$unique_page_content = get_page_by_title($instance['unique_page_id']);
		global $wp_query;
		$current_page = $wp_query->post->ID;
 
		if ($current_page==$unique_page_content->ID || empty($instance['unique_page_id']) ) {
		
			echo $before_widget;
			$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
			if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
				
			echo "<div style='width:180px; margin-left:auto; margin-right:auto;' id='widget_myhealthfinder' ><script type='text/javascript' src='http://healthfinder.gov/widgets/myhealthfinder/content.aspx'></script><noscript><iframe src='http://healthfinder.gov/widgets/myhealthfinder/iframecontent.html' name='myhealthfinderframe' frameborder='0' id='Iframe1' scrolling='no' height='250' width='178' marginheight='0' title='myhealthfinder widget' marginwidth='0'></iframe></noscript></div>";
				
			echo $after_widget;
		}
		
	}
 
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['unique_page_id'] = $new_instance['unique_page_id'];
		return $instance;
	}
 
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'unique_page_id' => '' ) );
		$title = strip_tags($instance['title']);
		$unique_page_id = $instance['unique_page_id'];
		
?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>

			<p>
				<label for="<?php echo $this->get_field_id( 'unique_page_id' ); ?>">Display only on page:</label>
				<select id="<?php echo $this->get_field_id( 'unique_page_id' ); ?>" name="<?php echo $this->get_field_name( 'unique_page_id' ); ?>" class="widefat" style="width:100%;">
					<option value="">
					<?php echo attribute_escape(__('All pages')); ?></option> 
					<?php 
					$pages = get_pages(); 
					foreach ($pages as $pagg) {
						$title = $pagg->post_title;
						$option = '<option ';
						$option .= 'value="'.htmlspecialchars($title).'" ';
						if ($title == $instance['unique_page_id']) {
							$option .= ' selected="selected" >';
						} else {
							$option .= ' >';
						}
						$option .= $title;
						$option .= '</option>';
						echo $option;
				 	 }
				 	?>
				</select>
			</p>
<?php
	}
}
register_widget('UFCOM_myhealthfinder');

?>