<?php
  
  $slider_speed = of_get_option("opt_slider_speed") * 1000;
  if (empty($slider_speed)) {
    $slider_speed = 5000;
  }

?>
<script type="text/javascript">
  var sliderSpeed = <?php echo $slider_speed; ?>
</script>
<div id="featured-area" class="hide-for-small hide-for-medium">
  <div id="s1" class="pics">

  <?php	
    $featured_category_id = of_get_option("opt_featured_category");
  
    $stacker_feature_posts = new WP_Query();
    $stacker_feature_posts->query("showposts=3&cat=" . $featured_category_id . "");
    $stacker_feature_counter = 1;
  ?>
  
  <?php while ($stacker_feature_posts->have_posts()) : $stacker_feature_posts->the_post(); ?>
	
	<?php
	  $custom_meta = get_post_custom($post->ID);
	  $custom_button_text = $custom_meta['custom_meta_featured_content_button_text'][0];
	  $disabled_caption = $custom_meta['custom_meta_featured_content_disable_captions'];
	  $story_stacker_disable_dates = of_get_option("opt_story_stacker_disable_dates");
	?>
	
  <div class="pic-frame">
    <?php 
      if ( has_post_thumbnail() ) {
			  the_post_thumbnail('stacker-thumb');
		  }
		?>
	<?php if(!isset($disabled_caption)) : ?>
		<div class="excerpt">
		  <h3><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>
		  <?php the_excerpt(); ?>
		  <?php if(!empty($custom_button_text)): ?>
			<a class="read-more" href="<?php echo get_permalink(); ?>"><?php echo $custom_button_text; ?></a>
		  <?php endif; ?>
		</div>
	<?php endif ?>
    <a href="<?php echo get_permalink(); ?>"><span class="feat-overlay"></span></a>
  </div>
  
  <?php endwhile; ?>
  
  </div><!-- end .pics -->
  
  <div id="stacker-control">
  	
  <?php $stacker_feature_counter = 1; ?>
  <?php while ($stacker_feature_posts->have_posts()) : $stacker_feature_posts->the_post(); ?>
    
    <div class="featitem <?php if($stacker_feature_counter == 1) { echo " active"; } ?>">
    <div class="stacker-thumb">
    <?php 
      if ( has_post_thumbnail() ) {
			  the_post_thumbnail(array(87,87));
		  }
		?>
    </div>										
		
      <h2><?php the_title(); ?></h2>
	  <?php if(!$story_stacker_disable_dates) : ?>
		<span class="meta"><?php the_time('F j, Y'); ?></span>
	  <?php endif ?>
      <span class="order"><?php echo $stacker_feature_counter; ?></span>
    </div><!-- end .featitem -->
    
    <?php $stacker_feature_counter++; ?>
    
    
  <?php endwhile; ?>
    
  </div><!-- end #stacker-control -->
</div><!-- end featured area -->

<div class="hide-for-large">
  <?php include('feature-slider.php'); ?>
</div>