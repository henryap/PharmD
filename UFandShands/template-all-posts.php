<?php include("header.php"); ?>

	<?php //ufandshands_breadcrumbs(); ?>
	
	<div id="content-wrap">
	  <div id="content-shadow">
		<div id="content" class="container">
		
		  <article name ="content" id="main-content" class="span-24" role="main">
                    <div class="archive box">
                    <?php 
                        $opt_custom_posts_title = of_get_option('opt_custom_posts_title'); 
                        if (!empty($opt_custom_posts_title)) : ?>
                            <h1><?php echo $opt_custom_posts_title; ?></h1>
                        <?php else: ?>
                            <h1>All Posts</h1>  
                    <?php endif; ?>

    <?php
        $featured_content_category = of_get_option("opt_featured_category");
        $post_args = array( 'category__not_in' => $featured_content_category, 'paged' => get_query_var( 'paged') ); 
    
        $paged = (get_query_var('page')) ? get_query_var('page') : 1;
        $the_query = new WP_Query( $post_args );

        if ($the_query->have_posts()) :
        while ( $the_query->have_posts() ) :
	$the_query->the_post();
    ?>

         <div class="archive entry">

         
         <?php if (ufandshands_post_thumbnail('thumbnail', 'alignleft', 130, 100)) {
                        $margin = "margin-160";
                    } ?>   
         <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

         <!-- Display the date (November 16th, 2009 format) and a link to other posts by this posts author. -->
         <p class="published"><span class="black-50">Published: <?php the_time('F jS, Y') ?></p>
         <?php the_excerpt(); ?> 
         </div><!-- end .entry -->
         
         <?php endwhile; ?>
         
         <?php 
            if (function_exists("ufandshands_pagination")) {
            ufandshands_pagination($the_query->max_num_pages);
            }
         ?>
         
         <?php else: ?>
         <p>Sorry, no posts found.</p>
         <?php endif; ?>             
	
        </div> 
        </article><!-- end #main-content --> 
      
	    </div>
	  </div>
	</div>
<?php include('user-role-menu.php'); ?>
<?php include("footer.php"); ?>