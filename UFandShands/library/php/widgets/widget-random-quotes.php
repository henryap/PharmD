<?php 

class UFCOM_random_quotes extends WP_Widget {
	function UFCOM_random_quotes() {
		$widget_ops = array('classname' => 'widget_ufcom_random_quotes', 'description' => 'Insert 3 random quotes or testimonials' );
		$this->WP_Widget('UFCOM_random_quotes', 'Random Quotes or Testimonials', $widget_ops);
	}
 
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
 
		$unique_page_content = get_page_by_title($instance['unique_page_id']);
		global $wp_query;
		$current_page = $wp_query->post->ID;
 
		if ($current_page==$unique_page_content->ID || empty($instance['unique_page_id']) ) {

			echo $before_widget;
			$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
			
			$quote_1 = $instance['quote_1'];
			$signature_1 = $instance['signature_1'];
			
			$quote_2 = $instance['quote_2'];
			$signature_2 = $instance['signature_2'];
			
			$quote_3 = $instance['quote_3'];
			$signature_3 = $instance['signature_3'];
			 
			if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
			
		

			if (strlen($quote_1)>5) {
				$quote_array[] = $quote_1;
				$signature_array[] = $signature_1;
			}
			
			if (strlen($quote_2)>5) {
				$quote_array[] = $quote_2;
				$signature_array[] = $signature_2;
			}
			
			if (strlen($quote_3)>5) {
				$quote_array[] = $quote_3;
				$signature_array[] = $signature_3;
			}
			
			$quote_key = array_rand($quote_array,1);
			
			echo "<div class=\"widget_random_quote\">";
			echo "<div class=\"widget_random_quote_body\">".wpautop($quote_array[$quote_key])."</div>";
			echo "<div class=\"widget_random_quote_signature\">".wpautop($signature_array[$quote_key])."</div>";
			echo "</div>";

			
			echo $after_widget;
		}
	}
 
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		
		$instance['quote_1'] = $new_instance['quote_1'];
		$instance['signature_1'] = $new_instance['signature_1'];
		
		$instance['quote_2'] = $new_instance['quote_2'];
		$instance['signature_2'] = $new_instance['signature_2'];
		
		$instance['quote_3'] = $new_instance['quote_3'];
		$instance['signature_3'] = $new_instance['signature_3'];
 
		$instance['unique_page_id'] = $new_instance['unique_page_id'];

		return $instance;
	}
 
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'quote_1' => '', 'signature_1' => '', 'quote_2' => '', 'signature_2' => '', 'quote_3' => '', 'signature_3' => '', '$unique_page_id' => '' ) );
		$title = strip_tags($instance['title']);
		
		$quote_1 = format_to_edit($instance['quote_1']);
		$signature_1 = format_to_edit($instance['signature_1']);
		
		$quote_2 = format_to_edit($instance['quote_2']);
		$signature_2 = format_to_edit($instance['signature_2']);
		
		$quote_3 = format_to_edit($instance['quote_3']);
		$signature_3 = format_to_edit($instance['signature_3']);

		$unique_page_id = $instance['unique_page_id'];
?>

			<p><label for="<?php echo $this->get_field_id('title'); ?>">Overall Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
			
			<label for="<?php echo $this->get_field_id('quote_1'); ?>">Quote #1:</label>
			<textarea class="widefat" rows="3" cols="20" id="<?php echo $this->get_field_id('quote_1'); ?>" name="<?php echo $this->get_field_name('quote_1'); ?>"><?php echo $quote_1; ?></textarea>
		
			<label for="<?php echo $this->get_field_id('signature_1'); ?>">Signature #1:</label>
			<textarea class="widefat" rows="2" cols="20" id="<?php echo $this->get_field_id('signature_1'); ?>" name="<?php echo $this->get_field_name('signature_1'); ?>"><?php echo $signature_1; ?></textarea>
			
			<hr />
			
			<label for="<?php echo $this->get_field_id('quote_2'); ?>">Quote #2:</label>
			<textarea class="widefat" rows="3" cols="20" id="<?php echo $this->get_field_id('quote_2'); ?>" name="<?php echo $this->get_field_name('quote_2'); ?>"><?php echo $quote_2; ?></textarea>
		
			<label for="<?php echo $this->get_field_id('signature_2'); ?>">Signature #2:</label>
			<textarea class="widefat" rows="2" cols="20" id="<?php echo $this->get_field_id('signature_2'); ?>" name="<?php echo $this->get_field_name('signature_2'); ?>"><?php echo $signature_2; ?></textarea>
			
			<hr />
			
			<label for="<?php echo $this->get_field_id('quote_3'); ?>">Quote #3:</label>
			<textarea class="widefat" rows="3" cols="20" id="<?php echo $this->get_field_id('quote_3'); ?>" name="<?php echo $this->get_field_name('quote_3'); ?>"><?php echo $quote_3; ?></textarea>
		
			<label for="<?php echo $this->get_field_id('signature_3'); ?>">Signature #3:</label>
			<textarea class="widefat" rows="2" cols="20" id="<?php echo $this->get_field_id('signature_3'); ?>" name="<?php echo $this->get_field_name('signature_3'); ?>"><?php echo $signature_3; ?></textarea>
	

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
register_widget('UFCOM_random_quotes');

?>