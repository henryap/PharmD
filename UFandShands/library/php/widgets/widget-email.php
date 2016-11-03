<?php

class UFCOM_emailcontact extends WP_Widget {
	function UFCOM_emailcontact() {
		$widget_ops = array('classname' => 'widget_ufcom_emailcontact', 'description' => 'Email Contact Form' );
		$this->WP_Widget('UFCOM_emailcontact', 'Email Contact Form', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);

		echo $before_widget;
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		$email_to = empty($instance['email_to']) ? '&nbsp;' : apply_filters('widget_email_to', $instance['email_to']);
                $captcha =  isset($instance['captcha']) ? $instance['captcha'] : false;
		//if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };

		include 'widget-email-contact.php';
		
		echo $after_widget;
	}
 
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['email_to'] = strip_tags($new_instance['email_to']);
                $instance['captcha'] = $new_instance['captcha'];
		return $instance;
	}
 
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => 'Contact Us', 'email_to' => '' ) );
		$title = strip_tags($instance['title']);
		$email_to = strip_tags($instance['email_to']);
                $captcha = $instance['captcha'];
		
?>

			<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>

			<p><label for="<?php echo $this->get_field_id('email_to'); ?>">To Email Address(es) (separated by commas, must be @ufl.edu): <input class="widefat" id="<?php echo $this->get_field_id('email_to'); ?>" name="<?php echo $this->get_field_name('email_to'); ?>" type="text" value="<?php echo attribute_escape($email_to); ?>" /></label></p>
                        
                        <p><input class="checkbox" type="checkbox" <?php checked( $instance['captcha'], 'on' ); ?> id="<?php echo $this->get_field_id( 'captcha' ); ?>" name="<?php echo $this->get_field_name( 'captcha' ); ?>" /> &nbsp; <label for="<?php echo $this->get_field_id( 'captcha' ); ?>">Show Captcha?</label></p>

<?php

	}

}

register_widget('UFCOM_emailcontact');
?>