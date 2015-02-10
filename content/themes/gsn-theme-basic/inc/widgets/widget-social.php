<?php

/**
 * Social  Widget
 * gsnbasic Theme
 */
class gsnbasic_social_widget extends WP_Widget
{
	 function gsnbasic_social_widget(){

        $widget_ops = array('classname' => 'gsnbasic-social','description' => __( "gsnbasic Social Widget" ,'gsnbasic') );
		    $this->WP_Widget('gsnbasic-social', __('gsnbasic Social Widget','gsnbasic'), $widget_ops);
    }

    function widget($args , $instance) {
    	extract($args);
        $title = ($instance['title']) ? $instance['title'] : __('Follow us' , 'gsnbasic');

      echo $before_widget;
      echo $before_title;
      echo $title;
      echo $after_title;

		/**
		 * Widget Content
		 */
    ?>

    <!-- social icons -->
    <div class="social-icons sticky-sidebar-social">


    <?php gsnbasic_social(); ?>


    </div><!-- end social icons -->


		<?php

		echo $after_widget;
    }


    function form($instance) {
      if(!isset($instance['title'])) $instance['title'] = __('Follow us' , 'gsnbasic');
    ?>

      <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title ','gsnbasic') ?></label>

      <input type="text" value="<?php echo esc_attr($instance['title']); ?>"
                          name="<?php echo $this->get_field_name('title'); ?>"
                          id="<?php $this->get_field_id('title'); ?>"
                          class="widefat" />
      </p>

    	<?php
    }

}

?>