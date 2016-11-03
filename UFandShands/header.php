<?php 
	if(isset($_POST['submit'])){
		if($_POST['name']=='' && $_POST['first_name']!=='First Name*'){
		//create array of data to be posted
			$first_name = $_POST['first_name'];
			$last_name = $_POST['last_name'];
			$email = $_POST['email'];
			$phone = $_POST['phone'];
			$program_type = $_POST['program_type'];

  			header('Location: http://marketing.apollidon.com/l/63232/2015-04-13/292st?first_name='.$first_name.'&last_name='.$last_name.'&email='.$email.'&phone='.$phone.'&program_type='.$program_type);
  			}else{
  				header('Location: http://pharmacyelectives.pharmacy.ufl.edu//contact-thank-you/');
  			}
 		 }
?><?php session_start(); ?>
<!DOCTYPE html class="js">
<!--[if lt IE 7]> <html class="no-js ie6" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<?php 
	$custom_meta = get_post_custom($post->ID);
	if (isset($custom_meta['custom_meta_noindex'][0])) {
	 echo '<meta name="robots" content="noindex" />';
	}
	?>
<?php wp_head(); ?>

<?php 
	//banner background overrides
	ufandshands_bannercolor(); 
	ufandshands_bannerbg();	
	ufandshands_formcolor();	
?>

</head>
<body <?php body_class($class); ?>>

<div class="overlay"></div><!--end overlay-->
<div class="modal_form">
		<div class="main-banner-form">
			<img id="close_modal" src="<?php echo get_stylesheet_directory_uri(); ?>/library/images/closeBtn.png">
			<?php ufandshands_bannerform(); ?>
	</div>	
</div>

<?php 
    if (isset($custom_meta['custom_meta_custom_js_body_top'][0])) {
	echo $custom_meta['custom_meta_custom_js_body_top'][0];
    }
    
?>

<?php include('library/php/menu-responsive.php'); //offcanvuas logic ?>

<div id ="whole_page" role="main">
<ul class="screen-reader-text">
  <li><a href="http://assistive.usablenet.com/tt/<?php bloginfo('url'); ?>" accesskey="t" title="Text-only version of this website" >Text-only version of this website</a></li>
  <li><a href="#content" accesskey="s" title="Skip navigation">Skip navigation</a></li>
  <li><a href="/" accesskey="1" title="Home page">Home page</a></li>
  <li><a href="#recent-posts" accesskey="2" title="what's new">What's new</a></li>
  <li><a href="#searchform" accesskey="4" title="Search">Search</a></li>
  <li><a href="<?php ufandshands_contact_webmaster_link() ?>" accesskey="6" title="Contact Webmaster">Contact Webmaster</a></li>
  <li><a href="#footer-links" accesskey="8" title="Website policies">Website policies</a></li>
  <li><a href="http://www.ufl.edu/disability/" accesskey="0" title="Disability services">Disability services</a></li>
</ul>

<?php 	
	// turns off institutional global elements
	$disabled_global_elements = of_get_option('opt_disable_global_elements'); 
?>
<div class="ribbon hide-for-large" id="ribbon-responsive-top">
  <div id="responsive-top" class="container">
	<a href="#" id="responsive-nav-toggle" class="hide-for-large" ><img src="<?php bloginfo('template_url'); ?>/images/sb_btn.png" width="27" height="26" alt="Menu"/></a>
	<?php if (!$disabled_global_elements) : ?><a href="https://ufhealth.org" id="responsive-home"><img src="<?php bloginfo('template_url'); ?>/images/ufhealth-badge-responsive.png" width="202" height="76" alt="UF Health Home" /></a> <!-- ufhealth-badge.png --><?php endif ?>
	<!-- utility-banner-small-screen.png -->
	<a id="responsive-search-toggle" href="#" class="hide-for-large" ><img src="<?php bloginfo('template_url'); ?>/images/search-button-responsive.png" width="27" height="26" alt="Search"/></a>
  </div><!-- end #responsive-top -->
</div><!-- end #ribbon-responsive-top -->

	<?php

	if (!$disabled_global_elements) { ?>
		<div class="ribbon hide-for-small hide-for-medium" id="ribbon-institutional-nav">
	<?php include('library/php/ufandshands-institutional-nav.php'); ?>
			</div><!-- end #ribbon-institutional-nav -->
	<?php } ?>

<div id="ribbon-responsive-search" class="ribbon hide-for-large">
	<?php
	get_search_form();
	?>
</div><!--end ribbon-responsive-search -->
<header role="banner" class="ribbon" id="ribbon-header">
    <div class="container">

	<!-- begin website title logic -->
<?php ufandshands_site_title(); ?>
	<!-- end website title logic -->

<?php if (!$disabled_global_elements) : ?>
  		<ul id="header-social" class="hide-for-medium">
  		    <li><a href="<?php ufandshands_get_socialnetwork_url("facebook"); ?>" class="facebook ir">Facebook</a></li>
  		    <li><a href="<?php ufandshands_get_socialnetwork_url("youtube"); ?>" class="youtube ir">YouTube</a></li>
  		    <li><a href="<?php ufandshands_get_socialnetwork_url("twitter"); ?>" class="twitter ir">Twitter</a></li>
  		</ul>
<?php endif ?>
	<div id="header-search-wrap" class="hide-for-medium">
<?php if (has_nav_menu('header_links')) { //detects if the header_links menu is being used ?>
		    <nav id="utility-links" class="black-25 span-7half" role="navigation">
			<ul><?php wp_nav_menu(array('theme_location' => 'header_links', 'container' => false)); ?></ul>
		    </nav>
<?php } ?>
	    <div id="searchform-wrap">
	    <?php
		get_search_form();
	    ?>
	    </div> 
	</div><!-- end header-search-wrap -->

<?php
// orange header action item box

$actionitem_text = of_get_option(opt_actionitem_text);
$actionitem_url = of_get_option(opt_actionitem_url);
// checks for a 0, if 0 then puts mobile item In page, else puts it in mobile nav there is another function that puts this in the menu-responsive-nav file
if (of_get_option(opt_actionitem_mobile_location) == '0') { $actionitem_location_css ="hide-for-small hide-for-medium"; } 

if (!empty($actionitem_text))  {
	//echo "<h1>".of_get_option(opt_actionitem_mobile_location)."</h1>";
  echo "<a class='".$actionitem_location_css."' id='header-actionitem' href='" . $actionitem_url . "'>" . $actionitem_text . "</a>";
}
?>

    </div><!-- end header .container -->
</header><!-- end #ribbon-header -->

<div class="ribbon hide-for-large" id="ribbon-primary-nav-responsive">
&nbsp;	
</div><!-- end #ribbon-primary-nav-responsive -->

<div class="ribbon" id="ribbon-primary-nav">
	<?php include('library/php/menu.php'); //menu logic ?>
</div>
