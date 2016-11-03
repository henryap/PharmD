<?php

// Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
  die('Please do not load this page directly. Thanks!');

if (post_password_required()) {
  ?>
  <p class="nocomments">This post is password protected. Enter the password to view comments.</p>
  <?php
  return;
}
?>

<?php if (have_comments()) : // if there are comments ?>

  <div id="comment-wrap" class="clearfix">
    

      <h3 id="comments"><?php comments_number('No Responses', 'One Response', '% Responses'); ?> to &#8220;<?php the_title(); ?>&#8221;</h3>

      <a href="#respond"><span class="continue-reading "><?php comment_form_title('Leave a Comment', 'Leave a Reply to %s'); ?></span></a></p>

    

    <ol class="commentlist">
      <?php wp_list_comments('type=comment&callback=ufandshands_comment'); ?>
    </ol>



    <?php if (!empty($comments_by_type['pings'])) : // if there are pings ?>

      <h3 id="pings">Trackbacks for This Post</h3>

      <ol class="pinglist">
        <?php wp_list_comments('type=pings&callback=ufandshands_list_pings'); ?>
      </ol>



    <?php endif; ?>

    <div class="navigation">
      <div class="alignleft"><?php previous_comments_link(); ?></div>
      <div class="alignright"><?php next_comments_link(); ?></div>
    </div>
  </div>
  <?php
  
  // No commments or closed comments

  if ('closed' == $post->comment_status) : // if the post has comments but comments are now closed 
    ?>

  <h4>Comments are now closed for this article.</h4>

  <?php endif; ?>

<?php else : ?>

  <?php if ('open' == $post->comment_status) : // if comments are open but no comments so far  ?>

  <?php else : // if comments are closed  ?>

  <?php endif; ?>

<?php
endif;


// Commment form

if (comments_open()) :
  ?>

  <div id="respond-wrap" class="clearfix">


    <div id="respond" class="<?php if (is_user_logged_in()) { echo "logged-in" ;} ?> clearfix">
       
        <h3><?php comment_form_title( 'Leave a comment (Gatorlink Required)', 'Leave a comment to %s (Gatorlink Required)' ); ?></h3>
        
      <div class="cancel-comment-reply">
  <?php cancel_comment_reply_link(); ?>
      </div>

      <?php if (get_option('comment_registration') && !is_user_logged_in()) : ?>
        <p>You must be <a href="<?php echo wp_login_url(get_permalink()); ?>">logged in</a> to post a comment.</p>
  <?php else : ?>

        <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

    <?php if (is_user_logged_in()) : ?>

            <p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a><br /><a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account">Log out &raquo;</a></p>

    <?php else : ?>
           <div class="commentform-inputs">
            <p><label for="author"><small>Name <?php if ($req)
        echo "<span class='req'>*</span>"; ?></small></label><input type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" size="22" tabindex="1" <?php if ($req)
        echo "aria-required='true'"; ?> /></p>
            
            <p><label for="email"><small>Mail (will not be published) <?php if ($req)
        echo "<span class='req'>*</span>"; ?></small></label><input type="text" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" size="22" tabindex="2" <?php if ($req)
        echo "aria-required='true'"; ?> /></p>
            
            <p><label for="url"><small>Website</small></label><input type="text" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" size="22" tabindex="3" /></p>
          </div>

    <?php endif; ?>
          <div class="commentform-textarea">
            <?php if (!is_user_logged_in()) : ?><small>Message</small><?php endif; ?>
            <p><textarea name="comment" id="comment" cols="58" rows="10" tabindex="4"></textarea></p>

    <!--<p class="allowed-tags"><small><strong>XHTML:</strong> You can use these tags: <code><?php echo allowed_tags(); ?></code></small></p>-->

          </div>
          <p class="clear"><input name="submit" type="submit" id="submit" tabindex="5" value="Post Comment" />
        <?php comment_id_fields(); ?>
          </p>

    <?php do_action('comment_form', $post->ID); ?>

        </form>

  <?php endif; // If registration required and not logged in  ?>
    </div>
  </div>
<?php endif; // if you delete this the sky will fall on your head  ?>
