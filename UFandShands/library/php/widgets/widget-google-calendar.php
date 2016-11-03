<?php
include_once 'widget-simple-google-events-3.php';


class UFCOM_google extends WP_Widget {

	function UFCOM_google() {
		$widget_ops = array('classname' => 'widget_UFCOM_google', 'description' => 'Insert a Google Calendar' );
		$this->WP_Widget('UFCOM_google', 'Google Calendar', $widget_ops);
        
        add_action( 'wp_ajax_nopriv_UFCOM_google', array( 'UFCOM_google', 'ajax_response') );
        add_action( 'wp_ajax_UFCOM_google', array( 'UFCOM_google', 'ajax_response') );
	}
 
    static function ajax_response() {
        extract( $_POST );
        
        // Get widget type and number
		$id_base = explode( '-', $widget_id );
		$widget_nr = array_pop( $id_base );
		$id_base = implode( '-', $id_base );

		// Get widget instance
		$widget_key = 'widget_' . $id_base;
		$widgets = get_option( $widget_key );
		$instance =& $widgets[ $widget_nr ];

		$eventdays = empty($instance['eventdays']) ? '5' : apply_filters('widget_eventdays', $instance['eventdays']);
        $eventcount = empty($instance['eventcount']) ? '' : apply_filters('widget_eventcount', $instance['eventcount']);
        $googleaccount = empty($instance['googleaccount']) ? $googleaccount_original : apply_filters('widget_googleaccount', $instance['googleaccount']);
        $cache_key = md5($googleaccount); //md5 not used for security, just used to shorten the value in case there it is really long (e.g. college of medicine)
        if (function_exists('apc_fetch')) {
            $eventsHtml = apc_fetch($cache_key, $success);
        }
        
        if (!isset($eventsHtml) || empty($eventsHtml)) {
            $events = new google_calendar_parser($googleaccount, eventdays, $eventcount);
            $eventsHtml = $events->ListEvents();
            
            apc_store($cache_key, $eventsHtml, 600);
        }
        
        
        echo $eventsHtml;
        die();
    }
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
			$unique_page_content = get_page_by_title($instance['unique_page_id']);
			global $wp_query;
			$current_page = $wp_query->post->ID;
	 
			if ($current_page==$unique_page_content->ID || empty($instance['unique_page_id']) ) {

				echo $before_widget;
				$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
				$eventdays = empty($instance['eventdays']) ? '5' : apply_filters('widget_eventdays', $instance['eventdays']);
				$eventcount = empty($instance['eventcount']) ? '' : apply_filters('widget_eventcount', $instance['eventcount']);
				$googleaccount = empty($instance['googleaccount']) ? $googleaccount_original : apply_filters('widget_googleaccount', $instance['googleaccount']);
				$cache_key = md5($googleaccount);  //md5 not used for security, just used to shorten the value in case there it is really long (e.g. college of medicine)
					if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
					
					if (strlen($googleaccount)>5) {	
                        
                        
                        if (function_exists('apc_fetch')) {
                            $eventsHtml = apc_fetch($cache_key);
                            
                        }
                        
                        echo "<div id='events' class='" . $args['widget_id'] . "'>";
                        if (isset($eventsHtml) && !empty($eventsHtml)) {
                            echo $eventsHtml;
                        }
                        else{
							if (function_exists('domain_mapping_siteurl')) 
								$domain = domain_mapping_siteurl(null);
							else
								$domain = get_bloginfo('url');
							
                            if (!defined('DONOTCACHEPAGE'))
                                define('DONOTCACHEPAGE', 1);
                            
                            echo 'Loading events...';
                            echo '<script type="text/javascript">';
                            echo ' $(document).ready(function() {
	                                $.ajax({
                                      type:\'POST\',
                                      data:{
                                        action:\'UFCOM_google\',
                                        sidebar_id:\''. $args['id'] . '\',
                                        widget_id:\'' . $args['widget_id'] . '\'
                                      },
                                      url: "' . $domain . '/wp-admin/admin-ajax.php",
                                      success: function(value) {
                                        $(\'.' . $args['widget_id'] . '\').html(value);
                                      }
                                    });
                                 });';
                            echo '</script>';
                        }
                        echo "</div>";
					} else { echo "<p>Please enter a proper Google Calendar ID in your widget</p>"; }
					
					$allCalendars = 'src=' . preg_replace('/,( )?/', '&src=', $googleaccount);
					echo "<div style=\"font-size:.7em;font-weight:bold;text-transform:uppercase\">&raquo; <a href=\"http://www.google.com/calendar/embed?".$allCalendars."&amp;ctz=America/New_York&amp;title=" . $title . "\">View full calendar</a></div>";
					
					echo $after_widget;
					
				
			} 		
	}
 
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['eventdays'] = strip_tags($new_instance['eventdays']);
		$instance['eventcount'] = strip_tags($new_instance['eventcount']);
		$instance['googleaccount'] = strip_tags($new_instance['googleaccount']);

		$instance['unique_page_id'] = $new_instance['unique_page_id'];

		return $instance;
	}
 
	function form($instance) {
		$googleaccount_original = get_option('COM_google_calendar_account');
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'googleaccount' => $googleaccount_original, 'eventdays' => '5', 'eventcount'=> '', 'unique_page_id' => '' ) );
		$title = strip_tags($instance['title']);
		$eventdays = strip_tags($instance['eventdays']);
		$eventcount = strip_tags($instance['eventcount']);
		$googleaccount = strip_tags($instance['googleaccount']);

		$unique_page_id = $instance['unique_page_id'];
		
?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>

			<p><label for="<?php echo $this->get_field_id('googleaccount'); ?>">Google Calendar ID <input class="widefat" id="<?php echo $this->get_field_id('googleaccount'); ?>" name="<?php echo $this->get_field_name('googleaccount'); ?>" type="text" value="<?php echo attribute_escape($googleaccount); ?>" /></label></p>

			<p><label for="<?php echo $this->get_field_id('eventdays'); ?>">Total days of events to show<input class="widefat" id="<?php echo $this->get_field_id('eventdays'); ?>" name="<?php echo $this->get_field_name('eventdays'); ?>" type="text" value="<?php echo attribute_escape($eventdays); ?>" /></label></p>

			<p><label for="<?php echo $this->get_field_id('eventcount'); ?>">Total number of events to show (trumps total days)<input class="widefat" id="<?php echo $this->get_field_id('eventcount'); ?>" name="<?php echo $this->get_field_name('eventcount'); ?>" type="text" value="<?php echo attribute_escape($eventcount); ?>" /></label></p>


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
register_widget('UFCOM_google');



function get_google_calendar_html() {
       
}


?>