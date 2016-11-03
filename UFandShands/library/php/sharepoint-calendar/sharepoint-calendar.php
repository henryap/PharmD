<?php

/*
  Plugin Name: Sharepoint Calendar
  Plugin URI:
  Description: Enables sharepoint calendar shortcode and widget for displaying events.
  Author: seanj
  Author URI: http://webservices.ahc.ufl.edu/
  Version: 1.0
  Text Domain: sharepoint-calendar
 */

include_once 'shortcode-sharepoint-calendar.php';
include_once 'widget-sharepoint-calendar.php';

class sharepoint_calendar {

    function sharepoint_calendar() {
        add_action('init', array(&$this, 'init'));
    }

    function init() {
        add_action('template_redirect', array(&$this, 'template_redirect'));
        add_filter('wp_title', array(&$this, 'wp_title'), 10, 3);
    }

    function template_redirect() {
        global $wp_query;

        if ($this->isCalendarPage()) {
            header('HTTP/1.1 200 OK');
            $path = locate_template('events-calendar.php');
            load_template($path);

            die();
        }
    }

    function wp_title($title, $sep, $seplocation) {

        if ($this->isCalendarPage())
            $title = 'Calendar ' . $sep;

        return $title;
    }

    function isCalendarPage() {
        return preg_match('/\/full-calendar(|\/)(|\?.*)$/i', $_SERVER['REQUEST_URI']);
    }

}

$sharepointCalendar = new sharepoint_calendar();
?>
