<ul class="featured-slider-responsive">
  <li>

  </li>
  
</ul><!-- end featured-slider-responsive -->


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
      $custom_meta = get_post_custom($post->ID);
      $image_type = $custom_meta['custom_meta_image_type'];
      $image_effect_disabled = $custom_meta['custom_meta_image_effect_disabled'];
      $custom_button_text = $custom_meta['custom_meta_featured_content_button_text'][0]; 
      $disabled_caption = $custom_meta['custom_meta_featured_content_disable_captions'];
      $disable_timeline = of_get_option("opt_featured_content_disable_timeline");
        ?>
        
        <!-- Full-Size Image Output -->
        
        <?php if (isset($image_type)) : ?>
          <div class="slide <?php echo 'slide-' . $slider_feature_counter; ?> full-image-feature">
              <?php 
                if ( has_post_thumbnail() ) {
                  the_post_thumbnail('full-width-thumb');
                }
              ?>         
        <?php if(!isset($disabled_caption)) : ?>
          <div class="excerpt">
          <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
          <?php echo the_excerpt(); ?>
          <?php if (!empty($custom_button_text)): ?>
            <a class="read-more" href="<?php echo get_permalink(); ?>"><?php echo $custom_button_text; ?></a>
          <?php endif ?>
          </div><!-- end .excerpt -->
        <?php endif ?>
          </div><!-- end .slide -->
          
        <!-- Half-Size Image Output -->
        
        <?php else : ?>
          <div class="slide <?php echo 'slide-' . $slider_feature_counter; ?> half-image-feature <?php if(isset($image_effect_disabled)) {echo 'half-image-style-disabled'; } ?> ">
              <?php if ( has_post_thumbnail() ) : ?>
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
          <?php endif ?>
          </div><!-- end .excerpt -->
          </div><!-- end .slide -->
        <?php endif; ?>
          
            <?php $slider_feature_counter++; ?>

        <?php endwhile; ?>
        
    </div><!-- end #slideshow-reel -->
    
    
    <?php if(!($slider_number_of_posts == 1)) : ?>
      <a href="#" id="slideshow-left" class="slideshow-arrow"></a>
      <a href="#" id="slideshow-right" class="slideshow-arrow"></a>
      
    <?php if(!$disable_timeline): ?>
      <div id="slideshow-nav-wrap">
      <div id="slideshow-nav">
      <?php $slider_feature_counter = 1; // Reset the counter ?>
      <?php while ($slider_feature_posts->have_posts()) : $slider_feature_posts->the_post(); ?>
      <a href="#" class="nav-item">
        <span class="nav-item-line <?php if($slider_feature_counter == 1) { echo "nav-item-line-hidden"; } ?>"></span>
        <span class="nav-item-dot"></span>
        <span class="nav-item-line <?php if($slider_feature_counter == $slider_number_of_posts) { echo "nav-item-line-hidden"; } ?>"></span>
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
