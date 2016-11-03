<?php
class ufandshands_widget_video extends WP_Widget {
	
	static function get_preview_ajax() {
		extract($_POST);
		
		if (!empty($url))
			$embed_code = wp_oembed_get($url, array('width'=>'220'));
		
		echo $embed_code;
		die();
	}
	
	function ufandshands_widget_video() {
		$widget_ops = array('classname' => 'ufandshands_widget_video', 'description' => __('Video'));
		$this->WP_Widget('ufandshands_widget_video', __('Video'), $widget_ops);
		
		add_action('wp_ajax_widget_video_preview', array('ufandshands_widget_video', 'get_preview_ajax'));
	}
	
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		$url = empty($instance['url']) ? '' : $instance['url'];
		$width = empty($instance['width']) ? '300' : $instance['width'];
		
		$embed_code = wp_oembed_get($url, array('width'=>$width));
		echo $before_widget;
		echo '<h3>' . $title . '</h3>';
		echo $embed_code;
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['url'] = strip_tags($new_instance['url']);
		$instance['width'] = strip_tags($new_instance['width']);
		return $instance;
	}
	
	function form($instance) {
		$instance = wp_parse_args((array) $instance, array('title' => '', 'url' => '', 'width' => '300'));
		$title = strip_tags($instance['title']);
		$url = strip_tags($instance['url']);
		$width = strip_tags($instance['width']);
		
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>

		<p><label for="<?php echo $this->get_field_id('url'); ?>">Video Url: <input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo attribute_escape($url); ?>" /></label></p>
			
		<p><label for="<?php echo $this->get_field_id('width'); ?>">Width: <input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo attribute_escape($width); ?>" /></label></p>
		
		<div id="video_preview_<?php echo $this->id; ?>" style="display:none;"><label>Preview</label>
			<div id="preview_div_<?php echo $this->id; ?>"></div>
		</div>
		<?php
		
		if (function_exists('domain_mapping_siteurl')) 
			$domain = domain_mapping_siteurl(null);
		else
			$domain = get_bloginfo('url');
		
		$initJquery = '
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					$("#' . $this->get_field_id('url') . '").blur(function () {
						getPreviewImg();
					});
					
					function getPreviewImg() {
						videoUrl = $("#' . $this->get_field_id('url') . '").val();
					
						if (videoUrl == "") {
							$("#video_preview_' . $this->id . '").hide();
							return;
						}
						
						$.ajax({
										type: "post",
										data: {
											action:"widget_video_preview",
											url: videoUrl
										},
										url: "' . $domain . '/wp-admin/admin-ajax.php",
										success: function(value) {
											$("#preview_div_' . $this->id . '").html(value);
											$("#video_preview_' . $this->id . '").show();
										}
									});
						
					}
				});
				
				
			</script>';
		
		echo $initJquery;
	}
}

register_widget('ufandshands_widget_video');
?>