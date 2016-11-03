<?php

class UFCOM_image extends WP_Widget {
	function UFCOM_image() {
		$widget_ops = array('classname' => 'widget_ufcom_image', 'description' => 'Insert an image' );
		$this->WP_Widget('UFCOM_image', 'Image', $widget_ops);
	}
 
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);

		$unique_page_content = get_page_by_title($instance['unique_page_id']);
		global $wp_query;
		$current_page = $wp_query->post->ID;
	 
		if ($current_page==$unique_page_content->ID || empty($instance['unique_page_id']) ) {
 
			echo $before_widget;
			
			$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
			$imgurl = empty($instance['imgurl']) ? '&nbsp;' : apply_filters('widget_imgurl', $instance['imgurl']);
			$imglink = empty($instance['imglink']) ? '' : apply_filters('widget_imglink', $instance['imglink']);
			$noeffects = isset( $instance['noeffects'] ) ? $instance['noeffects'] : false;
			$imgclass = "ufhealth-image-widget";
			if ($noeffects){ $imgclass = 'noeffects'; }

			if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
			
				if($imglink){ echo "<a href=\"".$imglink."\" >"; }
				echo "<img class='".$imgclass."' style=\"margin-left:auto;margin-right:auto;display:block;\" src=\"".$imgurl."\" alt=\"$title\" />";
				if($imglink){ echo "</a>"; }
				
			echo $after_widget;
		}
	}
 
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['imgurl'] = strip_tags($new_instance['imgurl']);
		$instance['imglink'] = strip_tags($new_instance['imglink']);
		$instance['unique_page_id'] = $new_instance['unique_page_id'];
		$instance['noeffects'] = $new_instance['noeffects'];
		return $instance;
	}
 
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'imgurl' => '', 'imglink' => '', 'unique_page_id' => '' ) );
		$title = strip_tags($instance['title']);
		$imgurl = strip_tags($instance['imgurl']);
		$imglink = strip_tags($instance['imglink']);

		$unique_page_id = $instance['unique_page_id'];
?>

			<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>

			<p><label for="<?php echo $this->get_field_id('imgurl'); ?>">Location (URL) of Image: <input class="widefat" id="<?php echo $this->get_field_id('imgurl'); ?>" name="<?php echo $this->get_field_name('imgurl'); ?>" type="text" value="<?php echo attribute_escape($imgurl); ?>" /></label></p>

			<p><label for="<?php echo $this->get_field_id('imglink'); ?>">Link (URL) when you click: <input class="widefat" id="<?php echo $this->get_field_id('imglink'); ?>" name="<?php echo $this->get_field_name('imglink'); ?>" type="text" value="<?php echo attribute_escape($imglink); ?>" /></label></p>

			<p><input class="checkbox" type="checkbox" <?php checked( $instance['noeffects'], 'on' ); ?> id="<?php echo $this->get_field_id( 'noeffects' ); ?>" name="<?php echo $this->get_field_name( 'noeffects' ); ?>" /> &nbsp; <label for="<?php echo $this->get_field_id( 'noeffects' ); ?>">Disable background and border?</label></p>
			
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


<?php	}
}
register_widget('UFCOM_image');
?>