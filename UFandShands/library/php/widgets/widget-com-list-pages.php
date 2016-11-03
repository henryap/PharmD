<?php

class COM_widget_pages extends WP_Widget {

	function COM_widget_pages() {

		$widget_ops = array('classname' => 'widget_pages', 'description' => __( 'Your blog&#8217;s WordPress Pages') );

		$this->WP_Widget('pages', __('Pages'), $widget_ops);

	}

	function widget( $args, $instance ) {

		extract( $args );

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Pages' ) : $instance['title']);

		$sortby = empty( $instance['sortby'] ) ? 'menu_order' : $instance['sortby'];

		$exclude = empty( $instance['exclude'] ) ? '' : $instance['exclude'];

		

		if ($sortby == 'post_date') { $showdate = "created"; }

		

		if ( $sortby == 'menu_order' )

			$sortby = 'menu_order, post_title, post_date';

		$out = wp_list_pages( apply_filters('widget_pages_args', array('title_li' => '', 'echo' => 0, 'sort_column' => $sortby, 'exclude' => $exclude, 'show_date' => $showdate ) ) );

		if ( !empty( $out ) ) {

			echo $before_widget;

			if ( $title)

				echo $before_title . $title . $after_title;

		?>

		<ul>

			<?php echo $out; ?>

		</ul>

		<?php

			echo $after_widget;

		}

	}

	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);

		if ( in_array( $new_instance['sortby'], array( 'post_title', 'menu_order', 'post_date', 'ID' ) ) ) {

			$instance['sortby'] = $new_instance['sortby'];

		} else {

			$instance['sortby'] = 'menu_order';

		}

		$instance['exclude'] = strip_tags( $new_instance['exclude'] );

		return $instance;

	}

	function form( $instance ) {

		//Defaults

		$instance = wp_parse_args( (array) $instance, array( 'sortby' => 'post_title', 'title' => '', 'exclude' => '') );

		$title = esc_attr( $instance['title'] );

		$exclude = esc_attr( $instance['exclude'] );

	?>

		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p>

			<label for="<?php echo $this->get_field_id('sortby'); ?>"><?php _e( 'Sort by:' ); ?></label>

			<select name="<?php echo $this->get_field_name('sortby'); ?>" id="<?php echo $this->get_field_id('sortby'); ?>" class="widefat">

				<option value="post_title"<?php selected( $instance['sortby'], 'post_title' ); ?>><?php _e('Page title'); ?></option>

				<option value="menu_order"<?php selected( $instance['sortby'], 'menu_order' ); ?>><?php _e('Page order'); ?></option>

				<option value="post_date"<?php selected( $instance['sortby'], 'post_date' ); ?>><?php _e('Page Date'); ?></option>

				<option value="ID"<?php selected( $instance['sortby'], 'ID' ); ?>><?php _e( 'Page ID' ); ?></option>

			</select>

		</p>

		<p>

			<label for="<?php echo $this->get_field_id('exclude'); ?>"><?php _e( 'Exclude:' ); ?></label> <input type="text" value="<?php echo $exclude; ?>" name="<?php echo $this->get_field_name('exclude'); ?>" id="<?php echo $this->get_field_id('exclude'); ?>" class="widefat" />

			<br />

			<small><?php _e( 'Page IDs, separated by commas.' ); ?></small>

		</p>

<?php

	}

}
register_widget('COM_widget_pages');

?>