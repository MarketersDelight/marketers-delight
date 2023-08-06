<div class="md-widget md-toggle md-sep-small">

	<h3 class="md-widget-title"><?php echo __( 'Content', 'md' ); ?></h3>

	<div class="md-widget-item">

		<div class="columns-3 columns-single">
			<?php foreach ( $options['content'] as $field => $label ) : ?>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'content', $field ), array(
						'type' => 'color',
						'label' => $label,
						'default' => $defaults['colors']['content'][$field]
					) ); ?>
				</div>
			<?php endforeach; ?>
		</div>

		<hr class="md-sep-small" />

		<div class="columns-3 columns-single">
			<div class="col md-sep-micro">
				<?php $this->fields->field( array( 'featured_image', 'position' ), array(
					'type' => 'select',
					'label' => __( 'Featured Image', 'md' ),
					'empty_label' => __( 'Set image position...', 'md' ),
					'options' => $this->sanitize->values['featured_image'],
					'style' => 'max-width: 100%;'
				) ); ?>
			</div>
			<div class="col md-sep-small">
				<?php $this->fields->field( 'style', array(
					'type' => 'select',
					'label' => __( 'Style', 'md' ),
					'empty_label' => __( 'Box style (default)', 'md' ),
					'options' => array(
						'minimal' => __( 'Simple style', 'md' )
					)
				) ); ?>
			</div>
			<div class="col md-sep-small">
				<?php $this->fields->field( 'layout', array(
					'type' => 'select',
					'label' => __( 'Layout', 'md' ),
					'empty_label' => __( 'Content / Sidebar', 'md' ),
					'options' => $sanitize->values['content_box']
				) ); ?>
			</div>
		</div>

		<hr class="md-sep-small" />

		<div class="columns-3 columns-single md-sep-small">
			<div class="col md-sep-small">
				<?php $this->fields->field( array( 'breadcrumbs', 'position' ), array(
					'type' => 'select',
					'label' => __( 'Breadcrumbs', 'md' ),
					'empty_label' => __( 'Show breadcrumbs...', 'md' ),
					'options' => array(
						'before_page_title' => __( 'Before Page Title', 'md' ),
						'before_content_box' => __( 'Before Content Box', 'md' )
					),
					'style' => 'width:100%'
				) ); ?>
			</div>
		</div>

		<hr class="md-sep-small" />

		<div class="columns-2 columns-single md-sep-micro">
			<div class="col md-sep-small">
				<?php $this->fields->field( array( 'width', 'content' ), array(
					'type' => 'range',
					'label' => __( 'Post Width', 'md' ),
					'placeholder' => $defaults['colors']['width']['post'],
					'min' => ( $defaults['colors']['width']['post'] - $line_height * 4 ),
					'max' => ( $defaults['colors']['width']['post'] + $line_height * 8 )
				) ); ?>
			</div>
			<div class="col md-sep-small">
				<?php $this->fields->field( array( 'width', 'sidebar' ), array(
					'type' => 'range',
					'label' => __( 'Sidebar Width', 'md' ),
					'placeholder' => $defaults['colors']['width']['sidebar'],
					'min' => ( $defaults['colors']['width']['sidebar'] - $line_height * 4 ),
					'max' => ( $defaults['colors']['width']['sidebar'] + $line_height * 8 )
				) ); ?>
			</div>
			<div class="col md-sep-small">
				<?php $this->fields->field( array( 'width', 'site' ), array(
					'type' => 'range',
					'label' => __( 'Site Width', 'md' ),
					'placeholder' => $defaults['colors']['width']['site'],
					'min' => ( $defaults['colors']['width']['site'] - $line_height * 4 ),
					'max' => ( $defaults['colors']['width']['site'] + $line_height * 8 )
				) ); ?>
			</div>
		</div>

		<p class="description"><?php echo __( '<b>Tip:</b> Set the <b>Post Width</b> to the exact length your text will read in the content box.', 'md' ); ?>

		<p class="description"><?php echo sprintf( __( '<b>Tip:</b> For the most accurate results you must add any extra spacing within the content box and sidebar to find the true width of your site.<br /><br /><code><b>%s</b> + <b>%2s</b>%3s = <b>%4s</b></code>', 'md' ), $values['colors']['width']['post'], $values['colors']['width']['sidebar'], ( md_setting( array( 'content', 'style' ) ) == '' ? ' + <b>' . ( $layout_spacing * 2 ) . '</b>' : '' ), $values['colors']['width']['site'] ); ?></p>

	</div>
</div>
