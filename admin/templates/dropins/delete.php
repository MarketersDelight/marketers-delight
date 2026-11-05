<?php
$deletable = $active = array();

foreach ( $slugs as $slug ) {
	if ( empty( $details[$slug] ) )
		continue;

	if ( ! empty( $state['installed'][$slug]['status']['enable'] ) )
		$active[] = $details[$slug]['Name'];
	else
		$deletable[$slug] = $details[$slug];
} ?>

<div class="md-dropins md-dropins-delete md-content-wrap-wide">

	<h2 class="md-title"><?php echo esc_html( _n( 'Delete Drop-in', 'Delete Drop-ins', max( 1, count( $deletable ) ), 'md' ) ); ?></h2>

	<?php if ( $active ) : ?>

	<div class="notice notice-warning inline">
		<p><?php printf( esc_html__( 'Active Drop-ins can\'t be deleted. Deactivate them first: %s', 'md' ), esc_html( implode( ', ', $active ) ) ); ?></p>
	</div>

	<?php endif; ?>

	<?php if ( $deletable ) : ?>

	<p><?php echo esc_html( _n( 'You are about to remove the following Drop-in:', 'You are about to remove the following Drop-ins:', count( $deletable ), 'md' ) ); ?></p>

	<ul class="ul-disc">

		<?php foreach ( $deletable as $slug => $dropin ) : ?>
		<li><?php printf( wp_kses_post( __( '<strong>%1$s</strong> by <em>%2$s</em>', 'md' ) ), esc_html( $dropin['Name'] ), esc_html( $dropin['Author'] ) ); ?></li>
		<?php endforeach; ?>

	</ul>

	<p><?php esc_html_e( 'Are you sure you want to delete these files? Settings the Drop-ins saved stay in the database.', 'md' ); ?></p>

	<form method="post" action="<?php echo esc_url( md_dropins::url() ); ?>" class="md-inline-form">
		<input type="hidden" name="page" value="md_dropins" />
		<input type="hidden" name="action" value="delete-selected" />
		<input type="hidden" name="verify-delete" value="1" />
		<?php foreach ( array_keys( $deletable ) as $slug ) : ?>
		<input type="hidden" name="checked[]" value="<?php echo esc_attr( $slug ); ?>" />
		<?php endforeach; ?>
		<?php wp_nonce_field( 'bulk-dropins' ); ?>
		<?php submit_button( _n( 'Yes, delete these files', 'Yes, delete these files', count( $deletable ), 'md' ), '', 'submit', false ); ?>
	</form>

	<?php else : ?>

	<p><?php esc_html_e( 'There\'s nothing to delete.', 'md' ); ?></p>

	<?php endif; ?>

	<form method="get" action="<?php echo esc_url( self_admin_url( 'admin.php' ) ); ?>" class="md-inline-form">
		<input type="hidden" name="page" value="md_dropins" />
		<?php submit_button( __( 'No, return me to the Drop-ins list', 'md' ), '', 'submit', false ); ?>
	</form>

</div>
