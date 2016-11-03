<div class="ribbon" id="ribbon-user-role-menu"> 
  <?php if(has_nav_menu('rolebased_nav')) : ?> 
  <nav id="user-role" role="navigation">
  	<div class="container-5 clearfix">

    <?php 
      wp_nav_menu( array(
        'theme_location' => 'rolebased_nav',
        'container' => false,
        'depth' => 2,
        'walker' => new ufandshands_rolebased_walker
      ));
    ?>
    </div>
  </nav>
  <?php endif; ?>
</div><!-- end #ribbon-user-role-menu -->
