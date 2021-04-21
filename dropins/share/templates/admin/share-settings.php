<div class="md-share md-content-wrap">
	<h2 class="md-title"><?php echo __( 'Share Buttons', 'md' ); ?></h2>
	<p><?php echo __( 'Deploy share buttons throughout your site and adjust your share networks.', 'md' ); ?></p>
	<hr class="md-sep-small" />
	<div class="columns-3 md-sep-small">
		<div class="col">
			<?php $this->fields->field( 'post_types', array(
				'type' => 'checkbox',
				'label' =>  __( 'Show on Post Types', 'md' ),
				'options' => $types
			) ); ?>
		</div>
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
				<?php $this->fields->field( 'inline', array(
					'type' => 'checkbox',
					'options' => $this->inline
				) ); ?>
			</div>
		</div>
		<div class="col">
			<div class="md-sep-small">
				<?php $this->fields->field( 'floating', array(
					'type' => 'select',
					'label' => __( 'Floating Icons', 'md' ),
					'empty_label' => __( 'Enable on...', 'md' ),
					'options' => $this->floating
				) ); ?>
			</div>
		</div>
	</div>
	<hr />
</div>
<div class="md-content-wrap-wide">
	<?php include( md_template( $this->dir, 'share/admin/share-icons', true ) ); ?>
	<hr class="md-sep-small" />
	<?php $this->fields->save(); ?>
	<?php foreach ( $post_types as $post_type ) : ?>
		<?php $this->fields->field( "{$post_type}_likes", array( 'type' => 'text', 'hidden' => true ) ); ?>
	<?php endforeach; ?>
</div>