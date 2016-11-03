<?php


require_once 'sharepoint-calendar-helper.php';
class ufshands_sharepoint_calendar_shortcode {
	static $add_script;
 
	function init() {
		add_shortcode('sharepoint-calendar', array(__CLASS__, 'handle_shortcode'));
		add_action('init', array(__CLASS__, 'register_script'));
		add_action('wp_footer', array(__CLASS__, 'print_script'));
	}
 
	function handle_shortcode($atts) {
		self::$add_script = true;
 
                extract(shortcode_atts(array(
                    'username' => SHAREPOINT_USER,
                    'password' => SHAREPOINT_USER_PW,
                    'calendarUrl' => 'https://test-intranet.ahc.ufl.edu/AHC/Lists/Calendar/calendar.aspx'
                ), $atts));

                
                global $post;
                echo '<div id="calendar"></div><a href="#eventDetails" rel="prettyPhoto" id="eventOpen"></a>' . sharepoint_calendar_helper::EventDetailHtml() . sharepoint_calendar_helper::CalendarJs($post->ID);

                $listName = sharepoint_calendar_helper::GetListNameFromUrl($calendarUrl);
                // saves the wsdl location into post meta data so the feed page can retrieve it
                add_post_meta($post->ID, 'wsdl', sharepoint_calendar_helper::ConvertUrlToWSDL($calendarUrl));
                add_post_meta($post->ID, 'listName', $listName);
	}
 
	function register_script() {
                $pluginUrl = WP_PLUGIN_URL . '/' . basename( dirname( __FILE__ ) );
                wp_register_script('fcalendar', get_bloginfo('template_url') . '/library/js/fullcalendar.min.js');
                wp_register_script('prettyPhoto', get_bloginfo('template_url') . '/library/js/jquery.prettyPhoto.js');
                wp_register_script('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js');
	}
 
	function print_script() {
		if ( ! self::$add_script )
			return;
 
		wp_print_scripts('fcalendar');
                wp_print_scripts('prettyPhoto');
        }
}
 
ufshands_sharepoint_calendar_shortcode::init();

?>
