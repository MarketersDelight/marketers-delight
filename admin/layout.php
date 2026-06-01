<?php
/**
 * Build layout options that appear across post and term meta,
 * and admin settings with relationships.
 *
 * @since 4.7
 */

class md_layout extends md_api {

	public $name;
	private $page_types;

	/**
	 * Register admin page, meta box, and term interfaces
	 * with accepted fields.
	 *
	 * @since 5.0
	 */

	public function register() {
		$this->name = __( 'Layout', 'md' );
		$this->page_types = array(
			'archive' => __( 'Archive', 'md' ),
			'term' => __( 'Categories', 'md' ),
			'single' => __( 'Single', 'md' )
		);

		return array(
			'admin_page' => array(
				'name' => $this->name,
				'parent_group' => 'page_settings',
				'fields' => $this->fields()
			),
			'taxonomy' => array(
				'name' => $this->name,
				'fields' => $this->fields()
			),
			'meta_box' => array(
				'name' => $this->name,
				'child_of' => 'page_settings',
				'fields' => $this->fields()
			),
			'term' => array(
				'name' => $this->name,
				'parent_group' => 'page_settings',
				'fields' => $this->fields()
			)
		);
	}

	/**
	 * All known custom fields across the Layout interfaces.
	 *
	 * @since 4.7
	 * @moved 6.0
	 */

	public function fields() {
		$menus = array();
		$nav_menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
		foreach ( $nav_menus as $menu )
			$menus[] = esc_attr( $menu->slug );

		$toggle_fields = array(
			'sidebar' => array(
				'options' => array( 'add', 'remove', 'global', 'alt' ),
				'areas' => md_layout_areas( 'sidebar', true )
			),
			'panel' => array(
				'options' => array( 'add', 'remove', 'global', 'alt', 'close' ),
				'areas' => md_layout_areas( 'panel', true )
			)
		);

		$fields = array(
			'header' => array(
				'type' => 'checkbox',
				'options' => array( 'remove', 'logo', 'tagline', 'menu', 'elements' )
			),
			'header_menu' => array(
				'type' => 'select',
				'options' => $menus
			),
			'content' => array(
				'type' => 'checkbox',
				'options' => array( 'remove', 'builder', 'the_content', 'headline', 'author_box', 'add_author_box', 'post_nav', 'add_post_nav', 'full', 'wpautop' )
			),
			'content_style' => array(
				'type' => 'select',
				'options' => array_keys( md_filter_loop_styles() )
			),
			'featured_image' => array(
				'type' => 'select',
				'options' => array_keys( $this->fields->data->values['featured_image'] )
			),
			'breadcrumbs' => array(
				'type' => 'checkbox',
				'options' => array( 'add', 'remove' )
			),
			'footer' => array(
				'type' => 'checkbox',
				'options' => array( 'remove', 'columns' )
			)
		);

		foreach ( $toggle_fields as $id => $data ) {
			$fields[$id] = array(
				'type' => 'checkbox',
				'options' => $data['options']
			);
			$fields["custom_$id"] = array(
				'type' => 'select',
				'options' => $data['areas']
			);
			$fields["entries_$id"] = array(
				'type' => 'select',
				'options' => $data['areas']
			);

			foreach ( array( 'archive', 'term', 'single' ) as $type ) {
				$fields["{$id}_$type"] = array(
					'type' => 'select',
					'options' => $data['areas']
				);
				$fields["{$id}_{$type}_show"] = array(
					'type' => 'checkbox',
					'options' => array( 'enable', 'disable' )
				);
			}
		}

		return apply_filters( 'md_filter_save_layout_fields', $fields );
	}

	/**
	 * Post screen meta box template callback.
	 *
	 * @since 5.0
	 */

	public function meta_box() {
		$this->admin_template();
	}

	/**
	 * Add settings template and script to Page Settings sections.
	 *
	 * @since 6.0
	 */

	public function admin_fields() { ?>
		<div class="md-widget md-toggle md-sep-small">
			<h3 class="md-widget-title"><?php echo esc_html( $this->name ); ?></h3>
			<div class="md-widget-item">
				<?php $this->admin_template(); ?>
			</div>
		</div>
	<?php }

	/**
	 * The actual admin template rendered to each screen type.
	 *
	 * @since 4.7
	 */

	public function admin_template() {
		$screen_id = '';
		$screen = get_current_screen();
		$post_type = $screen->post_type;
		$is_post = in_array( $screen->base, array( 'post', 'post-new' ) ) ? true : false;
		$is_term = $screen->base == 'term' ? true : false;
		$is_admin = ! in_array( $screen->base, array( 'post', 'post-new', 'term' ) ) ? true : false;

		if ( $is_post )
			$screen_id = isset( $_GET['post'] ) ? sanitize_key( $_GET['post'] ) : '';
		elseif ( $is_term )
			$screen_id = isset( $_GET['tag_ID'] ) ? sanitize_key( $_GET['tag_ID'] ) : '';

		$header = $this->fields->module( 'header' );
		$content = $this->fields->module( 'content' );
		$footer = $this->fields->module( 'footer' );

		$context = array(
			'post_type' => $post_type,
			'is_post' => $is_post,
			'is_admin' => $is_admin,
			'is_term' => $is_term,
			'screen_id' => $screen_id,
			'toggles' => $this->layout_toggles()
		);
		$sidebar = $this->get_layout_state( 'sidebar', $context );
		$panel = $this->get_layout_state( 'panel', $context );

		$breadcrumbs_options = array( 'add' => __( 'Add <b>Breadcrumbs</b>', 'md' ) );

		if ( ! $is_admin && md_post_type_field( array( 'layout', 'breadcrumbs', 'add' ), null, $post_type ) )
			$breadcrumbs_options = array( 'remove' => __( 'Remove <b>Breadcrumbs</b>', 'md' ) );

		$author_box = md_post_type_field( array( 'layout', 'content', 'add_author_box' ), null, $post_type );
		$nav_menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

		foreach ( $nav_menus as $menu )
			$menus[$menu->slug] = $menu->name;

		if ( $is_post )
			echo "<div class=\"md-$this->_clean_id md-tab-content active\">";

		include md_template( 'admin/layout', true );

		if ( $is_post )
			echo '</div>';

		do_action( 'md_layout_edit_screen_fields' );

		$this->scripts( $sidebar, $panel );
	}

	/**
	 * Layout Toggles are used for Sidebar and Panel, and open to other
	 * elements. The purpose is to provide a simple way to reuse
	 * interface options and visibility logic to show layout elements
	 * across page types.
	 *
	 * @since 6.0
	 */

	private function layout_toggles() {
		return apply_filters( 'md_filter_layout_toggles', array(
			'sidebar' => array(
				'title' => __( 'Sidebar', 'md' ),
				'settings' => array(
					'alt' => array(
						'default' => false,
						'labels' => array(
							true => __( 'Show on left', 'md' ),
							false => __( 'Show on right', 'md' )
						)
					)
				)
			),
			'panel' => array(
				'title' => __( 'Panel', 'md' ),
				'settings' => array(
					'alt' => array(
						'default' => false,
						'labels' => array(
							true => __( 'Show on right', 'md' ),
							false => __( 'Show on left', 'md' )
						)
					),
					'close' => array(
						'default' => false,
						'labels' => array(
							true => __( 'Closed by default', 'md' ),
							false => __( 'Opened by default', 'md' )
						)
					)
				)
			)
		) );
	}

	/**
	 * Determines the display status of the layout across global and single views.
	 * Delivers data relevant to making those decisions in the UI.
	 *
	 * @since 6.0
	 */

	private function get_layout_state( $id, $context ) {
		$layout = array(
			'global' => false,
			'display' => 'none',
			'classes' => array( 'md-layouts', 'md-sep-small' ),
			'areas' => md_layout_areas( $id ),
			'has' => md_has_layout( $id, array(
				'page' => ( $context['is_post'] ? 'single' : 'term' ),
				'post_type' => $context['post_type'],
				'post_id' => $context['screen_id'],
				'exclude_single' => true
			) )
		);

		$single_add = $this->fields->module( array( $id, 'add' ) );
		$single_remove = $this->fields->module( array( $id, 'remove' ) );

		if ( ( $layout['has'] || $single_add ) && ! $single_remove )
			$layout['display'] = 'block';

		if ( $context['is_admin'] ) {
			$layout['global'] = $this->fields->module( array( $id, 'global' ) );

			if ( $layout['global'] )
				$layout['classes'][] = 'is-global';
		}

		$layout['classes'] = join( ' ', $layout['classes'] );

		return $layout;
	}

	/**
	 * Determine reversible setting labels for single toggles based on global settings.
	 *
	 * @since 6.0
	 */

	private function layout_labels( $id, $settings, $context, $use_global ) {
		$options = array();

		foreach ( $settings as $setting => $data ) {
			$default = md_setting( array( 'layout', $id, $setting ), $data['default'] );

			if ( $use_global )
				$current = ! empty( $default );
			else
				$current = md_post_type_field( array( 'layout', $id, $setting ), $default, $context['post_type'] );

			$options[$setting] = $data['labels'][$current ? false : true];
		}

		return $options;
	}

	/**
	 * Render shared layout fields for sidebar and panel blocks.
	 *
	 * @since 6.0
	 */

	public function toggle_fields( $id, $layout, $context ) {
		if ( empty( $context['toggles'][$id] ) )
			return;

		include md_template( 'admin/fields/layout-toggle', true );
	}

	/**
	 * Print footer scripts to admin screens to toggle options.
	 *
	 * @since 4.7
	 */

	public function scripts( $sidebar, $panel ) {
		$prefix = $this->_prefix();
		$screen = get_current_screen();
		$layouts = array(
			'sidebar' => array(
				'areas' => $sidebar['areas'],
				'has' => $sidebar['has']
			),
			'panel' => array(
				'areas' => $panel['areas'],
				'has' => $panel['has']
			)
		);
	?>

	<script>
	( function() {

		function toggleDisplay( id, show ) {
			var element = document.getElementById( id );

			if ( element )
				element.style.display = show ? 'block' : 'none';
		}

		function toggleClass( id, className, state ) {
			var element = document.getElementById( id );

			if ( element )
				element.classList.toggle( className, state );
		}

		function bindToggle( id, handler ) {
			var element = document.getElementById( id );

			if ( element )
				element.onchange = function() { handler( this.checked, this ); };
		}

		bindToggle( '<?php echo $prefix; ?>_content_remove', function( checked ) {
			toggleClass( 'md_layout', 'remove-content-box', checked );
			toggleDisplay( 'content_options', ! checked );
			toggleDisplay( 'layout_fields_tabs', ! checked );
		} );

		<?php if ( in_array( $screen->post_type, array( 'post', 'page' ) ) && $screen->base !== 'term' ) : ?>
		bindToggle( '<?php echo $prefix; ?>_content_headline', function( checked ) {
			toggleDisplay( 'headline_options', ! checked );
		} );
		<?php endif; ?>

		<?php foreach ( $layouts as $layout => $fields ) :
			if ( empty( $fields['areas'] ) )
				continue;

			if ( in_array( $screen->base, array( 'post', 'post-new', 'term' ) ) ) : ?>

			bindToggle( '<?php echo "{$prefix}_{$layout}_" . ( $fields['has'] ? 'remove' : 'add' ); ?>', function( checked ) {
				toggleDisplay( '<?php echo "{$layout}_options"; ?>', <?php echo $fields['has'] ? '! checked' : 'checked'; ?> );
			} );

			<?php else : ?>

			bindToggle( '<?php echo "{$prefix}_{$layout}_global"; ?>', function( checked ) {
				toggleClass( '<?php echo "{$layout}_fields"; ?>', 'is-global', checked );
			} );

		<?php endif; endforeach; ?>

	} )();
	</script>

	<?php }

}

new md_layout;