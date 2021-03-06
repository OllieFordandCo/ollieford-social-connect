<?php 

/**
 * Adds Foo_Widget widget.
 */
class OF_Twitter_Timeline extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'of_twitter_timeline', // Base ID
			__('Ollie Ford & Co Twitter Timeline', 'text_domain'), // Name
			array( 'classname'   => 'of_twitter_timeline', 'description' => __( 'Display your Twitter timeline', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		
		$title = apply_filters( 'widget_title', $instance['title'] );
		$no_tweets = $instance['no_tweets'];
    	$default_screen_name = $instance['default_screen_name'];

		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];

		if( $tweets = OF_Social_Connect::retrieve_tweets($default_screen_name, $no_tweets) ) :

			$user_template = locate_template( 'social/twitter-widget.php' );
				
			if (!empty( $user_template )) :
					  
				include(locate_template( 'social/twitter-widget.php' ));
				
			else :			
			
				include( plugin_dir_path( __FILE__ ) . 'templates/widget-timeline.php' );
				
			endif;
		
		else :
		
			//echo 'Please, authorise your twitter account before retrieving tweets.';
		
		endif;
		
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$of_twitter_api = get_option('of_twitter_api');
		
		if(!empty($of_twitter_api['default_screen_name'])) :
			$default_screen_name = $of_twitter_api['default_screen_name'];
		else :
			$default_screen_name = 'olliefordandco';
		endif;
		
		
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Twitter Timeline', 'text_domain' );
		}
		$default_screen_name = isset($instance[ 'default_screen_name' ]) ? $instance[ 'default_screen_name' ] : $default_screen_name;
		$no_tweets = isset($instance[ 'no_tweets' ]) ? $instance[ 'no_tweets' ] : 4;
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'default_screen_name' ); ?>"><?php _e( 'Screenname:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'default_screen_name' ); ?>" name="<?php echo $this->get_field_name( 'default_screen_name' ); ?>" type="text" value="<?php echo esc_attr( $default_screen_name ); ?>">
		</p>  
		<p>
		<label for="<?php echo $this->get_field_id( 'no_tweets' ); ?>"><?php _e( 'Number of Tweets:' ); ?></label> 
		<select class="widefat" id="<?php echo $this->get_field_id( 'no_tweets' ); ?>" name="<?php echo $this->get_field_name( 'no_tweets' ); ?>">      	
            <option value="1"<?php echo ($no_tweets == 1) ? ' selected': ''; ?>>1</option>
            <option value="2"<?php echo ($no_tweets == 2) ? ' selected': ''; ?>>2</option>
            <option value="3"<?php echo ($no_tweets == 3) ? ' selected': ''; ?>>3</option>
            <option value="4"<?php echo ($no_tweets == 4) ? ' selected': ''; ?>>4</option>
            <option value="5"<?php echo ($no_tweets == 5) ? ' selected': ''; ?>>5</option>
            <option value="6"<?php echo ($no_tweets == 6) ? ' selected': ''; ?>>6</option>
        </select>
		</p>              
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['default_screen_name'] = ( ! empty( $new_instance['default_screen_name'] ) ) ? strip_tags( $new_instance['default_screen_name'] ) : '';
		$instance['no_tweets'] = ( ! empty( $new_instance['no_tweets'] ) ) ? strip_tags( $new_instance['no_tweets'] ) : '';

		return $instance;
	}

} // class Foo_Widget