<?php
require_once 'sharepoint-calendar-helper.php';

class UFCOM_sharepoint_calendar_widget extends WP_Widget {
    static $add_script;

    function UFCOM_sharepoint_calendar_widget() {
        $widget_ops = array('classname' => 'widget_ufcom_sharepoint_calendar sidebar_widget', 'description' => 'Sharepoint Calendar');
        $this->WP_Widget('UFCOM_sharepoint_calendar', 'Sharepoint Calendar', $widget_ops);
        add_action('init', array(__CLASS__, 'register_script'));
        add_action('wp_footer', array(__CLASS__, 'print_script'));
    }

    function init() {
        add_action( 'widgets_init',  array(__CLASS__, 'UFCOM_sharepoint_calendar_widget_init') );
    }

    function UFCOM_sharepoint_calendar_widget_init() {
        // widget: Sharepoint Calendar
	register_widget( 'UFCOM_sharepoint_calendar_widget' );
    }
    
    function widget($args, $instance) {
        extract($args);
        self::$add_script = true;
        
    	$unique_page_content = get_page_by_title($instance['unique_page_id']);
            global $wp_query;
            $current_page = $wp_query->post->ID;
        
	if ($current_page==$unique_page_content->ID || empty($instance['unique_page_id']) ) {
        
            echo $before_widget;
            $calendarUrl = $instance['calendarUrl'];
            $calendarTitle = $instance['calendarTitle'];
            $listName = $instance['listName'];
            $rowLimit = $instance['rowLimit'];
            $unique_page_id = $instance['unique_page_id'];

            // check to see if events is in cache
            if (function_exists('apc_fetch'))
                $events = apc_fetch('SharepointCalendar|' . $calendarUrl . '|' . $listName . '|' . $rowLimit);

            if (!$events) {
                $events = sharepoint_calendar_helper::GetUpcomingEvents(sharepoint_calendar_helper::ConvertUrlToWSDL($calendarUrl), $listName, $rowLimit);
                if (function_exists('apc_add'))
                    apc_add('SharepointCalendar|' . $calendarUrl . '|' . $listName . '|' . $rowLimit, $events, 600);  // insert data into cache with life of 10 min
            }

            echo $before_title . $calendarTitle . $after_title;

            $event_display="<div class='item'><div class='event-date'>###DATE###</div><h4><a href='###LINK###' id='###EVENTID###'>###TITLE###</a></h4><p class='time'>###TIME###</p></div>";
            echo "<div id='events'>";

            if (!$events)
                echo "No upcoming events.<br /><br />";
            else {
                foreach ($events as $event) {
                    $simplegCalStartTime = strtotime($event['start']);
                    $simplegCalEndTime = strtotime($event['end']);
					$today = time();
					
					
					$simpledateconverted = "<span class='month'>";
					
					if ($simplegCalStartTime >= $today) 
						$simpledateconverted .= date('M', $simplegCalStartTime);	
					else
						$simpledateconverted .= date('M', $today);	
                    
                    $simpledateconverted .= "</span><span class='day'>";
					
					if ($simplegCalStartTime >= $today) 
						$simpledateconverted .= date('j', $simplegCalStartTime);
					else
						$simpledateconverted .= date('j', $today);
					
                    $simpledateconverted .= "</span>";

                    $simpledateconvertednoyear = date('n/j', $simplegCalStartTime);

                    $temp_event=$event_display;
                    $temp_dateheader=$event_dateheader;
                    $temp_event=str_replace("###TITLE###",$event['title'],$temp_event);
                    $temp_event=str_replace("###DATE###",$simpledateconverted,$temp_event);
                    $temp_event=str_replace("###LINK###", "/full-calendar/?c=". UFCOM_sharepoint_calendar_widget::slug($instance['calendarTitle']) . "&" . date("U") . "#" . $event['id'] . "|" . date("U", $simplegCalStartTime),$temp_event);
                    $temp_event=str_replace("###EVENTID###",$event['id'],$temp_event);

                    if ($event['allDay'] == '1') {
                        $temp_event = str_replace("###TIME###", "all day event", $temp_event);
                    } else {
                        $temp_event = str_replace("###TIME###", "From " . $event['startTime'] . " until " . $event['endTime'], $temp_event);
                    }

                    echo $temp_event;

                }
            }
            echo "<div class='more'><a href='/full-calendar/?c=". UFCOM_sharepoint_calendar_widget::slug($instance['calendarTitle']) . "'>&raquo; View all events</a></div>";
            echo sharepoint_calendar_helper::EventDetailHtml() . "<script type='text/javascript'>
                            $(document).ready(function() {
                                $('.eventLink').click(function() {
                                    $(this).attr('id');
                                });
                            });
                      </script>";
            echo "</div>";
            echo sharepoint_calendar_helper::EventDetailHtml();


            echo $after_widget;
        } else {
            return false;
    }
}
    
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['calendarUrl'] = strip_tags($new_instance['calendarUrl']);
        
        
        $instance['listName'] = sharepoint_calendar_helper::GetListNameFromUrl($instance['calendarUrl']);
        $instance['calendarTitle'] = strip_tags($new_instance['calendarTitle']);
        $instance['rowLimit'] = strip_tags($new_instance['rowLimit']);
        $instance['unique_page_id'] = $new_instance['unique_page_id'];
        $calOptions = array('listName' => $instance['listName'], 'wsdl' => sharepoint_calendar_helper::ConvertUrlToWSDL($instance['calendarUrl']));

        global $blog_id;
        update_blog_option($blog_id, strtolower(UFCOM_sharepoint_calendar_widget::slug($instance['calendarTitle'])), $calOptions);

        return $instance;
    }

    function form($instance) {
        
        $instance = wp_parse_args((array) $instance, array('wsdl' => '', 'calendarTitle' => 'Calendar', 'rowLimit' => '5'));
        $calendarUrl = strip_tags($instance['calendarUrl']);
        $calendarTitle = strip_tags($instance['calendarTitle']);
        $rowLimit = strip_tags($instance['rowLimit']);
        $unique_page_id = $instance['unique_page_id'];

        if (empty($calendarUrl)) {
            $calendarUrl = of_get_option('opt_sharepoint_url');
        }

        ?>
        <p><label for="<?php echo $this->get_field_id('calendarTitle'); ?>">Calendar Title: <input class="widefat" id="<?php echo $this->get_field_id('calendarTitle'); ?>" name="<?php echo $this->get_field_name('calendarTitle'); ?>" type="text" value="<?php echo attribute_escape($calendarTitle); ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('calendarUrl'); ?>">Calendar Url: <input class="widefat" id="<?php echo $this->get_field_id('calendarUrl'); ?>" name="<?php echo $this->get_field_name('calendarUrl'); ?>" type="text" value="<?php echo attribute_escape($calendarUrl); ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('rowLimit'); ?>">Number of events: <input class="widefat" id="<?php echo $this->get_field_id('rowLimit'); ?>" name="<?php echo $this->get_field_name('rowLimit'); ?>" type="text" value="<?php echo attribute_escape($rowLimit); ?>" /></label></p>

        <p><label for="<?php echo $this->get_field_id( 'unique_page_id' ); ?>">Display only on page:</label>
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
            } ?>
            </select>
	</p>
            
    <?php
    }
    
    function register_script() {
        wp_register_script('prettyPhoto', get_bloginfo('template_url') . '/library/js/jquery.prettyPhoto.js');
    }

    function print_script() {
        if (!self::$add_script)
            return;

        wp_print_scripts('prettyPhoto');
    }

    public static function slug($str){
        $str = strtolower(trim($str));
        $str = preg_replace('/[^a-z0-9-]/', '-', $str);
        $str = preg_replace('/-+/', "-", $str);
        return $str;
    }
}

UFCOM_sharepoint_calendar_widget::init();


	
?>
