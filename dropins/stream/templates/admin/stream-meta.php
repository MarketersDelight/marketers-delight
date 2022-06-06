<div class="md-sep-small">
	<?php $this->fields->field( 'post_id', array(
		'type' => 'number',
		'label' => __( 'Post ID', 'md' ),
		'description' => __( 'To share a post, enter its ID here to embed in your status.', 'md' )
	) ); ?>
</div>
<div class="md-sep">
	<?php $this->fields->field( 'thread', array(
		'type' => 'group',
		'label' => __( 'Thread', 'md' ),
		'callback' => array( $this, 'thread' ),
		'style' => 'boxes'
	) ); ?>
</div>