<?php

include_once(dirname(__FILE__).'/config.php');

$useGoogleSearch = of_get_option('opt_google_site_search');
if($useGoogleSearch) {
	add_action( 'wp_enqueue_scripts', 'load_scripts' , 5 );
}

function load_scripts(){
	global $gsc_plugin_dir_path, $gsc_hide_search_button;

	//Adding javascripts
	wp_enqueue_script("jquery");
	wp_enqueue_script("jquery-ui-dialog");
	wp_enqueue_script("jquery-ui-resizable");
	wp_enqueue_script("jquery-ui-core");
	wp_enqueue_script("jquery-ui-draggable ");
	wp_enqueue_script("jquery-ui-selectable ");
	wp_enqueue_script('gsc_dialog', get_bloginfo('template_directory') .'/library/js/gsc.js');
	wp_enqueue_script('gsc_jsapi', 'http://www.google.com/jsapi');
	
	//Adding CSS
	//wp_enqueue_style('gsc_style', get_bloginfo('template_directory') . '/library/css/smoothness/jquery-ui-1.7.3.custom.css');
	//wp_enqueue_style('gsc_style_search_bar', 'http://www.google.com/cse/style/look/minimalist.css');
	
	wp_enqueue_style('gsc_style_search_bar', get_bloginfo('template_directory').'/library/css/ufandshands-gsc.css');
}



/**
 * Displays the Google Custom Search box
 */
function display_search_box(){
	
	$random_number = rand(0, 99);
	$search_div_id = "cse-search-form".$random_number;
	
	$useGoogleCSE = of_get_option('opt_google_cse');
	$googleCSEID = of_get_option('opt_google_cse_id');
	
	$formHtml = '<div id="' . $search_div_id . '" style="width: 100%;">Loading</div>
				<script type="text/javascript">
					google.load(\'search\', \'1\');
					google.setOnLoadCallback(function() {
					var customSearchOptions = {};';
	
	if (function_exists('domain_mapping_siteurl')) 
		$domain = domain_mapping_siteurl(null);
	else
		$domain = get_bloginfo('url');
	
	if ($useGoogleCSE) { 
		$formHtml .= 'var customSearchControl = new google.search.CustomSearchControl(\'' . $googleCSEID . '\', customSearchOptions);';
	} else {
		$formHtml .= 'var customSearchControl = new google.search.CustomSearchControl({crefUrl:\'' . $domain . '/wp-content/themes/UFandShands/cref_xml.php\'});';
	}
	
	$formHtml .= 'customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);
				  var options = new google.search.DrawOptions();
				  options.setAutoComplete(true);';
	
	if ($useGoogleCSE) { 
		$formHtml .= 'customSearchControl.setAutoCompletionId(\'' . $googleCSEID . '+qptype:1\');';
	} 
	
	$formHtml .= 'options.enableSearchboxOnly("' . $domain . '/", "s");
					customSearchControl.draw(\''. $search_div_id . '\', options);
					}, true);
					</script>';
	
	return $formHtml;
}
?>
