<?php 
date_default_timezone_set('America/New_York');
echo '<?xml version="1.0" encoding="UTF-8" ?>';
require( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
global $blog_id;

	 $sites = of_get_option('opt_sites_to_search');

	 if (isset($sites) && !empty($sites)) {
	     $sites = split(PHP_EOL, $sites);
	 } else {
	     $sites = array();
	 }

?>

<GoogleCustomizations>
    <CustomSearchEngine creator="<?php echo GOOGLE_CSE ?>" id="ahc_web_search_<?php echo $blog_id; ?>">
        <Title><?php echo get_bloginfo('title'); ?></Title>
        <Context>
           <BackgroundLabels>
		<Label name="ahc_websites" mode="FILTER" />
          </BackgroundLabels>
        </Context>
        <LookAndFeel nonprofit="true">
        </LookAndFeel>
    </CustomSearchEngine>
   <Annotations>
   <?php 
   if (function_exists('domain_mapping_siteurl')) 
	   $homeurl = domain_mapping_siteurl(null);
   else
	   $homeurl = get_bloginfo('url');
   ?>
   
	<Annotation about="<?php echo $homeurl ?>/*">
	    <Label name="ahc_websites" />
	</Annotation>
    <?php 
	$counter = 0;
	
	if (isset($sites) && count($sites) > 0) {
	    foreach ($sites as $site) {
		$s = trim($site);

		if (!empty($s)) {
		    $counter++;
		    $s = (preg_match('|/$|', $s) > 0 ? $s : $s . '/');
		    if ($s != $homeurl . '/') {
			    echo '<Annotation about="' . $s . '*">
				<Label name="ahc_websites" />
				</Annotation>' . PHP_EOL ;
		    }
		}
	    }
	
	}
    ?>
  </Annotations>
</GoogleCustomizations>
