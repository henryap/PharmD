<?php require( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' ); ?>

<?php 
    do_action('access_denied');
    include("header.php"); 
?>

	<?php ufandshands_breadcrumbs(); ?>
	
	<div id="content-wrap">
	  <div id="content-shadow">
		<div id="content" class="container">
		
		  <article name ="content" id="main-content" class="span-24" role="main">
		    <div class="box">
  		  <img src="<?php bloginfo('template_url'); ?>/images/404.jpg" class="alignright" alt="404 Error - Page Not Found" />
  		  <h1>403 Forbidden</h1>
  		  <h2 class="medium-blue">You do not have permission to access this page</h2>
        </div>
		  </article><!-- end #main-content -->
		  
	  </div>
	</div>
	</div>
<?php include('user-role-menu.php'); ?>
<?php include("footer.php"); ?>
