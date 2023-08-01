<?php
/**
 * Create and store loops settings data.
 *
 * @since 5.1
 */

class md_loop extends md_api {

	/**
	 * Include related files.
	 *
	 * @since 5.6
	 */

	public function includes() {
		include_once( 'loop-functions.php' );
	}

	/**
	 * Create meta box and terms.
	 *
	 * @since 5.0
	 */

	public function register() {
		$this->_id = 'md_loop';
		$this->name = __( 'Loop', 'md' );
		$save = new md_sanitize;
		$fields = $this->fields();

		return array(
			'term' => array(
				'name' => $this->name,
				'page_settings' => true,
				'fields' => $fields
			),
			'admin_page' => array(
				'name' => $this->name,
				'parent' => 'design',
				'fields' => $fields
			)
		);
	}

	/**
	 * Register fields for save.
	 *
	 * @since 5.1
	 * @moved 5.6
	 */

	public function fields() {
		$cta_ids = array();
		$cta = md_setting( array( 'cta', 'forms' ) );

		if ( ! empty( $cta ) )
			foreach ( $cta as $cta_id => $cta_fields )
				$cta_ids[] = $cta_id;

		return array(
			'archives' => array(
				'type' => 'select',
				'options' => md_loops( 'ids' )
			),
			'featured' => array( 'type' => 'number' ),
			'columns' => array( 'type' => 'number' ),
			'byline' => array(
				'type' => 'checkbox',
				'options' => md_byline_items( 'ids' )
			),
			'byline_position' => array(
				'type' => 'select',
				'options' => array( 'before_headline', 'after_headline' )
			),
			'content' => array(
				'type' => 'select',
				'options' => array( 'excerpt', 'hide' )
			),
			'excerpt_length' => array( 'type' => 'number' ),
			'excerpt_more' => array( 'type' => 'text' ),
			'read_more' => array( 'type' => 'text' ),
			'pagination' => array(
				'type' => 'select',
				'options' => array( 'page_numbers', 'prev_next' )
			),
			'previous_label' => array( 'type' => 'text' ),
			'next_label' => array( 'type' => 'text' ),
			'cta_x_loop' => array( 'type' => 'number' ),
			'x_cta' => array(
				'type' => 'select',
				'options' => $cta_ids
			)
		);
	}

	/**
	 * Add fields to terms interface.
	 *
	 * @since 5.1
	 */

	public function term() {
		echo "<div class=\"md-$this->_clean_id md-tab-content\">";
		$this->admin_template();
		echo '</div>';
	}

	/**
	 * Add settings template and script to Page Settings sections.
	 *
	 * @since 5.6
	 */

	public function admin_fields() {
		$screen = get_current_screen();
		$page = isset( $_GET['page'] ) ? esc_attr( $_GET['page'] ) : '';
		$screen_base = ! empty( $page ) ? $page : $screen->base;
		do_action( "md_layout_{$screen_base}_before_settings" );
	?>
		<div class="md-widget md-toggle md-sep-small">
			<h3 class="md-widget-title"><?php echo esc_html( $this->name ); ?></h3>
			<div class="md-widget-item">
				<?php $this->admin_template(); ?>
			</div>
		</div>
	<?php do_action( "md_layout_{$screen_base}_after_settings" ); }

	/**
	 * Call template with required data passed down.
	 *
	 * @since 5.1
	 */

	public function admin_template() {
		$cta_options = array();
		$cta = md_setting( array( 'cta', 'forms' ) );
		$archives_loop = $this->fields->module( 'archives' );

		if ( ! empty( $cta ) )
			foreach ( $cta as $cta_id => $cta_fields )
				$cta_options[$cta_id] = ! empty( $cta_fields['name'] ) ? $cta_fields['name'] : __( 'Untitled', 'md' );

		include( 'loop-settings.php' );

		$this->admin_script();
	}

	/**
	 * Admin scripts for Content settings.
	 *
	 * @since 5.1
	 */

	public function admin_script() {
		$prefix = $this->_prefix();
	?>
		<script>
			document.getElementById( '<?php echo "{$prefix}_archives"; ?>' ).onchange = function( e ) {
				document.getElementById( 'content_loop_teasers' ).style.display = this.value == 'teasers' ? 'block' : 'none';
			}
		</script>
	<?php }

}

new md_loop;
