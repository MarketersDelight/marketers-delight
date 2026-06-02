<?php
	$c = 0;
	$fields = apply_filters( "md_{$context}_{$this->_clean_id}_fields", array() );
?>

<?php do_action( "md_{$context}_{$this->_clean_id}_before" ); ?>

<div class="md-tabs">

	<div class="nav-tab-wrapper">
		<?php foreach ( $fields as $id => $field ) : ?>
		<a href="#" class="md-tab nav-tab<?php echo $c == 0 ? ' nav-tab-active' : ''; ?>" data-md-tab="md-<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $field['name'] ); ?></a>
		<?php $c++; endforeach; ?>
	</div>

	<?php foreach ( $fields as $id => $field ) :
		call_user_func( $field['callback'] );
	endforeach; ?>

</div>