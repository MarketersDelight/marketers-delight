<div class="columns-3 md-sep-micro">
	<?php if ( empty( $screen->taxonomy ) ) : ?>
		<div class="col">
			<div class="md-sep-micro">
				<?php $this->fields->field( 'inline_style', array(
					'type' => 'select',
					'label' => __( 'Inline Buttons', 'md' ),
					'empty_label' => __( 'Choose style...', 'md' ),
					'options' => $this->inline_style
				) ); ?>
			</div>
			<div class="md-sep-small">
				<?php foreach ( $this->inline as $position => $label ) : ?>
					<?php if ( in_array( get_post_type(), array_keys( $post_types ) ) && in_array( $position, array_keys( $inline ) ) ) : ?>
						<?php $this->fields->field( 'remove', array(
							'type' => 'checkbox',
							'options' => array(
								$position => '<b>' . __( 'Remove', 'md' ) . "</b> $label"
							)
						) ); ?>
					<?php else : ?>
						<?php $this->fields->field( 'inline', array(
							'type' => 'checkbox',
							'options' => array(
								$position => '<b>' . __( 'Add', 'md' ) . "</b> $label"
							)
						) ); ?>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>
	<div class="col">
		<div class="md-sep-small">
			<?php $this->fields->field( 'floating', array(
				'type' => 'select',
				'label' => __( 'Floating Icons', 'md' ),
				'empty_label' => __( 'Enable on...', 'md' ),
				'options' => array_merge( $this->floating, array(
					'remove' => __( 'Remove floating icons', 'md' )
				) )
			) ); ?>
			<?php if ( in_array( get_post_type(), array_keys( $post_types ) ) && ! empty( $floating ) && ! md_post_meta( array( 'share', 'floating' ) ) ) : ?>
				<p class="description"><?php echo sprintf( __( 'Current position: %s', 'md' ), '<b>' . $this->floating[$floating] . '</b>' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</div>
<hr class="md-sep-micro" />
<?php $this->fields->field( 'buttons', array(
	'type' => 'checkbox',
	'options' => array(
		'custom' => __( 'Use custom share buttons', 'md' )
	)
) ); ?>
<div id="md_share_buttons" style="display: <?php echo $this->fields->module( 'buttons' ) ? 'block' : 'none'; ?>">
	<?php include( md_template( 'dropins', 'share/admin/share-icons', true ) ); ?>
</div>
<?php $this->fields->field( 'likes', array(
	'type' => 'text',
	'hidden' => true
) ); ?>