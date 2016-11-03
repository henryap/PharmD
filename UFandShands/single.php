<?php include("header.php"); ?>

	<?php ufandshands_breadcrumbs(); ?>
	
	<div id="content-wrap">
	  <div id="content-shadow">
		<div id="content" class="container">
		
		  <article id="main-content" class="span-17" role="main">
		  <div class="box">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			  
			  <?php ufandshands_content_title(); //page title ?>
          			
				<div class="single-meta">
                    <?php do_action('ufandshands_single_meta'); ?>
					<p class="published"><span class="black-50">Published: </span><?php the_time('F jS, Y') ?></p>
                                        <?php 
                                            $about_author = of_get_option('opt_about_author');
                                            $about_author_cat = of_get_option('opt_about_author_category');
                                            $authorname = get_the_author_meta('first_name') . "&nbsp;" . get_the_author_meta('last_name');
                                            $category = get_the_category();

                                            if (($about_author) && (in_category($about_author_cat) || $about_author_cat == "All Categories")) { ?>
                                                <p class="author"><span class="black-50">By: </span><a href="/author/<?php the_author_meta('user_login'); ?>"><?php echo $authorname; ?></a></p>
                                            <?php } ?>
					<p class="category"><span class="black-50">Category:</span> <?php the_category(', ') ?></p>
				</div>
			
				<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>
				<?php // wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				
                                <?php 
                                    if (($about_author) && (in_category($about_author_cat) || $about_author_cat == "All Categories")) { include('library/php/author.php'); } 
                                ?>
                      
				<div class="single-meta">
				  <?php the_tags('<p class="tag black-50">Tagged as: ', ', ','</p>'); ?>
				</div>
				<div id="social-content">
					<div><fb:like href="<?php echo get_permalink(); ?>" show_faces="false" layout="button_count" send="true"></fb:like></div>
					<div><a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" >Tweet</a></div>
					<div><g:plusone size="medium"></g:plusone></div>
				</div>
				
			  <div class="single-navigation clear">
  				<div class="nav-previous"><?php previous_post_link('%link','&larr; Older Post') ?></div>
  				<div class="nav-next"><?php next_post_link('%link','Newer Post &rarr;') ?></div>
  			</div>
        
        <?php $comments_count = get_comments_number(); ?>
				<div id="comment-container" class="clear">
					<?php comments_template(); ?>
				</div>					
				
			<?php endwhile; ?>
        
  			
  		<?php endif; ?>
			
		</div>
		</article><!-- end #main-content --> 
		
		
		<?php get_sidebar(post_sidebar); ?>
		
		
		
	  </div>
	</div>
	</div>
<?php include('user-role-menu.php'); ?>
<?php include("footer.php"); ?>