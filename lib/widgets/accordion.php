<?php
/**
 * Create widget interface and frontend output.
 *
 * @since 1.0
 */

class md_accordion_widget extends WP_Widget {

	/**
	 * Create widget attributes and fire any needed actions.
	 *
	 * @since 1.0
	 */

	public function __construct() {
		$this->terms_order = array(
			'count' => __( 'Posts count', 'md' ),
			'slug' => __( 'Slug', 'md' ),
			'id' => __( 'Category ID', 'md' ),
			'term_order' => __( 'Category Order', 'md' )
		);
		parent::__construct( 'md_accordion_widget', __( 'MD &rarr; Accordion Nav', 'md' ), array(
			'description' => __( 'List category links in a highly organized accordion widget.', 'md' ),
			'customize_selective_refresh' => true
		) );
	}

	/**
	 * Frontend template with passed data.
	 *
	 * @since 1.0
	 */

	public function widget( $args, $val ) {
		$c = 1;
		$title = $val['title'];
		$terms = $current = $terms_args = array();
		$page_id = get_queried_object_id();
		$tax = ! empty( $val['taxonomy'] ) ? $val['taxonomy'] : 'category';
		$taxonomy = get_taxonomy( $tax );
		$post_type = $taxonomy->object_type;
		$terms_args['taxonomy'] = $tax;

		if ( ! empty( $val['direction'] ) )
			$terms_args['order'] = 'DESC';

		if ( ! empty( $val['order'] ) )
			$terms_args['orderby'] = esc_attr( $val['order'] );

		if ( ! empty( $val['exclude'] ) )
			$terms_args['exclude'] = esc_html( $val['exclude'] );

		$terms_data = get_terms( $terms_args );

		foreach ( $terms_data as $term_count => $term )
			$terms[$term->term_id] = $term;

		if ( is_tax() || is_singular() ) {
			if ( is_singular() ) {
				$get_terms = get_the_terms( $page_id, $tax );
				$get_terms = $get_terms[0];
			}
			else
				$get_terms = $terms[$page_id];

			if ( ! empty( $get_terms ) ) {
				$current = $terms[$get_terms->term_id];
				unset( $terms[$get_terms->term_id] );
			}
			array_unshift( $terms, $current );
		}

		include( md_template( 'widgets/accordion', true ) );
	}

	/**
	 * Sanitize saved data.
	 *
	 * @since 1.0
	 */

	public function update( $new, $val ) {
		$sanitize = new md_sanitize;
		$val['title'] = $sanitize->text( $new['title'] );
		$val['see_more'] = $sanitize->text( $new['see_more'] );
		$val['taxonomy'] = $sanitize->select( $new['taxonomy'], md_taxonomy_meta() );
		$val['posts_per_category'] = $sanitize->number( $new['posts_per_category'] );
		$val['direction'] = $sanitize->select( $new['direction'], array( 'DESC' ) );
		$val['order'] = $sanitize->select( $new['order'], array_keys( $this->terms_order ) );
		$val['exclude'] = esc_html( $new['exclude'] );
		return $val;
	}

	/**
	 * Build widget form settings.
	 *
	 * @since 1.0
	 */

	public function form( $val ) {
		$val = wp_parse_args( (array) $val, array(
			'title' => '',
			'see_more' => '',
			'taxonomy' => '',
			'posts_per_category' => '',
			'direction' => '',
			'order' => '',
			'exclude' => ''
		) );
		$taxonomies = md_taxonomy_meta();
	?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __( 'Title', 'md' ); ?>:</label><br />
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $val['title'] ); ?>" class="widefat" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'taxonomy' ); ?>"><?php echo __( 'Category type', 'md' ); ?>:</label><br />
			<select id="<?php echo $this->get_field_id( 'taxonomy' ); ?>" name="<?php echo $this->get_field_name( 'taxonomy' ); ?>">
				<option value=""><?php echo __( 'Select category type...', 'md' ); ?></option>
				<?php foreach ( $taxonomies as $count => $tax ) : ?>
					<option value="<?php echo esc_attr( $tax ); ?>"<?php echo selected( $val['taxonomy'], esc_attr( $tax ), false ); ?>><?php echo $tax; ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'direction' ); ?>"><?php echo __( 'List direction', 'md' ); ?>:</label><br />
			<select id="<?php echo $this->get_field_id( 'direction' ); ?>" name="<?php echo $this->get_field_name( 'direction' ); ?>">
				<option value=""><?php echo __( 'Ascending (default)', 'md' ); ?></option>
				<option value="DESC"<?php echo selected( $val['direction'], 'DESC', false ); ?>><?php echo __( 'Descending', 'md' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php echo __( 'Order by', 'md' ); ?>:</label><br />
			<select id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>">
				<option value=""><?php echo __( 'Name (default)', 'md' ); ?></option>
				<?php foreach ( $this->terms_order as $term_slug => $term_name ) : ?>
					<option value="<?php echo esc_attr( $term_slug ); ?>"<?php echo selected( $val['order'], esc_attr( $term_slug ), false ); ?>><?php echo esc_html( $term_name ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'posts_per_category' ); ?>"><?php echo __( 'Posts per category', 'md' ); ?>:</label><br />
			<input type="number" id="<?php echo $this->get_field_id( 'posts_per_category' ); ?>" name="<?php echo $this->get_field_name( 'posts_per_category' ); ?>" value="<?php echo esc_attr( $val['posts_per_category'] ); ?>" class="widefat" placeholder="5" style="width: 25%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'exclude' ); ?>"><?php echo __( 'Exclude', 'md' ); ?>:</label><br />
			<input type="text" id="<?php echo $this->get_field_id( 'exclude' ); ?>" name="<?php echo $this->get_field_name( 'exclude' ); ?>" value="<?php echo esc_attr( $val['exclude'] ); ?>" placeholder="23, 45, 345" class="widefat" />
			<span class="description"><?php echo __( 'Enter categories to exclude by the category ID. Separate IDs by a comma <code>,</code>', 'md' ); ?></span>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'see_more' ); ?>"><?php echo __( 'See more text', 'md' ); ?>:</label><br />
			<input type="text" id="<?php echo $this->get_field_id( 'see_more' ); ?>" name="<?php echo $this->get_field_name( 'see_more' ); ?>" value="<?php echo esc_attr( $val['see_more'] ); ?>" class="widefat" />
			<span class="description" style="padding:0"><?php echo __( '<b>Default:</b> <i>See all {count} {category} &rarr;</i>', 'md' ); ?></span>
		</p>
	<?php }

}
