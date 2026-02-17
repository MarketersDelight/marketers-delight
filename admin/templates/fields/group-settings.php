<?php
	$c = 0;
	$settings = apply_filters( "md_{$context}_{$this->_clean_id}_child_fields", array() );
?>

<div class="md-tabs">

	<div class="nav-tab-wrapper">
		<?php foreach ( $settings as $id => $name ) : ?>
		<a href="#" class="md-tab nav-tab<?php echo $c == 0 ? ' nav-tab-active' : ''; ?>" data-md-tab="md-<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $name ); ?></a>
		<?php $c++; endforeach; ?>
	</div>

	<?php do_action( "md_{$context}_{$this->_clean_id}_fields" ); ?>

</div>