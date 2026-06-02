<div class="columns-2 columns-single md-sep-small">

	<div class="col col1">

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
			$display_options = array(
				'single'       => __( 'Apply to all <strong>Posts</strong>', 'md' ),
				'show_excerpt' => __( 'Show <strong>Excerpt</strong> in Post Titles', 'md' )
			);
		}
		$this->fields->field( 'display', array(
			'type' => 'checkbox',
			'label' => __( 'Settings', 'md' ),
			'inline' => true,
			'options' => $display_options
		) ); ?>

	</div>

	<div class="col col2">
		<?php $this->fields->field( 'photo', array(
			'type' => 'upload',
			'label' => __( 'Upload Image', 'md' ),
			'upload_type' => 'media'
		) ); ?>
	</div>

</div>

<?php if ( in_array( $screen->base, array( 'post', 'post-new' ) ) ) : ?>

<hr class="md-sep-small" />

<?php $this->fields->field( 'title_content', array(
	'type' => 'editor',
	'init' => true,
	'label' => __( 'Page Content', 'md' ),
	'description' => __( 'Overwrite the page excerpt or display formatted content to show below the page title.', 'md' ),
	'rows' => 4
) ); ?>

<?php endif; ?>