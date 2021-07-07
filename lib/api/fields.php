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

	public $_option = 'marketers_delight';

	/**
	 * Set properties of instance.
	 *
	 * @since 5.0
	 */

	public function __construct( $id ) {
		$this->_id = $id;
		$this->_clean_id = preg_replace( '/^' . preg_quote( 'md_', '/' ) . '/', '', $this->_id );
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
		$name = "{$this->_option}[$this->_clean_id]";
		$id = "{$this->_option}_{$this->_clean_id}";
		$screen = get_current_screen();
		$register = md_register();
		$args['field'] = $field;

		if ( wp_doing_ajax() || in_array( $screen->base, array( 'post', 'post-new' ) ) )
			$setting = get_post_meta( get_the_ID(), $this->_option, true );
		elseif ( $screen->base == 'term' )
			$setting = get_term_meta( $_GET['tag_ID'], $this->_option, true );
		else
			$setting = get_option( $this->_option );

		$group = ! empty( $setting[$this->_clean_id] ) ? $setting[$this->_clean_id] : array();

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
			$option = isset( $setting[$this->_clean_id][$field] ) ? $setting[$this->_clean_id][$field] : '';
		}

		if ( isset( $args['label'] ) && $args['type'] !== 'group' )
			$this->label( $id, $args );

		$this->field_type( $args['type'], $name, $id, $option, $args );

		if ( isset( $args['description'] ) )
			$this->description( $args['description'] );
	}

	/**
	 * Get field values based on current screen.
	 *
	 * @since 5.0.9
	 */

	public function get_field( $keys ) {
		$c = 0;
		$screen = get_current_screen();

		if ( in_array( $screen->base, array( 'post', 'post-new' ) ) )
			$option = md_post_meta();
		elseif ( $screen->base == 'term' )
			$option = md_term_meta();
		else
			$option = md_setting();

		if ( isset( $keys ) ) {
			if ( is_string( $keys ) )
				$keys = (array) $keys;
			foreach ( $keys as $key ) {
				$option = ! empty( $option[$key] ) ? $option[$key] : ( $c == 0 ? array() : '' );
				$c++;
			}
		}

		return $option;
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

		if ( $type == 'upload' )
			$this->upload( $name, $id, $option, $args );

		if ( $type == 'group' || $type == 'repeat' )
			$this->group( $name, $id, $option, $args );

		#deprecated 4.8.4
		if ( $type == 'media' )
			$this->media( $name, $id, $option, $args );
	}

	/**
	 * Outputs a simple text input field with attributes.
	 *
	 * @since 4.0
	 */

	public function text( $name, $id, $option, $args ) {
		$type = ! empty( $args['hidden'] ) ? 'hidden' : 'text';
		$value = isset( $args['default'] ) && $option == '' ? $args['default'] : $option;
		$placeholder = isset( $args['placeholder'] ) ? ' placeholder="' . esc_attr( $args['placeholder'] ) . '"' : '';
		$readonly = isset( $args['readonly_after_save'] ) && ! empty( $option ) ? ' readonly' : '';
		$style = isset( $args['style'] ) ? ' style="' . esc_attr( $args['style'] ) . '"' : '';
		$populate = isset( $args['populate'] ) ? ' md-populate-' . $args['populate'] : '';
		$classes = isset( $args['classes'] ) ? ' ' . $args['classes'] : '';
		$disabled = ! empty( $args['disabled'] ) ? ' disabled' : '';
	?>
		<input type="<?php echo $type; ?>" name="<?php echo $name; ?>" id="<?php echo $id; ?>" value="<?php echo esc_attr( stripslashes( $value ) ); ?>"<?php echo $placeholder; ?> class="regular-text<?php echo esc_attr( $classes ); ?><?php echo esc_attr( $populate ); ?>"<?php echo $readonly; ?><?php echo $style; ?><?php echo $disabled; ?> />
	<?php }

	/**
	 * Outputs a simple textarea.
	 *
	 * @since 4.0
	 */

	public function textarea( $name, $id, $option, $args ) {
		$rows = ! empty( $args['rows'] ) ? intval( $args['rows'] ) : 6;
	?>
		<textarea name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="large-text" rows="<?php echo $rows; ?>"><?php echo esc_attr( stripslashes( $option ) ); ?></textarea>
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
		<input type="number" class="regular-text" name="<?php echo $name; ?>" id="<?php echo $id; ?>" value="<?php echo esc_attr( $option ); ?>"<?php echo $placeholder; ?> style="width: <?php echo $width; ?>px;"<?php echo $max; ?> /><?php echo $unit; ?>
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
			<textarea name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="large-text" rows="<?php echo esc_attr( $rows ); ?>"><?php echo stripslashes( $option ); ?></textarea>
		</div>
		<?php wp_enqueue_script( 'md-code-editor' ); ?>
	<?php }

	/**
	 * Outputs a simple text field for URL entry.
	 *
	 * @since 4.0
	 */

	public function url( $name, $id, $option, $args ) {
		$placeholder = isset( $args['placeholder'] ) ? ' placeholder="' . esc_attr( $args['placeholder'] ) . '"' : '';
		$disabled = isset( $args['disabled'] ) ? ' disabled' : '';
	?>
		<input type="text" name="<?php echo $name; ?>" id="<?php echo $id; ?>" value="<?php echo esc_attr( esc_url( $option ) ); ?>" class="regular-text"<?php echo $placeholder; ?><?php echo $disabled; ?> />
	<?php }

	/**
	 * Outputs a multi-checkbox field.
	 *
	 * @since 4.0
	 */

	public function checkbox( $name, $id, $option, $args ) { ?>
		<div class="md-checkboxes<?php echo isset( $args['multi'] ) ? ' md-multi-checkbox' : ''; ?>">
			<?php foreach ( $args['options'] as $val => $label ) :
				$nameval = esc_attr( "{$name}[$val]" );
				$idval = esc_attr( "{$id}_$val" );
				$check = isset( $option[$val] ) ? esc_attr( $option[$val] ) : '';
			?>
				<p class="md-checkbox">
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

	public function radio( $name, $id, $option, $args ) { ?>
		<?php foreach ( $args['options'] as $val => $label ) :
			$idval = esc_attr( "{$id}_$val" );
			$image = is_array( $label ) && isset( $label['image'] ) ? ' style="background-image: url(\'' . esc_url( $label['image'] ) . '\');"' : '';
			$text = is_array( $label ) ? $label['name'] : $label;
		?>
			<label for="<?php echo $idval; ?>" class="md-radio<?php echo ( is_array( $label ) ? ' md-radio-image' : '' ); ?>">
				<input type="radio" name="<?php echo $name; ?>" id="<?php echo $idval; ?>" class="md-radio-check" value="<?php echo esc_attr( $val ); ?>"<?php echo checked( $option, $val ); ?> />
				<span class="md-radio-label"<?php echo $image; ?>><span class="md-radio-text"><?php echo $text; ?></span></span>
			</label>
		<?php endforeach; ?>
	<?php }

	/**
	 * Outputs a simple select field.
	 *
	 * @since 4.0
	 */

	public function select( $name, $id, $option, $args ) {
		$classes = isset( $args['classes'] ) ? ' class="' . $args['classes'] . '"' : '';
	?>
		<select name="<?php echo $name; ?>" id="<?php echo $id; ?>"<?php echo $classes; ?>>
			<?php if ( isset( $args['empty_label'] ) ) : ?>
				<option value=""><?php echo esc_html( $args['empty_label'] ); ?></option>
			<?php endif; ?>
			<?php if ( isset( $args['optgroup'] ) ) : ?>
				<?php foreach ( $args['options'] as $group => $items ) : ?>
					<optgroup label="<?php echo esc_html( ucwords( $group ) ); ?>">
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
			<input name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="md-range-number" type="number" value="<?php echo esc_attr( $option ); ?>" placeholder="<?php echo $placeholder; ?>" style="width: 65px;" /> <?php echo esc_html( $unit ); ?>
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
	?>	
		<?php if ( $type == 'media' ) : ?>
			<div class="md-upload md-upload-<?php echo $type; ?><?php echo ! empty( $upload_url ) ? ' has-upload' : ''; ?>">
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
		$alpha = ' data-default-color="' . $default . '" data-alpha="true"';
		$class = isset( $args['group'] ) ? 'md-group-color-picker' : 'md-color-picker';
	?>
		<input type="text" name="<?php echo $name; ?>" id="<?php echo $id; ?>" value="<?php echo esc_attr( $option ); ?>" placeholder="<?php echo $placeholder; ?>" class="<?php echo esc_attr( $class ); ?>"<?php echo $alpha; ?> />
		<?php wp_enqueue_style( 'wp-color-picker' ); ?>
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
				'image' => MD_PLUGIN_URL . 'assets/images/fonts.png'
			),
			'google' => array(
				'name' => __( 'Google Fonts', 'md' ),
				'image' => MD_PLUGIN_URL . 'assets/images/google.png'
			)
		);
		if ( md_setting( array( 'integrations', 'api_keys', 'typekit' ) ) )
			$font_types['typekit'] = array(
				'name' => __( 'TypeKit', 'md' ),
				'image' => MD_PLUGIN_URL . 'assets/images/typekit-small.png'
			);
	?>
		<div class="columns-2 columns-single">
			<?php foreach ( $fonts as $font => $label ) : ?>
				<div class="col md-sep-small">
					<?php foreach ( $devices as $device ) : ?>
						<div class="md-<?php echo $device; ?>">
							<?php $this->field( array_merge( $field, array( $font, $device ) ), array(
								'type' => 'range',
								'label' => "$label ($device)",
								'placeholder' => isset( $args[$font][$device] ) ? $args[$font][$device] : '',
								'min' => isset( $args[$font][$device] ) ? round( $args[$font][$device] * ( $g / 2 ) ) : '',
								'max' => isset( $args[$font][$device] ) ? round( $args[$font][$device] * $g ) : ''
							) ); ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
			<div class="col">
				<p>
					<?php $this->field( array_merge( $field, array( 'font_family' ) ), array(
						'type' => 'text',
						'label' => __( 'Font Family', 'md' ),
						'placeholder' => isset( $args['font_family']['placeholder'] ) ? $args['font_family']['placeholder'] : $defaults['typography']['body']['font_family']
					) ); ?>
				</p>
				<?php $this->field( array_merge( $field, array( 'font_type' ) ), array(
					'type' => 'radio',
					'options' => $font_types
				) ); ?>
			</div>
			<div class="col">
				<div class="md-sep-micro">
					<?php $this->field( array_merge( $field, array( 'font_weight' ) ), array(
						'type' => 'select',
						'label' => __( 'Font Weight', 'md' ),
						'empty_label' => isset( $args['font_weight']['empty_label'] ) ? $args['font_weight']['empty_label'] : __( 'Select font weight...', 'md' ),
						'options' => $sanitize->_font_weights
					) ); ?>
				</div>
				<?php if ( isset( $args['bold'] ) ) : ?>
					<div class="md-sep-micro">
						<?php $this->field( array_merge( $field, array( 'bold' ) ), array(
							'type' => 'select',
							'label' => __( 'Bold Text', 'md' ),
							'empty_label' => isset( $args['font_weight']['empty_label'] ) ? $args['font_weight']['empty_label'] : __( 'Select font weight...', 'md' ),
							'options' => $sanitize->_font_weights
						) ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php }

	/**
	 * Output MD Save button with optional flush rules.
	 *
	 * @since 5.0
	 */

	public function save( $label = null, $args = null ) {
		$label = isset( $label ) ? $label : __( 'Save settings', 'md' );
		// recompile CSS on save
		$admin_tabs = array();
		foreach ( md_register( 'admin_pages' ) as $admin_page => $fields )
			$admin_tabs[] = "md_$admin_page";
		if ( isset( $_GET['page'] ) && ( $_GET['page'] == $this->_id || in_array( $this->_id, $admin_tabs ) ) && isset( $_GET['settings-updated'] ) ) {
			md_compile_css( true ); // heh
			flush_rewrite_rules();
		}
	?>
		<input type="submit" name="submit" id="submit" class="button button-primary md-button" value="<?php echo $label; ?>" />
	<?php }

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
	 * Easily create a label for fields.
	 *
	 * @since 4.0
	 */

	public function label( $id, $args ) {
		$classes = isset( $args['type'] ) && $args['type'] == 'group' ? ' md-title' : '';
	?>
		<p class="md-label-wrap"><label for="<?php echo esc_attr( $id ); ?>" class="md-label<?php echo $classes; ?>"><?php echo $args['label']; ?></label></p>
	<?php }

	/**
	 * Easily create a description for fields.
	 *
	 * @since 5.0
	 */

	public function description( $description ) { ?>
		<p class="description"><?php echo $description; ?></p>
	<?php }

	/*--------*/
	/**
	 * Get field values based on current screen.
	 *
	 * @since 4.1
	 * @deprecated 5.0.9
	 */
	public function module( $field ) {
		$fields = array();
		$screen = get_current_screen();
		if ( in_array( $screen->base, array( 'post', 'post-new' ) ) )
			$fields = md_post_meta( array( $this->_clean_id, $field ) );
		elseif ( $screen->base == 'term' ) {
			$term = get_term_meta( $_GET['tag_ID'], $this->_option, true );
			$fields = ! empty( $term[$this->_clean_id][$field ] ) ? $term[$this->_clean_id][$field] : '';
		}
		else
			$fields = md_setting( array( $this->_clean_id, $field ) );
		return $fields;
	}
	/**
	 * Outputs a hidden text field (saves data as unique
	 * value in validation method).
	 *
	 * @DEPRECATED 5.0 use $this->text() with $args['type'] = 'hiden'
	 * @since 4.6.2
	 */
	public function hidden( $name, $id, $option, $args ) {
		$classes = isset( $args['classes'] ) ? ' ' . $args['classes'] : '';
	?>
		<input type="hidden" name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="regular-text<?php echo $classes; ?>" value="<?php echo esc_attr( stripslashes( $option ) ); ?>" />
	<?php }
	/**
	 * @DEPRECATED 5.0. Use $this->group()
	 * Outputs repeatable fields.
	 *
	 * @since 4.0
	 */
	public function repeat( $field, $name, $option, $args ) {
		$r = 0;
		$repeat = array(
			'parent' => $field,
			'count' => $r,
			'value' => $option
		);
		$add_new = ! empty( $args['add_new'] ) ? $args['add_new'] : __( 'Add New', 'md' );
		$main_classes = ( isset( $args['columns'] ) ? ' columns-single columns-' . $args['columns'] : '' ) . ( isset( $args['sort'] ) ? ' md-sortable sortable' : '' );
	?>
		<div class="md-repeat">
			<?php if ( isset( $args['title'] ) ) : ?>
				<h3 class="md-button-title"><?php echo esc_html( $args['title'] ); ?></h3>
			<?php endif; ?>
			<a href="#" class="md-repeat-add button md-spacer"><?php echo $add_new; ?></a>
			<div class="md-repeat-fields md-spacer-small<?php echo esc_attr( $main_classes ); ?>">
				<?php if ( ! is_array( $option ) ) : ?>
					<?php $this->repeat_field( $repeat, $args ); ?>
				<?php else : ?>
					<?php foreach ( $option as $field ) :
						$repeat['value'] = $field;
						$repeat['count'] = $r;
					?>
						<?php $this->repeat_field( $repeat, $args ); ?>
					<?php $r++; endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	<?php }
	/**
	 * @DEPRECATED 5.0. Use $this->group();
	 * Condense different repeatable attributes into single method.
	 *
	 * @since 4.6.2
	 */
	public function repeat_field( $repeat, $args ) {
		$sub_classes = ( isset( $args['columns'] ) ? ' col' : '' ) . ( isset( $args['sort'] ) ? ' md-sortable-item md-sortable-hidden' : '' );
		$delete_classes = isset( $args['sort'] ) ? 'md-sortable-delete' : 'md-circle-badge';
		$skip = $repeat['count'] == 0 && ! empty( $args['skip'] ) ? ' style="display: none;"' : null;
	?>
		<div class="md-repeat-field<?php echo esc_attr( $sub_classes ); ?>"<?php echo $skip; ?>>
			<?php if ( isset( $args['sort'] ) ) : ?>
				<div class="md-repeat-bar md-clear">
					<?php $this->field( 'text', 'name', null, array_merge( $repeat, array(
						'atts' => array( 'placeholder' => isset( $args['sort_text'] ) ? esc_html( $args['sort_text'] ) : __( 'New entry...', 'md' ) )
					) ) ); ?>
					<span class="toggle-indicator" aria-hidden="true"></span>
				</div>
			<?php endif; ?>
			<div class="md-repeat-content">
				<?php call_user_func( array( $this, $args['callback'] ), $repeat ); ?>
			</div>
			<a href="#" class="md-repeat-delete <?php echo esc_attr( $delete_classes ); ?>">&times;</a>
		</div>
	<?php }
	/**
	 * @DEPRECATED 4.8.4. Use $this->upload();
	 * Outputs image upload field.
	 *
	 * @since 4.0
	 */
	public function media( $name, $id, $option, $args ) {
		$placeholder = isset( $args['placeholder'] ) ? ' placeholder="' . esc_attr( $args['placeholder'] ) . '"' : '';
		$src = ! empty( $option ) ? $option : '#';
	?>
		<div class="md-media">
			<div class="md-media-columns columns-2 columns-30-70">
				<div class="col col1">
					<div class="col-inner">
						<p><img class="md-media-preview-image md-media-add" src="<?php echo $src; ?>" alt="<?php _e( 'Preview image', 'md' ); ?>" /></p>
					</div>
				</div>
				<div class="col col2">
					<div class="col-inner">
						<p>
							<label class="md-label" for="<?php echo $name; ?>[url]"><?php echo __( 'Image Link', 'md' ); ?></label>
							<input type="url" class="md-media-url regular-text" name="<?php echo $name; ?>" id="<?php echo $id; ?>" value="<?php echo esc_attr( $option ); ?>" placeholder="<?php _e( 'http://', 'md' ); ?>">
						</p>
						<input type="hidden" class="md-media-id regular-text">
					</div>
				</div>
			</div>
			<div class="md-media-buttons">
				<input type="button" class="md-media-add button" value="<?php _e( 'Add Image', 'md' ); ?>" style="display: <?php echo empty( $option ) ? 'inline-block' : 'none'; ?>;" />
				<input type="button" class="md-media-remove button" value="<?php _e( 'Remove Image', 'md' ); ?>" style="display: <?php echo ! empty( $option ) ? 'inline-block' : 'none'; ?>;" />
			</div>
		</div>
		<?php wp_enqueue_media(); ?>
	<?php }

}