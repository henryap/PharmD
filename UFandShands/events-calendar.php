<?php include("header.php"); ?>
<?php
    $calendar = $_GET['c'];
            wp_enqueue_style('fullcalendar-css', get_bloginfo('template_directory') . '/library/css/fullcalendar.css', array(), '', 'screen');
            wp_enqueue_style('fullcalendar-print-css', get_bloginfo('template_directory') . '/library/css/fullcalendar.print.css', array(), '', 'print');
            wp_enqueue_style('prettyPhoto-css', get_bloginfo('template_directory') . '/library/css/prettyPhoto.css');
            wp_enqueue_style('sharepoint-calendar-css', get_bloginfo('template_directory') . '/library/css/sharepoint-calendar.css');

            wp_enqueue_script('fullcalendar-js', get_bloginfo('template_directory') . '/library/js/fullcalendar.min.js');
	    wp_enqueue_script('fullcalendar-jquery-ui', get_bloginfo('template_directory') . '/library/js/fullcalendar.min.js');
	    wp_enqueue_script('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js');
?>

<?php ufandshands_breadcrumbs(); ?>
    
<div id="content-wrap">
    <div id="content-shadow">
        <div id="content" class="container">

            <?php $currenttemplate = get_post_meta($post->ID, '_wp_page_template', true); //members only template check ?>

            <?php if (($currenttemplate != "membersonly.php") || ( ($currenttemplate == "membersonly.php") && ufandshands_members_only() )) { //members only logic?>
                
                <article id="main-content" class="span-23 box" role="main">
                    
                    <div id='calendar'>
                    </div>


                    <a href="#eventDetails" rel="prettyPhoto" id="eventOpen"></a>
                    
                    <?php echo sharepoint_calendar_helper::EventDetailHtml() ?>

                    <?php echo sharepoint_calendar_helper::CalendarJs() ?>

                </article>

<?php } ?>
        </div>
    </div>
</div>
<?php include('user-role-menu.php'); ?>
<?php include("footer.php"); ?>

