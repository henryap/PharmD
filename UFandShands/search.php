<?php get_header(); ?>

<?php ufandshands_breadcrumbs(); ?>

<div id="content-wrap">
    <div id="content-shadow">
	<div id="content" class="container">

	    <article id="main-content" class="span-24" role="main">
		<div class="box">
		<?php 
        $useGoogleSearch = of_get_option('opt_google_site_search');
        $useGoogleCSE = of_get_option('opt_google_cse');
        if (!$useGoogleCSE && !$useGoogleSearch) {
            if (have_posts()) {
                // Retrieve search count
                $allsearch = &new WP_Query("s=$s&showposts=-1");
                
                $key = wp_specialchars($s, 1);
                $count = $allsearch->post_count;
                wp_reset_query();
	?>

	<h1 class="title medium-blue">Search Results for <span class="light-blue">&ldquo;</span><strong class="dark-blue"><?php the_search_query(); ?></strong><span class="light-blue">&rdquo;</span></h1>
<!-- 	<h4 class="black-75"><?php
                if ($count == '1') {
                    echo ' ' . $count . ' result was found';
                } else {
                    echo ' ' . $count . ' results were found';
                }
	?></h4> -->

	<?php while (have_posts()) :
              the_post(); ?>
	    <?php
              // Set Loop variables
              $currenttemplate = get_post_meta($post->ID, '_wp_page_template', true);
              $ip = $_SERVER['REMOTE_ADDR'];
              $members_only = ufandshands_members_only();
	    ?>

	    <div class="entry">

		<?php
              if ($currenttemplate == "membersonly.php") :
                  if ($members_only) :
			?>

		        <!-- Members Only -->
			<?php
                      if (function_exists("ufandshands_post_thumbnail")) {
                          ufandshands_post_thumbnail('thumbnail', 'alignleft', 130, 100);
                      }
			?>
		        <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
		        <p class="published"><span class="black-50">Published: <?php the_time('M jS, Y') ?></span></p>
			<?php the_excerpt(); ?>

		<?php else : ?>

		        <!-- Non-Members -->
		        <p>This document can only be seen by users inside the UF/Shands network.</p>

		    <?php endif; ?>

		<?php else : ?>

		    <!-- Non Members-Only Templates -->	
		    <?php
                  if (function_exists("ufandshands_post_thumbnail")) {
                      ufandshands_post_thumbnail('thumbnail', 'alignleft', 130, 100);
                  }
		    ?>

		    <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>  
		    <p class="published"><span class="black-50">Published: <?php the_time('M jS, Y') ?></span></p>
		<?php the_excerpt() ?>

		</div><!-- end .entry -->

	    <?php endif; ?>

	<?php endwhile; ?>

	<?php
                if (function_exists("ufandshands_pagination")) {
                    ufandshands_pagination($additional_loop->max_num_pages);
                } else {
                    echo '<div class="single-navigation clear">
		<div class="nav-previous">' . previous_posts_link('&larr; Newer Entries') . '</div>
		<div class="nav-next">' . next_posts_link('Older Entries &rarr;') . '</div>
		</div>';
                }
            } else {
                echo '<h2>No results found. Try a different search?</h2>';
            }
        } else {
			GoogleSearchResult();
        }
		?>

		</div> <!-- end box div -->
	    </article><!-- end #main-content --> 
	</div>
    </div>
</div>
<?php include('user-role-menu.php'); ?>
<?php include("footer.php"); ?>

<?php


function GoogleSearchResult() {
    
    $useGoogleSearch = of_get_option('opt_google_site_search');
    $useGoogleCSE = of_get_option('opt_google_cse');
    $googleCSEID = of_get_option('opt_google_cse_id');
    
	if (function_exists('domain_mapping_siteurl')) 
		$domain = domain_mapping_siteurl(null);
	else
		$domain = get_bloginfo('url');
	
    if ($useGoogleCSE)
        $controlInit = 'var customSearchOptions = {};  var customSearchControl = new google.search.CustomSearchControl(
      \'' . $googleCSEID . '\');';
    else
        $controlInit = 'var customSearchOptions = {};  var customSearchControl = new google.search.CustomSearchControl({crefUrl:\''. $domain . '/wp-content/themes/UFandShands/cref_xml.php\'});';
    
    echo '<div id="cse" style="width: 100%;">Loading</div>
<script src="http://www.google.com/jsapi" type="text/javascript"></script>
<script type="text/javascript"> 
  google.load(\'search\', \'1\', {language : \'en\', style : google.loader.themes.V2_DEFAULT});
  google.setOnLoadCallback(function() {
    '. $controlInit . '
    customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);
        var options = new google.search.DrawOptions();
	options.setAutoComplete(true);' . ($useGoogleCSE ? 'customSearchControl.setAutoCompletionId(\'' . $googleCSEID . '+qptype:1\');' : '') . '
    customSearchControl.draw(\'cse\', options);
    function parseParamsFromUrl() {
      var params = {};
      var parts = window.location.search.substr(1).split(\'\\x26\');
      for (var i = 0; i < parts.length; i++) {
        var keyValuePair = parts[i].split(\'=\');
        var key = decodeURIComponent(keyValuePair[0]);
        params[key] = keyValuePair[1] ?
            decodeURIComponent(keyValuePair[1].replace(/\\+/g, \' \')) :
            keyValuePair[1];
      }
      return params;
    }

    var urlParams = parseParamsFromUrl();
    var queryParamName = "s";
    if (urlParams[queryParamName]) {
      customSearchControl.execute(urlParams[queryParamName]);
    }
  }, true);
</script>

<style type="text/css">
  #cse div.gs-visibleUrl.gs-visibleUrl-short { display: none; }
  #cse div.gs-visibleUrl.gs-visibleUrl-long { display: block;}

</style>

 ';
}
?>