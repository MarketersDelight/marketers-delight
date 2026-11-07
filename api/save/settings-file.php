<?php
/**
 * Moves MD's settings between sites as one JSON file: design, theme, post type
 * and drop-in settings, and widgets. Content and menus travel in WordPress's
 * XML export.
 *
 * export() builds a file, read() decodes one, check() says what it holds and
 * import() writes parts through md_save::apply(), which does the validating.
 *
 * @since 6.2
 */

class md_settings_file {

	public $format = 'md-export';
	public $version = 1;
	public $max_size = 2097152;

	/**
	 * The sections a file can hold, in import order: Theme settings
	 * first, so custom sidebars exist before their widgets are placed.
	 *
	 * @since 6.2
	 */

	public function sections() {
		return array(
			'settings' => __( 'Theme settings', 'md' ),
			'design' => __( 'Design', 'md' ),
			'post_types' => __( 'Post type settings', 'md' ),
			'dropins' => __( 'Drop-in settings', 'md' ),
			'widgets' => __( 'Widgets', 'md' )
		);
	}

	/**
	 * Build an export file from this site's settings. $sections lists the
	 * sections to include, all by default.
	 *
	 * @since 6.2
	 */

	public function export( $sections = null ) {
		$sections = $sections === null ? array_keys( $this->sections() ) : (array) $sections;
		$refs = $out = array();

		foreach ( $this->site_entries() as $entry )
			if ( in_array( $entry['section'], $sections, true ) )
				$out[$entry['section']][] = $this->export_entry( $entry, $refs );

		if ( in_array( 'post_types', $sections, true ) )
			foreach ( $this->post_type_entries() as $entry )
				$out['post_types'][] = $this->export_entry( $entry, $refs );

		if ( in_array( 'dropins', $sections, true ) )
			foreach ( $this->export_options() as $option => $args )
				$out['options'][$option] = array(
					'source' => $args['source'],
					'values' => get_option( $option, array() )
				);

		if ( in_array( 'widgets', $sections, true ) )
			$out['widgets'] = $this->export_widgets();

		return array(
			'format' => $this->format,
			'version' => $this->version,
			'md_version' => MD_VERSION,
			'created' => gmdate( 'Y-m-d\TH:i:s\Z' ),
			'site' => home_url(),
			'dropins' => $this->dropins_list(),
			'sections' => $out,
			'refs' => $refs
		);
	}

	/**
	 * The file name an export downloads as.
	 *
	 * @since 6.2
	 */

	public function file_name() {
		$host = sanitize_title( (string) wp_parse_url( home_url(), PHP_URL_HOST ) );

		return "md-$host-" . gmdate( 'Y-m-d' ) . '.json';
	}

	/**
	 * Decode an uploaded file. Refuses what isn't an MD file or was made
	 * by a newer format version; everything else is checked by apply()
	 * as it imports.
	 *
	 * @since 6.2
	 */

	public function read( $json ) {
		if ( ! is_string( $json ) || strlen( $json ) > $this->max_size )
			return new WP_Error( 'md_import_size', __( 'This file is too large to be an MD settings file.', 'md' ) );

		$file = json_decode( $json, true, 64 );

		if ( ! is_array( $file ) )
			return new WP_Error( 'md_import_json', __( 'This file can\'t be read. Upload a .json file exported from Settings → Import & Export.', 'md' ) );

		if ( ! isset( $file['format'] ) || ! in_array( $file['format'], array( $this->format, 'md-style' ), true ) )
			return new WP_Error( 'md_import_format', __( 'This isn\'t an MD settings file.', 'md' ) );

		if ( ! isset( $file['version'] ) || ! is_numeric( $file['version'] ) || (int) $file['version'] > $this->version )
			return new WP_Error( 'md_import_version', __( 'This file was made by a newer version of MD. Update MD, then upload it again.', 'md' ) );

		$file['sections'] = isset( $file['sections'] ) && is_array( $file['sections'] ) ? $file['sections'] : array();
		$file['refs'] = isset( $file['refs'] ) && is_array( $file['refs'] ) ? $file['refs'] : array();
		$file['dropins'] = isset( $file['dropins'] ) && is_array( $file['dropins'] ) ? $file['dropins'] : array();

		return $file;
	}

	/**
	 * What a file holds, for the check step: where it came from, its
	 * parts, drop-ins that are missing or inactive here, and sections this
	 * version doesn't use.
	 *
	 * @since 6.2
	 */

	public function check( $file ) {
		$missing = $inactive = $skipped = array();

		foreach ( array_diff( array_keys( $file['sections'] ), array_keys( $this->sections() ), array( 'options' ) ) as $section )
			$skipped[] = sprintf( __( 'The `%s` section isn\'t used by this version of MD.', 'md' ), $section );

		foreach ( $this->file_sources( $file ) as $source ) {
			$name = isset( $file['dropins'][$source]['name'] ) ? $file['dropins'][$source]['name'] : $source;

			if ( ! md_get_dropin_details( $source ) )
				$missing[$source] = $name;
			elseif ( ! md_has( $source ) )
				$inactive[$source] = $name;
		}

		return array(
			'site' => isset( $file['site'] ) ? (string) $file['site'] : '',
			'md_version' => isset( $file['md_version'] ) ? (string) $file['md_version'] : '',
			'created' => isset( $file['created'] ) ? (string) $file['created'] : '',
			'parts' => $this->parts( $file ),
			'missing' => $missing,
			'inactive' => $inactive,
			'skipped' => $skipped
		);
	}

	/**
	 * Import a file's selected parts and report what was skipped. Settings save
	 * twice, rebuilding fields between, since fields can list values the same
	 * file brings.
	 *
	 * @since 6.2
	 */

	public function import( $file, $parts = null ) {
		$all = $this->parts( $file );
		$parts = $parts === null ? array_keys( $all ) : array_values( array_intersect( (array) $parts, array_keys( $all ) ) );
		$imported = array();
		$save = new md_save;

		$this->import_entries( $file, $parts, $save );
		md_refresh_fields();

		$skipped = $this->import_entries( $file, $parts, $save );

		if ( in_array( 'dropins', $parts, true ) && ! empty( $file['sections']['options'] ) )
			$this->import_options( $file['sections']['options'], $skipped );

		if ( in_array( 'widgets', $parts, true ) && ! empty( $file['sections']['widgets'] ) )
			$this->import_widgets( $file['sections']['widgets'], $skipped );

		foreach ( $parts as $part )
			$imported[] = $all[$part];

		$result = array(
			'imported' => $imported,
			'skipped' => array_values( array_unique( $skipped ) )
		);

		$save->after_save();

		return $result;
	}

	/**
	 * Save a file's selected settings entries. Returns what was skipped.
	 *
	 * @since 6.2
	 */

	private function import_entries( $file, $parts, $save ) {
		$skipped = array();

		foreach ( $this->entries( $file ) as $entry )
			if ( in_array( $entry['part'], $parts, true ) )
				$this->import_entry( $entry, $file['refs'], $save, $skipped );

		return $skipped;
	}

	/**
	 * Replace mode: the file's values, plus null for each registered field stored
	 * here and not in the file. Taxonomy branches inside a post type's branch
	 * stay.
	 *
	 * @since 6.2
	 */

	public function replace_input( $stored, $values, $fields ) {
		$input = is_array( $values ) ? $values : array();
		$stored = is_array( $stored ) ? $stored : array();

		foreach ( $fields as $key => $field ) {
			if ( ! is_array( $field ) )
				continue;

			if ( ! array_key_exists( $key, $input ) ) {
				if ( ! isset( $field['type'] ) && isset( $stored[$key] ) && is_array( $stored[$key] ) )
					$input[$key] = $this->replace_input( $stored[$key], array(), $field );
				elseif ( array_key_exists( $key, $stored ) )
					$input[$key] = null;

				continue;
			}

			if ( ! isset( $stored[$key] ) || ! is_array( $stored[$key] ) || ! is_array( $input[$key] ) )
				continue;

			$type = isset( $field['type'] ) ? $field['type'] : null;

			if ( $type === null )
				$input[$key] = $this->replace_input( $stored[$key], $input[$key], $field );
			elseif ( in_array( $type, array( 'group', 'builder' ), true ) ) {
				$item_fields = isset( $field['fields'] ) ? $field['fields'] : array();

				foreach ( $input[$key] as $item => $item_values )
					if ( isset( $stored[$key][$item] ) && is_array( $item_values ) )
						$input[$key][$item] = $this->replace_input( $stored[$key][$item], $item_values, $item_fields );
			}
		}

		return $input;
	}

	/**
	 * Site settings groups and the section each exports to: Design, Theme
	 * settings or Drop-in settings. A mixed group, ex: logo, is split between
	 * Design and Theme settings.
	 *
	 * @since 6.2
	 */

	private function site_entries() {
		$design = $this->design_groups();
		$entries = array();

		foreach ( md_register( 'admin_pages' ) as $group => $page ) {
			if ( empty( $page['fields'] ) || ! empty( $page['has_groups'] ) || ! in_array( 'site', md_scope::all( $group ), true ) )
				continue;

			$source = isset( $page['_source'] ) ? $page['_source'] : 'theme';
			$entry = array(
				'scope' => 'site',
				'group' => $group,
				'source' => $source
			);

			if ( isset( $design[$group] ) && $design[$group] === true )
				$entries[] = array_merge( $entry, array( 'section' => 'design' ) );
			elseif ( isset( $design[$group] ) ) {
				$entries[] = array_merge( $entry, array( 'section' => 'design' ) );
				$entries[] = array_merge( $entry, array( 'section' => 'settings' ) );
			}
			else
				$entries[] = array_merge( $entry, array( 'section' => $source === 'theme' ? 'settings' : 'dropins' ) );
		}

		return $entries;
	}

	/**
	 * Post type and taxonomy settings: each host's whole branch, child
	 * groups included, and each taxonomy branch, so a replace also clears
	 * what the file's site doesn't set.
	 *
	 * @since 6.2
	 */

	private function post_type_entries() {
		$taxonomies = apply_filters( 'md_taxonomy_groups', array() );
		$entries = array();

		foreach ( md_register( 'admin_pages' ) as $host => $page ) {
			if ( empty( $page['has_groups'] ) || empty( $page['fields'] ) )
				continue;

			$source = isset( $page['_source'] ) ? $page['_source'] : 'theme';
			$entries[] = array(
				'scope' => "post_type:$host",
				'group' => $host,
				'source' => $source,
				'section' => 'post_types'
			);

			foreach ( array_keys( isset( $taxonomies["md_$host"] ) ? $taxonomies["md_$host"] : array() ) as $taxonomy )
				$entries[] = array(
					'scope' => "taxonomy:$host/$taxonomy",
					'group' => $host,
					'source' => $source,
					'section' => 'post_types'
				);
		}

		return $entries;
	}

	/**
	 * One file entry: the group's stored values limited to its registered
	 * fields (and to its part of a split group), with a portable name for
	 * every ID it holds added to $refs.
	 *
	 * @since 6.2
	 */

	private function export_entry( $entry, &$refs ) {
		$scope = md_scope::parse( $entry['scope'] );
		$target = $scope->target( $entry['group'] );
		$fields = $this->section_fields( $entry['section'], $entry['group'], $target['fields'] );
		$values = $this->limit( $scope->read( $entry['group'] ), $fields );

		$this->collect_refs( $values, $fields, $refs );

		return array(
			'scope' => $entry['scope'],
			'group' => $entry['group'],
			'source' => $entry['source'],
			'values' => $values
		);
	}

	/**
	 * The fields of a group that belong to a section. Design takes only
	 * the design keys of a split group, Theme settings the rest.
	 *
	 * @since 6.2
	 */

	private function section_fields( $section, $group, $fields ) {
		$design = $this->design_groups();

		if ( ! isset( $design[$group] ) || $design[$group] === true )
			return $fields;

		return $this->filter_fields( $fields, $design[$group], $section === 'design' );
	}

	/**
	 * Keep ($keep true) or drop the fields named in a tree of keys.
	 *
	 * @since 6.2
	 */

	private function filter_fields( $fields, $keys, $keep ) {
		$filtered = array();

		foreach ( $fields as $key => $field ) {
			if ( isset( $keys[$key] ) && is_array( $keys[$key] ) ) {
				$nested = $this->filter_fields( $field, $keys[$key], $keep );

				if ( $nested )
					$filtered[$key] = $nested;
			}
			elseif ( isset( $keys[$key] ) === $keep )
				$filtered[$key] = $field;
		}

		return $filtered;
	}

	/**
	 * Values limited to the keys a set of fields registers, walking into
	 * nested sets of fields.
	 *
	 * @since 6.2
	 */

	private function limit( $values, $fields ) {
		$limited = array();

		foreach ( is_array( $values ) ? $values : array() as $key => $value ) {
			if ( ! isset( $fields[$key] ) || ! is_array( $fields[$key] ) )
				continue;

			if ( ! isset( $fields[$key]['type'] ) && is_array( $value ) ) {
				$nested = $this->limit( $value, $fields[$key] );

				if ( $nested )
					$limited[$key] = $nested;
			}
			else
				$limited[$key] = $value;
		}

		return $limited;
	}

	/**
	 * Add a portable name for each ID in a group's values: the file path
	 * of an attachment, a menu's name.
	 *
	 * @since 6.2
	 */

	private function collect_refs( $values, $fields, &$refs ) {
		foreach ( $values as $key => $value ) {
			$field = isset( $fields[$key] ) && is_array( $fields[$key] ) ? $fields[$key] : array();
			$type = isset( $field['type'] ) ? $field['type'] : null;

			if ( $type === 'upload' && is_array( $value ) && ! empty( $value['id'] ) ) {
				foreach ( wp_parse_id_list( $value['id'] ) as $id )
					$this->add_ref( 'attachment', $id, $refs );
			}
			elseif ( ! empty( $field['ref'] ) && is_scalar( $value ) && is_numeric( $value ) )
				$this->add_ref( $field['ref'], (int) $value, $refs );
			elseif ( in_array( $type, array( 'group', 'builder' ), true ) && is_array( $value ) ) {
				foreach ( $value as $item )
					if ( is_array( $item ) )
						$this->collect_refs( $item, isset( $field['fields'] ) ? $field['fields'] : array(), $refs );
			}
			elseif ( $type === null && is_array( $value ) )
				$this->collect_refs( $value, $field, $refs );
		}
	}

	/**
	 * Name one ID for the file's refs.
	 *
	 * @since 6.2
	 */

	private function add_ref( $type, $id, &$refs ) {
		if ( ! $id || isset( $refs["$type:$id"] ) )
			return;

		$file = $type === 'attachment' ? get_post_meta( $id, '_wp_attached_file', true ) : '';
		$menu = $type === 'nav_menu' ? wp_get_nav_menu_object( $id ) : false;

		if ( $file )
			$refs["$type:$id"] = array( 'file' => $file );
		elseif ( $menu )
			$refs["$type:$id"] = array( 'name' => $menu->name );
	}

	/**
	 * Swap the file's IDs for this site's, found by their refs. A value
	 * that can't be found is taken out of the input, so the site's
	 * current value stays, and listed.
	 *
	 * @since 6.2
	 */

	private function swap_refs( $input, $fields, $refs, &$skipped, $prefix = '' ) {
		foreach ( $input as $key => $value ) {
			$field = isset( $fields[$key] ) && is_array( $fields[$key] ) ? $fields[$key] : array();
			$type = isset( $field['type'] ) ? $field['type'] : null;
			$name = $prefix . $key;

			if ( $type === 'upload' && is_array( $value ) && ! empty( $value['id'] ) ) {
				$ids = array();

				foreach ( wp_parse_id_list( $value['id'] ) as $id )
					$ids[] = $this->find_ref( 'attachment', $id, $refs );

				if ( in_array( 0, $ids, true ) ) {
					unset( $input[$key] );
					$skipped[] = sprintf( __( 'The image for `%s` isn\'t in this site\'s Media Library, so it was left as it is.', 'md' ), $name );
				}
				else
					$input[$key]['id'] = isset( $field['multiple'] ) ? implode( ',', $ids ) : $ids[0];
			}
			elseif ( ! empty( $field['ref'] ) && is_scalar( $value ) && is_numeric( $value ) && $value ) {
				$id = $this->find_ref( $field['ref'], (int) $value, $refs );

				if ( $id )
					$input[$key] = (string) $id;
				else {
					unset( $input[$key] );
					$skipped[] = sprintf( __( 'The menu for `%s` wasn\'t found on this site, so it was left as it is.', 'md' ), $name );
				}
			}
			elseif ( in_array( $type, array( 'group', 'builder' ), true ) && is_array( $value ) ) {
				foreach ( $value as $item => $item_values )
					if ( is_array( $item_values ) )
						$input[$key][$item] = $this->swap_refs( $item_values, isset( $field['fields'] ) ? $field['fields'] : array(), $refs, $skipped, "$name.$item." );
			}
			elseif ( $type === null && is_array( $value ) )
				$input[$key] = $this->swap_refs( $value, $field, $refs, $skipped, "$name." );
		}

		return $input;
	}

	/**
	 * This site's ID for a file ID, from its ref, or 0.
	 *
	 * @since 6.2
	 */

	private function find_ref( $type, $id, $refs ) {
		$ref = isset( $refs["$type:$id"] ) && is_array( $refs["$type:$id"] ) ? $refs["$type:$id"] : array();

		if ( $type === 'attachment' && ! empty( $ref['file'] ) && is_string( $ref['file'] ) ) {
			$found = get_posts( array(
				'post_type' => 'attachment',
				'post_status' => 'inherit',
				'posts_per_page' => 1,
				'fields' => 'ids',
				'meta_query' => array( array( 'key' => '_wp_attached_file', 'value' => $ref['file'] ) )
			) );

			return $found ? (int) $found[0] : 0;
		}

		if ( $type === 'nav_menu' && ! empty( $ref['name'] ) && is_string( $ref['name'] ) ) {
			$menu = wp_get_nav_menu_object( $ref['name'] );

			return $menu ? (int) $menu->term_id : 0;
		}

		return 0;
	}

	/**
	 * A file's settings entries in import order, each with the part it
	 * belongs to.
	 *
	 * @since 6.2
	 */

	private function entries( $file ) {
		$entries = array();

		foreach ( array_keys( $this->sections() ) as $section ) {
			if ( $section === 'widgets' || empty( $file['sections'][$section] ) || ! is_array( $file['sections'][$section] ) )
				continue;

			foreach ( $file['sections'][$section] as $raw ) {
				$raw = is_array( $raw ) ? $raw : array();
				$raw['section'] = $section;
				$raw['part'] = $this->part_key( $section, $raw );
				$entries[] = $raw;
			}
		}

		return $entries;
	}

	/**
	 * Import one settings entry through apply(), reporting what it skips.
	 * Files only reach site, post type and taxonomy settings, never one
	 * object's meta.
	 *
	 * @since 6.2
	 */

	private function import_entry( $entry, $refs, $save, &$skipped ) {
		if ( ! isset( $entry['scope'], $entry['group'], $entry['values'] ) || ! is_string( $entry['scope'] ) || ! is_string( $entry['group'] ) || ! is_array( $entry['values'] ) ) {
			$skipped[] = sprintf( __( 'An entry in `%s` can\'t be read.', 'md' ), $entry['section'] );
			return;
		}

		$scope = md_scope::parse( $entry['scope'] );

		if ( ! is_wp_error( $scope ) && ! in_array( $scope->type, array( 'site', 'post_type', 'taxonomy' ), true ) ) {
			$skipped[] = sprintf( __( 'Settings for `%s` can\'t be imported.', 'md' ), $entry['scope'] );
			return;
		}

		$target = is_wp_error( $scope ) ? $scope : $scope->target( $entry['group'] );

		if ( is_wp_error( $target ) ) {
			$skipped[] = $target->get_error_message();
			return;
		}

		$applied = $save->apply( $entry['scope'], $entry['group'], $this->entry_input( $entry, $scope, $target, $refs, $skipped ), array( 'compile' => false ) );

		if ( is_wp_error( $applied ) )
			$skipped[] = $applied->get_error_message();
		else
			$skipped = array_merge( $skipped, $applied['warnings'] );
	}

	/**
	 * apply() input for one entry: replace or merge over what's stored,
	 * with this site's IDs swapped in.
	 *
	 * @since 6.2
	 */

	private function entry_input( $entry, $scope, $target, $refs, &$skipped ) {
		$fields = $this->section_fields( $entry['section'], $entry['group'], $target['fields'] );
		$stored = $scope->read( $entry['group'] );
		$values = $this->limit( $entry['values'], $fields );

		foreach ( array_keys( array_diff_key( $entry['values'], $values ) ) as $key )
			$skipped[] = sprintf( __( '`%1$s.%2$s` isn\'t used on this site.', 'md' ), $entry['group'], $key );

		$input = isset( $entry['mode'] ) && $entry['mode'] === 'merge' ?
			md_save::merge_items( $stored, $values, $fields ) :
			$this->replace_input( $stored, $values, $fields );

		return $this->swap_refs( $input, $fields, $refs, $skipped );
	}

	/**
	 * The parts a file can import and their labels, in order: one per
	 * section it holds, and one per labelled entry ("Docs sidebars").
	 *
	 * @since 6.2
	 */

	private function parts( $file ) {
		$parts = array();

		foreach ( $this->sections() as $section => $label ) {
			$data = isset( $file['sections'][$section] ) && is_array( $file['sections'][$section] ) ? $file['sections'][$section] : array();

			if ( $section === 'widgets' ) {
				if ( $data )
					$parts[$section] = $label;

				continue;
			}

			foreach ( $data as $raw )
				$parts[$this->part_key( $section, $raw )] = is_array( $raw ) && ! empty( $raw['label'] ) && is_string( $raw['label'] ) ? sanitize_text_field( $raw['label'] ) : $label;

			if ( $section === 'dropins' && ! empty( $file['sections']['options'] ) )
				$parts[$section] = $label;
		}

		return $parts;
	}

	/**
	 * A part's key: its section, or section:label for a labelled entry.
	 *
	 * @since 6.2
	 */

	private function part_key( $section, $raw ) {
		return is_array( $raw ) && ! empty( $raw['label'] ) && is_string( $raw['label'] ) ? "$section:" . sanitize_title( $raw['label'] ) : $section;
	}

	/**
	 * The drop-ins a file's entries and options come from.
	 *
	 * @since 6.2
	 */

	private function file_sources( $file ) {
		$sources = array();

		foreach ( array( 'settings', 'design', 'post_types', 'dropins', 'options' ) as $section )
			foreach ( isset( $file['sections'][$section] ) && is_array( $file['sections'][$section] ) ? $file['sections'][$section] : array() as $raw )
				if ( is_array( $raw ) && ! empty( $raw['source'] ) && is_string( $raw['source'] ) && $raw['source'] !== 'theme' )
					$sources[] = sanitize_key( $raw['source'] );

		return array_values( array_unique( $sources ) );
	}

	/**
	 * Active drop-ins with their name and version, for the file.
	 *
	 * @since 6.2
	 */

	private function dropins_list() {
		$list = array();

		foreach ( md_get_dropins( 'active' ) as $slug ) {
			$details = md_get_dropin_details( $slug );

			if ( $details )
				$list[$slug] = array( 'name' => $details['Name'], 'version' => $details['Version'] );
		}

		return $list;
	}

	/**
	 * Options a drop-in stores outside MD's settings and exports through
	 * md_export_options: array( option => array( source, sanitize ) ).
	 * Without a sanitize callback an option is exported but not imported.
	 *
	 * @since 6.2
	 */

	private function export_options() {
		$options = array();

		foreach ( (array) apply_filters( 'md_export_options', array() ) as $option => $args )
			if ( is_string( $option ) && is_array( $args ) && ! empty( $args['source'] ) )
				$options[$option] = $args;

		return $options;
	}

	/**
	 * Import drop-in options through their sanitize callback.
	 *
	 * @since 6.2
	 */

	private function import_options( $options, &$skipped ) {
		$declared = $this->export_options();

		foreach ( (array) $options as $option => $data ) {
			if ( empty( $declared[$option]['sanitize'] ) || ! is_callable( $declared[$option]['sanitize'] ) ) {
				$skipped[] = sprintf( __( 'The `%s` settings can\'t be imported on this site.', 'md' ), $option );
				continue;
			}

			update_option( $option, call_user_func( $declared[$option]['sanitize'], isset( $data['values'] ) && is_array( $data['values'] ) ? $data['values'] : array() ) );
		}
	}

	/**
	 * Widgets with their settings and the sidebar each sits in.
	 *
	 * @since 6.2
	 */

	private function export_widgets() {
		$sidebars = get_option( 'sidebars_widgets', array() );
		$widgets = array( 'sidebars' => array(), 'instances' => array() );

		foreach ( is_array( $sidebars ) ? $sidebars : array() as $sidebar => $ids ) {
			if ( ! is_array( $ids ) || $sidebar === 'wp_inactive_widgets' || strpos( $sidebar, 'orphaned_widgets' ) === 0 )
				continue;

			$widgets['sidebars'][$sidebar] = array();

			foreach ( $ids as $id ) {
				$instance = $this->widget_instance( $id );

				if ( $instance === null )
					continue;

				$widgets['sidebars'][$sidebar][] = $id;
				$widgets['instances'][$id] = $instance;
			}
		}

		return $widgets;
	}

	/**
	 * A widget's settings by its ID, ex: text-2, or null.
	 *
	 * @since 6.2
	 */

	private function widget_instance( $id ) {
		if ( ! is_string( $id ) || ! preg_match( '/^(.+)-(\d+)$/', $id, $match ) )
			return null;

		$option = get_option( "widget_{$match[1]}", array() );

		return isset( $option[$match[2]] ) && is_array( $option[$match[2]] ) ? $option[$match[2]] : null;
	}

	/**
	 * Replace the widgets of each sidebar in the file that exists here.
	 * Each widget gets a new number in its type's option; the sidebar's
	 * old widgets are deleted.
	 *
	 * @since 6.2
	 */

	private function import_widgets( $widgets, &$skipped ) {
		$sidebars = get_option( 'sidebars_widgets', array() );
		$sidebars = is_array( $sidebars ) ? $sidebars : array();
		$instances = isset( $widgets['instances'] ) && is_array( $widgets['instances'] ) ? $widgets['instances'] : array();
		$types = $this->widget_types();
		$areas = $this->widget_areas();
		$options = array();

		foreach ( isset( $widgets['sidebars'] ) && is_array( $widgets['sidebars'] ) ? $widgets['sidebars'] : array() as $sidebar => $ids ) {
			if ( ! in_array( $sidebar, $areas, true ) ) {
				if ( ! $ids )
					continue;

				$skipped[] = sprintf( __( 'The `%s` sidebar doesn\'t exist here, so its widgets weren\'t placed. Select Theme settings to create it.', 'md' ), $sidebar );
				continue;
			}

			foreach ( isset( $sidebars[$sidebar] ) && is_array( $sidebars[$sidebar] ) ? $sidebars[$sidebar] : array() as $old ) {
				if ( preg_match( '/^(.+)-(\d+)$/', $old, $match ) ) {
					$options[$match[1]] = isset( $options[$match[1]] ) ? $options[$match[1]] : get_option( "widget_{$match[1]}", array() );
					unset( $options[$match[1]][$match[2]] );
				}
			}

			$sidebars[$sidebar] = array();

			foreach ( (array) $ids as $id ) {
				if ( ! is_string( $id ) || ! preg_match( '/^(.+)-(\d+)$/', $id, $match ) || ! isset( $instances[$id] ) || ! is_array( $instances[$id] ) )
					continue;

				$type = $match[1];

				if ( ! in_array( $type, $types, true ) ) {
					$skipped[] = sprintf( __( 'The `%s` widget isn\'t available on this site.', 'md' ), $type );
					continue;
				}

				$options[$type] = isset( $options[$type] ) ? $options[$type] : get_option( "widget_$type", array() );
				$options[$type] = is_array( $options[$type] ) ? $options[$type] : array();
				$numbers = array_filter( array_keys( $options[$type] ), 'is_int' );
				$number = max( array_merge( array( 1 ), $numbers ) ) + 1;

				$options[$type][$number] = current_user_can( 'unfiltered_html' ) ? $instances[$id] : $this->kses_widget( $instances[$id] );
				$options[$type]['_multiwidget'] = 1;
				$sidebars[$sidebar][] = "$type-$number";
			}
		}

		foreach ( $options as $type => $option )
			update_option( "widget_$type", $option );

		update_option( 'sidebars_widgets', $sidebars );
	}

	/**
	 * Widget text through wp_kses_post() for users who can't post
	 * unfiltered HTML.
	 *
	 * @since 6.2
	 */

	public function kses_widget( $instance ) {
		foreach ( $instance as $key => $value ) {
			if ( is_array( $value ) )
				$instance[$key] = $this->kses_widget( $value );
			elseif ( is_string( $value ) )
				$instance[$key] = wp_kses_post( $value );
		}

		return $instance;
	}

	/**
	 * Widget types registered here, by ID base.
	 *
	 * @since 6.2
	 */

	public function widget_types() {
		global $wp_widget_factory;

		$types = array();

		if ( isset( $wp_widget_factory->widgets ) )
			foreach ( $wp_widget_factory->widgets as $widget )
				$types[] = $widget->id_base;

		return $types;
	}

	/**
	 * Sidebars that exist here: the registered ones, and MD's custom
	 * sidebars and panels from settings the same import just saved.
	 *
	 * @since 6.2
	 */

	public function widget_areas() {
		global $wp_registered_sidebars;

		$settings = get_option( 'marketers_delight', array() );
		$areas = is_array( $wp_registered_sidebars ) ? array_keys( $wp_registered_sidebars ) : array();

		foreach ( array( 'sidebars', 'panels' ) as $type )
			if ( isset( $settings['settings'][$type] ) && is_array( $settings['settings'][$type] ) )
				$areas = array_merge( $areas, array_map( 'strval', array_keys( $settings['settings'][$type] ) ) );

		return $areas;
	}

	/**
	 * The one list of what Design is: whole groups, and trees of keys
	 * inside groups that mix design with site content.
	 *
	 * @since 6.2
	 */

	private function design_groups() {
		$type = array_fill_keys( array( 'color', 'font_family', 'font_type', 'font_weight', 'font_size', 'line_height' ), true );

		return array(
			'colors' => true,
			'typography' => true,
			'logo' => array(
				'logo_width' => true,
				'site_title' => $type,
				'site_tagline' => $type
			)
		);
	}

}
