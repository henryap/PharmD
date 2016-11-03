<?php
  global $ufandshands_sidebar_nav;
  global $ufandshands_sidebar_widgets;
  $ufandshands_sidebar_nav = ufandshands_sidebar_navigation($post);
  $ufandshands_sidebar_widgets = ufandshands_sidebar_detector('page_sidebar', false);
?>

<?php if(!empty($ufandshands_sidebar_nav) || !empty($ufandshands_sidebar_widgets)) :  ?>

  <nav id="sidebar-nav" class="span-6" role="navigation">
	<div class="shadow"></div>
  <?php if(!empty($ufandshands_sidebar_nav)) : ?>
    <ul class="site-nav" <?php if($ufandshands_sidebar_widgets) {echo "style='margin-bottom:25px;'";} ?>>
      <?php echo $ufandshands_sidebar_nav; ?>
    </ul>
  <?php endif ;?>
	
	<?php
    if($ufandshands_sidebar_widgets) {
      echo $ufandshands_sidebar_widgets;
    }
  ?>
	
  </nav>
  
<?php endif; ?>