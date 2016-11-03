<?php

date_default_timezone_set('America/New_York');
require_once 'sharepoint-calendar-helper.php';


//$data = sharepoint_calendar_helper::GetEventByID('{B07C58F1-485A-43A6-AC4F-C0077DD3F508}', 'https://test-intranet.ahc.ufl.edu/AHC/_vti_bin/Lists.asmx?wsdl', 'Calendar');
//var_dump($data);
//die();

$startDate = date('c', $_GET['start']);
$startDate1 = new DateTime($startDate);
$startDate1->add(new DateInterval('P10D'));
$startDate = date_format($startDate1, 'c');
$data = null;

if (!empty($_GET['calendar'])) {  // displaying calendar using data from widget
    $calendar = $_GET['calendar'];
    $data = fetchFromCache($calendar . '|' . $startDate);

    if (!$data) {
        require( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
        global $blog_id;
        $calOptions = get_blog_option($blog_id, $calendar);

        if (isset($calOptions)) {
            $listName = $calOptions['listName'];
            $wsdl = $calOptions['wsdl'];

            $data = sharepoint_calendar_helper::GetEvents($wsdl, $listName, $startDate);
            saveInCache($calendar . '|' . $startDate, $data);
        }
    }
} else if (!empty($_GET['postID'])) {  //display calendar form shortcode
    if (isset($_GET['postID']))
        $postID = $_GET['postID'];

    if (isset($postID)) {
        
        // checks to see if there are data in cache already
        $data = fetchFromCache($postID . '|' . $startDate);

        if (!$data) {
            // gets the wsdl url from post meta data
            require( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
            $postID = $_GET['postID'];
            $wsdl = get_post_meta($postID, 'wsdl', true);
            $listName = get_post_meta($postID, 'listName', true);

            $data = sharepoint_calendar_helper::GetEvents($wsdl, $listName, $startDate);
            saveInCache($postID . '|' . $startDate, $data);
        }
    } 
} else {  // get default calendar url from theme options
    
    $data = fetchFromCache('Default Calendar|' . $startDate);
    
    if (!$data) {
        require( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
        
        $listName = sharepoint_calendar_helper::GetListNameFromUrl(of_get_option('opt_sharepoint_url'));
        $wsdl = sharepoint_calendar_helper::ConvertUrlToWSDL(of_get_option('opt_sharepoint_url'));
        
        $data = sharepoint_calendar_helper::GetEvents($wsdl, $listName, $startDate);
        saveInCache('Default Calendar|' . $startDate, $data);
    }
    
}


// outputs the data as json, to be consumed by jquery ajax calls
if ($data)
    echo json_encode($data);


// returns data from cache if apc is installed
function fetchFromCache($key) {
    if (function_exists('apc_fetch')) 
        return apc_fetch($key);
    else
        return null;
}

// saves data into cache if apc is installed
function saveInCache($key, $data) {
     if (function_exists('apc_add'))
            apc_add($key, $data, 600);  // insert data into cache with life of 10 min
}
?>
