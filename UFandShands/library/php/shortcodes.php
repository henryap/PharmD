<?php
/*-----------------------------------------------------------------------------------*/
/*	Custom ShortCodes
/*-----------------------------------------------------------------------------------*/
include_once('shortcode-people-listing.php');

/* ----------------------------------------------------------------------------------- */
/* Insert a widget via a shortcode
/*
/* courtesy of: http://digwp.com/2010/04/call-widget-with-shortcode/
/* modified to allow the passing of attributes
/* [widget widget_name="Your_Custom_Widget"]
/* ----------------------------------------------------------------------------------- */
function ufandshands_widget_shortcode($atts) {
    
    global $wp_widget_factory;
    
    extract(shortcode_atts(array(
        'widget_name' => FALSE, // specific class name of shortcode
		'title' => '', // universal to all widgets
		'numberofposts' => '3', // recent posts
		'showexcerpt' => 1, // recent posts
		'showthumbnails' => 1, // recent posts
		'showdate' => 1, // recent posts
		'showrssicon' => 1, // recent posts
		'specific_category_id' => ''
    ), $atts));
    
    $widget_name = wp_specialchars($widget_name);
    
    if (!is_a($wp_widget_factory->widgets[$widget_name], 'WP_Widget')):
	
        $wp_class = 'WP_Widget_'.ucwords(strtolower($class));
        
        if (!is_a($wp_widget_factory->widgets[$wp_class], 'WP_Widget')):
            return '<p>'.sprintf(__("%s: Widget class not found. Make sure this widget exists and the class name is correct"),'<strong>'.$class.'</strong>').'</p>';
        else:
            $class = $wp_class;
        endif;
    endif;
    
    
	$instance = '&title='.urlencode($title);
	$instance .= '&numberofposts='.$numberofposts;
	$instance .= '&showexcerpt='.$showexcerpt;
	$instance .= '&showthumbnails='.$showthumbnails;
	$instance .= '&showdate='.$showdate;
	$instance .= '&showrssicon='.$showrssicon;
	$instance .= '&specific_category_id='.urlencode($specific_category_id);
		// $instance .= '&='.$;	

    ob_start();
	the_widget($widget_name, $instance, array('widget_id'=>'arbitrary-instance-'.$id,
		'before_widget' => '<div class="widget_body">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
				
	));
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
    
}
add_shortcode('widget','ufandshands_widget_shortcode'); 




// flowerplayer&video shortcode -- not using the extra attribute yet, leaving as template
function ufandshands_flow_func($atts, $content = null) {
	extract(shortcode_atts(array(
		'foo' => 'something',
		'bar' => 'something else',
		'height' => '470',
		'width' => '100%',
	), $atts));
	


	// iPad plugin wont play nice with multiple players on screen, so use a splash image to trigger default flowplayer ipad/iphone behavior
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	if (preg_match('/ipod/i',$user_agent)>0 || preg_match('/iphone/i',$user_agent)>0 || preg_match('/ipad/i',$user_agent)>0 || preg_match('/android/i',$user_agent)>0 || preg_match('/opera mini/i',$user_agent)>0 ) {
		$user_agent = "<img src=\"http://media.news.health.ufl.edu/video-splash-ufandshands.jpg\"";
	} else { $user_agent = ""; }

	// build flowjava return
	$flowjava = "<script type=\"text/javascript\" src=\"/flowplayer/flowplayer-3.2.6.min.js\"></script><script type=\"text/javascript\" src=\"/flowplayer/flowplayer.ipad-3.2.2.min.js\"></script>";
	$flowjava .="<a class=\"player\"
				href=\"".$content."\"
				style=\"display:block;width:".$width.";height:".$height."px;margin-bottom:20px;\"  
				>".$user_agent."
			</a>";
	$flowjava .="	<script>
				flowplayer(\"a.player\", {src: \"/flowplayer/flowplayer-3.2.7.swf\", wmode: 'opaque' }, {
					clip:  {
                				autoPlay: false,
               					autoBuffering: true,
						scaling: 'orig'
                			}
                		}).ipad(\"a.player\");
			</script>";
	return $flowjava;
}
add_shortcode('video', 'ufandshands_flow_func');
add_shortcode('flv', 'ufandshands_flow_func');



// custom vimeo embed -- disabled 6-25-2011 -- WordPress' own oembed function now supports vimeo
// function orange_and_blue_vimeo_func($atts, $content = null) {
	// extract(shortcode_atts(array(
		// 'foo' => 'something',
		// 'bar' => 'something else',
	// ), $atts));

	// if (preg_match('~^http://(?:www\.)?vimeo\.com/(?:clip:)?(\d+)~', $content, $match)) {
    		// $vimeo_id = $match[1];
	// } else { return "Please use the following format for Vimeo videos: http://vimeo.com/7573098"; }

	// $vimeo_embed = "<object width=\"100%\" height=\"470\"><param name=\"allowfullscreen\" value=\"true\" /><param name=\"allowscriptaccess\" value=\"always\" /><param name=\"movie\" value=\"http://vimeo.com/moogaloop.swf?clip_id=".$vimeo_id."&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=1&amp;color=00ADEF&amp;fullscreen=1&amp;autoplay=0&amp;loop=0\" /><embed src=\"http://vimeo.com/moogaloop.swf?clip_id=".$vimeo_id."&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=1&amp;color=00ADEF&amp;fullscreen=1&amp;autoplay=0&amp;loop=0\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" allowscriptaccess=\"always\" width=\"100%\" height=\"470\"></embed></object>";

	// return $vimeo_embed;
// }
// add_shortcode('vimeo', 'orange_and_blue_vimeo_func');


// split content into two columns

function ufandshands_shortcode_float_left($atts, $content = null) {
	extract(shortcode_atts(array(
                'autop' => '1',
		'foo' => 'something',
		'bar' => 'something else',
	), $atts));

	$content = do_shortcode($content);

	$left_float = "<div class='shortcode_alignleft'>";
        if ($replacelinebreaks=='1')
            $left_float .= wpautop($content);
        else
            $left_float .= $content;
        
	$left_float .= "</div>";

	return $left_float;
}
add_shortcode('left', 'ufandshands_shortcode_float_left');

function ufandshands_shortcode_float_right($atts, $content = null) {
	extract(shortcode_atts(array(
                'autop' => '1',
		'foo' => 'something',
		'bar' => 'something else',
	), $atts));
	$content = do_shortcode($content);

	$right_float = "<div class='shortcode_alignright'>";
        if ($replacelinebreaks=='1')
            $right_float .= wpautop($content);
        else
            $right_float .= $content;
            
	$right_float .= "</div>";
	$right_float .= "<div class='clear'>&nbsp;</div>";

	return $right_float;
}
add_shortcode('right', 'ufandshands_shortcode_float_right');


// google maps shortcode, courtesy of: http://blue-anvil.com/archives/8-fun-useful-shortcode-functions-for-wordpress/ 
// and courtesy of http://www.developer.com/tech/article.php/3615681/Introducing-Googles-Geocoding-Service.htm
// example usage: [googlemap zoom="13" center="52.66389056542801, 0.1641082763671875" marker="52.66389056542801, 0.1641082763671875" width="488px"]

function ufandshands_googlemap_shortcode( $atts ) {
    extract(shortcode_atts(array(
        'width' => '100%',
        'height' => '400px',
        'address' => '',
        'zoom' => '13'
    ), $atts));
 
    $rand = rand(1,100) * rand(1,100);
 
    return '
    	<script src="http://maps.googleapis.com/maps/api/js?sensor=true" type="text/javascript"></script>
 	<div id="map_canvas_'.$rand.'" style="width: '.$width.'; height: '.$height.'"></div>
	    <script type="text/javascript">

                $(document).ready(function () {
                    var geocoder;
                    var map;
                    var address = "'.$address.'";
                    
                    var mapOptions = {
                      zoom: '.$zoom.',
                      mapTypeId: google.maps.MapTypeId.ROADMAP
                    };
                    
                    var map = new google.maps.Map(document.getElementById("map_canvas_'.$rand.'"),
                        mapOptions);
                    
                    geocoder = new google.maps.Geocoder();
                    geocoder.geocode( { "address": address}, function(results, status) {
                          if (status == google.maps.GeocoderStatus.OK) {
                            map.setCenter(results[0].geometry.location);
                            var marker = new google.maps.Marker({
                                title: "",
                                map: map,
                                position: results[0].geometry.location
                            });
                            
                            var infowindow = new google.maps.InfoWindow({
                                content: address
                            });
                            

                            infowindow.open(map, marker);

                          } 
                    });
                    
                });
   		

	</script>
    ';
}
add_shortcode('googlemap', 'ufandshands_googlemap_shortcode');



// google graphs shortcode, courtesy of: http://blue-anvil.com/archives/8-fun-useful-shortcode-functions-for-wordpress/
// example usage: [chart data="41.52,37.79,20.67,0.03" bg="F7F9FA" labels="Reffering+sites|Search+Engines|Direct+traffic|Other" colors="058DC7,50B432,ED561B,EDEF00" size="488x200" title="Traffic Sources" type="pie"]

function ufandshands_chart_shortcode( $atts ) {
	extract(shortcode_atts(array(
	    'data' => '',
	    'colors' => '',
	    'size' => '650x250',
	    'bg' => 'ffffff',
	    'title' => '',
	    'labels' => '',
	    'advanced' => '',
	    'type' => 'pie'
	), $atts));
 
	switch ($type) {
		case 'line' :
			$charttype = 'lc'; break;
		case 'xyline' :
			$charttype = 'lxy'; break;
		case 'sparkline' :
			$charttype = 'ls'; break;
		case 'meter' :
			$charttype = 'gom'; break;
		case 'scatter' :
			$charttype = 's'; break;
		case 'venn' :
			$charttype = 'v'; break;
		case 'pie' :
			$charttype = 'p3'; break;
		case 'pie2d' :
			$charttype = 'p'; break;
		default :
			$charttype = $type;
		break;
	}
 
	if ($title) $string .= '&chtt='.$title.'';
	if ($labels) $string .= '&chl='.$labels.'';
	if ($colors) $string .= '&chco='.$colors.'';
	$string .= '&chs='.$size.'';
	$string .= '&chd=t:'.$data.'';
	$string .= '&chf=bg,s,'.$bg.'';
 
	return '<img title="'.$title.'" src="http://chart.apis.google.com/chart?cht='.$charttype.''.$string.$advanced.'" alt="'.$title.'" />';
}
add_shortcode('chart', 'ufandshands_chart_shortcode');



// insert RSS feed using shortcode
function ufandshands_readRss($atts) {
        
    global $wp_widget_factory;
    
    extract(shortcode_atts(array(
                "widget_name" => 'UFandShands_WP_Widget_RSS',
		"feed" => 'http://',
		"num" => '1',
		"summary" => 'false',
		"date" => 'false',
                "rss_showimage" => 'false'
	), $atts));

    
        if (strtolower($rss_showimage) != 'true' )
        {
            // Get RSS Feed(s)
            include_once(ABSPATH . WPINC . '/feed.php');

            // Get a SimplePie feed object from the specified feed source.
            $rss = fetch_feed($feed);

            if (!is_wp_error( $rss ) ) : // Checks that the object is created correctly 
                    // Figure out how many total items there are, but limit it to num. 
                    $maxitems = $rss->get_item_quantity($num); 

                    // Build an array of all the items, starting with element 0 (first element).
                    $rss_items = $rss->get_items(0, $maxitems); 
            endif;

            $rss_widget_output = "<ul>";

            if ($maxitems == 0) {
                    $rss_widget_output .= '<li>No items.</li>'; }
            else {
                    // Loop through each feed item and display each item as a hyperlink.
                    foreach ( $rss_items as $item ) : 
                    $rss_widget_output .= "<li><a href=\"".$item->get_permalink()."\" title=\"Posted: ".$item->get_date('j F Y | g:i a')."\" >";
                    $rss_widget_output .= $item->get_title();
                    $rss_widget_output .="</a>";
                    if( $date=='true' ){
                            $rss_widget_output .= "<br /><span class='rss-date'>".$item->get_date('F j, Y')."</span>";
                    }
                    if($summary=="true") {
			list($new_string, $elli)= explode("\n", wordwrap(strip_tags($item->get_description()), 200, "\n", false));
			$new_string = ( $elli ) ? $new_string.'...' : $new_string;
                        $rss_widget_output .= "<p>".$new_string."</p>";
                    }

                    $rss_widget_output .= "</li>";
                    endforeach; 
            }

            $rss_widget_output .= "</ul>";

            return $rss_widget_output;
        }
        else 
        {

            $widget_name = wp_specialchars($widget_name);

            if (!is_a($wp_widget_factory->widgets[$widget_name], 'WP_Widget')):

                $wp_class = 'UFandShands_WP_Widget_RSS';

                if (!is_a($wp_widget_factory->widgets[$wp_class], 'WP_Widget')):
                    return '<p>'.sprintf(__("%s: Widget class not found. Make sure this widget exists and the class name is correct"),'<strong>'.'UFandShands_WP_Widget_RSS'.'</strong>').'</p>';
                else:
                    $class = $wp_class;
                endif;
            endif;

                $instance = '&url='.$feed;
                $instance .= '&items='.$num;
                $instance .= '&show_summary='.$summary;
                $instance .= '&show_date='.$date;
                $instance .= '&rss_showimage='.$rss_showimage;
                        // $instance .= '&='.$;	

            ob_start();
                the_widget($widget_name, $instance, array('widget_id'=>'arbitrary-instance-'.$id,
                        'before_widget' => '<div class="widget_body">',
                        'after_widget' => '</div>',
                        'before_title' => '<h3>',
                        'after_title' => '</h3>',

                ));
            $rss_widget_output = ob_get_contents();
            ob_end_clean();
            return $rss_widget_output;
        }
        
}
add_shortcode('rss', 'ufandshands_readRss');


// embed swf shortcode

function ufandshands_shortcode_swf($atts, $content = null) {
    extract(shortcode_atts(array(
	"width" => '100%',
	"height" => '400',
    ), $atts));

	$embed_code = "<object type=\"application/x-shockwave-flash\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0\" data=\"".$content."\" width=\"".$width."\" height=\"".$height."\" style=\"background-color:red;\">
<param name=\"movie\" value=\"".$content."\" />
<param name=\"quality\" value=\"high\"/>
</object>";

	return $embed_code;
}

add_shortcode('swf', 'ufandshands_shortcode_swf');


// insert HTML sitemap (http://wordpress.org/extend/plugins/html-sitemap/)
// adds an HTML (Not XML) sitemap of your blog pages (not posts) by entering the shortcode [html-sitemap].
// example: [html-sitemap depth=4 exclude=24]

function ufandshands_html_sitemap_shortcode_handler( $args, $content = null )
{
	if( is_feed() )
		return '';
		
	$args['echo'] = 0;
	$args['title_li'] = '';
	unset($args['link_before']);
	unset($args['link_after']);
	if( isset($args['child_of']) && $args['child_of'] == 'CURRENT' )
		$args['child_of'] = get_the_ID();
	else if( isset($args['child_of']) && $args['child_of'] == 'PARENT' )
	{
		$post = &get_post( get_the_ID() );
		if( $post->post_parent )
			$args['child_of'] = $post->post_parent;
		else
			unset( $args['child_of'] );
	}
	
	$html = wp_list_pages($args);

	// Remove the classes added by WordPress
	$html = preg_replace('/( class="[^"]+")/is', '', $html);
	return '<ul>'. $html .'</ul>';
}
add_shortcode('html-sitemap', 'ufandshands_html_sitemap_shortcode_handler');


// insert a tag cloud using a short code
function ufandshands_tagcloud_shortcode($atts) {
	if ($atts['format'] != 'columns') {  // render the tag cloud normally
	    extract(shortcode_atts(array(
		"taxonomy" => 'post_tag',
		"num" => '45',
		"format" => 'flat',
		"smallest" => '8',
		"largest" => '22',
		"orderby" => 'name',
		"order" => 'ASC',
		), $atts));

	    $order = strtoupper($order);
	    
	    //ob_start();
	    $tag_cloud = wp_tag_cloud(apply_filters('shortcode_widget_tag_cloud_args', array('taxonomy' => post_tag, 'echo' => false, 'number' => $num, 'format' => $format, 'smallest' => $smallest, 'largest' => $largest, 'orderby' => $orderby, 'order' => $order, "taxonomy" => $taxonomy) ));
	    //$tag_cloud = ob_get_contents();
	    //ob_end_clean();
	
	    return $tag_cloud;
	}
	else { // render the tag in multi-column format
	    return wp_mcTagMap_renderTags($atts);
	}
}
add_shortcode('tagcloud', 'ufandshands_tagcloud_shortcode');



// ** functions for rendering multi-column tag clouds **
function wp_mcTagMap_renderTags($options) {

    extract(shortcode_atts(array(
		"columns" => "4",
		"taxonomy" => 'post_tag',
		"show_empty" => "no",
		    ), $options));

    if ($show_empty == "yes") {
	$show_empty = "0";
    }
    if ($show_empty == "no") {
	$show_empty = "1";
    }


    
    $list = '<!-- begin list --><div id="mcTagMap">';
    $tags = get_terms($taxonomy, 'order=ASC&hide_empty=' . $show_empty . ''); // new code!
    $groups = array();


    if ($tags && is_array($tags)) {
	foreach ($tags as $tag) {
	    $first_letter = strtoupper($tag->name[0]);
	    $groups[$first_letter][] = $tag;
	}
	if (!empty($groups)) {
	    $count = 0;
	    $howmany = count($groups);

	    // this makes 2 columns
	    if ($columns == 2) {
		$firstrow = ceil($howmany * 0.5);
		$secondrow = ceil($howmany * 1);
		$firstrown1 = ceil(($howmany * 0.5) - 1);
		$secondrown1 = ceil(($howmany * 1) - 0);
	    }


	    //this makes 3 columns
	    if ($columns == 3) {
		$firstrow = ceil($howmany * 0.33);
		$secondrow = ceil($howmany * 0.66);
		$firstrown1 = ceil(($howmany * 0.33) - 1);
		$secondrown1 = ceil(($howmany * 0.66) - 1);
	    }

	    //this makes 4 columns
	    if ($columns == 4) {
		$firstrow = ceil($howmany * 0.25);
		$secondrow = ceil(($howmany * 0.5) + 1);
		$firstrown1 = ceil(($howmany * 0.25) - 1);
		$secondrown1 = ceil(($howmany * 0.5) - 0);
		$thirdrow = ceil(($howmany * 0.75) - 0);
		$thirdrow1 = ceil(($howmany * 0.75) - 1);
	    }

	    //this makes 5 columns
	    if ($columns == 5) {
		$firstrow = ceil($howmany * 0.2);
		$firstrown1 = ceil(($howmany * 0.2) - 1);
		$secondrow = ceil(($howmany * 0.4));
		$secondrown1 = ceil(($howmany * 0.4) - 1);
		$thirdrow = ceil(($howmany * 0.6) - 0);
		$thirdrow1 = ceil(($howmany * 0.6) - 1);
		$fourthrow = ceil(($howmany * 0.8) - 0);
		$fourthrow1 = ceil(($howmany * 0.8) - 1);
	    }

	    foreach ($groups as $letter => $tags) {
		if ($columns == 2) {
		    if ($count == 0 || $count == $firstrow || $count == $secondrow) {
			$list .= wp_mcTagMap_renderDivider($count, $firstrow);
		    }
		}
		if ($columns == 3) {
		    if ($count == 0 || $count == $firstrow || $count == $secondrow) {
			$list .= wp_mcTagMap_renderDivider($count, $secondrow);
		    }
		}
		if ($columns == 4) {
		    if ($count == 0 || $count == $firstrow || $count == $secondrow || $count == $thirdrow) {
			$list .= wp_mcTagMap_renderDivider($count, $thirdrow);
		    }
		}
		if ($columns == 5) {
		    if ($count == 0 || $count == $firstrow || $count == $secondrow || $count == $thirdrow || $count == $fourthrow){
			$list .= wp_mcTagMap_renderDivider($count, $fourthrow);
		    }
		}

		$list .= '<div class="tagindex">';
		$list .="\n";
		$list .='<h4>' . apply_filters('the_title', $letter) . '</h4>';
		$list .="\n";
		$list .= '<ul class="links">';
		$list .="\n";
		$i = 0;
		foreach ($tags as $tag) {
		    $url = get_term_link( intval($tag->term_id), $tag->taxonomy );
		    //$url = attribute_escape(get_tag_link($tag->term_id));
		    $name = apply_filters('the_title', $tag->name);
		    //	$name = ucfirst($name);
		    $i++;
		    $counti = $i;
		    
		$list .= '<li><a title="' . $name . '" href="' . $url . '">' . $name . '</a></li>';
		    $list .="\n";
		}

		$list .= '</ul>';
		$list .="\n";
		$list .= '</div>';
		$list .="\n\n";
		if ($columns == 3 || $columns == 2) {
		    if ($count == $firstrown1 || $count == $secondrown1) {
			$list .= "</div>";
		    }
		}
		if ($columns == 4) {
		    if ($count == $firstrown1 || $count == $secondrown1 || $count == $thirdrow1) {
			$list .= "</div>";
		    }
		}
		if ($columns == 5) {
		    if ($count == $firstrown1 || $count == $secondrown1 || $count == $thirdrow1 || $count == $fourthrow1) {
			$list .= "</div>";
		    }
		}

		$count++;
	    }
	}
	$list .="</div>";
	$list .= "<div style='clear: both;'></div></div><!-- end list -->";
    }
    else
	$list .= '<p>Sorry, but no tags were found</p>';

    return $list;
}

function wp_mcTagMap_renderDivider($count, $rowNum) {
    $divider = "";
    if ($count == $rowNum) {
	$divider .= "\n<div class='holdleft noMargin'>\n";
	$divider .="\n";
    } else {
	$divider .= "\n<div class='holdleft'>\n";
	$divider .="\n";
    }
    
    return $divider;
}

// ** end functions for rendering multi-column tag clouds **


/*===============
* New Gallery Shortcode: will help us avoid having to hack at the core for the Gallery shortcode business
* courtesy of: http://coding.smashingmagazine.com/2011/05/26/better-image-management-practices-with-wordpress/
* ...with some slight modifications to the code to match current WP gallery output.
================*/
remove_shortcode('gallery', 'gallery_shortcode');
add_shortcode('gallery', 'ufandshands_gallery_shortcode');

function ufandshands_gallery_shortcode($attr) {
	$post = get_post();

	static $instance = 0;
	$instance++;

	if ( ! empty( $attr['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $attr['orderby'] ) )
			$attr['orderby'] = 'post__in';
		$attr['include'] = $attr['ids'];
	}

	// Allow plugins/themes to override the default gallery template.
	$output = apply_filters('post_gallery', '', $attr);
	if ( $output != '' )
		return $output;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => ''
	), $attr));

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}

	$itemtag = tag_escape($itemtag);
	$captiontag = tag_escape($captiontag);
	$columns = intval($columns);
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$gallery_style = $gallery_div = '';
	if ( apply_filters( 'use_default_gallery_style', true ) )
		$gallery_style = "
		<style type='text/css'>
			#{$selector} {
				margin: auto;
			}
			#{$selector} .gallery-item {
				float: {$float};
				margin-top: 10px;
				text-align: center;
				width: {$itemwidth}%;
			}
			#{$selector} img {
				border: 2px solid #cfcfcf;
			}
			#{$selector} .gallery-caption {
				margin-left: 0;
			}
		</style>
		<!-- see gallery_shortcode() in wp-includes/media.php -->";
	$size_class = sanitize_html_class( $size );
	$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
	$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		$link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link($id, $size, false, false);

		$output .= "<{$itemtag} class='gallery-item'>";
		$output .= "
			<{$icontag} class='gallery-icon'>
				$link
			</{$icontag}>";
		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= "
				<{$captiontag} class='wp-caption-text gallery-caption'>
				" . wptexturize($attachment->post_excerpt) . "
				</{$captiontag}>";
		}
		$output .= "</{$itemtag}>";
		if ( $columns > 0 && ++$i % $columns == 0 )
			$output .= '<br style="clear: both" />';
	}

	$output .= "
			<br style='clear: both;' />
		</div>\n";

	return $output;
}



function do_accordion($atts, $content){
	if(isset($atts['num']))
	{
	    wp_enqueue_script('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js');
		if(isset($atts['tabs']))
		{
			$tabs = $atts['tabs'];
		}
		$cnt_input = $atts['num'];
		if($cnt_input == "1")
		{
			$return ="\n";
			//$return .= "<link href='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css' rel='stylesheet' type='text/css' /><style> .accordion h3 {padding:1px 0px 1px 30px; font-size:14px !important; text-transform:none !important;} .ui-state-active {background:#3D55B7;color:#FFF;}</style>";
      $return .= "<link rel='stylesheet' href='" . get_bloginfo('template_directory') . "/library/css/accordion.css'>\n";
			$return .= "<script>\$(document).ready(function(){\$(\"#accordion" .$cnt_input. "\").accordion({header:'h3', autoHeight:false,collapsible:true,active:false, change: function(event, ui){if(ui.newHeader.val() != null){\$('html,body');}}});});</script>";
			//$return .= "<style>#content {width:650px !important; padding-right:10px;}</style>";
			$return .= "\n";
		}else{
			$return .= "<script>\$(document).ready(function(){\$(\"#accordion" .$cnt_input. "\").accordion({header:'h3', autoHeight:false,collapsible:true,active:false, change: function(event, ui){if(ui.newHeader.val() != null){\$('html,body');}}});});</script><style> #accordion".$cnt_input." h3 {padding:1px 0px 1px 30px; text-transform:none !important;} </style>\n";
		}
		
		if($tabs == 'yes' && isset($atts['subtabs']))
		{

			$sub_tabs = explode(",", $atts['subtabs']);
			$sub_count = count($sub_tabs);
			$return .= "<style>.ui-accordion .ui-accordion-content {padding:1em 1em;}.ui-tabs-nav{margin-left:0px !important;}.ui-tabs .ui-tabs-panel {padding:.5em .5em;}</style><script>jQuery(document).ready(function(){";
			for( $i=0; $i < $sub_count; $i++ )
			{
				$return .= "\$(\"#" .$sub_tabs[$i]. "\").tabs();";
			}
			$return .= "});</script>";
		}
			
		$return .= "<div id='accordion" .$cnt_input. "' class='accordion'> " .$content. "</div>\n";
	}else{
		$return = $content;
	}
	return $return;
}
add_shortcode('accordion', 'do_accordion');




function do_tabs($atts, $content){
    wp_enqueue_script('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js');
    wp_enqueue_script('jquery-tabs', get_bloginfo('template_directory')."/js/tabs.js", null, false, true);
	$return ="\n";
	/*************************************************************************************
		Uncomment below line if you need to include jQueryUI, jQueryUI CSS and jQuery from google API library
	*************************************************************************************/
	$return .= "<link href='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css' rel='stylesheet' type='text/css' />";
	$return .= "<div class='tabs'>".$content."</div>\n";
	return $return;
}

add_shortcode('tabs ', 'do_tabs');
?>