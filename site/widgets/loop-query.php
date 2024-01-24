<?php
/**
 * Register email form widget.
 *
 * @since 5.6
 */

class md_loop_query_widget extends WP_Widget {

	/**
 	 * Assign widget constructors and run Widget actions.
 	 *
 	 * @since 5.6
 	 */

	public function __construct() {
		parent::__construct( 'md_loop_query_widget', __( 'MD &rarr; Loop Query', 'md' ), array(
			'description' => __( 'Display posts from your own Loop Queries in a widget.', 'md' ),
			'customize_selective_refresh' => true
		) );
	}

	/**
 	 * Get Query data in various formats.
 	 *
 	 * @since 5.6
 	 */

	public function get_queries( $sort ) {
		$queries = array();
		$post_types = array_keys( get_post_types( array( 'public' => true ) ) );

		foreach ( $post_types as $post_type ) {
			$post_type_query = md_setting( array( $post_type, 'loop', 'query' ), array() );

			foreach ( $post_type_query as $query_id => $query_fields ) {
				$query_id = "{$post_type}_{$query_id}";

				if ( $sort == 'ids' )
					$queries[] = $query_id;
				elseif ( $sort == 'options' )
					$queries[$post_type][$query_id]['name'] = isset( $query_fields['name'] ) ? $query_fields['name'] : __( 'Untitled', 'md' );
			}
		}

		return $queries;
	}

	/**
 	 * Render Widget HTML on the frontend.
 	 *
 	 * @since 5.6
 	 */

	public function widget( $args, $val ) {
		echo $args['before_widget'];

		if ( $val['title'] )
			echo $args['before_title'] . md_text_field( $val['title'] ) . $args['after_title'];

		if ( $val['description'] )
			echo '<div class="description">' . wpautop( $val['description'] ) . '</div>';

		if ( $val['query'] ) {
			$query_attrs = explode( '_', $val['query'] );
			$loop = md_setting( array( $query_attrs[0], 'loop', 'query', $query_attrs[1] ), array() );
			$loop['is_query'] = true;
			$loop['is_inline'] = true;

			include( md_template( 'loop/query', true ) );
		}

		echo $args['after_widget'];
	}

	/**
 	 * Sanitize data on save.
 	 *
 	 * @since 5.6
 	 */

	public function update( $new, $val ) {
		$sanitize = new md_sanitize;

		foreach ( array( 'title', 'description' ) as $text )
			$val[$text] = $sanitize->text( $new[$text] );

		$val['query'] = $sanitize->select( $new['query'], $this->get_queries( 'ids' ) );

		return $val;
	}

	/**
 	 * Build Widget Admin Form.
 	 *
 	 * @since 5.6
 	 */

	public function form( $val ) {
		$val = wp_parse_args( (array) $val, array(
			'title' => '',
			'description' => '',
			'query' => ''
		) );
		$queries = $this->get_queries( 'options' );
	?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __( 'Title', 'md' ); ?>:</label>

			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $val['title'] ); ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php echo __( 'Description', 'md' ); ?>:</label>

			<textarea id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>" class="widefat" rows="4"><?php echo esc_textarea( $val['description'] ); ?></textarea>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'query' ); ?>"><?php echo __( 'Select Query', 'md' ); ?>:</label><br />

			<select id="<?php echo $this->get_field_id( 'query' ); ?>" name="<?php echo $this->get_field_name( 'query' ); ?>" style="max-width: 100%;">
				<option value=""><?php echo __( 'Select a query', 'md' ); ?></option>
				<?php foreach ( $queries as $post_type => $post_type_fields ) : ?>
					<optgroup label="<?php echo ucwords( esc_html( $post_type ) ); ?>">
					<?php foreach ( $post_type_fields as $query_id => $query_fields ) : ?>
						<option value="<?php echo esc_attr( $query_id ); ?>"<?php echo selected( $val['query'], $query_id, false ); ?>><?php esc_html_e( $query_fields['name'] ); ?></option>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</select>
		</p>

	<?php }
}