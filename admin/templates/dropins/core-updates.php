<h2><?php esc_html_e( 'Drop-ins', 'md' ); ?></h2>

<?php if ( empty( $updates ) ) : ?>

<p><?php esc_html_e( 'Your Drop-ins are all up to date.', 'md' ); ?></p>

<?php else : ?>

<p><?php esc_html_e( 'The following Drop-ins have new versions available. Check the ones you want to update and then click "Update Drop-ins".', 'md' ); ?></p>

<form method="post" action="<?php echo esc_url( self_admin_url( 'update.php?action=update-md-dropins' ) ); ?>" name="upgrade-md-dropins" class="upgrade">

	<?php wp_nonce_field( 'bulk-dropins' ); ?>

	<p><input type="submit" class="button" value="<?php esc_attr_e( 'Update Drop-ins', 'md' ); ?>" /></p>

	<table class="widefat updates-table" id="update-md-dropins-table">

		<thead>
			<tr>
				<td class="manage-column check-column"><input type="checkbox" id="md-dropins-select-all" /></td>

				<td class="manage-column"><label for="md-dropins-select-all"><?php esc_html_e( 'Select All', 'md' ); ?></label></td>
			</tr>
		</thead>

		<tbody class="plugins">

			<?php foreach ( $updates as $update ) :
				$slug = $update['slug'];
				$name = $update['name'];
			?>

			<tr>

				<td class="check-column">
					<input type="checkbox" name="dropins[]" id="md-dropin-update-<?php echo esc_attr( $slug ); ?>" value="<?php echo esc_attr( $slug ); ?>" />
					<label for="md-dropin-update-<?php echo esc_attr( $slug ); ?>" class="screen-reader-text"><?php printf( esc_html__( 'Select %s', 'md' ), esc_html( $name ) ); ?></label>
				</td>

				<td class="plugin-title">
					<p>
						<?php echo md_get_dropin_icon( $slug ); ?>
						<strong><?php echo esc_html( $name ); ?></strong>
						<?php printf( esc_html__( 'You have version %1$s installed. Update to %2$s.', 'md' ), esc_html( $update['installed_version'] ), esc_html( $update['new_version'] ) ); ?>
					</p>
				</td>

			</tr>

			<?php endforeach; ?>

		</tbody>

	</table>

	<p><input type="submit" class="button" value="<?php esc_attr_e( 'Update Drop-ins', 'md' ); ?>" /></p>

</form>

<?php endif; ?>
