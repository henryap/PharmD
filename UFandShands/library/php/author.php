<?php 
    $authorname = get_the_author_meta('first_name') . "&nbsp;" . get_the_author_meta('last_name');
    $authortitle = get_the_author_meta('title'); 
    $authorbio = get_the_author_meta('description');
?>

<div class="about-author">
    <h2>About the Author</h2>
        <div class="user-picture">
                <a href="/author/<?php the_author_meta('user_login'); ?>" title="Find more posts by <?php echo $authorname; ?>"><?php echo get_avatar(get_the_author_meta('ID'), 100); ?></a>
        </div>
        <h3><?php echo $authorname; ?></h3>
        <p class="author-title"><?php echo $authortitle; ?></p>
        <p class="author-description"><?php echo wp_trim_words($authorbio, 32); ?></p>
        
        <?php if(get_the_author_meta('profile_link') != ''): ?>
            <p><a href="<?php echo get_the_author_meta('profile_link'); ?>" class="more-link">View profile</a> | 
            <a href="/author/<?php the_author_meta('user_login'); ?>" class="more-link">Find more posts by <?php echo $authorname; ?> &raquo;</a></p>
        <?php else: ?>
            <p><a href="/author/<?php the_author_meta('user_login'); ?>" class="more-link">Find more posts by <?php echo $authorname; ?> &raquo;</a></p>
        <?php endif; ?>
</div>