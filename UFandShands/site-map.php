<?php include("header.php"); ?>

<?php ufandshands_breadcrumbs(); ?>

<div id="content-wrap">
    <div id="content-shadow">
        <div id="content" class="container">
            
            <?php $currenttemplate = get_post_meta($post->ID, '_wp_page_template', true); //members only template check ?>

            <?php if (($currenttemplate != "membersonly.php") || ( ($currenttemplate == "membersonly.php") && ufandshands_members_only() )) { //members only logic?>
                
                <article id="main-content" class="span-23 box" role="main">
                    <h1>Sitemap</h1>
                    <?php
                        if (function_exists('ufandshands_html_sitemap_shortcode_handler')) {
                            $args = Array();
                            echo ufandshands_html_sitemap_shortcode_handler($args);
                        }
                    ?>
                   
                </article>

<?php } ?>
        </div>
    </div>
</div>
<?php include('user-role-menu.php'); ?>
<?php include("footer.php"); ?>

