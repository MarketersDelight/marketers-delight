<?php
/**
 * Define and resolve the palettes and semantic color roles used by MD Design.
 *
 * @since 6.0
 */

class md_design_colors {

	/**
	 * Fixed palette keys and their built-in hex values.
	 *
	 * @since 6.0
	 */

	public $palette = array(
		'background' => '#FFFFFF',
		'surface' => '#F0F0F0',
		'primary' => '#AE2525',
		'secondary' => '#2E2E2E',
		'tertiary' => '#DDDDDD',
		'border' => '#CCCCCC',
		'highlight' => '#FFFBCC',
		'text-main' => '#1E1E1E',
		'text-secondary' => '#777777',
		'button' => '#22A340',
		'white' => '#FFFFFF'
	);

	/**
	 * Semantic color roles. Each field has one unresolved default: a palette
	 * key, an independent color, or an empty inherited value. An `inherit` array
	 * points to another role in this tree and is the single source of truth for
	 * both color resolution and the admin's inherited label.
	 *
	 * @since 6.0
	 */

	public $roles = array(
		'site' => array(
			'bg_color' => array(
				'label' => 'Background',
				'default' => 'background'
			),
			'text_color' => array(
				'label' => 'Text',
				'default' => 'text-main'
			),
			'muted_text_color' => array(
				'label' => 'Muted Text',
				'default' => 'text-secondary'
			),
			'contrast_text_color' => array(
				'label' => 'Contrast Text',
				'default' => '#FFFFFF'
			),
			'link_color' => array(
				'label' => 'Links',
				'default' => 'primary'
			),
			'muted_link_color' => array(
				'label' => 'Muted Links',
				'default' => '',
				'inherit' => array( 'site', 'muted_text_color' )
			),
			'headline_color' => array(
				'label' => 'Headlines',
				'default' => '',
				'inherit' => array( 'site', 'text_color' )
			),
			'headline_link_color' => array(
				'label' => 'Headline Links',
				'default' => '',
				'inherit' => array( 'site', 'headline_color' )
			)
		),
		'actions' => array(
			'primary' => array(
				'bg_color' => array(
					'label' => 'Background',
					'default' => 'button'
				),
				'text_color' => array(
					'label' => 'Text',
					'default' => '#FFFFFF'
				)
			),
			'secondary' => array(
				'bg_color' => array(
					'label' => 'Background',
					'default' => 'secondary'
				),
				'text_color' => array(
					'label' => 'Text',
					'default' => '#FFFFFF'
				)
			),
			'status' => array(
				'danger_color' => array(
					'label' => 'Danger',
					'default' => '#AE2525'
				),
				'warning_color' => array(
					'label' => 'Warning',
					'default' => '#F58F2A'
				)
			)
		),
		'header' => array(
			'bg_color' => array(
				'label' => 'Background',
				'default' => '#FFFFFF'
			),
			'text_color' => array(
				'label' => 'Text',
				'default' => '#1E1E1E'
			),
			'border_color' => array(
				'label' => 'Border',
				'default' => '#CCCCCC'
			),
			'menu' => array(
				'link_color' => array(
					'label' => 'Links',
					'default' => '',
					'inherit' => array( 'header', 'text_color' )
				),
				'link_hover_color' => array(
					'label' => 'Links Hover',
					'default' => 'primary'
				),
				'link_active_color' => array(
					'label' => 'Links Active',
					'default' => '',
					'inherit' => array( 'header', 'menu', 'link_hover_color' )
				)
			),
			'submenu' => array(
				'bg_color' => array(
					'label' => 'Background',
					'default' => '#FFFFFF'
				),
				'link_color' => array(
					'label' => 'Links',
					'default' => '#777777'
				),
				'link_hover_color' => array(
					'label' => 'Links Hover',
					'default' => 'primary'
				),
				'border_color' => array(
					'label' => 'Border',
					'default' => '#CCCCCC'
				)
			)
		),
		'content' => array(
			'main_bg_color' => array(
				'label' => 'Main Background',
				'default' => 'surface'
			),
			'border_color' => array(
				'label' => 'Content Border',
				'default' => 'border'
			),
			'box_bg_color' => array(
				'label' => 'Content Box',
				'default' => '#FFFFFF'
			),
			'box_border_color' => array(
				'label' => 'Box Border',
				'default' => '',
				'inherit' => array( 'content', 'border_color' )
			),
			'box_text_color' => array(
				'label' => 'Text',
				'default' => '#1E1E1E'
			),
			'box_muted_text_color' => array(
				'label' => 'Muted Text',
				'default' => '#777777'
			),
			'box_link_color' => array(
				'label' => 'Links',
				'default' => 'primary'
			),
			'page_cover_overlay_color' => array(
				'label' => 'Page Cover Overlay',
				'default' => '#00000080'
			)
		),
		'sidebar' => array(
			'bg_color' => array(
				'label' => 'Background',
				'default' => ''
			),
			'text_color' => array(
				'label' => 'Text',
				'default' => '#777777'
			),
			'title_color' => array(
				'label' => 'Title',
				'default' => '#1E1E1E'
			),
			'title_link_color' => array(
				'label' => 'Title Link',
				'default' => '',
				'inherit' => array( 'sidebar', 'title_color' )
			),
			'link_color' => array(
				'label' => 'Links',
				'default' => '',
				'inherit' => array( 'sidebar', 'text_color' )
			)
		),
		'panel' => array(
			'bg_color' => array(
				'label' => 'Background',
				'default' => 'surface'
			),
			'text_color' => array(
				'label' => 'Text',
				'default' => '#1E1E1E'
			),
			'link_color' => array(
				'label' => 'Links',
				'default' => '',
				'inherit' => array( 'panel', 'text_color' )
			),
			'border_color' => array(
				'label' => 'Border',
				'default' => 'border'
			)
		),
		'footer' => array(
			'bg_color' => array(
				'label' => 'Background',
				'default' => '#FFFFFF'
			),
			'text_color' => array(
				'label' => 'Text',
				'default' => '#1E1E1E'
			),
			'border_color' => array(
				'label' => 'Border',
				'default' => '#CCCCCC'
			),
			'title_color' => array(
				'label' => 'Title',
				'default' => '',
				'inherit' => array( 'footer', 'text_color' )
			),
			'title_link_color' => array(
				'label' => 'Title Link',
				'default' => '',
				'inherit' => array( 'footer', 'title_color' )
			),
			'link_color' => array(
				'label' => 'Links',
				'default' => '#777777'
			)
		)
	);

	/**
	 * Build the fixed, built-in palette used by the theme. Each declared key is
	 * paired with its display name, then any saved hex override for that same
	 * key is applied. Additional custom colors are intentionally excluded so
	 * the main palette admin always represents the stable set of theme colors.
	 *
	 * @since 6.0
	 */

	public function base_palette() {
		$palette = array();

		foreach ( $this->palette as $key => $hex )
			$palette[$key] = array(
				'hex' => $hex,
				'name' => ucwords( str_replace( '-', ' ', $key ) )
			);

		$colors = md_setting( 'colors' );

		if ( ! empty( $colors['palette'] ) )
			foreach ( $colors['palette'] as $key => $data )
				if ( ! empty( $data['hex'] ) && isset( $palette[$key] ) )
					$palette[$key]['hex'] = $data['hex'];

		return $palette;
	}

	/**
	 * Build the complete selectable palette. Additional Colors are layered onto
	 * the built-in palette using their saved key (or a key generated from their
	 * name). This is the palette used by color pickers, helper functions, the
	 * editor, and semantic color resolution.
	 *
	 * @since 6.0
	 */

	public function active_palette() {
		$palette = $this->base_palette();
		$colors = md_setting( 'colors' );

		if ( ! empty( $colors['custom'] ) )
			foreach ( $colors['custom'] as $data )
				if ( ! empty( $data['name'] ) && ! empty( $data['hex'] ) ) {
					$key = sanitize_title( ! empty( $data['key'] ) ? $data['key'] : $data['name'] );
					$palette[$key] = array(
						'hex' => $data['hex'],
						'name' => $data['name']
					);
				}

		return $palette;
	}

	/**
	 * Translate the active MD palette into the name, slug, and color structure
	 * expected by the Block Editor and theme.json. No defaults or inheritance
	 * are resolved here; this only reformats the selectable palette.
	 *
	 * @since 4.9
	 */

	public function editor_colors() {
		$colors = array();

		foreach ( $this->active_palette() as $key => $color )
			$colors[] = array(
				'name' => $color['name'],
				'slug' => $key,
				'color' => $color['hex']
			);

		return $colors;
	}

	/**
	 * Project the descriptive role tree into a matching unresolved value tree.
	 * Palette keys, literal colors, and empty inherited defaults are preserved;
	 * labels and other admin metadata are removed.
	 *
	 * @since 6.0
	 */

	public function defaults() {
		return $this->role_defaults( $this->roles );
	}

	/**
	 * Recursively project role fields into their unresolved default values.
	 *
	 * @since 6.0
	 */

	private function role_defaults( $roles ) {
		if ( array_key_exists( 'default', $roles ) )
			return $roles['default'];

		$defaults = array();

		foreach ( $roles as $key => $fields )
			$defaults[$key] = $this->role_defaults( $fields );

		return $defaults;
	}

	/**
	 * Convert a merged semantic color tree into final CSS-ready values. Palette
	 * keys are resolved first so parents already contain their active hex value.
	 * inheritance() then builds the fallback tree, and the original tree is
	 * layered back over it so explicit saved values win.
	 *
	 * @since 6.0
	 */

	public function resolve( $colors, $palette ) {
		foreach ( array_keys( $this->roles ) as $group )
			if ( isset( $colors[$group] ) )
				$colors[$group] = $this->resolve_palette_colors( $colors[$group], $palette );

		return $this->apply_explicit_colors( $this->inheritance( $colors ), $colors );
	}

	/**
	 * Recursively replace scalar values that match an active palette key with
	 * that color's current hex value. resolve() only calls this for known
	 * semantic groups, so custom palette metadata is left untouched.
	 *
	 * @since 6.0
	 */

	private function resolve_palette_colors( $colors, $palette ) {
		foreach ( $colors as $key => $value ) {
			if ( is_array( $value ) )
				$colors[$key] = $this->resolve_palette_colors( $value, $palette );
			elseif ( is_string( $value ) && isset( $palette[$value] ) )
				$colors[$key] = $palette[$value]['hex'];
		}

		return $colors;
	}

	/**
	 * Layer explicit nonblank colors over the inherited fallback tree. Blank
	 * values intentionally leave their inherited or transparent defaults intact.
	 *
	 * @since 6.0
	 */

	private function apply_explicit_colors( $fallbacks, $colors ) {
		foreach ( $colors as $key => $value ) {
			if ( is_array( $value ) ) {
				if ( isset( $fallbacks[$key] ) && is_array( $fallbacks[$key] ) )
					$fallbacks[$key] = $this->apply_explicit_colors( $fallbacks[$key], $value );
				else
					$fallbacks[$key] = $value;
			}
			elseif ( $value !== '' && $value !== null )
				$fallbacks[$key] = $value;
		}

		return $fallbacks;
	}

	/**
	 * Return the label of a role identified by an array path. Inherited fields
	 * use this to display the actual source field's label in the admin.
	 *
	 * @since 6.0
	 */

	public function role_label( $path ) {
		$role = $this->path_value( $this->roles, $path );

		return ! empty( $role['label'] ) ? $role['label'] : '';
	}

	/**
	 * Build a full fallback copy of the resolved color tree. Each field with an
	 * `inherit` path receives the referenced role's effective color, following
	 * additional inheritance paths when the referenced field is also empty.
	 *
	 * Inherited fields are replaced even when the input contains an explicit
	 * selection because this copy represents the value a reset should reveal.
	 * resolve() layers the original nonblank selections back over this copy, so
	 * saved child values still win.
	 *
	 * @since 6.0
	 */

	public function inheritance( $colors ) {
		return $this->apply_inheritance( $this->roles, $colors, $colors );
	}

	/**
	 * Walk the role and value trees together, replacing inherited fields in the
	 * returned subtree. The untouched root tree remains available for resolving
	 * references across groups.
	 *
	 * @since 6.0
	 */

	private function apply_inheritance( $roles, $colors, $root ) {
		foreach ( $roles as $key => $role ) {
			if ( array_key_exists( 'default', $role ) ) {
				if ( ! empty( $role['inherit'] ) )
					$colors[$key] = $this->inherit_value( $root, $role['inherit'] );
			}
			else {
				$values = ! empty( $colors[$key] ) && is_array( $colors[$key] ) ? $colors[$key] : array();
				$colors[$key] = $this->apply_inheritance( $role, $values, $root );
			}
		}

		return $colors;
	}

	/**
	 * Resolve one inherited role path. A nonblank explicit/default value wins;
	 * otherwise the referenced role's own inheritance path is followed. Already
	 * visited paths return blank to prevent circular definitions from recursing.
	 *
	 * @since 6.0
	 */

	private function inherit_value( $colors, $path, $visited = array() ) {
		if ( in_array( $path, $visited, true ) )
			return '';

		$visited[] = $path;
		$value = $this->path_value( $colors, $path );
		$role = $this->path_value( $this->roles, $path );

		if ( $value !== '' && $value !== null )
			return $value;

		if ( ! empty( $role['inherit'] ) )
			return $this->inherit_value( $colors, $role['inherit'], $visited );

		return '';
	}

	/**
	 * Read a nested value from an array-key path without modifying the source.
	 * Invalid paths return null.
	 *
	 * @since 6.0
	 */

	private function path_value( $values, $path ) {
		foreach ( $path as $key ) {
			if ( ! is_array( $values ) || ! array_key_exists( $key, $values ) )
				return null;

			$values = $values[$key];
		}

		return $values;
	}

}
