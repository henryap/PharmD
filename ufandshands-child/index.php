<?php get_header(); ?>

    <div class="container" role="main">

      <div class="row">

        <div class="col-md-9">

        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

            <div class="page-header">
              <h1><?php the_title(); ?></h1>
            </div>


          <?php the_content(); ?>

        <?php endwhile; else: ?>

          <div class="page-header">
              <h1>Oh no!</h1>
            </div>

            <p>We could not find this page!!!</p>

        <?php endif; ?>

        </div>

        <div id="homepage-right-sidebar" class="col-md-3">

      				<?php if ( dynamic_sidebar( 'page-sidebar') ); ?>
	</div>

      </div>

    </div>

<?php get_footer(); ?>