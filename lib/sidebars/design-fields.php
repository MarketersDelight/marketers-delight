<div class="md-widget md-toggle md-sep-small">

	<h3 class="md-widget-title"><?php echo __( 'Design', 'md' ); ?></h3>

	<div class="md-widget-item columns-3 columns-single">

		<div class="col md-sep-small">
			<?php $this->fields->field( 'bg_color', array(
				'type' => 'color',
				'label' => __( 'Background', 'md' )
			) ); ?>
		</div>

		<div class="col md-sep-small">
			<?php $this->fields->field( 'text', array(
				'type' => 'color',
				'label' => __( 'Text', 'md' ),
				'default' => $defaults['sidebar']['text']
			) ); ?>
		</div>

		<div class="col md-sep-small">
			<?php $this->fields->field( 'title', array(
				'type' => 'color',
				'label' => __( 'Title', 'md' ),
				'default' => $defaults['sidebar']['title']
			) ); ?>
		</div>

		<div class="col md-sep-small">
			<?php $this->fields->field( 'links', array(
				'type' => 'color',
				'label' => __( 'Links', 'md' ),
				'default' => $defaults['sidebar']['links']
			) ); ?>
		</div>

	</div>

</div>

<div class="md-widget md-toggle md-sep-small">

	<h3 class="md-widget-title"><?php echo __( 'Typography', 'md' ); ?></h3>

	<div class="md-widget-item">

		<div class="md-sep-small">
			<?php $this->fields->typography( null, array(
				'font_size' => array(
					'desktop' => $defaults['sidebar']['font_size']['desktop'],
					'tablet' => $defaults['sidebar']['font_size']['tablet'],
					'mobile' => $defaults['sidebar']['font_size']['mobile']
				),
				'line_height' => array(
					'desktop' => $defaults['sidebar']['line_height']['desktop'],
					'tablet' => $defaults['sidebar']['line_height']['tablet'],
					'mobile' => $defaults['sidebar']['line_height']['mobile']
				)
			) ); ?>
		</div>

		<hr class="md-sep" />

		<div class="md-sep-small">

			<h4 class="md-title"><?php echo __( 'Sidebar Title', 'md' ); ?></h4>

			<?php $this->fields->typography( 'sidebar_title', array(
				'font_size' => array(
					'desktop' => $defaults['sidebar']['sidebar_title']['font_size']['desktop'],
					'tablet' => $defaults['sidebar']['sidebar_title']['font_size']['tablet'],
					'mobile' => $defaults['sidebar']['sidebar_title']['font_size']['mobile']
				),
				'line_height' => array(
					'desktop' => $defaults['sidebar']['sidebar_title']['line_height']['desktop'],
					'tablet' => $defaults['sidebar']['sidebar_title']['line_height']['tablet'],
					'mobile' => $defaults['sidebar']['sidebar_title']['line_height']['mobile']
				),
				'font_family' => array( 'placeholder' => __( 'Inherit from <h3>', 'md' ) ),
				'font_weight' => array( 'empty_label' => __( 'Inherit from <h3>', 'md' ) )
			) ); ?>

		</div>

	</div>

</div>
