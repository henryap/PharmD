<?php
  
  $slider_speed = of_get_option("opt_slider_speed") * 1000;
  if (empty($slider_speed)) {
    $slider_speed = 5000;
  }

?>
<script type="text/javascript">
  var sliderSpeed = <?php echo $slider_speed; ?>
</script>
<?php

  $featured_category_id = of_get_option("opt_featured_category");
  $featured_cat_obj = get_category($featured_category_id);
  $featured_cat_number_of_posts = $featured_cat_obj->count;
  
  $slider_number_of_posts = of_get_option("opt_number_of_posts_to_show");
  
  // Checks if user has chosen a selection (theme options) that exceeds number of available posts
  // in the featured category -- if so, falls back valid number.
   
  if($slider_number_of_posts > $featured_cat_number_of_posts) {
    $slider_number_of_posts = $featured_cat_number_of_posts;
  }
  
  $slider_feature_posts = new WP_Query();
  $slider_feature_posts->query("showposts=". $slider_number_of_posts . "&cat=" . $featured_category_id . "");
  $slider_feature_counter = 1;
?>
<div id="slideshow-wrap">
<div id="slideshow">
    <div id="slideshow-reel">
        <?php while ($slider_feature_posts->have_posts()) : $slider_feature_posts->the_post(); ?>
        <?php  

  //     disables full width sliders from being generated
  //     $disable_fullwidthslides = of_get_option('opt_disable_fullwidthslides'); 
	//     echo "<h1>DISABLE: ".$disable_fullwidthslides."</h1>";

      $custom_meta = get_post_custom($post->ID);
			$image_type = $custom_meta['custom_meta_image_type'];
			$image_effect_disabled = $custom_meta['custom_meta_image_effect_disabled'];
			$custom_button_text = $custom_meta['custom_meta_featured_content_button_text'][0]; 
			$disabled_caption = $custom_meta['custom_meta_featured_content_disable_captions'];
			$disable_timeline = of_get_option("opt_featured_content_disable_timeline");
      
      if( class_exists( 'kdMultipleFeaturedImages' ) ) {
        echo "<!-- featured-2-comment -->";
        $mobile_slide_bg = kd_mfi_get_featured_image_url( 'featured-image-2', 'post', 'mobile-full-width' );
      }


      ?>
      
        <!-- Full-Size Image Output -->
        
        <?php if ( isset($image_type)): ?>
          <div class="slide <?php echo 'slide-' . $slider_feature_counter; ?> full-image-feature">
              <?php if ( has_post_thumbnail() ): 
                $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'half-width-thumb'); 
              ?>
                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
                <?php the_post_thumbnail('full-width-thumb'); ?>
                </a>
              <?php endif;?>
				 
              <?php if( class_exists( 'kdMultipleFeaturedImages' ) && !empty($mobile_slide_bg) ) {
                $thumb[0] = $mobile_slide_bg;
                echo '<!-- FEATURE_IMAGE_2 -->';
              }
              ?>
 <!-- Slide Background for repsonsive -->
      <style>
      /* phone ============== */
        #slideshow .slide-<?php echo $slider_feature_counter; ?> {

          /* IE9 SVG, needs conditional override of 'filter' to 'none' */
          background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIxJSIgc3RvcC1jb2xvcj0iIzFlNTc5OSIgc3RvcC1vcGFjaXR5PSIwLjMiLz4KICAgIDxzdG9wIG9mZnNldD0iMTQlIiBzdG9wLWNvbG9yPSIjMWE0Yzg1IiBzdG9wLW9wYWNpdHk9IjAuMyIvPgogICAgPHN0b3Agb2Zmc2V0PSIxMDAlIiBzdG9wLWNvbG9yPSIjMDAwMDAwIiBzdG9wLW9wYWNpdHk9IjAuNzUiLz4KICA8L2xpbmVhckdyYWRpZW50PgogIDxyZWN0IHg9IjAiIHk9IjAiIHdpZHRoPSIxIiBoZWlnaHQ9IjEiIGZpbGw9InVybCgjZ3JhZC11Y2dnLWdlbmVyYXRlZCkiIC8+Cjwvc3ZnPg==), url('<?php echo $thumb[0]; ?>') no-repeat;
          background: url('<?php echo $thumb[0]; ?>') no-repeat;
          background-size:cover;
          background-position: center center;
          }
      /* tablet ============== */
      @media only screen and (min-width: 600px) {
        #slideshow .slide-<?php echo $slider_feature_counter; ?> { 
          background: url('<?php echo $thumb[0]; ?>') no-repeat;
          background-size:cover;
          background-position: center center;
          }
      } /* end tablet */

      /* desktop ============== */
      @media only screen and (min-width: 900px) {
        #slideshow .slide-<?php echo $slider_feature_counter; ?> { background: transparent url(<?php bloginfo('template_url'); ?>/images/bg-half-image-feature.png) 2px 0 no-repeat;}
      } /* end desktop */
      </style>



				<div class="excerpt <?php if(isset($disabled_caption)) : ?>hide-for-large<?php endif ?>">
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
  					<?php echo the_excerpt(); ?>
  					<?php if (!empty($custom_button_text)): ?>
  					  <a class="read-more" href="<?php echo get_permalink(); ?>"><?php echo $custom_button_text; ?></a>
  					<?php endif ?>
			  </div><!-- end .excerpt -->
		
          </div><!-- end .slide -->
          
        <!-- Half-Size Image Output -->
        
        <?php else : ?>
          <div class="slide <?php echo 'slide-' . $slider_feature_counter; ?> half-image-feature <?php if(isset($image_effect_disabled)) {echo 'half-image-style-disabled'; } ?> ">
              <?php if ( has_post_thumbnail() ) : 
                  $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'half-width-thumb');
                  //echo $thumb[0]; // thumbnail url 
                ?>
        
                  <!-- Slide Background for repsonsive -->
                  <style>
                  /* phone ============== */
                    #slideshow .slide-<?php echo $slider_feature_counter; ?> {
                     /* IE9 SVG, needs conditional override of 'filter' to 'none' */
                      background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIxJSIgc3RvcC1jb2xvcj0iIzFlNTc5OSIgc3RvcC1vcGFjaXR5PSIwLjMiLz4KICAgIDxzdG9wIG9mZnNldD0iMTQlIiBzdG9wLWNvbG9yPSIjMWE0Yzg1IiBzdG9wLW9wYWNpdHk9IjAuMyIvPgogICAgPHN0b3Agb2Zmc2V0PSIxMDAlIiBzdG9wLWNvbG9yPSIjMDAwMDAwIiBzdG9wLW9wYWNpdHk9IjAuNzUiLz4KICA8L2xpbmVhckdyYWRpZW50PgogIDxyZWN0IHg9IjAiIHk9IjAiIHdpZHRoPSIxIiBoZWlnaHQ9IjEiIGZpbGw9InVybCgjZ3JhZC11Y2dnLWdlbmVyYXRlZCkiIC8+Cjwvc3ZnPg==), url('<?php echo $thumb[0]; ?>') no-repeat;
                      background: url('<?php echo $thumb[0]; ?>') no-repeat;
                      background-position: center center;
                      background-size: cover;
                      }

                  /* tablet ============== */
                  @media only screen and (min-width: 600px) {
                    #slideshow .slide-<?php echo $slider_feature_counter; ?> { 
                      background: url('<?php echo $thumb[0]; ?>') no-repeat;
                      background-size:cover;
                      background-position: center center;
                      }
                  } /* end tablet */

                  /* desktop ============== */
                  @media only screen and (min-width: 900px) {
                    #slideshow .slide-<?php echo $slider_feature_counter; ?> { background: transparent url(<?php bloginfo('template_url'); ?>/images/bg-half-image-feature.png) 2px 0 no-repeat; }
                  } /* end desktop */
                  </style>


                  <a href="<?php the_permalink(); ?>">
                  <?php the_post_thumbnail('half-width-thumb'); ?>
                  </a>
				<?php endif ?>
				  <div class="excerpt">
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<?php the_excerpt(); ?>
					<?php if (!empty($custom_button_text)): ?>
						<div class="custom-button-wrap">
							<a class="custom-button" href="<?php echo get_permalink(); ?>">
							  <?php echo $custom_button_text; ?><span></span>
							</a>
						</div>
					<?php endif; ?>
				  </div><!-- end .excerpt -->
          </div><!-- end .slide -->
        <?php endif; ?>
          
            <?php $slider_feature_counter++; ?>

        <?php endwhile; ?>
        
    </div><!-- end #slideshow-reel -->
    
    
    <?php if(!($slider_number_of_posts == 1)) : ?>
      <a href="#" id="slideshow-left" class="slideshow-arrow"><span class="hide-for-medium hide-for-large">Prev</span></a>
      <a href="#" id="slideshow-right" class="slideshow-arrow"><span class="hide-for-medium hide-for-large">Next</span></a>
      
	  <?php if(!$disable_timeline): ?>
      <div id="slideshow-nav-wrap">
		  <div id="slideshow-nav">
			<?php $slider_feature_counter = 1; // Reset the counter ?>
			<?php while ($slider_feature_posts->have_posts()) : $slider_feature_posts->the_post(); ?>
			<a href="#" class="nav-item">
				<span class="nav-item-line <?php if($slider_feature_counter == 1) { echo "nav-item-line-hidden"; } ?>"></span>
				<span class="nav-item-dot"></span>
				<span class="nav-item-line <?php if($slider_feature_counter == $slider_number_of_posts) { echo "nav-item-line-hidden"; } ?>"></span>
        <div class="slider-thumb">
          <?php the_post_thumbnail('thumbnail'); ?>
        </div>
			</a>
			
			<?php $slider_feature_counter++; ?>
			
			<?php endwhile; ?>
			
			<span id="active-nav-item"></span>
			
		  </div><!-- end #slideshow-nav -->
      </div>
	  <?php endif; ?>
    <?php endif; ?>
</div><!-- end #slideshow -->
</div><!-- end #slideshow-wrap -->
