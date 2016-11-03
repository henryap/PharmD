<?php
include_once(ABSPATH . WPINC . '/feed.php');

class ufandshands_widget_health_topics extends WP_Widget {
    static private $_url = 'https://ufandshands.org/';
    static function ajax_response() {
	extract($_POST);

	// Get widget type and number
	$id_base = explode('-', $widget_id);
	$widget_nr = array_pop($id_base);
	$id_base = implode('-', $id_base);

	// Get widget instance
	$widget_key = 'widget_' . $id_base;
	$widgets = get_option($widget_key);
	$instance = & $widgets[$widget_nr];

	$departments = empty($instance['departments']) ? '' : json_decode(apply_filters('widget_text', $instance['departments']));
	$items = empty($instance['numberOfItems']) ? '20' : apply_filters('widget_text', $instance['numberOfItems']);
	
	$rss_items = ufandshands_widget_health_topics::get_content($departments, $page, $items);
	$output = ufandshands_widget_health_topics::list_items($rss_items);
	
	echo $output;
	die();
    }

    static function get_content($departments, $page, $items, &$total=0) {

	$url = ufandshands_widget_health_topics::$_url . 'feed/health_topics/' . implode('+', $departments) . '/wpfeed.rss';
	
	if (function_exists('apc_fetch')) {
	    $rss_items = apc_fetch($rss, $success);
	    
	    if (isset($rss_items) && !empty($rss_items)) {
		$total = count($rss_items);
		$rss_items = array_slice($rss_items, ($page - 1) * $items, $items);

		return $rss_items;
	    }
	}

	// Get RSS Feed(s)
	include_once(ABSPATH . WPINC . '/feed.php');
	$rss = fetch_feed($url);
	if (!is_wp_error($rss)) {
	    // Build an array of all the items, starting with element 0 (first element).
	    $rss_items = $rss->get_items(0);
	    $total = count($rss_items);
	    if (function_exists('apc_store'))
		    apc_store($url, $rss_items, 600);
	    
	    $rss_items = array_slice($rss_items, ($page - 1) * $items, $items);
	    return $rss_items;
	 } else {
	    echo '<p>There has been an error when trying to get data from the RSS feed.</p>';
	    return;
	 }
    }

    function ufandshands_widget_health_topics() {
	$widget_ops = array('classname' => 'ufandshands_widget_health_topics', 'description' => __('UF&Shands Health Topics'));
	$this->WP_Widget('ufandshands_widget_health_topics', __('UF&Shands Health Topics'), $widget_ops);

    wp_enqueue_script('widget-chosen-js', get_bloginfo('template_directory') . '/admin/js/widget-chosen.js', array(), false, true);
	wp_enqueue_script('chosen-js', get_bloginfo('template_directory') . '/library/js/chosen.jquery.min.js', array(), false, true);
	wp_enqueue_script('jquery-pager-js', get_bloginfo('template_directory') . '/library/js/jquery.jqpagination.min.js', array(), false, true);
	wp_enqueue_style('chosen-css', get_bloginfo('template_directory') . '/library/css/chosen.css');
	//wp_enqueue_style('jquery-pager-css', get_bloginfo('template_directory') . '/library/css/jqpagination.css');

	add_action('wp_ajax_nopriv_ufandshands_widget_health_topics', array('ufandshands_widget_health_topics', 'ajax_response'));
	add_action('wp_ajax_ufandshands_widget_health_topics', array('ufandshands_widget_health_topics', 'ajax_response'));
    }

    function widget($args, $instance) {

	extract($args, EXTR_SKIP);

	$uniquePageID = get_page_by_title($instance['uniquePageID']);
	$departments = empty($instance['departments']) ? '' : json_decode(apply_filters('widget_text', $instance['departments']));
	$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
	$items = empty($instance['numberOfItems']) ? '20' : apply_filters('widget_text', $instance['numberOfItems']);

	global $wp_query;
	$current_page = $wp_query->post->ID;
	
	if ($current_page==$uniquePageID->ID || empty($instance['uniquePageID']) ) {
		// if both rss url and alternate url is empty then exit
		if (!is_array($departments))
			return;

		echo $before_widget;

		$current = intval($_GET['ufs-pg']);
		$current = $current == 0 ? 1 : $current;

		$rss_items = ufandshands_widget_health_topics::get_content($departments, $current, $items, $total);
		$totalPage = ceil($total/$items);
		$rss_widget_output = '<h3>' . $title . '</h3>';
		$rss_widget_output .= '<div id="' . $args['widget_id'] . '-loading" class="ufandshands-content-loading" style="visibility:hidden;">Loading...</div>';
		$rss_widget_output .= '<ul id="' . $args['widget_id'] . '">';

		if ($total > 0) {
			$rss_widget_output .= ufandshands_widget_health_topics::list_items($rss_items);  //the item list markup generated via another function
		} else {
			$rss_widget_output .= 'No topics found.';	
		}
		
		$rss_widget_output .= '</ul>';
		echo $rss_widget_output;

		if ($totalPage > 1) {
			if (function_exists('domain_mapping_siteurl')) 
				$domain = domain_mapping_siteurl(null);
			else
				$domain = get_bloginfo('url');
			
			$rss_widget_pager = '<div class="ufandshands-content-pagination clearfix">
			<a href="#" class="first" data-action="first">&laquo;</a>
			<a href="#" class="previous" data-action="previous">&lsaquo;</a>
			<input type="text" readonly="readonly" data-max-page="' . $totalPage . '" />
			<a href="#" class="next" data-action="next">&rsaquo;</a>
			<a href="#" class="last" data-action="last">&raquo;</a>
		    </div>
		    
			<script type="text/javascript">
		    $(document).ready(function() {
				$(".ufandshands-content-pagination").jqPagination({
					ink_string	: "/?ufs-pg={page_number}",
					current_page: ' . $current . ',
					max_page	: ' . $totalPage . ',
					paged       : function(pg) {
									$("#' . $args['widget_id'] . '").css("visibility", "hidden");
									$("#' . $args['widget_id'] . '-loading").css("visibility", "visible");
									$.ajax({
										type: "post",
										data: {
											action:"ufandshands_widget_health_topics",
											sidebar_id:"'. $args['id'] . '",
											widget_id:"' . $args['widget_id'] . '",
											page: pg
										},
										url: "' . $domain . '/wp-admin/admin-ajax.php",
										success: function(value) {
											$("#' . $args['widget_id'] . '").html(value);
											$("#' . $args['widget_id'] . '").css("visibility", "visible");
											$("#' . $args['widget_id'] . '-loading").css("visibility", "hidden");
										}
									});

								}
				});
		    });
		    </script>';
			echo $rss_widget_pager;
		}
		
		echo $after_widget;
	}
    }

    static function list_items($rss_items) {
	$rss_widget_output = '';
	// Loop through each feed item and display each item as a hyperlink.
	foreach ($rss_items as $item) {
	    $rss_widget_output .= "<li class='ufandshands-content'>";
	    $rss_widget_output .= "<a href='" . $item->get_link() . "'>" . $item->get_title() . "</a>";
	    $rss_widget_output .="</li>";
	}
	return $rss_widget_output;
    }

    function update($new_instance, $old_instance) {

	$instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['departments'] = json_encode($new_instance['departments']);
	$instance['numberOfItems'] = strip_tags($new_instance['numberOfItems']);
	$instance['uniquePageID'] = strip_tags($new_instance['uniquePageID']);

	return $instance;
    }

    function form($instance) {

	//Defaults
	$instance = wp_parse_args((array) $instance, array('departments' => '', 'numberOfItems' => '20'));
	$departments = json_decode($instance['departments']);
	$title = strip_tags($instance['title']);
	$numberOfItems = strip_tags($instance['numberOfItems']);
	$uniquePageID = strip_tags($instance['uniquePageID']);
    $apc_key = 'departments_and_servicelines';
	$departmentsUrl = 'https://ufandshands.org/taxonomy-terms/departments_and_servicelines/xml';
    
    if (function_exists('apc_fetch')) {
        $xmlString = apc_fetch($apc_key, $success);
    }
    
    if (!isset($xmlString) || empty($xmlString)) {

    	$xml = simplexml_load_file($departmentsUrl);
        
        if (function_exists('apc_store')) {
		    apc_store($apc_key, $xml->asXML(), 6000);
        }
    } else {
        $xml = simplexml_load_string($xmlString);
    }
    
	$results = $xml->xpath("item");
    ?>        

    
        <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
        <?php 
	if (!is_wp_error($feed)) {

	    echo '<p><label for="' . $this->get_field_id('departments') . '">Departments, Service Lines, and Specialties:</label>';
	    echo '<select id="' . $this->get_field_id('departments') . '" name="' . $this->get_field_name('departments') . '[]" data-placeholder="Choose a department..." class="chosen" multiple style="width:220px;">';
	    foreach ($results as $result) {
		if (isset($result->name) && isset($result->tid)) {
            if (!is_null($departments)) 
			    echo '<option value="' . $result->tid . '" ' . (in_array($result->tid, $departments) ? 'selected' : '') . '>' . $result->name . '</option>';
			else
				echo '<option value="' . $result->tid . '" >' . $result->name . '</option>';
		}
	    }
	    echo '</select></p>';
	}
	?>

			<p><label for="<?php echo $this->get_field_id('numberOfItems'); ?>">Number of topics per page: <input class="widefat" id="<?php echo $this->get_field_id('numberOfItems'); ?>" name="<?php echo $this->get_field_name('numberOfItems'); ?>" type="text" value="<?php echo attribute_escape($numberOfItems); ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id( 'uniquePageID' ); ?>">Display only on page:</label>
				<select id="<?php echo $this->get_field_id( 'uniquePageID' ); ?>" name="<?php echo $this->get_field_name( 'uniquePageID' ); ?>" style="width:220px" class="chosen" >
					<option value="">
					<?php echo attribute_escape(__('All pages')); ?></option> 
					<?php 
						$pages = get_pages(); 
						foreach ($pages as $pagg) {
							$title = $pagg->post_title;
							$option = '<option ';
							$option .= 'value="'.htmlspecialchars($title).'" ';
							if ($title == $instance['uniquePageID']) {
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
	
    <?php if ($this->number != '__i__') { ?>
	<script type="text/javascript">
	    jQuery(document).ready(function(){
		    jQuery("#<?php echo $this->get_field_id('departments'); ?>").chosen();
			jQuery("#<?php echo $this->get_field_id('uniquePageID'); ?>").chosen();
		    jQuery(".chzn-container").parents('.widget').css('overflow', 'visible');
		 });
	</script>
	<?php
          }
    }

}

register_widget('ufandshands_widget_health_topics');
?>