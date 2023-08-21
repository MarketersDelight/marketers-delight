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
		$data = md_get_builder( 'header', 'data' );
		$header_center = md_setting( array( 'header', 'layout' ) ) == 'flyer' ? true : false;
		$fields = md_setting( array( 'header', 'builder' ) );

		echo '<div class="header-controls">';

		if ( md_has_menu() && md_setting( array( 'header', 'layout_mobile' ) ) == 'expanded' )
			$this->menu_trigger();

		if ( md_has_logo() )
			md_logo();

		$this->header_triggers();

		echo '</div>';

		echo $header_center ? '<div class="header-primary">' : '';

		if ( ! empty( $data['header'] ) )
			foreach ( $data['header'] as $order => $items ) {
				$type = esc_attr( $items['type'] );
				$id = esc_attr( $items['id'] );
				if ( ! empty( $fields[$id] ) )
					call_user_func( array( $this, esc_attr( $type ) ), $fields[$id] );
			}
		else
			$this->menu();

		echo $header_center ? '</div>' : '';

		if ( ! empty( $data['header_aside'] ) ) {
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
		$elements = md_get_builder( 'header' );

		echo '<div class="header-triggers">';

		if ( md_has_header_search() )
			$this->search_trigger();

		if ( md_has_menu() && md_setting( array( 'header', 'layout_mobile' ) ) !== 'expanded' )
			$this->menu_trigger();

		do_action( 'md_hook_header_triggers' );

		if ( ! empty( $elements['link'] ) )
			foreach ( $elements['link'] as $c => $link_id ) {
				$fields = md_setting( array( 'header', 'builder', $link_id ) );
				$this->link( $fields );
			}

		echo '</div>';
	}

	/**
	 * Frontend markup for Menu.
	 *
	 * @since 5.6
	 */

	public function menu( $fields = null ) {
		$parent = isset( $fields['area'] ) ? $fields['area'] : 'header';
		$menu_id = isset( $fields['menu'] ) ? $fields['menu'] : '';
		$args = array(
			'menu' => md_module( array( 'layout', 'header_menu' ), $menu_id ),
			'container' => false,
			'fallback_cb' => false,
			'menu_class' => 'menu menu-' . esc_attr( $parent ),
			'walker' => new md_menu_walker( true, true )
		);

		if ( empty( $menu_id ) ) {
			$menu_location = is_user_logged_in() && has_nav_menu( 'header_loggedin' ) ? 'header_loggedin' : 'header';
			$args['theme_location'] = $menu_location;
		}

	?>
		<nav class="<?php echo esc_attr( $parent ); ?>-menu">
			<?php wp_nav_menu( $args ); ?>
		</nav>
	<?php }

	/**
	 * Header menu trigger template.
	 *
	 * @since 5.6
	 */

	public function menu_trigger() {
		$elements = md_get_builder( 'header' );
		$element_id = ! empty( $elements['menu'][0] ) ? $elements['menu'][0] : '';
		$nav_menu_title = md_get_menu_name( 'header' );
		$title = md_setting( array( 'header', 'builder', $element_id, 'title' ), $nav_menu_title );
		$hide_label = md_setting( array( 'header', 'builder', $element_id, 'toggle', 'hide_label' ) );
		$hide_label_mobile = md_setting( array( 'header', 'builder', $element_id, 'toggle', 'hide_label_mobile' ) );
		$label_classes = array( 'trigger', 'trigger-menu' );

		if ( $hide_label )
			$label_classes[] = 'hide-label';
		elseif ( $hide_label_mobile )
			$label_classes[] = 'hide-label-mobile';

		$label_classes = join( ' ', $label_classes );
	?>
		<span id="header_menu_trigger" class="<?php echo esc_attr( $label_classes ); ?>">
			<?php echo md_icon( 'menu', array( 'classes' => 'trigger-icon' ) ); ?>
			<span class="trigger-text"><?php echo md_text_field( $title ); ?></span>
		</span>
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
	 * Header search trigger template.
	 *
	 * @since 5.6
	 */

	public function search_trigger() {
		$elements = md_get_builder( 'header' );
		$element_id = ! empty( $elements['search'][0] ) ? $elements['search'][0] : '';
		$title = md_setting( array( 'header', 'builder', $element_id, 'title' ), __( 'Search', 'md' ) );

		$hide_label = md_setting( array( 'header', 'builder', $element_id, 'toggle', 'hide_label' ) );
		$hide_label_mobile = md_setting( array( 'header', 'builder', $element_id, 'toggle', 'hide_label_mobile' ) );
		$label_classes = array( 'trigger', 'trigger-search' );

		if ( $hide_label )
			$label_classes[] = 'hide-label';
		elseif ( $hide_label_mobile )
			$label_classes[] = 'hide-label-mobile';

		$label_classes = join( ' ', $label_classes );
	?>
		<span class="<?php echo esc_attr( $label_classes ); ?>" data-md-parent="header">
			<?php echo md_icon( 'search', array( 'classes' => 'trigger-icon' ) ); ?>
			<span class="trigger-text"><?php echo md_text_field( $title ); ?></span>
		</span>
	<?php }

	/**
	 * Frontend markup for Link.
	 *
	 * @since 5.6
	 */

	public function link( $fields ) {
		$parent_classes = $classes = $styles = array();
		$html = 'span';
		$class = $href = $target = $popup = '';
		$parent = isset( $fields['area'] ) ? $fields['area'] : '';
		$url = isset( $fields['url'] ) ? $fields['url'] : '';
		$phone = isset( $fields['phone'] ) ? $fields['phone'] : '';
		$style = isset( $fields['link_style'] ) ? $fields['link_style'] : 'link';
		$type = isset( $fields['link_type'] ) ? $fields['link_type'] : 'url';
		$icon_classes = 'trigger-icon';

		if ( $parent )
			$parent_classes[] = "{$parent}-link";

		if ( $style )
			$parent_classes[] = "is-{$style}";

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
			$button_color = '';
			$classes[] = 'button';
			$icon_classes = 'link-icon';

			if ( ! empty( $fields['button_color'] ) )
				$button_color = $fields['button_color'];

			if ( ! empty( $fields['button_style'] ) ) {
				if ( $fields['button_style'] == 'outline' ) {
					$classes[] = 'button-outline';
					if ( $button_color )
						$styles['border_color'] = $styles['color'] = esc_attr( $button_color );
				}
			}
			elseif ( $button_color )
				$styles['bg_color'] = esc_attr( $button_color );
		}

		if ( $type == 'popup' && isset( $fields['popup'] ) ) {
			$popup = ' data-popup="md_popup_' . esc_attr( $fields['popup'] ) . '"';
			$classes[] = 'md-popup-trigger';
			md_popup( array( 'id' => esc_attr( $fields['popup'] ) ) );
		}

		if ( ! empty( $fields['toggle']['hide_label'] ) )
			$classes[] = 'hide-label';

		if ( ! empty( $fields['toggle']['hide_label_mobile'] ) )
			$classes[] = 'hide-label-mobile';

		$style = md_style( $styles );

		$classes = join( ' ', $classes );

		if ( $parent_classes )
			$parent_classes = join( ' ', $parent_classes );

		if ( $classes )
			$class = ' class="' . esc_attr( $classes ) . '"';
	?>
		<span class="<?php echo esc_attr( $parent_classes ); ?>">
			<<?php echo $html . $href . $popup . $class . $target . $style; ?>>
				<?php echo ( isset( $fields['icon'] ) ? md_icon( $fields['icon'], array( 'classes' => $icon_classes ) ) : '' ); ?>
				<?php echo ( isset( $fields['title'] ) ? '<span class="trigger-text">' . md_text_field( $fields['title'] ) . '</span>' : '' ); ?>
			</<?php echo $html; ?>>
		</span>
	<?php }

}
