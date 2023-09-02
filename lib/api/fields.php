<?php
/**
 * Organize different admin HTML fields and their respective data
 * into this class. Pulls data from MDAPI $this->field() method and
 * is used exclusively to create admin options throughout MD.
 *
 * @since 4.7
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class md_fields {

	/**
	 * Set properties of instance.
	 *
	 * @since 5.0
	 */

	public function __construct( $args ) {
		$this->_id       = $args['id'];
		$this->_clean_id = $args['clean_id'];
		$this->_prefix   = $args['prefix'];
		$this->_option   = isset( $args['option'] ) ? $args['option'] : 'marketers_delight';
	}

	/**
	 * Versatile in nature, this is the method called to load every field
	 * within the MD API. To support many nested option formats field()
	 * switches up how it processes data based on the user's needs.
	 * In its simplest form, field accepts the field name ($field) and an array
	 * of arguments ($args), but to create different option groups per page
	 * the argument names get switched up between $parent, $fields, and $args.
	 * More clear documentation coming soon.
	 *
	 * @since 4.0
	 */

	public function field( $field, $args ) {
		$page = $has_parent = false;
		$wrap_classes = array( 'md-field' );
		$clean_id = $this->_clean_id;
		$name = "{$this->_option}[$clean_id]";
		$id = "{$this->_option}_{$clean_id}";
		$screen = get_current_screen();
		$register = md_register();
		$args['field'] = $field;

		if ( wp_doing_ajax() || in_array( $screen->base, array( 'post', 'post-new' ) ) )
			$setting = get_post_meta( get_the_ID(), $this->_option, true );
		elseif ( $screen->base == 'term' ) {
			$tag_id = esc_attr( $_GET['tag_ID'] );
			$setting = get_term_meta( $tag_id, $this->_option, true );
		}
		elseif ( in_array( $screen->base, array( 'profile', 'user-edit' ) ) && isset( $args['user_meta'] ) ) {
			$user_meta = $args['user_meta'];
			$user_id = esc_attr( $user_meta->data->ID );
			$setting = get_user_meta( $user_id, $this->_option, true );
		}
		else {
			$setting = get_option( $this->_option );
			$page = esc_attr( $_GET['page'] );
			$page_types = md_admin_settings();

			if ( ! empty( $page_types[$page] ) ) {
				$children = $page_types[$page];

				if ( in_array( $clean_id, $children ) )
					$has_parent = true;

				if ( $has_parent ) {
					$page = md_clean_id( $page );
					$setting = ! empty( $setting[$page] ) ? $setting[$page] : '';
					$name = "{$this->_option}[{$page}][$clean_id]";
					$id = "{$this->_option}_{$page}_{$clean_id}";
				}
			}
		}

		$group = ! empty( $setting[$clean_id] ) ? $setting[$clean_id] : array();

		if ( is_array( $field ) ) {
			foreach ( $field as $key ) {
				$name .= "[$key]";
				$id .= "_{$key}";
				$group = isset( $group[$key] ) ? $group[$key] : '';
			}

			$option = $group;
		}
		else {
			$name .= "[$field]";
			$id .= "_{$field}";
			$option = isset( $setting[$clean_id][$field] ) ? $setting[$clean_id][$field] : '';
		}

		if ( isset( $args['label'] ) && $args['type'] !== 'group' )
			$this->label( $id, $args );

		$wrap_classes[] = 'md-field-' . esc_attr( $args['type'] );

		if ( isset( $args['wrap_classes'] ) )
			$wrap_classes[] = $args['wrap_classes'];

		$wrap_classes = join( ' ', $wrap_classes );

		echo '<div class="' . esc_attr( $wrap_classes ) . '">';

		$this->field_type( $args['type'], $name, $id, $option, $args );

		if ( isset( $args['description'] ) && $args['type'] !== 'builder' )
			$this->description( $args['description'] );

		echo '</div>';
	}

	/**
	 * Get field values based on current screen.
	 *
	 * @since 5.0.9
	 */

	public function get_field( $keys, $default = null ) {
		$c = 0;
		$screen = get_current_screen();

		if ( in_array( $screen->base, array( 'post', 'post-new' ) ) )
			$option = md_post_meta();
		elseif ( $screen->base == 'term' )
			$option = md_term_meta();
		elseif ( in_array( $screen->base, array( 'profile', 'user-edit' ) ) )
			$option = md_user_meta();
		elseif ( ! empty( $_GET['page'] ) ) {
			$page = esc_attr( $_GET['page'] );
			$page_types = md_admin_settings();
			$option = md_setting();

			if ( ! empty( $page_types[$page] ) ) {
				$page = md_clean_id( $page );
				$option = $option[$page];
			}
		}

		if ( isset( $keys ) ) {
			if ( is_string( $keys ) )
				$keys = (array) $keys;
			foreach ( $keys as $key ) {
				$option = ! empty( $option[$key] ) ? $option[$key] : ( $c == 0 ? array() : '' );
				$c++;
			}
		}

		if ( empty( $option ) && isset( $default ) )
			$option = $default;

		return $option;
	}

	/**
	 * Get field values based on current admin screen.
	 *
	 * @since 4.1
	 */

	public function module( $keys, $default = null ) {
		if ( is_string( $keys ) )
			$keys = (array) $keys;

		$c = 0;
		$fields = array();
		$screen = get_current_screen();
		$page_types = md_admin_settings();

		$page = isset( $_GET['page'] ) ? esc_attr( $_GET['page'] ) : '';
		$tag_id = isset( $_GET['tag_ID'] ) ? esc_attr( $_GET['tag_ID'] ) : '';

		if ( in_array( $screen->base, array( 'post', 'post-new' ) ) ) {
			array_unshift( $keys, $this->_clean_id );
			$fields = md_post_meta( $keys, null, $default );
		}
		elseif ( $screen->base == 'term' && ! empty( $tag_id ) ) {
			array_unshift( $keys, $this->_clean_id );
			$fields = md_term_meta( $keys, $tag_id, $default );
		}
		elseif ( ! empty( $page_types[$page] ) ) {
			$page_id = md_clean_id( $page );
			array_unshift( $keys, $page_id, $this->_clean_id );
			$fields = md_setting( $keys, $default );
		}
		else {
			array_unshift( $keys, $this->_clean_id );
			$fields = md_setting( $keys );
		}

		return $fields;
	}

	/**
	 * Get field based on type and trickle data down to display field HTML.
	 *
	 * @since 4.7
	 */

	public function field_type( $type, $name, $id, $option, $args ) {
		if ( $type == 'text' )
			$this->text( $name, $id, $option, $args );

		if ( $type == 'textarea' )
			$this->textarea( $name, $id, $option, $args );

		if ( $type == 'number' )
			$this->number( $name, $id, $option, $args );

		if ( $type == 'code' )
			$this->code( $name, $id, $option, $args );

		if ( $type == 'url' )
			$this->url( $name, $id, $option, $args );

		if ( $type == 'hidden' )
			$this->hidden( $name, $id, $option, $args );

		if ( $type == 'checkbox' )
			$this->checkbox( $name, $id, $option, $args );

		if ( $type == 'radio' )
			$this->radio( $name, $id, $option, $args );

		if ( $type == 'select' )
			$this->select( $name, $id, $option, $args );

		if ( $type == 'range' )
			$this->range( $name, $id, $option, $args );

		if ( $type == 'color' )
			$this->color( $name, $id, $option, $args );

		if ( $type == 'editor' )
			$this->editor( $name, $id, $option, $args );

		if ( $type == 'upload' )
			$this->upload( $name, $id, $option, $args );

		if ( $type == 'group' )
			$this->group( $name, $id, $option, $args );

		if ( $type == 'builder' )
			$this->builder( $name, $id, $option, $args );

		if ( $type == 'terms' )
			$this->terms( $name, $id, $option, $args );

		#deprecated 4.8.4
		if ( $type == 'media' )
			$this->media( $name, $id, $option, $args );
	}

	/**
	 * Easily create a label for fields.
	 *
	 * @since 4.0
	 */

	public function label( $id, $args ) {
		$classes = isset( $args['type'] ) && $args['type'] == 'group' ? ' md-title' : '';
		$icon = isset( $args['label_icon'] ) ? $args['label_icon'] : '';
	?>
		<p class="md-label-wrap">
			<label for="<?php echo esc_attr( $id ); ?>" class="md-label<?php echo esc_attr( $classes ); ?>">
				<?php echo ( $icon ? '<i class="' . esc_attr( $icon ) . '"></i>' : '' ); ?>
				<?php echo md_text_field( $args['label'] ); ?>
			</label>
		</p>
	<?php }

	/**
	 * Easily create a description for fields.
	 *
	 * @since 5.0
	 */

	public function description( $description ) { ?>
		<p class="description"><?php echo md_text_field( $description ); ?></p>
	<?php }

	/**
	 * Outputs a simple text input field with attributes.
	 *
	 * @since 4.0
	 */

	public function text( $name, $id, $option, $args ) {
		$type = ! empty( $args['hidden'] ) ? 'hidden' : 'text';
		$option = ! empty( $args['option'] ) ? $args['option'] : $option;
		$value = isset( $args['default'] ) && $option == '' ? $args['default'] : $option;
		$placeholder = isset( $args['placeholder'] ) ? ' placeholder="' . esc_attr( $args['placeholder'] ) . '"' : '';
		$readonly = ! empty( $args['readonly_after_save'] ) && ! empty( $option ) ? ' readonly' : '';
		$style = isset( $args['style'] ) ? ' style="' . esc_attr( $args['style'] ) . '"' : '';
		$populate = isset( $args['populate'] ) ? ' md-populate-' . $args['populate'] : '';
		$classes = isset( $args['classes'] ) ? ' ' . $args['classes'] : '';
		$disabled = ! empty( $args['disabled'] ) ? ' disabled' : '';
		$unit = isset( $args['unit'] ) ? ' <label for="' . $id . '" class="description">' . $args['unit'] . '</label> ' : '';
	?>
		<?php echo $unit; ?><input type="<?php echo esc_attr( $type ); ?>" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( stripslashes( $value ) ); ?>"<?php echo $placeholder; ?> class="regular-text<?php echo esc_attr( $classes ); ?><?php echo esc_attr( $populate ); ?>"<?php echo $readonly; ?><?php echo $style; ?><?php echo $disabled; ?> />
	<?php }

	/**
	 * Outputs a simple textarea.
	 *
	 * @since 4.0
	 */

	public function textarea( $name, $id, $option, $args ) {
		$classes = array( 'large-text' );
		if ( isset( $args['classes'] ) )
			$classes[] = $args['classes'];
		$classes = join( ' ', $classes );
		$rows = ! empty( $args['rows'] ) ? intval( $args['rows'] ) : 6;
	?>
		<textarea name="<?php echo $name; ?>" id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $classes ); ?>" rows="<?php echo esc_attr( $rows ); ?>"><?php echo esc_attr( stripslashes( $option ) ); ?></textarea>
	<?php }

	/**
	 * Outputs a simple number input field with attributes.
	 *
	 * @since 4.0
	 */

	public function number( $name, $id, $option, $args ) {
		$class_size  = isset( $args['size'] ) ? 'size="' . $args['size'] . '"' : '';
		$placeholder = isset( $args['placeholder'] ) ? ' placeholder="' . esc_attr( $args['placeholder'] ) . '"' : '';
		$width = isset( $args['width'] ) ? $args['width'] : 70;
		$max = isset( $args['max'] ) ? ' max="' . esc_attr( $args['max'] ) . '"' : '';
		$unit = isset( $args['unit'] ) ? ' <label for="' . $id . '" class="description">' . $args['unit'] . '</label>' : '';
	?>
		<input type="number" class="regular-text" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( $option ); ?>"<?php echo $placeholder; ?> style="width: <?php echo $width; ?>px;"<?php echo $max; ?> /><?php echo $unit; ?>
	<?php }

	/**
	 * Outputs a simple textarea to paste code into.
	 *
	 * @since 4.0
	 */

	public function code( $name, $id, $option, $args ) {
		$rows = isset( $args['rows'] ) ? $args['rows'] : 10;
	?>
		<div class="md-code-editor">
			<textarea name="<?php echo $name; ?>" id="<?php echo esc_attr( $id ); ?>" class="large-text" rows="<?php echo esc_attr( $rows ); ?>"><?php echo stripslashes( $option ); ?></textarea>
		</div>
		<?php wp_enqueue_script( 'md-code-editor' ); ?>
	<?php }

	/**
	 * Outputs a simple text field for URL entry.
	 *
	 * @since 4.0
	 */

	public function url( $name, $id, $option, $args ) {
		$placeholder = isset( $args['placeholder'] ) ? esc_attr( $args['placeholder'] ) : 'https://';
		$disabled = isset( $args['disabled'] ) ? ' disabled' : '';
	?>
		<input type="text" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( esc_url( $option ) ); ?>" class="regular-text" placeholder="<?php echo $placeholder; ?>"<?php echo $disabled; ?> />
	<?php }

	/**
	 * Outputs a multi-checkbox field.
	 *
	 * @since 4.0
	 */

	public function checkbox( $name, $id, $option, $args ) {
		$classes = array( 'md-checkboxes' );
		if ( isset( $args['multi'] ) )
			$classes[] = 'md-multi-checkbox';
		if ( isset( $args['inline'] ) )
			$classes[] = 'md-inline-checkbox';
		if ( isset( $args['classes'] ) )
			$classes[] = $args['classes'];
		$classes = join( ' ', $classes );
	?>
		<div class="<?php echo esc_attr( $classes ); ?>">
			<?php foreach ( $args['options'] as $val => $label ) :
				$nameval = esc_attr( "{$name}[$val]" );
				$idval = esc_attr( "{$id}_$val" );
				$check = isset( $option[$val] ) ? esc_attr( $option[$val] ) : '';
			?>
				<p class="md-checkbox md-checkbox-<?php echo esc_attr( $val ); ?>">
					<input type="checkbox" name="<?php echo $nameval; ?>" id="<?php echo $idval; ?>" value="1"<?php echo checked( $check ); ?> />
					<label for="<?php echo $idval; ?>"><?php echo $label; ?></label>
				</p>
			<?php endforeach; ?>
		</div>
	<?php }

	/**
	 * Outputs single-select radio fields.
	 *
	 * @since 5.0
	 */

	public function radio( $name, $id, $option, $args ) {
		$style = array();
		$layout = isset( $args['layout'] ) ? $args['layout'] : '';
		$columns = isset( $args['columns'] ) ? round( ( 100 / $args['columns'] ) - 2 ) : '';
		if ( ! empty( $columns ) ) {
			$style['width'] = $columns;
			$style['width_unit'] = '%';
		}
		foreach ( $args['options'] as $val => $label ) {
			$idval = esc_attr( "{$id}_$val" );
			$image = is_array( $label ) && isset( $label['image'] ) ? esc_url( $label['image'] ) : '';
			$bg_image = $layout !== 'banner' ? ' style="background-image: url(\'' . esc_url( $label['image'] ) . '\');"' : '';
			$text = is_array( $label ) ? $label['name'] : $label;
	?>
		<label for="<?php echo $idval; ?>" class="md-radio <?php echo ( $layout == 'banner' ? 'md-radio-banner' : 'md-radios' ) . ( ! empty( $image ) ? ' md-radio-has-image' : '' ); ?>"<?php echo md_style( $style ); ?>>
			<?php if ( $layout == 'banner' && ! empty( $image ) ) : ?>
				<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_html( $text ); ?>" class="md-radio-image" />
			<?php endif; ?>
			<input type="radio" name="<?php echo $name; ?>" id="<?php echo $idval; ?>" class="md-radio-check" value="<?php echo esc_attr( $val ); ?>"<?php echo checked( $option, $val ); ?> />
			<span class="md-radio-label"<?php echo $bg_image; ?>><span class="md-radio-text"><?php echo esc_html( $text ); ?></span></span>
			<?php if ( ! empty( $label['description'] ) ) : ?>
				<p class="description"><?php echo esc_html( $label['description'] ); ?></p>
			<?php endif; ?>
		</label>
	<?php } }

	/**
	 * Outputs a simple select field.
	 *
	 * @since 4.0
	 */

	public function select( $name, $id, $option, $args ) {
		$classes = isset( $args['classes'] ) ? ' class="' . $args['classes'] . '"' : '';
		$style = isset( $args['style'] ) ? ' style="' . esc_attr( $args['style'] ) . '"' : '';
	?>
		<select name="<?php echo $name; ?>" id="<?php echo $id; ?>"<?php echo $classes; ?><?php echo $style; ?>>
			<?php if ( isset( $args['empty_label'] ) ) : ?>
				<option value=""><?php echo esc_html( $args['empty_label'] ); ?></option>
			<?php endif; ?>
			<?php if ( isset( $args['optgroup'] ) ) : ?>
				<?php foreach ( $args['options'] as $group => $items ) : ?>
					<optgroup label="<?php echo esc_html( ucwords( str_replace( '_', ' ', $group ) ) ); ?>">
					<?php foreach ( $items as $list => $fields ) : ?>
						<option value="<?php echo esc_attr( $list ); ?>"<?php echo selected( $option, $list, false ); ?>><?php echo esc_html( $fields['name'] ); ?></option>
					<?php endforeach; ?>
					</optgroup>
				<?php endforeach; ?>
			<?php elseif ( ! empty( $args['options'] ) ) : ?>
				<?php foreach ( $args['options'] as $val => $label ) : ?>
					<option value="<?php echo esc_attr( $val ); ?>"<?php echo selected( $option, $val, false ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			<?php endif; ?>
		</select>
	<?php }

	/**
	 * Create a range field with reset value.
	 *
	 * @since 5.0
	 */

	public function range( $name, $id, $option, $args ) {
		$default = ! empty( $args['default'] ) ? $args['default'] : '';
		$placeholder = ! empty( $args['placeholder'] ) ? $args['placeholder'] : $default;
		$unit = ! empty( $args['unit'] ) ? $args['unit'] : 'px';
		$min = ! empty( $args['min'] ) ? $args['min'] : 0;
		$max = ! empty( $args['max'] ) ? $args['max'] : 100;
	?>
		<p class="md-range">
			<input type="range" class="md-range-field" value="<?php echo esc_attr( $option ); ?>" min="<?php echo $min; ?>" max="<?php echo $max; ?>" />
			<input name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="md-range-number" type="number" value="<?php echo esc_attr( $option ); ?>" placeholder="<?php echo $placeholder; ?>" style="width: 75px;" /> <?php echo esc_html( $unit ); ?>
			<span class="md-range-reset dashicons dashicons-image-rotate" data-default="<?php echo $default; ?>"></span>
		</p>
	<?php }

	/**
	 * Outputs upload field. Only built to support
	 * media, will be expanding to other file types soon.
	 *
	 * @since 4.8.4
	 */

	public function upload( $name, $id, $option, $args ) {
		$type = isset( $args['upload_type'] ) ? $args['upload_type'] : 'media';
		$upload_url = ! empty( $option['url'] ) ? $option['url'] : '';
		$upload_id = ! empty( $option['id'] ) ? $option['id'] : '';
		$placeholder = isset( $args['placeholder'] ) ? ' placeholder="' . $args['placeholder'] . '"' : '';
		$upload_action = isset( $args['upload_action'] ) ? $args['upload_action'] : '';
		$accepts = isset( $args['accept'] ) ? $args['accept'] : '';
		$accept = ! empty( $accepts ) ? " accept=\"$accepts\"" : '';
		$classes = isset( $args['classes'] ) ? ' ' . $args['classes'] : '';
	?>
		<?php if ( $type == 'media' ) : ?>
			<div class="md-upload md-upload-<?php echo $type; ?><?php echo ! empty( $upload_url ) ? ' has-upload' : ''; ?><?php echo esc_attr( $classes ); ?>">
				<div class="md-uploader">
					<div class="md-upload-preview md-upload-add">
						<div class="md-upload-previewer">
							<span class="dashicons dashicons-upload"></span>
							<p class="md-upload-preview-text"><?php echo __( 'Click to upload', 'md' ); ?></p>
						</div>
						<div class="md-upload-preview-image">
							<img src="<?php echo $upload_url; ?>" alt="<?php echo __( 'Preview Image', 'md' ); ?>" />
						</div>
					</div>
					<div class="md-upload-controls">
						<label class="md-label" for="<?php echo $id; ?>_url"><?php echo __( 'Image URL', 'md' ); ?></label>
						<input type="url" class="md-upload-url regular-text" name="<?php echo $name; ?>[url]" id="<?php echo "{$id}_url"; ?>" value="<?php echo esc_attr( $upload_url ); ?>" placeholder="https://">
						<input type="hidden" class="md-upload-id regular-text" name="<?php echo $name; ?>[id]" id="<?php echo "{$id}_id"; ?>" value="<?php echo esc_attr( $upload_id ); ?>" placeholder="">
						<?php if ( $upload_id ) : ?>
							<div class="md-upload-id-label">
								<?php echo sprintf( __( 'ID: %s', 'md' ), $upload_id ); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="md-upload-buttons">
					<input type="button" class="md-upload-add button" value="<?php echo __( 'Add Image', 'md' ); ?>" />
					<input type="button" class="md-upload-remove button" value="<?php echo __( 'Remove Image', 'md' ); ?>" />
				</div>
			</div>
			<?php wp_enqueue_media(); ?>
		<?php elseif ( $type == 'file' ) :
			$alert = isset( $args['alert'] ) ? $args['alert'] : __( 'You are about to upload a new file. Do you want to proceed?', 'md' );
			$success_text = isset( $args['success_text'] ) ? $args['success_text'] : __( 'File successfully updated.', 'md' );
		?>
			<div class="md-file-upload">
				<div class="md-file-upload-field">
					<input type="file" name="<?php echo $name; ?>[url]" id="<?php echo esc_attr( "{$id}_file" ); ?>"<?php echo $accept; ?> />
					<span class="md-loading md-file-uploading"><i class="dashicons dashicons-update-alt"></i></span>
					<span class="md-tooltip md-file-upload-success"><i class="dashicons dashicons-yes"></i> <?php echo esc_html( $success_text ); ?></span>
				</div>
			</div>
			<?php wp_add_inline_script( 'marketers-delight', "MD.fileUpload( '" . esc_attr( "{$id}_file" ) . "', '{$upload_action}' );" ); ?>
		<?php endif; ?>
	<?php }

	/**
	 * Outputs a simple text input field with attributes.
	 *
	 * @since 4.0
	 */

	public function color( $name, $id, $option, $args ) {
		$default = isset( $args['default'] ) ? $args['default'] : '';
		$placeholder = isset( $args['placeholder'] ) ? $args['placeholder'] : $default;
		$option = ! empty( $option ) ? $option : '';
	?>
		<div class="md-color-picker-wrap">
			<input type="text" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $id ); ?>" class="md-color-picker<?php echo ! empty( $option ) ? ' md-has-color-value' : ''; ?>" placeholder="<?php echo esc_html( $placeholder ); ?>" value="<?php echo esc_attr( $option ); ?>" data-jscolor="{ value: '<?php echo esc_attr( $option ); ?>' }" />
			<div class="md-color-picker-controls">
				<span class="md-color-picker-fill"<?php echo md_style( array( 'bg_color' => $default ) ); ?>></span>
				<span class="md-color-picker-reset" title="<?php echo __( 'Restore default color', 'md' ); ?>"><i class="dashicons dashicons-undo"></i></span>
			</div>
		</div>
	<?php }

	/**
	 * WP Editor field. Accepts _WP_Editors::parse_settings( $settings ).
	 *
	 * @since 5.3.1
	 */

	public function editor( $name, $id, $option, $args ) {
		if ( is_array( $args['field'] ) && empty( $option ) ) {
			$args['classes'] = 'md-group-wp-editor';
			$this->textarea( $name, $id, $option, $args );
		}
		else {
			$settings = wp_parse_args( $args, array(
				'textarea_name' => $name,
				'textarea_rows' => 10
			) );
			wp_editor( $option, $id, $settings );
			wp_enqueue_editor();
		}
	}

	/**
	 * Return terms hierarchy category structure.
	 *
	 * @since 5.3.1
	 */

	public function terms( $name, $id, $option, $args ) {
		$defaults = array(
			'description' => '',
			'post_type' => 'post',
			'taxonomy' => 'category',
			'depth' => 0,
			'hide_empty' => false,
			'hierarchical' => true,
			'order' => 'ASC',
			'orderby' => 'name',
			'style' => 'list',
			'use_desc_for_title' => true
		);

		if ( isset( $args['order'] ) )
			$defaults['order'] = esc_attr( $args['order'] );

		$parsed_args = wp_parse_args( $args, $defaults );

		if ( ! isset( $parsed_args['class'] ) )
			$parsed_args['class'] = 'category' === $parsed_args['taxonomy'] ? 'categories' : $parsed_args['taxonomy'];

		$parsed_args['walker'] = new md_category_options_walker( $args['field'], $this, $parsed_args );

		if ( ! taxonomy_exists( $parsed_args['taxonomy'] ) )
			return false;

		$output = '';
		$categories = get_categories( $parsed_args );

		if ( isset( $args['select_type'] ) && $args['select_type'] = 'select' ) {
			$category_options = array();
			foreach ( $categories as $category_order => $category_field ) {
				$category_id = esc_attr( $category_field->term_id );
				$args['options'][$category_id] = esc_html( $category_field->name );
			}
			$output .= $this->select( $name, $id, $option, $args );
		}
		else {
			$output .= '<ul class="md-terms-list">';
			if ( empty( $categories ) )
				$output .= '<li class="cat-item-none">' . __( 'No categories', 'md' ) . '</li>';
			$output .= walk_category_tree( $categories, $parsed_args['depth'], $parsed_args );
			$output .= '</ul>';
		}

		if ( ! empty( $parsed_args['description'] ) )
			$output .= '<p class="description">' . $parsed_args['description'] . '</p>';

		echo $output;
	}

	/**
	 * Disable the automatic output of the clone button in $this->group()
	 * to call this anywhere in custom settings controls.
	 *
	 * @since 5.0
	 */

	public function clone_button( $field, $args = null ) {
		$label = ! empty( $args['button_text'] ) ? $args['button_text'] : __( 'Add New', 'md' );
		$classes = ! empty( $args['classes'] ) ? ' ' . $args['classes'] : '';
	?>
		<span class="md-clone-add button<?php echo esc_attr( $classes ); ?>" data-clone-group="<?php echo esc_attr( "{$this->_id}_" .  $field ); ?>"><?php echo $label; ?></span>
	<?php }

	/**
	 * Wrapper for clone/group fields.
	 *
	 * @since 5.0
	 */

	public function group( $name, $id, $option, $args ) {
		$var = '{clone}';
		$option = ! empty( $option ) ? $option : array();
		$empty[$var] = array();
		$option = array_merge( $empty, $option );
		$style = isset( $args['style'] ) ? $args['style'] : 'list';
	?>

		<div class="md-group-head md-clear">
			<?php if ( isset( $args['label'] ) ) : ?>
				<?php $this->label( $id, $args ); ?>
			<?php endif; ?>
			<?php if ( ! isset( $args['hide_button'] ) ) : ?>
				<?php $this->clone_button( $args['field'] ); ?>
			<?php endif; ?>
		</div>

		<div id="md_group_<?php echo esc_attr( "{$this->_id}_" . $args['field'] ); ?>" class="md-groups md-group-<?php echo $style; ?>">
			<?php foreach ( $option as $group => $fields ) :
				$valid = isset( $args['active_key'] ) && ! empty( $fields[$args['active_key']] ) ? ' valid' : '';
			?>
				<div class="md-group<?php echo ( $valid ) . ( "$group" == $var ? ' empty' : '' ) . ( $style == 'boxes' ? ' md-widget md-toggle' : '' ); ?>">
					<div class="md-group-controls<?php echo ( $style == 'boxes' ? ' md-widget-title' : '' ); ?>">
						<?php if ( $style == 'boxes' ) : ?>
							<?php $this->field( array( $args['field'], $group, 'name' ), array(
								'type' => 'text',
								'placeholder' => isset( $args['new_label'] ) ? $args['new_label'] : __( 'New entry...', 'md' ),
								'classes' => 'md-focus'
							) ); ?>
						<?php endif; ?>
						<span class="md-group-controls-inner">
							<span class="md-delete dashicons dashicons-no" title="<?php echo __( 'Delete', 'md' ); ?>"></span>
							<span class="md-reorder dashicons dashicons-menu" title="<?php echo __( 'Reorder', 'md' ); ?>"></span>
						</span>
					</div>
					<div class="md-group-content<?php echo ( $style == 'boxes' ? ' md-widget-item' : '' ); ?>">
						<?php call_user_func( $args['callback'], $args['field'], $group ); ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

	<?php }

	/**
	 * Apply Builder template. Holds Elements tray for dragging new
	 * elements and programmatically display drop areas.
	 *
	 * @since 5.6
	 */

	public function builder( $name, $id, $option, $args ) {
		$areas = $args['areas'];
		$elements = $args['elements'];
		$key = esc_attr( $args['field'] );
		$active_tab = isset( $args['active_tab'] ) ? $args['active_tab'] : '';
	?>
		<div class="md-builder-controls">
			<?php if ( isset( $args['devices'] ) ) $this->devices(); ?>
			<h3 class="md-builder-title"><i class="dashicons dashicons-plus-alt"></i> <?php echo isset( $args['title'] ) ? esc_html( $args['title'] ) : __( 'Add Elements', 'md' ); ?></h3>
			<?php if ( isset( $args['description'] ) ) : ?>
				<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
			<?php endif; ?>
			<div class="md-builder-elements" data-canvas="elements">
				<?php foreach ( $elements as $element_id => $element ) {
					$this->builder_fields( $key, '{clone}', $element_id, $element );
				} ?>
			</div>
		</div>
		<?php if ( isset( $args['tabs'] ) && ( count( $args['tabs'] ) > 1 ) ) : ?>
			<div class="md-builder-tabs nav-tab-wrapper">
				<?php $t = 0; foreach ( $args['tabs'] as $tab_id => $tab_name ) : ?>
					<a href="#" class="md-tab nav-tab<?php echo $tab_id == $active_tab ? ' nav-tab-active' : ''; ?>" data-md-tab="md-builder-<?php echo esc_attr( $tab_id ); ?>"><?php echo esc_html( $tab_name ); ?></a>
				<?php $t++; endforeach; ?>
			</div>
		<?php endif; ?>
		<?php foreach ( $areas as $area_id => $area_fields ) :
			$tab_classes = '';
			if ( isset( $area_fields['tab'] ) ) {
				$tab = $area_fields['tab'];
				$tab_classes .= " md-tab-content md-builder-$tab";
				if ( $active_tab == $tab )
					$tab_classes .= ' active';
			}
		?>
			<div class="md-builder-<?php echo esc_attr( $area_id ); ?> md-builder-row<?php echo esc_attr( $tab_classes ); ?>">
				<div class="md-builder-head">
					<h3 class="md-builder-title"><i class="dashicons dashicons-admin-page"></i> <?php echo md_text_field( $area_fields['title'] ); ?></h3>
					<?php if ( $area_fields['description'] ) : ?>
						<p class="description"><?php echo md_text_field( $area_fields['description'] ); ?></p>
					<?php endif; ?>
				</div>
				<div class="md-builder<?php echo empty( $option ) ? ' empty' : ''; ?>" data-canvas="<?php echo esc_attr( $area_id ); ?>">
					<?php if ( $option ) : ?>
						<?php foreach ( $option as $group => $fields ) {
							$group = esc_attr( $group );
							$area = ! empty( $fields['area'] ) ? esc_attr( $fields['area'] ) : '';
							$type = ! empty( $fields['type'] ) ? esc_attr( $fields['type'] ) : '';
							if ( $area == $area_id )
								$this->builder_fields( $key, $group, $type, $elements[$type] );
						} ?>
					<?php endif; ?>
					<p class="md-builder-empty"><i class="dashicons dashicons-move"></i> <?php echo __( 'Drag an element here.', 'md' ); ?></p>
				</div>
			</div>
		<?php endforeach; ?>
	<?php $this->field( "{$key}_data", array( 'type' => 'text', 'hidden' => true ) ); }

	/**
	 * Render callback for each Builder Fields Group.
	 *
	 * @since 5.6
	 */

	public function builder_fields( $key, $group, $type, $fields ) {
		$icon = ! empty( $fields['icon'] ) ? $fields['icon'] : 'move';
		$color = ! empty( $fields['color'] ) ? $fields['color'] : '';
	?>
		<div class="md-builder-group">
			<div class="md-builder-tab md-reorder">
				<p class="md-builder-tab-icon"<?php echo ! empty( $color ) ? ' style="color: ' . esc_attr( $color ) . ';"' : ''; ?>><i class="dashicons dashicons-<?php echo esc_attr( $icon ); ?>"></i></p>
				<p class="md-builder-tab-label"><?php echo esc_html( $fields['title'] ); ?></p>
			</div>
			<div class="md-widget md-toggle md-group">
				<h3 class="md-widget-title md-group-controls">
					<span class="md-badge"<?php echo ! empty( $color ) ? ' style="background-color: ' . esc_attr( $color ) . ';"' : ''; ?>><i class="dashicons dashicons-<?php echo esc_attr( $icon ); ?>"></i> <?php echo esc_html( $fields['title'] ); ?></span>
					<?php if ( ! isset( $fields['hide_title'] ) || $fields['hide_title'] !== false ) : ?>
						<?php $this->field( array( $key, $group, 'title' ), array(
							'type' => 'text',
							'placeholder' => isset( $fields['placeholder'] ) ? $fields['placeholder'] : __( 'Enter label...', 'md' )
						) ); ?>
					<?php endif; ?>
					<span class="md-group-controls-inner">
						<span class="md-delete dashicons dashicons-no" title="<?php echo __( 'Delete', 'md' ); ?>"></span>
						<span class="md-reorder dashicons dashicons-menu" title="<?php echo __( 'Reorder', 'md' ); ?>"></span>
					</span>
				</h3>
				<div class="md-widget-item">
					<?php $this->field( array( $key, $group, 'type' ), array(
						'type' => 'text',
						'hidden' => true,
						'default' => esc_attr( $type )
					) ); ?>
					<?php $this->field( array( $key, $group, 'area' ), array(
						'type' => 'text',
						'hidden' => true,
						'classes' => 'canvas-area',
						'default' => $group
					) ); ?>
					<?php call_user_func( $fields['callback'], $group, $type ); ?>
				</div>
			</div>
		</div>
	<?php }

	/**
	 * Render admin group fields for an easy to use feature
	 * deployment throughout various screen in WP admin.
	 *
	 * @since 5.6
	 */

	public function layout_toggle( $types, $args = null ) { ?>

		<div class="md-layout-toggle">

			<?php foreach ( $types as $type => $pages ) :
				$name = '';
				$icon = 'dashicons-admin-post';
				$post_type = get_post_type_object( $type );

				if ( ! empty( $post_type->labels->name ) )
					$name = $post_type->labels->name;

				if ( ! empty( $post_type->menu_icon ) )
					$icon = $post_type->menu_icon;
			?>

				<div class="col-style md-sep-small">

					<h3 class="md-title normal">
						<i class="md-title-icon dashicons <?php echo esc_attr( $icon ); ?>"></i>
						<?php echo esc_html( $name ); ?>
					</h3>

					<hr class="md-sep-small" />

					<?php foreach ( $pages as $page => $val ) {
						echo '<div class="md-layout-toggle-fields">';
						if ( $page )
							if ( $page === 'single' )
								$label = $post_type->labels->singular_name;
							elseif ( $page == 'archive' )
								$label = sprintf( __( '%s page', 'md' ), $name );
							else {
								$page_label = str_replace( "{$type}_", '', $page );
								$label = "$name $page_label";
							}

						call_user_func( $args['callback'], $type, $page, $label );
						echo '</div>';
					} ?>

				</div>

			<?php endforeach; ?>

		</div>

	<?php }

	/**
	 * Create group typography fields.
	 *
	 * @since 5.0
	 */

	public function typography( $field, $args = null ) {
		$g = 1.618;
		$field = is_array( $field ) ? $field : (array) $field;
		$devices = isset( $args['devices'] ) ? $args['devices'] : array( 'desktop', 'tablet', 'mobile' );
		$sanitize = new md_sanitize;
		$design = new md_design;
		$defaults = $design->defaults();
		$fonts = array(
			'font_size' => __( 'Font Size', 'md' ),
			'line_height' => __( 'Line Height', 'md' )
		);
		$font_types = array(
			'default' => array(
				'name' => __( 'Default Fonts', 'md' ),
				'image' => MD_URL . 'lib/admin/images/fonts.png'
			),
			'google' => array(
				'name' => __( 'Google Fonts', 'md' ),
				'image' => MD_URL . 'lib/admin/images/google.png'
			)
		);

		if ( md_setting( array( 'integrations', 'api_keys', 'typekit' ) ) )
			$font_types['typekit'] = array(
				'name' => __( 'TypeKit', 'md' ),
				'image' => MD_URL . 'lib/admin/images/typekit-small.png'
			);

		include( MD_DIR . 'lib/design/templates/typography-fields.php' );
	}

	/**
	 * Devices toggle controls, adds device classes to .md.wrap
	 * to toggle controls for different screen sizes.
	 *
	 * @since 5.0
	 */

	public function devices() { ?>
		<div class="md-devices alignright">
			<span id="desktop" class="md-device desktop active" title="<?php echo __( 'Switch to Desktop controls', 'md' ); ?>">
				<i class="dashicons dashicons-desktop"></i>
			</span>
			<span id="tablet" class="md-device tablet" title="<?php echo __( 'Switch to Tablet controls', 'md' ); ?>">
				<i class="dashicons dashicons-tablet"></i>
			</span>
			<span id="mobile" class="md-device mobile" title="<?php echo __( 'Switch to Mobile controls', 'md' ); ?>">
				<i class="dashicons dashicons-smartphone"></i>
			</span>
		</div>
	<?php }

	/**
	 * Output MD Save button with optional flush rules.
	 *
	 * @since 5.0
	 */

	public function save( $label = null, $args = null ) {
		$admin_pages = md_register( 'admin_pages' );
		$admin_tabs = array();

		foreach ( $admin_pages as $admin_page => $fields )
			$admin_tabs[] = "md_$admin_page";

		if ( isset( $_GET['page'] ) && ( $_GET['page'] == $this->_id || in_array( $this->_id, $admin_tabs ) ) ) {
			if ( isset( $_GET['settings-updated'] ) ) {
				md_compile( true ); // heh
				flush_rewrite_rules();
			}
			if ( ! $label && ! empty( $admin_pages[$this->_clean_id]['name'] ) )
				$label = sprintf( __( 'Save %s', 'md' ), esc_html( $admin_pages[$this->_clean_id]['name'] ) );
		}
	?>
		<input type="submit" name="submit" id="submit" class="button button-primary md-button" value="<?php echo $label; ?>" />
	<?php }

}
