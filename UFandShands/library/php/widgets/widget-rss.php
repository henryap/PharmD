<?php

class UFandShands_WP_Widget_RSS extends WP_Widget {

    function UFandShands_WP_Widget_RSS() {
        $widget_ops = array('classname' => 'rss', 'description' => 'Insert an RSS feed from another website');
        $this->WP_Widget('rss', 'RSS v2.0', $widget_ops);
    }

    function widget($args, $instance) {
        extract($args, EXTR_SKIP);

        $unique_page_content = get_page_by_title($instance['unique_page_id']);
        global $wp_query;
        $current_page = $wp_query->post->ID;

        if ($current_page == $unique_page_content->ID || empty($instance['unique_page_id'])) {
            
            $url = empty($instance['url']) ? '' : apply_filters('widget_text', $instance['url']);
            $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
            $items = empty($instance['items']) ? '5' : apply_filters('widget_text', $instance['items']);
            $rss_alt_url = empty($instance['rss_alt_url']) ? '' : apply_filters('widget_text', $instance['rss_alt_url']);
            $rss_showimage = isset($instance['rss_showimage']) ? $instance['rss_showimage'] : false;
            $show_summary = isset($instance['show_summary']) ? $instance['show_summary'] : false;
            $show_date = isset($instance['show_date']) ? $instance['show_date'] : false;
            $rss_icon = isset($instance['rss_icon']) ? $instance['rss_icon'] : false;
            $rss_order_alphabetically = isset($instance['rss_order_alphabetically']) ? $instance['rss_order_alphabetically'] : false;
            
            // if both rss url and alternate url is empty then exit
            if (empty($url) && empty($rss_alt_url))
                return;
            
            echo $before_widget;

            if (!empty($rss_icon) && strtolower($rss_icon) != 'false') {
                $iconpath = get_bloginfo('template_url') . '/images/rss.png';
                $showrssiconimage = "<a title='Subscribe to this RSS Feed' href='{$url}'><img class='rss-icon' src='" . $iconpath . "' class='rss_icon' alt='Subscribe to this RSS Feed'/></a> ";
            };


            // Get RSS Feed(s)
            include_once(ABSPATH . WPINC . '/feed.php');

            // Get a SimplePie feed object from the specified feed source.
            // $rss = fetch_feed($url);
			// $url_array = explode(",",$url);
			
			$url = preg_replace('( )','',$url); // strip spaces
			
			$url_array = split(",",$url); // turn comma delimited string of feeds into array

                        if (count($url_array) > 1)
                            $rss = fetch_feed($url_array);
                        else
                            $rss = fetch_feed($url);

            if (!is_wp_error($rss)) : // Checks that the object is created correctly 
                // Figure out how many total items there are, but limit it to num. 
                $maxitems = $rss->get_item_quantity($items);

                // Build an array of all the items, starting with element 0 (first element).
                $rss_items = $rss->get_items(0, $maxitems);

            //sort($rss_items, STRING);
            else: 
                echo '<p>There has been an error when trying to get data from the RSS feed.</p>';
                echo $after_widget;
                return;
            endif;


            if (empty($rss_alt_url)) {
                $rss_link = esc_url(strip_tags($rss->get_permalink()));
                $rss_link = "<a href='{$rss_link}'>";
            } else {
                $rss_link = esc_url(strip_tags($rss_alt_url));
                $rss_link = "<a href='{$rss_link}'>";
            }
            if (!empty($rss_link)) {
                $rss_link_a = "</a>";
            }

            if (empty($title)) {
                $title = $rss->get_title();
            }

            echo $before_title . $rss_link . $title . $rss_link_a . $showrssiconimage . $after_title;


            if ($maxitems == 0) {
                $rss_widget_output = '<p>Well this is embarassing -- your RSS feed is empty.</p>';
            } else {
                
                if ($rss_order_alphabetically) 
                    usort($rss_items, 'sort_rss_posts');
                
                // Loop through each feed item and display each item as a hyperlink.
                foreach ($rss_items as $item) :

                    // find images in the content of each item in the feed
                    if (!empty($rss_showimage) && strtolower($rss_showimage) != 'false') {
                        $pattern = '/src=[\'"]?([^\'" >]+)[\'" >]/';
                        preg_match($pattern, $item->get_content(), $img_matches);
                        $trimmed_img_matches = trim($img_matches[0], "src=");
                    }

                    $rss_widget_output .= "<div class='news-announcements'><div class='item'>";

                    $margin = ''; //reset margin value

                    if (!empty($trimmed_img_matches) && strtolower($trimmed_img_matches) != 'false') {
                        $margin = 'margin-160';
                        $rss_widget_output .= "<a title='{$item->get_title()}' href='{$item->get_permalink()}'><img width='130px' class='alignleft' src={$trimmed_img_matches} alt='{$item->get_title()}' /></a>";
                    }

					$rss_widget_output .= "<h4><a href='" . $item->get_permalink() . "' title='" . htmlspecialchars($item->get_description(), ENT_QUOTES ) . "' >";
                    $rss_widget_output .= $item->get_title();
                    $rss_widget_output .="</a></h4>";
                    if (!empty($show_date) && strtolower($show_date) != 'false') {
                        $rss_widget_output .= "<p class='time {$margin}'>" . $item->get_date('F j, Y') . "</p>";
                    }
		    
                    if (!empty($show_summary) && strtolower($show_summary) != 'false') {
			list($new_string, $elli)= explode("\n", wordwrap(strip_tags($item->get_description()), 200, "\n", false));
			$new_string = ( $elli ) ? $new_string.'...' : $new_string;
			
                        $rss_widget_output .= "<p>" . $new_string . "</p>";
                    }

                    $rss_widget_output .= "</div></div>";
                endforeach;
            }

            echo $rss_widget_output;



            echo $after_widget;
        }
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['url'] = strip_tags($new_instance['url']);
        $instance['items'] = strip_tags($new_instance['items']);
        $instance['rss_alt_url'] = strip_tags($new_instance['rss_alt_url']);
        $instance['unique_page_id'] = $new_instance['unique_page_id'];

        $instance['rss_showimage'] = $new_instance['rss_showimage'];
        $instance['show_summary'] = $new_instance['show_summary'];
        $instance['show_date'] = $new_instance['show_date'];
        $instance['rss_icon'] = $new_instance['rss_icon'];
        $instance['rss_order_alphabetically'] = $new_instance['rss_order_alphabetically'];
        return $instance;
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('title' => '', 'url' => '', 'items' => '5', 'rss_alt_url' => '', 'rss_showimage' => '', 'rss_icon' => '', 'show_summary' => '', 'show_date' => '', 'unique_page_id' => ''));
        $title = strip_tags($instance['title']);

        $url = strip_tags($instance['url']);
        $items = strip_tags($instance['items']);
        $rss_alt_url = strip_tags($instance['rss_alt_url']);

        $rss_showimage = $instance['rss_showimage'];
        $show_summary = $instance['show_summary'];
        if ($show_summary == 1)
            $show_summary = 'on';

        $show_date = $instance['show_date'];
        if ($show_date == 1)
            $show_date = 'on';

        $rss_icon = $instance['rss_icon'];
        $rss_order_alphabetically = $instance['rss_order_alphabetically'];

        $unique_page_id = $instance['unique_page_id'];
        ?>

        <p><label for="<?php echo $this->get_field_id('url'); ?>">URL to RSS feed: <input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo attribute_escape($url); ?>" /></label></p>

        <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>

        <p><label for="<?php echo $this->get_field_id('rss_alt_url'); ?>">Alternate URL for clickable Title: <input class="widefat" id="<?php echo $this->get_field_id('rss_alt_url'); ?>" name="<?php echo $this->get_field_name('rss_alt_url'); ?>" type="text" value="<?php echo attribute_escape($rss_alt_url); ?>" /></label></p>

        <p><label for="<?php echo $this->get_field_id('items'); ?>">Amount of posts to display <input class="widefat" id="<?php echo $this->get_field_id('items'); ?>" name="<?php echo $this->get_field_name('items'); ?>" type="text" value="<?php echo attribute_escape($items); ?>" /></label></p>

        <p><input class="checkbox" type="checkbox" <?php checked($instance['rss_showimage'], 'on'); ?> id="<?php echo $this->get_field_id('rss_showimage'); ?>" name="<?php echo $this->get_field_name('rss_showimage'); ?>" /> &nbsp; <label for="<?php echo $this->get_field_id('rss_showimage'); ?>">Show post thumbnails?</label></p>

        <p><input class="checkbox" type="checkbox" <?php checked($show_summary, 'on'); ?> id="<?php echo $this->get_field_id('show_summary'); ?>" name="<?php echo $this->get_field_name('show_summary'); ?>" /> &nbsp; <label for="<?php echo $this->get_field_id('show_summary'); ?>">Show summary/excerpt of post?</label></p>

        <p><input class="checkbox" type="checkbox" <?php checked($show_date, 'on'); ?> id="<?php echo $this->get_field_id('show_date'); ?>" name="<?php echo $this->get_field_name('show_date'); ?>" /> &nbsp; <label for="<?php echo $this->get_field_id('show_date'); ?>">Show post published date?</label></p>

        <p><input class="checkbox" type="checkbox" <?php checked($instance['rss_icon'], 'on'); ?> id="<?php echo $this->get_field_id('rss_icon'); ?>" name="<?php echo $this->get_field_name('rss_icon'); ?>" /> &nbsp; <label for="<?php echo $this->get_field_id('rss_icon'); ?>">Show RSS icon next to title?</label></p>

        <p><input class="checkbox" type="checkbox" <?php checked($instance['rss_order_alphabetically'], 'on'); ?> id="<?php echo $this->get_field_id('rss_order_alphabetically'); ?>" name="<?php echo $this->get_field_name('rss_order_alphabetically'); ?>" /> &nbsp; <label for="<?php echo $this->get_field_id('rss_order_alphabetically'); ?>">Order Alphabetically?</label></p>

        <p>
            <label for="<?php echo $this->get_field_id('unique_page_id'); ?>">Display only on page:</label>
            <select id="<?php echo $this->get_field_id('unique_page_id'); ?>" name="<?php echo $this->get_field_name('unique_page_id'); ?>" class="widefat" style="width:100%;">
                <option value="">
                <?php echo attribute_escape(__('All pages')); ?></option> 
                <?php
                $pages = get_pages();
                foreach ($pages as $pagg) {
                    $title = $pagg->post_title;
                    $option = '<option ';
                    $option .= 'value="' . htmlspecialchars($title) . '" ';
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


function sort_rss_posts($a, $b) {
    return strcmp($a->get_title(), $b->get_title());
}

register_widget('UFandShands_WP_Widget_RSS');
?>
