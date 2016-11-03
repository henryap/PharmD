<?php

	if ( has_post_thumbnail() ) {
		echo "<a href=\"".get_permalink()."\">";
		the_post_thumbnail('thumbnail', array('class' => 'alignleft')); 
		echo "</a>"; }
	else {

								//Get images attached to the post
								$img = 'reset';
								$args = array(
									'post_type' => 'attachment',
									'post_mime_type' => 'image',
									'numberposts' => -1,
									'order' => 'ASC',
									'post_status' => null,
									'post_parent' => $post->ID
								);
								$attachments = get_posts($args);
								if ($attachments) {
									foreach ($attachments as $attachment) {
										$img = wp_get_attachment_thumb_url( $attachment->ID );
								break; } } else {
										$pattern = '/src=[\'"]?([^\'" >]+)[\'" >]/'; 
										preg_match($pattern, $post->post_content, $img_matches); 
										$trimmed_img_matches = trim($img_matches[0], "src=");
										$image_file_extension = end(explode(".", $trimmed_img_matches));
										$chopend_img_matches = substr($trimmed_img_matches, 0, -12);
										
										$edited_image_reg_pattern = '/[0-9][0-9][0-9]x[0-9][0-9][0-9]/';
										if ($c=preg_match_all ($edited_image_reg_pattern, $trimmed_img_matches, $matches))
										  {
											  $edited_image_reg="true";
										  }
									}
								//Display image
								
								if($img!='reset'){ ?>
									<a href="<?php the_permalink() ?>"><img src="<?php echo $img; ?>" class="alignleft" alt="<?php the_title(); ?>" /></a>
								<?php } elseif($edited_image_reg) {

										if(strlen($img_matches[0])>7) { 
											// width of the thumbnails
											$thumbwidth = get_option('thumbnail_size_w');
										 
											//  height of the thumbnails
											$thumbheight = get_option('thumbnail_size_h'); 
											?>
											<a href="<?php the_permalink() ?>"><img class="alignleft" src=<?php echo $chopend_img_matches.$thumbwidth."x".$thumbheight.".$image_file_extension."; ?>" alt="<?php the_title(); ?>" /></a>
<?php } } } ?>						