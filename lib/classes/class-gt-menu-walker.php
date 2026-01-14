<?php
/**
 * Custom Category Walker for displaying terms/categories
 * checkbox options with MD API.
 *
 * @since 5.0
 */
class gt_menu_walker extends Walker_Nav_Menu {
	/**
	 * Constructor.
	 *
	 * @param bool $title Display title.
	 * @param bool $desc  Display description.
	 */
	function __construct( $title = true, $desc = false ) {
		$this->md_title = $title;
		$this->md_desc  = $desc;
	}

	/**
	 * Starts the element output.
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 * @param int      $id     Current item ID.
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent      = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$class_names = '';
		$classes     = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[]   = 'menu-item-' . $item->ID;
		$classes[]   = ! empty( $item->description ) ? 'menu-item-has-desc' : '';
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
		$id          = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
		$id          = $id ? ' id="' . esc_attr( $id ) . '"' : '';
		$output     .= $indent . '<li' . $id . $class_names . '>';

		// Prepare anchor attributes.
		$atts          = array();
		$atts['title'] = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']   = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']  = ! empty( $item->url ) ? $item->url : '';
		$atts          = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );
		$attributes    = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		// Prepare description if set.
		$desc = $this->md_desc && ! empty( $item->description )
			? '<span class="menu-item-desc">' . esc_html( $item->description ) . '</span>'
			: '';

		$item_output  = $args->before;

		// Check if URL is just a hash.
		if ( '#' === $item->url ) {
			$item_output .= '';
			$args->link_before = '';
			$args->link_after  = '';
			$item_output .= $this->md_title ? $args->link_before . md_text_field( $item->title, $item->ID ) . $args->link_after : '';
			$item_output .= $desc;
			$item_output .= '';
		} else {
			$item_output .= '<a' . $attributes . ' itemprop="url">';
			$args->link_before = '';
			$args->link_after  = '';
			$item_output .= $this->md_title ? $args->link_before . md_text_field( $item->title, $item->ID ) . $args->link_after : '';
			$item_output .= $desc;
			$item_output .= '</a>';
		}

		// Append toggle for sub-menu if item has children.
		if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
			$item_output .= '<span class="menu-toggle" data-menu-toggle="menu-item-' . esc_attr( $item->ID ) . '"></span>';
		}
		$item_output .= $args->after;
		$output      .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}
