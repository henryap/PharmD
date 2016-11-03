<?php

class UFCOM_recent_posts extends WP_Widget {
	function UFCOM_recent_posts() {
		$widget_ops = array('classname' => 'widget_ufcom_recent_posts sidebar_widget', 'description' => 'Your most recent posts' );
		$this->WP_Widget('UFCOM_recent_posts', 'Recent Posts', $widget_ops);
	}
    
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);

    	$unique_page_content = get_page_by_title($instance['unique_page_id']);
		global $wp_query;
		$current_page = $wp_query->post->ID;
        
		if ($current_page==$unique_page_content->ID || empty($instance['unique_page_id']) ) {
            
			echo $before_widget;
			$title = empty($instance['title']) ? '' : apply_filters('widget_title', urldecode($instance['title']));
			$numberofposts = empty($instance['numberofposts']) ? '&nbsp;' : apply_filters('widget_numberofposts', $instance['numberofposts']);
			$recent_news_alt_url = empty($instance['recent_news_alt_url']) ? '' : apply_filters('widget_text', $instance['recent_news_alt_url']);
			$showexcerpt = $instance['showexcerpt'] === 'true' || $instance['showexcerpt'] === 'on' ? true : false;
			$showthumbnails = $instance['showthumbnails'] === 'true' || $instance['showthumbnails'] === 'on' ? true : false;
			$showdate = $instance['showdate'] === 'true' || $instance['showdate'] === 'on' ? true : false;
			$showrssicon = $instance['showrssicon'] === 'true' || $instance['showrssicon'] === 'on' ? true : false;
                        $showmore = $instance['showmore'] === 'true' || $instance['showmore'] === 'on' ? true : false;
                        //$showmore_alt_title = empty($instance['showmore_alt_title']) ? '' : apply_filters('widget_text', $instance['showmore_alt_title']);
			
			//$feature_category_id = get_settings('featured_category_id');

			//$COM_featured_cat_id = get_cat_id(get_option(COM_featured_category_id));
			$featured_content_category = of_get_option("opt_featured_category");
			
            if (!empty($instance['specific_category_id'])) {
                // if category is array/multiple 
                if (is_array($instance['specific_category_id'])) {            
                    $specific_category_id = $instance['specific_category_id'];
                    $final_categories = implode(', ', $specific_category_id);
                    //$post_args['cat'] = $final_categories;
                    }
                // if category is single: old style
                if (!is_array($instance['specific_category_id'])) {
                    //echo "<h1>BEFORE: ".$instance['specific_category_id']."</h1>";
                    $specific_category_id = get_cat_id(urldecode($instance['specific_category_id']));
                    $specific_category_id_actual .= get_cat_id(urldecode($instance['specific_category_id']));
                    //echo "<h1>AFTER:".$specific_category_id.":</h1>";
                    $final_categories = $specific_category_id_actual;
                    //$specific_category_id_actual .= get_cat_id($instance['specific_category_id']);
                    }
                    
			}
                        //if (!$cats = $instance['cats']) $cats ='';
                        
			if ($showrssicon=="on"){
                            $iconpath = get_bloginfo('template_url') . '/images/rss.png';
                            $showrssiconimage = "<a href='".get_bloginfo('rss2_url')."'><img class='rss-icon' src='" . $iconpath . "' class='rss_icon' alt='Subscribe to RSS Feed'/></a> ";
			}

			if (empty($recent_news_alt_url)) {
				//Check if Single category is selected
                //Old method for storing single specific category
                if (!is_array($instance['specific_category_id'])){
                    $recent_news_link = esc_url(strip_tags(get_category_link(get_cat_id($instance['specific_category_id']))));
                } else {
                    if (is_array($instance['specific_category_id'])) { 
                        if (count($instance['specific_category_id']) === 1) {
                            $first_cat = $instance['specific_category_id']; 
                            $recent_news_link = get_category_link($first_cat[0]);    
                        } else {
                            //URL for multiple categories?
                            //Currently defaults back to /posts for multiple 
                        }
                    }
                }

                //$recent_news_link = esc_url(strip_tags(get_category_link(get_cat_id($instance['cats']))));

                //If 'All Categories' is selected (or Categories left blank), link the title to the default 'All Categories' archive page (/posts)
                if (empty($recent_news_link)) {
                        $posts_path = of_get_option('opt_custom_posts_path');
                            if (!empty($posts_path)) {
                                $recent_news_link = "<a href='{$posts_path}'>";
                            } else {
                                $recent_news_link = "<a href='/posts'>";
                            }
                } else {
                	$recent_news_link = "<a href='{$recent_news_link}'>";
                }
            } else {
            	//Use custom Title URL
                $recent_news_link_alt = esc_url(strip_tags($recent_news_alt_url));
                $recent_news_link = "<a href='{$recent_news_link_alt}'>";
            }

            if (!empty($recent_news_link)) {
                $recent_news_link_a = "</a>";
            } else {
            	$recent_news_link_a = '';
            }

            //if (empty($title)) {
            	//If single category is selected with Title field empty, use category name as the fallback title
                //$title = get_cat_name(get_cat_id($instance['cats']));
                //$title = ucwords($title);

                //Otherwise default to Recent News
                if (empty($title)) {
                	$title = 'Recent News';
                }
            //}
            
            //if (empty($showmore_alt_title)) {
                //If no alternative "more" title text is set, just show "more"
            //    $showmore_alt_title = 'More';
            //}    
    
            echo $before_title . $recent_news_link . $title . $recent_news_link_a . $showrssiconimage . $after_title;

            
			// if ( !empty( $title ) ) {
			// 	echo $before_title . '<a href="http://google.com">' . $title . '</a>' . $showrssiconimage . $after_title;
			// }

			//$recentPosts = new WP_Query();
            //$post_args = array( 'showposts' => $numberofposts, 'post_status' => 'publish');
			
            //do not put featured content if on the homepage
            $post_args = array( 'showposts' => $numberofposts, 'post_status' => 'publish', 'cat' => $final_categories);
            if (is_front_page()) { 
                $post_args['category__not_in'] = $featured_content_category;
            }
            
            // this gets old-style single category and children recent posts items
            // if (!empty($specific_category_id) && !is_array($specific_category_id)) {
            //     $post_args['cat'] = $specific_category_id_actual;    
            // }

            // is using multiple categories, thus an array 
            // if (!empty($specific_category_id) && is_array($specific_category_id)) {
            //     $all_cats = implode(', ', $specific_category_id);
            //     $post_args['cat'] = $all_cats;    
                   
            // }
            $recentPosts = new WP_Query( apply_filters( 'widget_posts_args', $post_args ) );
			while ($recentPosts->have_posts()) :
                $recentPosts->the_post();
				
                global $post;
                
                $margin = '';
               // print_r($specific_category_id);
                echo "<div id='recent-posts' class='news-announcements'><div class='item'>";
                if ($showthumbnails) {
                    if((ufandshands_post_thumbnail('thumbnail', 'alignleft', 130, 100))) {
                        $margin = "margin-160";
                    }
                }
                echo "<h4><a href=\"".get_permalink()."\" title=\"" . get_the_excerpt() . "\">".get_the_title()."</a></h4>";
                if ($showdate){ echo "<p class='time {$margin}'>".get_the_time('M jS, Y')."</p>"; }
                if ($showexcerpt) { echo "<p>".get_the_excerpt()."</p>"; }
                //if ($showthumbnails){ echo "<div style=\"clear:right;\"></div>"; } -- disabled -- not sure if needed in new UF&Shands template
                // echo "<div class=\"recent_post_container_bottom_border\"></div>"; -- disabled -- not sure if needed in new UF&Shands template
                
                echo "</div></div>";
			endwhile;

			wp_reset_query();
			
                        //$showmore_alt_title removed
                        //if ($showmore) { echo "<div class='more'>&raquo; {$recent_news_link} {$showmore_alt_title} </a></div>"; }
                        if ($showmore) { echo "<div class='more'>&raquo; {$recent_news_link} more</a></div>"; }
                        
                        echo $after_widget;

		} else {
            return false;
    	}
	}
    
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['recent_news_alt_url'] = strip_tags($new_instance['recent_news_alt_url']);
		$instance['numberofposts'] = strip_tags($new_instance['numberofposts']);
		$instance['showexcerpt'] = $new_instance['showexcerpt'];
		$instance['showthumbnails'] = $new_instance['showthumbnails'];
		$instance['showdate'] = $new_instance['showdate'];
		$instance['showrssicon'] = $new_instance['showrssicon'];
                $instance['showmore'] = $new_instance['showmore'];
                //$instance['showmore_alt_title'] = strip_tags($new_instance['showmore_alt_title']);

		$instance['unique_page_id'] = $new_instance['unique_page_id'];

		$instance['specific_category_id'] = $new_instance['specific_category_id'];

		return $instance;
	}
    
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => 'Recent News', 'numberofposts' => '5', 'showexcerpt' => 'on', 'showthumbnails' => true, 'showdate' => true, 'showrssicon' => true, 'showmore' => 'on', 'unique_page_id' => '', 'specific_category_id' => '') );
		$title = strip_tags($instance['title']);
		$recent_news_alt_url = strip_tags($instance['recent_news_alt_url']);
		$numberofposts = strip_tags($instance['numberofposts']);
		$showexcerpt = $instance['showexcerpt'];
		$showthumbnails = $instance['showthumbnails'];
		$showdate = $instance['showdate'];
		$showrssicon = $instance['showrssicon'];
                $showmore = $instance['showmore'];
                //$showmore_alt_title = strip_tags($instance['showmore_alt_title']);

		$unique_page_id = $instance['unique_page_id'];

		$specific_category_id = $instance['specific_category_id'];

?>

			<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>

			<p><label for="<?php echo $this->get_field_id('numberofposts'); ?>">Number of posts: <input class="widefat" id="<?php echo $this->get_field_id('numberofposts'); ?>" name="<?php echo $this->get_field_name('numberofposts'); ?>" type="text" value="<?php echo attribute_escape($numberofposts); ?>" /></label></p>
			
			<p><label for="<?php echo $this->get_field_id('recent_news_alt_url'); ?>">Alternate URL for clickable Title: <input class="widefat" id="<?php echo $this->get_field_id('recent_news_alt_url'); ?>" name="<?php echo $this->get_field_name('recent_news_alt_url'); ?>" type="text" value="<?php echo attribute_escape($recent_news_alt_url); ?>" /></label></p>
			
			<p><input class="checkbox" type="checkbox" <?php checked( $instance['showdate'], 'on' ); ?> id="<?php echo $this->get_field_id( 'showdate' ); ?>" name="<?php echo $this->get_field_name( 'showdate' ); ?>" /> &nbsp; <label for="<?php echo $this->get_field_id( 'showdate' ); ?>">Show post dates?</label></p>

			<p><input class="checkbox" type="checkbox" <?php checked( $instance['showexcerpt'], 'on' ); ?> id="<?php echo $this->get_field_id( 'showexcerpt' ); ?>" name="<?php echo $this->get_field_name( 'showexcerpt' ); ?>" /> &nbsp; <label for="<?php echo $this->get_field_id( 'showexcerpt' ); ?>">Show post excerpt?</label></p>

			<p><input class="checkbox" type="checkbox" <?php checked( $instance['showthumbnails'], 'on' ); ?> id="<?php echo $this->get_field_id( 'showthumbnails' ); ?>" name="<?php echo $this->get_field_name( 'showthumbnails' ); ?>" /> &nbsp; <label for="<?php echo $this->get_field_id( 'showthumbnails' ); ?>">Show post thumbnails?</label></p>

			<p><input class="checkbox" type="checkbox" <?php checked( $instance['showrssicon'], 'on' ); ?> id="<?php echo $this->get_field_id( 'showrssicon' ); ?>" name="<?php echo $this->get_field_name( 'showrssicon' ); ?>" /> &nbsp; <label for="<?php echo $this->get_field_id( 'showrssicon' ); ?>">Show RSS icon next to title?</label></p>

                        <p><input class="checkbox" type="checkbox" <?php checked( $instance['showmore'], 'on' ); ?> id="<?php echo $this->get_field_id( 'showmore' ); ?>" name="<?php echo $this->get_field_name( 'showmore' ); ?>" /> &nbsp; <label for="<?php echo $this->get_field_id( 'showmore' ); ?>">Show "MORE" link at bottom?</label></p>
                        
                        <!--<p><label for="<?php //echo $this->get_field_id('showmore_alt_title'); ?>">Alternate Title for "more" link: <input class="widefat" id="<?php //echo $this->get_field_id('showmore_alt_title'); ?>" name="<?php //echo $this->get_field_name('showmore_alt_title'); ?>" type="text" value="<?php //echo attribute_escape($showmore_alt_title); ?>" /></label></p>-->
                        
                        <p><label for="<?php echo $this->get_field_id( 'specific_category_id' ); ?>">From the category:</label>

	<?php 
        $categories = get_categories('hide_empty=0&orderby=name');
        //commented out as this variable is assigned higher up in the file
        
        if (!is_array($instance['specific_category_id'])) { $specific_category_id = array(0 => get_cat_id(urldecode($instance['specific_category_id']))); }
            echo '<select id="' . $this->get_field_id('specific_category_id') . '" name="' . $this->get_field_name('specific_category_id') . '[]" data-placeholder="Select the category..." class="chosen" multiple style="width:220px;">';
                foreach ($categories as $cat) {

                if (isset($cat->name) && isset($cat->term_id)) {
                    if ($specific_category_id) 
                        //if (!is_array($specific_category_id))
                          //  echo '<option value="' . $cat->term_id . '" ' . (in_array($cat->term_id, $specific_category_id) ? 'selected' : '') . '>' . $cat->name .'-Specific: '. $specific_category_id.'</option>'; 
                        //else-if (is_array($specific_category_id))
                            echo '<option value="' . $cat->term_id . '" ' . (in_array($cat->term_id, $specific_category_id) ? 'selected' : '') . '>' . $cat->name .'</option>'; 
                        //}
                        
                    else
                        echo '<option value="' . $cat->term_id . '" >' . $cat->name . '</option>'; 
                    }
                }
	    echo '</select></p>';
        ?>



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
<?php if(!is_array($instance['specific_category_id'])) {} ?>
<?php if ($this->number != '__i__') { ?>
	<script type="text/javascript">
	    jQuery(document).ready(function(){
                    jQuery("#<?php echo $this->get_field_id('specific_category_id'); ?>").chosen();
		    jQuery(".chzn-container").parents('.widget').css('overflow', 'visible');
		 });
	</script>                        
                        
<?php
	}
    }
}

register_widget( 'UFCOM_recent_posts' );

?>