<?php

$value = is_string( $option ) ? $option : '';
$default_label = isset( $args['default_label'] ) ? $args['default_label'] : __( 'Theme default', 'md' );
$default_stack = isset( $args['default_stack'] ) ? $args['default_stack'] : '';
$manage_url = file_exists( ABSPATH . 'wp-admin/font-library.php' ) ? admin_url( 'font-library.php' ) : '';
$menu_id = "{$id}_menu";

$groups = array(
	'library' => array(
		'label' => __( 'Your fonts', 'md' ),
		'fonts' => $this->data->site_fonts(),
		'link' => $manage_url
	),
	'stack' => array(
		'label' => __( 'Font stacks', 'md' ),
		'badge' => __( 'Font stack', 'md' ),
		'fonts' => $this->data->font_stacks()
	)
);

$title = $default_label;
$stack = $default_stack;
$badge = __( 'Default', 'md' );
$is_custom = $value !== '';

foreach ( $groups as $group )
	foreach ( $group['fonts'] as $font )
		if ( $value !== '' && $font['stack'] === $value ) {
			$title = $font['name'];
			$stack = $font['stack'];
			$badge = isset( $font['source'] ) ? $font['source'] : $group['badge'];
			$is_custom = false;
		}
?>

<div class="md-picker md-font-picker<?php echo $is_custom ? ' is-custom' : ''; ?>">

	<button type="button" id="<?php echo esc_attr( $id ); ?>" class="md-picker-trigger" aria-expanded="false" aria-controls="<?php echo esc_attr( $menu_id ); ?>" aria-haspopup="true">
		<span class="md-picker-title"<?php echo $stack ? ' style="font-family: ' . esc_attr( $stack ) . '"' : ''; ?>><?php echo esc_html( $title ); ?></span>
		<span class="md-font-badge"><?php echo esc_html( $badge ); ?></span>
		<span class="dashicons dashicons-arrow-down-alt2 md-picker-chevron" aria-hidden="true"></span>
	</button>

	<div class="md-font-custom">
		<input type="text" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $id ); ?>_custom" class="md-font-custom-input" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr( $default_stack ); ?>" aria-label="<?php esc_attr_e( 'Custom CSS font stack', 'md' ); ?>" />
		<button type="button" class="md-font-badge md-font-list" title="<?php esc_attr_e( 'Back to font list', 'md' ); ?>"><?php esc_html_e( 'Custom', 'md' ); ?> &times;</button>
	</div>

	<p class="md-font-hint"><?php esc_html_e( 'Any CSS font-family value. End with a fallback like sans-serif.', 'md' ); ?></p>

	<div id="<?php echo esc_attr( $menu_id ); ?>" class="md-picker-menu md-picker-list" role="dialog" aria-label="<?php esc_attr_e( 'Choose a font', 'md' ); ?>" hidden>

		<div class="md-picker-section">
			<button type="button" class="md-picker-option<?php echo $value === '' ? ' is-selected' : ''; ?>" data-stack="" data-preview="<?php echo esc_attr( $default_stack ); ?>" data-badge="<?php esc_attr_e( 'Default', 'md' ); ?>">
				<span class="md-picker-copy">
					<span class="md-picker-title"<?php echo $default_stack ? ' style="font-family: ' . esc_attr( $default_stack ) . '"' : ''; ?>><?php echo esc_html( $default_label ); ?></span>
					<span class="md-picker-meta"><?php echo esc_html( $default_stack ); ?></span>
				</span>
				<span class="dashicons dashicons-yes md-picker-check" aria-hidden="true"></span>
			</button>
			<button type="button" class="md-picker-option md-font-custom-open<?php echo $is_custom ? ' is-selected' : ''; ?>">
				<span class="md-picker-copy">
					<span class="md-picker-title"><?php esc_html_e( 'Custom font', 'md' ); ?></span>
					<span class="md-picker-meta"><?php esc_html_e( 'Type any CSS font-family', 'md' ); ?></span>
				</span>
				<span class="dashicons dashicons-edit md-font-custom-icon" aria-hidden="true"></span>
				<span class="dashicons dashicons-yes md-picker-check" aria-hidden="true"></span>
			</button>
		</div>

		<?php foreach ( $groups as $group ) :
			if ( empty( $group['fonts'] ) && empty( $group['link'] ) )
				continue;
		?>

		<div class="md-picker-section">

			<span class="md-picker-heading"><?php echo esc_html( $group['label'] ); ?></span>

			<?php if ( empty( $group['fonts'] ) ) : ?>
			<span class="md-font-empty"><?php esc_html_e( 'Fonts you add in Appearance › Fonts show here.', 'md' ); ?></span>
			<?php endif; ?>

			<?php foreach ( $group['fonts'] as $font ) : ?>
			<button type="button" class="md-picker-option<?php echo $value !== '' && $font['stack'] === $value ? ' is-selected' : ''; ?>" data-stack="<?php echo esc_attr( $font['stack'] ); ?>" data-badge="<?php echo esc_attr( isset( $font['source'] ) ? $font['source'] : $group['badge'] ); ?>">
				<span class="md-picker-copy">
					<span class="md-picker-title" style="font-family: <?php echo esc_attr( $font['stack'] ); ?>"><?php echo esc_html( $font['name'] ); ?></span>
					<span class="md-picker-meta"><?php echo esc_html( $font['stack'] ); ?></span>
				</span>
				<span class="dashicons dashicons-yes md-picker-check" aria-hidden="true"></span>
			</button>
			<?php endforeach; ?>

			<?php if ( ! empty( $group['link'] ) ) : ?>
			<a href="<?php echo esc_url( $group['link'] ); ?>" class="md-font-manage" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Manage fonts', 'md' ); ?> <span aria-hidden="true">&nearr;</span></a>
			<?php endif; ?>

		</div>

		<?php endforeach; ?>

	</div>

</div>
