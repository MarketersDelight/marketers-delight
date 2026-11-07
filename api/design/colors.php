<?php
/**
 * Define and resolve the palettes and semantic color roles used by MD Design.
 *
 * @since 6.0
 */

class md_design_colors {

	private $merged = array();
	private $saved = array();

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
		'divider' => '#CCCCCC',
		'highlight' => '#FFFBCC',
		'text-main' => '#1E1E1E',
		'text-secondary' => '#757575',
		'button' => '#1C8735',
		'white' => '#FFFFFF'
	);

	/**
	 * Color roles. Each default is a palette key, a color or empty. `inherit`
	 * points to the role it falls back to; `auto` points to the background a text
	 * role sits on.
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
					'default' => '#FFFFFF',
					'auto' => array( 'actions', 'primary', 'bg_color' )
				)
			),
			'secondary' => array(
				'bg_color' => array(
					'label' => 'Background',
					'default' => 'secondary'
				),
				'text_color' => array(
					'label' => 'Text',
					'default' => '#FFFFFF',
					'auto' => array( 'actions', 'secondary', 'bg_color' )
				)
			),
			'status' => array(
				'danger_color' => array(
					'label' => 'Danger',
					'default' => '#AE2525'
				),
				'warning_color' => array(
					'label' => 'Warning',
					'default' => '#C75000'
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
				'default' => '#1E1E1E',
				'auto' => array( 'header', 'bg_color' )
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
				'hover_bg_color' => array(
					'label' => 'Hover Background',
					'default' => ''
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
					'default' => '',
					'inherit' => array( 'header', 'text_color' ),
					'auto' => array( 'header', 'submenu', 'bg_color' )
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
				'default' => 'divider'
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
				'default' => '#1E1E1E',
				'auto' => array( 'content', 'box_bg_color' )
			),
			'box_muted_text_color' => array(
				'label' => 'Muted Text',
				'default' => '#757575'
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
				'default' => '#757575',
				'auto' => array( 'sidebar', 'bg_color' )
			),
			'title_color' => array(
				'label' => 'Title',
				'default' => '#1E1E1E',
				'auto' => array( 'sidebar', 'bg_color' )
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
				'default' => '#1E1E1E',
				'auto' => array( 'panel', 'bg_color' )
			),
			'link_color' => array(
				'label' => 'Links',
				'default' => '',
				'inherit' => array( 'site', 'muted_text_color' ),
				'auto' => array( 'panel', 'bg_color' )
			),
			'border_color' => array(
				'label' => 'Border',
				'default' => 'divider'
			)
		),
		'footer' => array(
			'bg_color' => array(
				'label' => 'Background',
				'default' => '#FFFFFF'
			),
			'text_color' => array(
				'label' => 'Text',
				'default' => '#1E1E1E',
				'auto' => array( 'footer', 'bg_color' )
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
				'default' => '#757575',
				'auto' => array( 'footer', 'bg_color' )
			)
		)
	);

	/**
	 * The built-in palette with saved hex overrides. Additional colors are left
	 * out, so the palette screen always shows the same set.
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
	 * The full selectable palette: built-in colors plus Additional Colors, used
	 * by color pickers, helpers, the editor and role resolution.
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
	 * Build the Block Editor gradient presets from the active palette.
	 *
	 * @since 6.0
	 */

	public function editor_gradients() {
		$c = array_column( $this->editor_colors(), 'color', 'slug' );
		$gradients = array(
			'primary-secondary' => array( __( 'Primary to Secondary', 'md' ), "linear-gradient(135deg, {$c['primary']} 0%, {$c['secondary']} 100%)" ),
			'primary-fade' => array( __( 'Primary Fade', 'md' ), "linear-gradient(180deg, {$c['primary']} 0%, {$c['background']} 100%)" ),
			'surface' => array( __( 'Surface', 'md' ), "linear-gradient(180deg, {$c['background']} 0%, {$c['surface']} 100%)" ),
			'highlight' => array( __( 'Highlight', 'md' ), "linear-gradient(180deg, {$c['highlight']} 0%, {$c['background']} 100%)" ),
			'dark' => array( __( 'Dark', 'md' ), "linear-gradient(180deg, {$c['secondary']} 0%, {$c['text-main']} 100%)" )
		);
		$presets = array();

		foreach ( $gradients as $slug => $gradient )
			$presets[] = array(
				'name' => $gradient[0],
				'slug' => $slug,
				'gradient' => $gradient[1]
			);

		return $presets;
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
	 * Turn a merged color tree into final CSS values: palette keys become hex,
	 * then each role resolves. $saved holds only what was saved, which `auto`
	 * leaves alone.
	 *
	 * @since 6.0
	 */

	public function resolve( $colors, $palette, $saved = array() ) {
		foreach ( array_keys( $this->roles ) as $group )
			if ( isset( $colors[$group] ) )
				$colors[$group] = $this->resolve_palette_colors( $colors[$group], $palette );

		$this->merged = $colors;
		$this->saved = (array) $saved;

		return $this->resolve_roles( $this->roles, array(), $colors );
	}

	/**
	 * Replace scalar values matching a palette key with that color's hex.
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
	 * Walk the role tree, setting each role to its final color.
	 *
	 * @since 6.2
	 */

	private function resolve_roles( $roles, $path, $values ) {
		foreach ( $roles as $key => $role ) {
			$role_path = array_merge( $path, array( $key ) );

			if ( array_key_exists( 'default', $role ) )
				$values[$key] = $this->role_color( $role_path );
			else
				$values[$key] = $this->resolve_roles( $role, $role_path, $values[$key] ?? array() );
		}

		return $values;
	}

	/**
	 * One role's final color: its own, else the role it inherits. An unsaved
	 * `auto` role as light or dark as its background becomes the site's Text
	 * or Contrast Text.
	 *
	 * @since 6.2
	 */

	private function role_color( $path ) {
		$role = $this->path_value( $this->roles, $path );
		$color = $this->path_value( $this->merged, $path );

		if ( ( $color === '' || $color === null ) && ! empty( $role['inherit'] ) )
			$color = $this->role_color( $role['inherit'] );

		if ( empty( $role['auto'] ) || $this->path_value( $this->saved, $path ) )
			return $color;

		$bg = $this->role_color( $role['auto'] );
		$light = md_color_is_light( $bg );

		if ( ! $bg || $light !== md_color_is_light( $color ) )
			return $color;

		return $this->role_color( array( 'site', $light ? 'text_color' : 'contrast_text_color' ) );
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
	 * Read a nested value from an array-key path without modifying the source.
	 * Invalid paths return null.
	 *
	 * @since 6.0
	 */

	public function path_value( $values, $path ) {
		foreach ( $path as $key ) {
			if ( ! is_array( $values ) || ! array_key_exists( $key, $values ) )
				return null;

			$values = $values[$key];
		}

		return $values;
	}

}
