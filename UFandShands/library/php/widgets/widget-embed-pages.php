<?php

class UFCOM_embed_pages extends WP_Widget {
	function UFCOM_embed_pages() {
		$widget_ops = array('classname' => 'widget_ufcom_embed_pages', 'description' => 'Insert a page\'s content into your widget' );
		$this->WP_Widget('UFCOM_embed_pages', 'Insert Page Content', $widget_ops);
	}
 
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
 
		$unique_page_content = get_page_by_title($instance['unique_page_id']);
		global $wp_query;
		$current_page = $wp_query->post->ID;
 
		if ($current_page==$unique_page_content->ID || empty($instance['unique_page_id']) ) {
	
			echo $before_widget;
			echo "<div class='widget_embed_pages'>";

				$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
				$page_id = empty($instance['page_id']) ? '&nbsp;' : apply_filters('widget_page_id', $instance['page_id']);
			 
				if ( !empty( $title ) ) { echo $before_title . $showrssiconimage . $title . $after_title; };
						
				$page_content = get_page_by_title($instance['page_id']);
				$page_content = $page_content->post_content;
				$page_content = wpautop($page_content);
				$page_content = do_shortcode($page_content);
				echo $page_content;

			echo "</div>";
			echo $after_widget;

		}
	}
 
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['page_id'] = strip_tags($new_instance['page_id']);
 		$instance['unique_page_id'] = $new_instance['unique_page_id'];
		return $instance;
	}
 
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'page_id' => '' ) );
		$title = strip_tags($instance['title']);
		$page_id = $instance['page_id'];
		$unique_page_id = $instance['unique_page_id'];

?>

			<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>

		<p>
			<label for="<?php echo $this->get_field_id( 'page_id' ); ?>">Page:</label>
			<select id="<?php echo $this->get_field_id( 'page_id' ); ?>" name="<?php echo $this->get_field_name( 'page_id' ); ?>" class="widefat" style="width:100%;">
				<option value="">
				<?php echo attribute_escape(__('Select page')); ?></option> 
				 <?php 
				  $pages = get_pages(); 
				  foreach ($pages as $pagg) {
					$title = $pagg->post_title;
					$option = '<option ';
					if ($title == $instance['page_id']) {
						$option .= ' selected="selected" >';
					} else {
						$option .= ' >';
					}
					$option .= $pagg->post_title;
					$option .= '</option>';
					echo $option;
				  }
				 ?>
			</select>
		</p>

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
register_widget('UFCOM_embed_pages');

?>