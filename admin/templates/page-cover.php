<div class="columns-2 columns-30-70 columns-single">

	<div class="col col1">
		<?php $this->fields->field( 'photo', array(
			'type' => 'upload',
			'upload_type' => 'media'
		) ); ?>
	</div>

	<div class="col col2">

		<div class="columns-2 columns-half mb-half">

			<div class="col">
				<?php
				$position_label = __( 'Do not use cover', 'md' );
				$position_options = array(
					'headline_cover' => __( 'Headline Cover', 'md' ),
					'header_cover' => __( 'Header Cover', 'md' ),
					'header_cover_full' => __( 'Full Header Cover', 'md' ),
				);
				if ( ! $is_admin ) {
					$position_label = __( 'Use default cover', 'md' );
					$position_options['remove'] = __( 'Do not use cover', 'md' );
				}
				$this->fields->field( 'position', array(
					'type' => 'select',
					'label' => __( 'Position', 'md' ),
					'empty_label' => $position_label,
					'options' => $position_options
				) ); ?>
			</div>

			<div class="col">
				<?php $this->fields->field( 'bg_color', array(
					'type' => 'color',
					'label' =>  __( 'Overlay Color', 'md' ),
					'default' => md_setting( array( 'colors', 'content', 'page_cover' ), 'rgba(0, 0, 0, 0.5)' )
				) ); ?>
			</div>

		</div>

		<?php
		$display_options = array(
			'alternate' => __( 'Use alternate text color', 'md' ),
			'bg_repeat' => __( 'Background repeat', 'md' ),
			'disable_overlay' => __( 'Remove overlay', 'md' )
		);
		if ( $is_admin ) {
			$display_options['term'] = __( 'Apply to all <strong>categories</strong>', 'md' );
			$display_options['single'] = __( 'Apply to all <strong>posts</strong>', 'md' );
		}
		$this->fields->field( 'display', array(
			'type' => 'checkbox',
			'label' => __( 'Settings', 'md' ),
			'inline' => true,
			'options' => $display_options
		) ); ?>

	</div>

</div>