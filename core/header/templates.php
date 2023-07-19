<?php
/**
 * This class holds all frontend templates and routes
 * for the Header area.
 *
 * @since 5.6
 */

class md_header_templates {

	/**
	 * Build Header template with flexible Builder fields data.
	 *
	 * @since 5.6
	 */

	public function template() {
		$data = unserialize( md_setting( array( 'header', 'builder_data' ) ) );
		$header_center = md_setting( array( 'header', 'layout' ) ) == 'flyer' ? true : false;
		$fields = md_setting( array( 'header', 'builder' ) );

		echo '<div class="header-controls">';

		if ( md_has_menu() && md_setting( array( 'header', 'layout_mobile' ) ) == 'expanded' )
			$this->header_menu_trigger();

		md_logo();

		$this->header_triggers();

		echo '</div>';

		if ( ! empty( $data['header'] ) ) {
			echo $header_center ? '<div class="header-primary">' : '';
			foreach ( $data['header'] as $order => $items ) {
				$type = esc_attr( $items['type'] );
				$id = esc_attr( $items['id'] );
				if ( ! empty( $fields[$id] ) )
					call_user_func( array( $this, esc_attr( $type ) ), $fields[$id] );
			}
			echo $header_center ? '</div>' : '';
		}

		if ( $header_center && ! empty( $data['header_aside'] ) ) {
			echo '<div class="header-aside">';
			foreach ( $data['header_aside'] as $order => $items ) {
				$type = esc_attr( $items['type'] );
				$id = esc_attr( $items['id'] );
				if ( ! empty( $fields[$id] ) )
					call_user_func( array( $this, esc_attr( $type ) ), $fields[$id] );
			}
			echo '</div>';
		}
	}

	/**
 	 * Displays the header menu and other triggers.
 	 *
 	 * @since 4.8
	 * Formerly md_header_triggers() #5.6
 	 */

	public function header_triggers( $args = null ) {
		$elements = unserialize( md_setting( array( 'header', 'builder_elements' ) ) );

		echo '<div class="header-triggers">';

		if ( md_has_header_search() )
			$this->header_search_trigger();

		if ( ! empty( $elements['link'] ) )
			foreach ( $elements['link'] as $c => $link_id ) {
				$fields = md_setting( array( 'header', 'builder', $link_id ) );
				$this->link( $fields );
			}

		if ( md_has_menu() && md_setting( array( 'header', 'layout_mobile' ) ) !== 'expanded' )
			$this->header_menu_trigger();

		do_action( 'md_hook_header_triggers' );

		echo '</div>';
	}

	/**
	 * Header menu trigger template.
	 *
	 * @since 5.6
	 */

	public function header_menu_trigger() {
		$elements = unserialize( md_setting( array( 'header', 'builder_elements' ) ) );
		$element_id = ! empty( $elements['menu'][0] ) ? $elements['menu'][0] : '';
		$nav_menu_title = md_get_menu_name( 'header' );
		$title = md_setting( array( 'header', 'builder', $element_id, 'title' ), $nav_menu_title );
		$hide_label = md_setting( array( 'header', 'builder', $element_id, 'toggle', 'hide_label' ) );
	?>
		<span id="header_menu_trigger" class="trigger trigger-menu<?php echo $hide_label ? ' hide-label' : ''; ?>">
			<?php echo md_icon( 'menu', array( 'classes' => 'trigger-icon' ) ); ?>
			<span class="trigger-text"><?php echo md_text_field( $title ); ?></span>
		</span>
	<?php }

	/**
	 * Header search trigger template.
	 *
	 * @since 5.6
	 */

	public function header_search_trigger() {
		$elements = unserialize( md_setting( array( 'header', 'builder_elements' ) ) );
		$element_id = ! empty( $elements['search'][0] ) ? $elements['search'][0] : '';
		$title = md_setting( array( 'header', 'builder', $element_id, 'title' ), __( 'Search', 'md' ) );
		$hide_label = md_setting( array( 'header', 'builder', $element_id, 'toggle', 'hide_label' ) );
	?>
		<span class="trigger trigger-search<?php echo $hide_label ? ' hide-label' : ''; ?>" data-md-parent="header">
			<?php echo md_icon( 'search', array( 'classes' => 'trigger-icon' ) ); ?>
			<span class="trigger-text"><?php echo md_text_field( $title ); ?></span>
		</span>
	<?php }

	/**
	 * Frontend markup for Menu.
	 *
	 * @since 5.6
	 */

	public function menu( $fields ) {
		$parent = $fields['area'];
		$menu_id = isset( $fields['menu'] ) ? $fields['menu'] : '';
		$args = array(
			'menu' => $menu_id,
			'container' => false,
			'fallback_cb' => false,
			'menu_class' => 'menu menu-' . esc_attr( $parent ),
			'walker' => new md_menu_walker( true, true )
		);
	?>
		<nav class="<?php echo esc_attr( $parent ); ?>-menu">
			<?php wp_nav_menu( $args ); ?>
		</nav>
	<?php }

	/**
	 * Frontend markup for Search.
	 *
	 * @since 5.6
	 */

	public function search( $fields ) {
		$parent = isset( $fields['parent'] ) ? $fields['parent'] : 'header';
		$title = isset( $fields['title'] ) ? $fields['title'] : __( 'Search', 'md' );
		$placeholder = isset( $fields['placeholder'] ) ? $fields['placeholder'] : __( 'Search...', 'md' );

		$fields['classes'][] = "{$parent}-search";
		$fields['classes'][] = 'search-form';
		$fields['classes'][] = 'form-controls';

		if ( ! empty( $fields['toggle']['search'] ) )
			$fields['classes'][] = 'form-toggle';

		if ( ! empty( $fields['toggle']['hide_label'] ) )
			$fields['classes'][] = 'hide-label';

		$classes = join( ' ', $fields['classes'] );

		include( md_template( 'searchform', true ) );
	}

	/**
	 * Frontend markup for Link.
	 *
	 * @since 5.6
	 */

	public function link( $fields ) {
		$classes = array();
		$html = 'span';
		$class = $href = $target = $popup = '';
		$parent = isset( $fields['area'] ) ? $fields['area'] : '';
		$url = isset( $fields['url'] ) ? $fields['url'] : '';
		$phone = isset( $fields['phone'] ) ? $fields['phone'] : '';
		$style = isset( $fields['link_style'] ) ? $fields['link_style'] : 'link';
		$type = isset( $fields['link_type'] ) ? $fields['link_type'] : 'url';
		$icon_classes = 'trigger-icon';
		if ( $type == 'url' && $url ) {
			$html = 'a';
			$href = ' href="' . esc_url( $url ) . '"';
			$target = ( isset( $fields['link_target']['new'] ) ? ' target="_blank"' : '' );
		}
		elseif ( $type == 'phone' && $phone ) {
			$html = 'a';
			$href = ' href="tel:' . esc_attr( $phone ) . '"';
			if ( empty( $fields['title'] ) )
				$fields['title'] = esc_attr( $phone );
			$fields['icon'] = 'phone';
		}
		if ( $style == 'button' ) {
			$classes[] = 'button';
			$icon_classes = 'link-icon';
		}
		if ( $type == 'popup' && isset( $fields['popup'] ) ) {
			$popup = ' data-popup="md_popup_' . esc_attr( $fields['popup'] ) . '"';
			$classes[] = 'md-popup-trigger';
			md_popup( array( 'id' => esc_attr( $fields['popup'] ) ) );
		}
		if ( ! empty( $fields['toggle']['hide_label'] ) )
			$classes[] = 'hide-label';
		$classes = join( ' ', $classes );
		if ( $classes )
			$class = ' class="' . esc_attr( $classes ) . '"';
	?>
		<span class="<?php echo "{$parent}-link"; ?>">
			<<?php echo $html . $href . $target . $popup . $class; ?>>
				<?php echo ( isset( $fields['icon'] ) ? md_icon( $fields['icon'], array( 'classes' => $icon_classes ) ) : '' ); ?>
				<?php echo ( isset( $fields['title'] ) ? '<span class="trigger-text">' . md_text_field( $fields['title'] ) . '</span>' : '' ); ?>
			</<?php echo $html; ?>>
		</span>
	<?php }

}