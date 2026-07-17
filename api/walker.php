<?php
/**
 * Custom Category Walker for displaying terms/categories
 * checkbox options with MD API.
 *
 * @since 5.3.1
 */

class md_category_options_walker extends Walker_Category {
	public function __construct( $option_name, $fields, $parsed_args ) {
		$this->option_name = $option_name;
		$this->fields = $fields;
		$this->parsed_args = $parsed_args;
	}
    public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
		$cat_name = apply_filters( 'list_cats', esc_attr( $category->name ), $category );
		if ( '' === $cat_name )
			return;
		$attributes = '';
		$atts = array();
		$atts['href'] = get_term_link( $category );
		$count = '<a href="' . admin_url( 'term.php?taxonomy=' . $category->taxonomy  . '&tag_ID=' . $category->term_id . '&post_type=' . $this->parsed_args['post_type'] ) . '">' . number_format_i18n( $category->count ) . '</a>';
		if ( 'list' == $args['style'] ) {
			$output .= "\t<li";
			$css_classes = array( 'cat-item', 'cat-item-' . $category->term_id );
			$css_classes = implode( ' ', apply_filters( 'category_css_class', $css_classes, $category, $depth, $args ) );
			$css_classes = $css_classes ? ' class="' . esc_attr( $css_classes ) . '"' : '';
			$output .= $css_classes;
			$output .= '>';
			ob_start();
			$this->fields->field( $this->option_name, array(
				'type' => 'checkbox',
				'options' => array( $category->term_id => "$category->name ($count)" )
			) );
			$output .= ob_get_contents();
			ob_end_clean();
		}
	}
}

/**
 * This adds 2 arguments that lets any menu using it determine
 * whether or not to show the title and description of each menu item.
 *
 * @since 4.0
 */

class md_menu_walker extends Walker_Nav_Menu {
	function __construct( $title = true, $desc = false ) {
		$this->md_title = $title;
		$this->md_desc  = $desc;
	}
	function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		}
		else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		$atts = array(
			'id' => ! empty( $this->submenu_id ) ? $this->submenu_id : '',
			'class' => 'sub-menu'
		);
		$atts = apply_filters( 'nav_menu_submenu_attributes', $atts, $args, $depth );
		$attributes = $this->build_atts( $atts );
		$output .= "{$n}{$indent}<ul{$attributes}>{$n}";
	}
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$class_names = '';
		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		$classes[] = ! empty( $item->description ) ? 'menu-item-has-desc' : '';
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
		$output .= $indent . '<li' . $id . $class_names .'>';
		$atts = array();
		$atts['title'] = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel'] = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href'] = ! empty( $item->url ) ? $item->url : '';
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );
		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}
		$desc = $this->md_desc && ! empty( $item->description ) ? '<span class="menu-item-desc">' . esc_html( $item->description ) . '</span>' : '';
		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$args->link_before = '<span class="menu-item-title">';
		$args->link_after  = '</span>';
		$item_title = wp_kses_data( $item->title );
		$item_output .= $this->md_title ? $args->link_before . $item_title . $args->link_after : '';
		$item_output .= $desc . '</a>';
		if ( in_array( 'menu-item-has-children', $item->classes ) ) {
			$this->submenu_id = 'submenu-' . $item->ID;
			$item_output .= '<button class="toggle trigger" data-toggle="menu-item" aria-expanded="false" aria-haspopup="true" aria-controls="' . esc_attr( $this->submenu_id ) . '" aria-label="' . sprintf( __( 'Toggle the %s submenu', 'md' ), $item_title ) . '"><i class="trigger-icon"></i></button>';
		}
		$item_output .= $args->after;
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}